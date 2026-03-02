<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Booking Details')->schema([
                Forms\Components\TextInput::make('reference')->disabled(),
                Forms\Components\TextInput::make('guest_name')->required(),
                Forms\Components\TextInput::make('guest_email')->email()->required(),
                Forms\Components\TextInput::make('guest_phone')->tel(),
                Forms\Components\TextInput::make('party_size')->numeric()->required(),
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                    'completed' => 'Completed',
                ])->required(),
                Forms\Components\Textarea::make('special_requests')->rows(2)->columnSpanFull(),
            ])->columns(3),
            Forms\Components\Section::make('Table & Time')->schema([
                Forms\Components\Select::make('table_id')
                    ->relationship('table', 'name')
                    ->required(),
                Forms\Components\Select::make('time_slot_id')
                    ->relationship('timeSlot', 'start_time')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('guest_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('guest_email')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('table.name')->label('Table'),
                Tables\Columns\TextColumn::make('timeSlot.date')->date()->label('Date')->sortable(),
                Tables\Columns\TextColumn::make('timeSlot.start_time')->label('Time'),
                Tables\Columns\TextColumn::make('party_size')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'confirmed' => 'success',
                    'pending' => 'warning',
                    'cancelled' => 'danger',
                    'completed' => 'info',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('coupon.code')->label('Coupon')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record) => $record->status === 'pending')
                    ->action(fn (Booking $record) => $record->update(['status' => 'confirmed'])),
                Tables\Actions\Action::make('cancel')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record) => in_array($record->status, ['pending', 'confirmed']))
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'cancelled']);
                        $record->timeSlot->update(['is_available' => true]);
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
