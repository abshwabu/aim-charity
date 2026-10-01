<?php

declare(strict_types=1);

namespace App\Filament\Resources\Partners\Tables;

use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PartnersTable
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

                TextColumn::make('name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('url')
                    ->label('Website')
                    ->url(fn ($state): ?string => is_string($state) && filled($state) ? $state : null)
                    ->openUrlInNewTab()
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
