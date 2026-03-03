<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidResource\Pages;
use App\Models\Bid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BidResource extends Resource
{
    protected static ?string $model = Bid::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('auction_id')
                    ->relationship('auction', 'title')
                    ->required()
                    ->searchable(),
                
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('auction.title')
                    ->label('Auction')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->description(function (Bid $record) {
                        if ($record->auction->buy_now_price) {
                            return 'Buy Now: Rp ' . number_format($record->auction->buy_now_price, 0, ',', '.');
                        }
                        return null;
                    }),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Bidder')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable()
                    ->description(function (Bid $record) {
                        if ($record->auction->buy_now_price && $record->amount >= $record->auction->buy_now_price) {
                            return '⚡ Buy Now Price';
                        }
                        return null;
                    })
                    ->color(function (Bid $record) {
                        if ($record->auction->buy_now_price && $record->amount >= $record->auction->buy_now_price) {
                            return 'warning';
                        }
                        return null;
                    }),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\Filter::make('buy_now')
                    ->label('Buy Now Price')
                    ->query(fn ($query) => $query->whereHas('auction', function ($q) {
                        $q->whereNotNull('buy_now_price')
                          ->whereRaw('bids.amount >= auctions.buy_now_price');
                    })),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Bid $record) => $record->status === 'pending')
                    ->action(function (Bid $record) {
                        $user = $record->user;
                        $auction = $record->auction;
                        
                        // Cek saldo cukup
                        if ($user->balance < $record->amount) {
                            \Filament\Notifications\Notification::make()
                                ->title('Saldo tidak cukup')
                                ->body('User tidak memiliki saldo yang cukup untuk bid ini.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Kurangi saldo user
                        $user->decrement('balance', $record->amount);
                        
                        // Update status bid
                        $record->update(['status' => 'approved']);
                        
                        // Update harga lelang
                        if ($record->amount > $auction->current_price) {
                            $auction->update(['current_price' => $record->amount]);
                        }
                        
                        // Catat transaksi
                        \App\Models\Transaction::create([
                            'buyer_id'    => $user->id,
                            'seller_id'   => $auction->user_id,
                            'auction_id'  => $auction->id,
                            'amount'      => $record->amount,
                            'status'      => 'pending',
                            'payment_ref' => 'BID-' . strtoupper(uniqid()),
                        ]);
                        
                        // Cek apakah bid mencapai buy_now_price
                        if ($auction->buy_now_price && $record->amount >= $auction->buy_now_price) {
                            // Tutup lelang dan set pemenang
                            $auction->update([
                                'status'    => 'ended',
                                'winner_id' => $user->id,
                            ]);
                            
                            // Update transaksi jadi completed
                            \App\Models\Transaction::where('auction_id', $auction->id)
                                ->where('buyer_id', $user->id)
                                ->where('status', 'pending')
                                ->latest()
                                ->first()
                                ?->update(['status' => 'completed']);
                            
                            // Refund semua bidder yang kalah
                            $losingBids = \App\Models\Bid::where('auction_id', $auction->id)
                                ->where('status', 'approved')
                                ->where('user_id', '!=', $user->id)
                                ->get()
                                ->groupBy('user_id');
                            
                            foreach ($losingBids as $userId => $bids) {
                                $latestBid = $bids->sortByDesc('amount')->first();
                                $latestBid->user->increment('balance', $latestBid->amount);
                                
                                \App\Models\Transaction::create([
                                    'buyer_id'    => $userId,
                                    'seller_id'   => $auction->user_id,
                                    'auction_id'  => $auction->id,
                                    'amount'      => $latestBid->amount,
                                    'status'      => 'completed',
                                    'payment_ref' => 'REFUND-' . strtoupper(uniqid()),
                                ]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('🎉 Lelang Selesai!')
                                ->body("Bid mencapai Buy Now Price! {$user->name} memenangkan lelang.")
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Bid disetujui')
                                ->success()
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Bid $record) => $record->status === 'pending')
                    ->action(function (Bid $record) {
                        $record->update(['status' => 'rejected']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Bid ditolak')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBids::route('/'),
            'create' => Pages\CreateBid::route('/create'),
            'edit' => Pages\EditBid::route('/{record}/edit'),
        ];
    }
}
