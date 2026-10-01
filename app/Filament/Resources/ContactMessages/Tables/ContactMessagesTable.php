<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable()
                    ->weight(fn (ContactMessage $record): string => $record->isUnread() ? 'bold' : 'normal'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('message')
                    ->label('Message Snippet')
                    ->limit(55)
                    ->tooltip(fn (ContactMessage $record): string => $record->message),

                TextColumn::make('read_at')
                    ->label('Status')
                    ->badge()
                    ->state(fn (ContactMessage $record): string => $record->isUnread() ? 'Unread' : 'Read')
                    ->color(fn (ContactMessage $record): string => $record->isUnread() ? 'warning' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('unread')
                    ->label('Unread only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at')),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('markRead')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->isUnread())
                    ->action(fn (ContactMessage $record) => $record->markAsRead()),

                Action::make('markUnread')
                    ->label('Mark Unread')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->visible(fn (ContactMessage $record): bool => ! $record->isUnread())
                    ->action(fn (ContactMessage $record) => $record->markAsUnread()),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Mark Selected Read')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->markAsRead())
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('markAsUnread')
                        ->label('Mark Selected Unread')
                        ->icon('heroicon-o-envelope')
                        ->action(fn (Collection $records) => $records->each->markAsUnread())
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
