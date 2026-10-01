<?php

declare(strict_types=1);

namespace App\Filament\Resources\Programs\Tables;

use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProgramsTable
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

                ImageColumn::make('image')
                    ->label('Cover')
                    ->disk('public')
                    ->rounded(),

                TextColumn::make('title')
                    ->label('Program Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('gray')
                    ->placeholder('None'),

                TextColumn::make('link_label')
                    ->label('CTA Label')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

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
