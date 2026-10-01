<?php

declare(strict_types=1);

namespace App\Filament\Resources\ImpactStats;

namespace App\Filament\Resources\ImpactStats\Tables;

use App\Models\ImpactStat;
use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ImpactStatsTable
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

                TextColumn::make('value')
                    ->label('Metric')
                    ->formatStateUsing(fn ($state, ImpactStat $record): string => "{$state}".($record->suffix ?? ''))
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('label')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('gray')
                    ->placeholder('None'),

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
