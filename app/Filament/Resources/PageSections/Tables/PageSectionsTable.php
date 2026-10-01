<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections\Tables;

use App\Filament\Resources\PageSections\PageSectionResource;
use App\Models\PageSection;
use App\Support\SectionTypes;
use App\Support\Site;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PageSectionsTable
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

                TextColumn::make('nav_label')
                    ->label('Navigation Label')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Key')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Section Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SectionTypes::label($state))
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'primary',
                        'donate' => 'warning',
                        'volunteer' => 'info',
                        'contact' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                ToggleColumn::make('is_visible')
                    ->label('Visible')
                    ->afterStateUpdated(fn () => Site::flushCache()),

                TextColumn::make('anchor')
                    ->label('Anchor')
                    ->prefix('#')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),

                ReplicateAction::make()
                    ->label('Duplicate')
                    ->modalHeading('Duplicate Section')
                    ->beforeReplicaSaved(function (PageSection $replica): void {
                        $baseKey = $replica->key;
                        $counter = 2;
                        while (PageSection::query()->where('key', "{$baseKey}-{$counter}")->exists()) {
                            $counter++;
                        }
                        $replica->key = "{$baseKey}-{$counter}";
                        $replica->nav_label = $replica->nav_label ? "{$replica->nav_label} (Copy)" : null;
                        $replica->anchor = $replica->key;
                        $replica->sort_order = (PageSection::max('sort_order') ?? 0) + 1;
                    })
                    ->after(fn () => Site::flushCache()),

                DeleteAction::make()
                    ->hidden(fn (PageSection $record): bool => in_array($record->key, PageSectionResource::CORE_KEYS, true))
                    ->after(fn () => Site::flushCache()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records): void {
                            $records
                                ->reject(fn (PageSection $record): bool => in_array($record->key, PageSectionResource::CORE_KEYS, true))
                                ->each->delete();

                            Site::flushCache();
                        }),
                ]),
            ]);
    }
}
