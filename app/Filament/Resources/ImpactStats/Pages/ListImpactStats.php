<?php

namespace App\Filament\Resources\ImpactStats\Pages;

use App\Filament\Resources\ImpactStats\ImpactStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListImpactStats extends ListRecords
{
    protected static string $resource = ImpactStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
