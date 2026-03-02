<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Models\Table;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables as TablesFacade;
use Filament\Tables\Table as FilamentTable;

class TableResource extends Resource
{
    protected static ?string $model = Table::class;
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static ?string $navigationLabel = 'Tables';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Table Details')->schema([
                Forms\Components\Select::make('restaurant_id')
                    ->relationship('restaurant', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('capacity')->numeric()->required()->minValue(1)->maxValue(20),
                Forms\Components\Select::make('location')->options([
                    'indoor' => 'Indoor',
                    'outdoor' => 'Outdoor',
                    'terrace' => 'Terrace',
                    'private' => 'Private Room',
                ])->required(),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TablesFacade\Columns\TextColumn::make('name')->searchable()->sortable(),
                TablesFacade\Columns\TextColumn::make('restaurant.name')->label('Restaurant'),
                TablesFacade\Columns\TextColumn::make('capacity')->badge()->color('primary'),
                TablesFacade\Columns\TextColumn::make('location')->badge()->color(fn (string $state) => match ($state) {
                    'indoor' => 'gray',
                    'outdoor' => 'success',
                    'terrace' => 'warning',
                    'private' => 'info',
                    default => 'gray',
                }),
                TablesFacade\Columns\TextColumn::make('bookings_count')->counts('bookings')->label('Bookings'),
                TablesFacade\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                TablesFacade\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
