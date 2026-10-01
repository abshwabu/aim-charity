<?php

namespace App\Filament\Resources\DonationMethods\Pages;

use App\Filament\Resources\DonationMethods\DonationMethodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDonationMethod extends EditRecord
{
    protected static string $resource = DonationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
