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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Seller')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('winner_id')
                    ->label('Pemenang')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->default(null),

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

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),

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