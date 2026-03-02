<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedBuildingStorefront;
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Restaurant Details')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('cuisine_type')->options([
                    'mediterranean' => 'Mediterranean',
                    'italian' => 'Italian',
                    'japanese' => 'Japanese',
                    'indian' => 'Indian',
                    'american' => 'American',
                    'middle_eastern' => 'Middle Eastern',
                    'french' => 'French',
                    'thai' => 'Thai',
                ]),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            ])->columns(3),
            Forms\Components\Section::make('Contact & Hours')->schema([
                Forms\Components\TextInput::make('address'),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TimePicker::make('opening_time')->seconds(false),
                Forms\Components\TimePicker::make('closing_time')->seconds(false),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cuisine_type')->badge(),
                Tables\Columns\TextColumn::make('tables_count')->counts('tables')->label('Tables'),
                Tables\Columns\TextColumn::make('opening_time')->label('Opens'),
                Tables\Columns\TextColumn::make('closing_time')->label('Closes'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit' => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
}
