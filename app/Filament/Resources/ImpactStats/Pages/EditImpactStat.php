<?php

namespace App\Filament\Resources\ImpactStats\Pages;

use App\Filament\Resources\ImpactStats\ImpactStatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditImpactStat extends EditRecord
{
    protected static string $resource = ImpactStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
