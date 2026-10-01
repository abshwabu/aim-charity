<?php

declare(strict_types=1);

namespace App\Filament\Resources\Steps\Schemas;

use App\Support\IconHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StepForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Process Step')
                    ->description('Step item for the "How It Works" workflow timeline.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Step Title')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g. 1. Grassroots Identification')
                                ->helperText('Title or phase header for this process milestone.'),

                            Select::make('icon')
                                ->label('Icon')
                                ->options(IconHelper::options())
                                ->searchable()
                                ->placeholder('Select an icon')
                                ->helperText('Heroicon displayed in the milestone badge or circle.'),
                        ]),

                        Textarea::make('description')
                            ->label('Step Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Detailed summary explaining what occurs during this stage of operations.'),

                        Toggle::make('is_visible')
                            ->label('Visible on Site')
                            ->default(true)
                            ->helperText('Show or hide this step in the public workflow section.'),
                    ]),
            ]);
    }
}
