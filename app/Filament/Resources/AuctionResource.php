<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuctionResource\Pages;
use App\Models\Auction;
use App\Models\Category;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuctionResource extends Resource
{
    protected static ?string $model = Auction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Lelang';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('slug', \Illuminate\Support\Str::slug($state) . '-' . \Illuminate\Support\Str::random(5))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->relationship('seller', 'name')
                            ->label('Seller')
                            ->searchable()
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Harga & Penawaran')
                    ->schema([
                        Forms\Components\TextInput::make('start_price')
                            ->label('Harga Awal')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('current_price')
                            ->label('Harga Saat Ini')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('min_bid_increment')
                            ->label('Min. Kenaikan Bid')
                            ->required()
                            ->numeric()
                            ->default(1000)
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('buy_now_price')
                            ->label('Harga Batas / Buy Now')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(null)
                            ->helperText('Opsional - lelang langsung selesai jika bid mencapai harga ini'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Waktu')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft'     => 'Draft',
                                'active'    => 'Active',
                                'ended'     => 'Ended',
                                'closed'    => 'Closed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),

                        Forms\Components\Select::make('winner_id')
                            ->relationship('winner', 'name')
                            ->label('Pemenang')
                            ->searchable()
                            ->default(null),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Harga Saat Ini')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('buy_now_price')
                    ->label('Harga Batas')
                    ->money('IDR')
                    ->sortable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Berakhir')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning'  => 'draft',
                        'success'  => 'active',
                        'danger'   => 'cancelled',
                        'secondary' => 'ended',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'active'    => 'Active',
                        'ended'     => 'Ended',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Auction $record): bool => $record->status === 'draft')
                    ->action(fn (Auction $record) => $record->update(['status' => 'active']))
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Auction $record): bool => $record->status === 'draft')
                    ->action(fn (Auction $record) => $record->update(['status' => 'cancelled']))
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAuctions::route('/'),
            'create' => Pages\CreateAuction::route('/create'),
            'edit'   => Pages\EditAuction::route('/{record}/edit'),
        ];
    }
}