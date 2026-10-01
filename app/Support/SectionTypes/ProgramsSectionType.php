<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class ProgramsSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'programs';
    }

    public static function getLabel(): string
    {
        return 'Programs & Initiatives';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Our coordinated community programs provide immediate emergency assistance, medical support, clean water access, and livelihood development.',
            'layout' => 'grid',
            'items_count' => 6,
            'buttons' => [
                ['label' => 'Support Our Programs', 'link_type' => 'section', 'target' => 'donate', 'url' => null, 'style' => 'primary'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Programs Display Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Summary of the coalition key program pillars...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Display Layout')
                            ->options([
                                'grid' => 'Grid Cards (3 Columns)',
                                'carousel' => 'Card Carousel / Slider',
                                'list' => 'Detailed Alternating List',
                            ])
                            ->default('grid')
                            ->helperText('Visual layout structure for program cards.'),

                        TextInput::make('content.items_count')
                            ->label('Max Programs to Display')
                            ->numeric()
                            ->default(6)
                            ->helperText('Leave empty to display all active visible programs.'),
                    ]),

                    static::buttonRepeater('content.buttons', 'Section Action Buttons'),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Specific program titles, icons, descriptions, and imagery are managed in the Programs resource.'),
                ]),
        ];
    }
}
