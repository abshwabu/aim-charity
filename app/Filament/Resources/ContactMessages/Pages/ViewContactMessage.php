<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property ContactMessage $record
 */
class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleReadStatus')
                ->label(fn (): string => $this->getRecord()->isUnread() ? 'Mark as Read' : 'Mark as Unread')
                ->icon(fn (): string => $this->getRecord()->isUnread() ? 'heroicon-o-check' : 'heroicon-o-envelope')
                ->color(fn (): string => $this->getRecord()->isUnread() ? 'success' : 'gray')
                ->action(function (): void {
                    /** @var ContactMessage $record */
                    $record = $this->getRecord();
                    if ($record->isUnread()) {
                        $record->markAsRead();
                    } else {
                        $record->markAsUnread();
                    }
                }),

            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var ContactMessage $record */
        $record = $this->getRecord();
        if ($record->isUnread()) {
            $record->markAsRead();
            $data['read_at'] = $record->fresh()->read_at?->toIso8601String();
        }

        return $data;
    }
}
