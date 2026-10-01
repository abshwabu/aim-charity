<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerApplications;

use App\Filament\Resources\VolunteerApplications\Pages\EditVolunteerApplication;
use App\Filament\Resources\VolunteerApplications\Pages\ListVolunteerApplications;
use App\Filament\Resources\VolunteerApplications\Schemas\VolunteerApplicationForm;
use App\Filament\Resources\VolunteerApplications\Tables\VolunteerApplicationsTable;
use App\Models\VolunteerApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VolunteerApplicationResource extends Resource
{
    protected static ?string $model = VolunteerApplication::class;

    protected static string|UnitEnum|null $navigationGroup = 'Inbox';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Volunteer Applications';

    protected static ?string $modelLabel = 'Volunteer Application';

    protected static ?string $pluralModelLabel = 'Volunteer Applications';

    public static function getNavigationBadge(): ?string
    {
        $count = VolunteerApplication::query()
            ->where('status', VolunteerApplication::STATUS_NEW)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return VolunteerApplicationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VolunteerApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVolunteerApplications::route('/'),
            'edit' => EditVolunteerApplication::route('/{record}/edit'),
        ];
    }
}
