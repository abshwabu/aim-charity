<?php

declare(strict_types=1);

namespace App\Filament\Resources\DonationMethods\Tables;

use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DonationMethodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->afterReordering(fn () => Site::flushCache())
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public'),

                TextColumn::make('label')
                    ->label('Channel / Bank')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Account number copied to clipboard')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—'),

                ToggleColumn::make('is_visible')
                    ->label('Visible')
                    ->afterStateUpdated(fn () => Site::flushCache()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->after(fn () => Site::flushCache()),
            ])
            ->toolbarActions([
                TableHelper::bulkActions(),
            ]);
    }
}
