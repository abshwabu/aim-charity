<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class HowItWorksSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'how_it_works';
    }

    public static function getLabel(): string
    {
        return 'How It Works (Process & Steps)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'A clear, transparent process ensuring that every contribution reaches verified local needs efficiently.',
            'layout' => 'steps',
            'items_count' => 4,
            'buttons' => [
                ['label' => 'Join the Coalition', 'link_type' => 'section', 'target' => 'contact', 'url' => null, 'style' => 'primary'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Process Flow Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Intro text introducing how the coalition operates...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Process Layout')
                            ->options([
                                'steps' => 'Horizontal Step Timeline',
                                'cards' => 'Numbered Process Cards',
                                'vertical' => 'Vertical Flowchart',
                            ])
                            ->default('steps'),

                        TextInput::make('content.items_count')
                            ->label('Max Steps to Show')
                            ->numeric()
                            ->default(4),
                    ]),

                    static::buttonRepeater('content.buttons', 'Process Action Buttons'),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Step titles, descriptions, and step icons are managed in the Steps resource.'),
                ]),
        ];
    }
}
