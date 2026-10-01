<?php

declare(strict_types=1);

namespace App\Filament\Resources\MemberGroups;

use App\Filament\Resources\MemberGroups\Pages\CreateMemberGroup;
use App\Filament\Resources\MemberGroups\Pages\EditMemberGroup;
use App\Filament\Resources\MemberGroups\Pages\ListMemberGroups;
use App\Filament\Resources\MemberGroups\Schemas\MemberGroupForm;
use App\Filament\Resources\MemberGroups\Tables\MemberGroupsTable;
use App\Models\MemberGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MemberGroupResource extends Resource
{
    protected static ?string $model = MemberGroup::class;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Member Groups';

    protected static ?string $modelLabel = 'Member Group';

    protected static ?string $pluralModelLabel = 'Member Groups';

    public static function form(Schema $schema): Schema
    {
        return MemberGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemberGroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberGroups::route('/'),
            'create' => CreateMemberGroup::route('/create'),
            'edit' => EditMemberGroup::route('/{record}/edit'),
        ];
    }
}
