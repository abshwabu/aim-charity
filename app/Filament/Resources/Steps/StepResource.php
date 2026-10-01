<?php

declare(strict_types=1);

namespace App\Filament\Resources\Steps;

use App\Filament\Resources\Steps\Pages\CreateStep;
use App\Filament\Resources\Steps\Pages\EditStep;
use App\Filament\Resources\Steps\Pages\ListSteps;
use App\Filament\Resources\Steps\Schemas\StepForm;
use App\Filament\Resources\Steps\Tables\StepsTable;
use App\Models\Step;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class StepResource extends Resource
{
    protected static ?string $model = Step::class;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Process Steps';

    protected static ?string $modelLabel = 'Process Step';

    protected static ?string $pluralModelLabel = 'Process Steps';

    public static function form(Schema $schema): Schema
    {
        return StepForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StepsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSteps::route('/'),
            'create' => CreateStep::route('/create'),
            'edit' => EditStep::route('/{record}/edit'),
        ];
    }
}
