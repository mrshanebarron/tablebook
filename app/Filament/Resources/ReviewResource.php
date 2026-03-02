<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedStar;
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Review Details')->schema([
                Forms\Components\TextInput::make('guest_name')->disabled(),
                Forms\Components\TextInput::make('rating')->disabled(),
                Forms\Components\Textarea::make('comment')->disabled()->columnSpanFull(),
                Forms\Components\TextInput::make('youtube_url')->disabled(),
                Forms\Components\Toggle::make('is_approved'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guest_name')->searchable(),
                Tables\Columns\TextColumn::make('booking.table.name')->label('Table'),
                Tables\Columns\TextColumn::make('rating')->badge()->color(fn (int $state) => match (true) {
                    $state >= 4 => 'success',
                    $state >= 3 => 'warning',
                    default => 'danger',
                }),
                Tables\Columns\TextColumn::make('comment')->limit(40),
                Tables\Columns\IconColumn::make('is_approved')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (Review $record) => !$record->is_approved)
                    ->action(fn (Review $record) => $record->update(['is_approved' => true])),
                Tables\Actions\Action::make('reject')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->visible(fn (Review $record) => $record->is_approved)
                    ->action(fn (Review $record) => $record->update(['is_approved' => false])),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
