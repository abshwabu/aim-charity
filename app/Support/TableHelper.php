<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;

class TableHelper
{
    /**
     * Standard bulk action group with show, hide, and delete.
     */
    public static function bulkActions(): BulkActionGroup
    {
        return BulkActionGroup::make([
            BulkAction::make('show')
                ->label('Show Selected')
                ->icon('heroicon-o-eye')
                ->deselectRecordsAfterCompletion()
                ->action(function (Collection $records): void {
                    $records->each->update(['is_visible' => true]);
                    Site::flushCache();
                }),

            BulkAction::make('hide')
                ->label('Hide Selected')
                ->icon('heroicon-o-eye-slash')
                ->deselectRecordsAfterCompletion()
                ->action(function (Collection $records): void {
                    $records->each->update(['is_visible' => false]);
                    Site::flushCache();
                }),

            DeleteBulkAction::make()
                ->after(fn () => Site::flushCache()),
        ]);
    }
}
