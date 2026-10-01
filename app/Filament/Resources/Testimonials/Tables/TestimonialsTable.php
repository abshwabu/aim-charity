<?php

declare(strict_types=1);

namespace App\Filament\Resources\Testimonials\Tables;

use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialsTable
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

                ImageColumn::make('author_photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('author_name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('author_role')
                    ->label('Role')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('memberGroup.name')
                    ->label('Member Group')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Coalition-wide'),

                TextColumn::make('quote')
                    ->label('Quote')
                    ->limit(50)
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
