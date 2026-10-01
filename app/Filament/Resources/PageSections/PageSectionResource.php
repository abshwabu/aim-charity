<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections;

use App\Filament\Resources\PageSections\Pages\CreatePageSection;
use App\Filament\Resources\PageSections\Pages\EditPageSection;
use App\Filament\Resources\PageSections\Pages\ListPageSections;
use App\Filament\Resources\PageSections\Schemas\PageSectionForm;
use App\Filament\Resources\PageSections\Tables\PageSectionsTable;
use App\Models\PageSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Page Sections';

    protected static ?string $pluralModelLabel = 'Page Sections';

    protected static ?string $modelLabel = 'Page Section';

    /**
     * Essential core sections that cannot be deleted.
     *
     * @var list<string>
     */
    public const CORE_KEYS = [
        'hero',
        'about',
        'contact',
        'donate',
    ];

    public static function form(Schema $schema): Schema
    {
        return PageSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageSectionsTable::configure($table);
    }

    public static function canDelete(Model $record): bool
    {
        /** @var PageSection $record */
        return ! in_array($record->key, static::CORE_KEYS, true);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageSections::route('/'),
            'create' => CreatePageSection::route('/create'),
            'edit' => EditPageSection::route('/{record}/edit'),
        ];
    }
}
