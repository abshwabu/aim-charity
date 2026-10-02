<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class ImpactStatsSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'impact_stats';
    }

    public static function getLabel(): string
    {
        return 'Impact Stats (Numbers & Metrics)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Measurable impact delivered directly to elderly neighbors and families in our town through our single group of 25 members.',
            'layout' => 'grid',
            'items_count' => 4,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Impact Metrics Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Context for the metrics displayed...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Metrics Layout Style')
                            ->options([
                                'grid' => '4-Column Metric Grid',
                                'cards' => 'Elevated Stat Cards with Icons',
                                'strip' => 'Minimal Horizontal Counter Strip',
                            ])
                            ->default('grid'),

                        TextInput::make('content.items_count')
                            ->label('Max Metrics to Show')
                            ->numeric()
                            ->default(4)
                            ->helperText('Number of stat items to display.'),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Individual stat values, suffixes, labels, and icons are managed in the Impact Stats resource.'),
                ]),
        ];
    }
}
