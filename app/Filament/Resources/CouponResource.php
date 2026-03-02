<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedTicket;
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Coupon Details')->schema([
                Forms\Components\TextInput::make('code')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('description')->required(),
                Forms\Components\Select::make('type')->options([
                    'percentage' => 'Percentage',
                    'fixed' => 'Fixed Amount',
                ])->required(),
                Forms\Components\TextInput::make('value')->numeric()->required()->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : '$'),
                Forms\Components\TextInput::make('min_party_size')->numeric()->default(1),
                Forms\Components\TextInput::make('max_uses')->numeric()->nullable(),
                Forms\Components\DatePicker::make('valid_from')->required(),
                Forms\Components\DatePicker::make('valid_until')->required(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('value')->formatStateUsing(fn ($state, $record) => $record->type === 'percentage' ? "{$state}%" : "\${$state}"),
                Tables\Columns\TextColumn::make('times_used')->label('Used'),
                Tables\Columns\TextColumn::make('max_uses')->label('Max'),
                Tables\Columns\TextColumn::make('valid_until')->date()->color(fn ($state) => now()->gt($state) ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
