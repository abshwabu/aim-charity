<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections\Pages;

use App\Filament\Resources\PageSections\PageSectionResource;
use App\Models\PageSection;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageSection extends EditRecord
{
    protected static string $resource = PageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Section')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn (PageSection $record): string => url('/#'.($record->anchor ?: $record->key)), shouldOpenInNewTab: true),

            DeleteAction::make()
                ->hidden(fn (PageSection $record): bool => in_array($record->key, PageSectionResource::CORE_KEYS, true)),
        ];
    }
}
