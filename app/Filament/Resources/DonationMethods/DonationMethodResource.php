<?php

declare(strict_types=1);

namespace App\Filament\Resources\DonationMethods;

use App\Filament\Resources\DonationMethods\Pages\CreateDonationMethod;
use App\Filament\Resources\DonationMethods\Pages\EditDonationMethod;
use App\Filament\Resources\DonationMethods\Pages\ListDonationMethods;
use App\Filament\Resources\DonationMethods\Schemas\DonationMethodForm;
use App\Filament\Resources\DonationMethods\Tables\DonationMethodsTable;
use App\Models\DonationMethod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DonationMethodResource extends Resource
{
    protected static ?string $model = DonationMethod::class;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Donation Methods';

    protected static ?string $modelLabel = 'Donation Method';

    protected static ?string $pluralModelLabel = 'Donation Methods';

    public static function form(Schema $schema): Schema
    {
        return DonationMethodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationMethodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDonationMethods::route('/'),
            'create' => CreateDonationMethod::route('/create'),
            'edit' => EditDonationMethod::route('/{record}/edit'),
        ];
    }
}
