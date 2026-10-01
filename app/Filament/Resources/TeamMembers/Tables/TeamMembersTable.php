<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamMembers\Tables;

use App\Support\Site;
use App\Support\TableHelper;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TeamMembersTable
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

                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('memberGroup.name')
                    ->label('Affiliation')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Central Leadership'),

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
