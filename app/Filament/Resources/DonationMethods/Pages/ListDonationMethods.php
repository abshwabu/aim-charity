<?php

namespace App\Filament\Resources\DonationMethods\Pages;

use App\Filament\Resources\DonationMethods\DonationMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDonationMethods extends ListRecords
{
    protected static string $resource = DonationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
