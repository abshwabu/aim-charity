<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class AboutSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'about';
    }

    public static function getLabel(): string
    {
        return 'About the Association';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-information-circle';
    }

    public static function getDefaultContent(): array
    {
        return [
            'coalition_explainer' => 'Aim Charity was started by 25 close friends in our small town. We pool weekly donations to care for elderly neighbors, provide food and clothes, and support local needs — 100% direct with zero overhead.',
            'mission' => 'To care for vulnerable neighbors in our town through consistent weekly giving, personal visits, and community solidarity.',
            'vision' => 'A town where no elder goes hungry, no child lacks warm clothes or school supplies, and neighbors always support each other.',
            'story' => '<p>Started by 25 close childhood friends around a coffee table, Aim Charity is a small-town charity association powered by weekly donations. We pool small contributions each week to directly support neighbors in need with complete openness and personal care.</p>',
            'images' => [],
            'values' => [
                ['icon' => 'heroicon-o-heart', 'title' => 'Weekly Direct Care', 'text' => 'Small consistent weekly gifts deliver direct food, medical care, and dignity.'],
                ['icon' => 'heroicon-o-shield-check', 'title' => 'Complete Openness', 'text' => '100% direct aid with every receipt and distribution shared openly with all friends.'],
                ['icon' => 'heroicon-o-user-group', 'title' => 'Neighborly Solidarity', 'text' => 'Friends and community members looking after each other as family.'],
            ],
            'buttons' => [
                ['label' => 'See What We Do', 'link_type' => 'section', 'target' => 'programs', 'url' => null, 'style' => 'primary'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Association Identity & Story')
                ->description('Describe the friend-founded town association and weekly donation story.')
                ->schema([
                    Textarea::make('content.coalition_explainer')
                        ->label('Association Story & Giving Model')
                        ->rows(3)
                        ->placeholder('Explain how Aim Charity operates as a small-town charity started by friends...')
                        ->helperText('Explains our small-town charity association model of 25 friends.'),

                    RichEditor::make('content.story')
                        ->label('Association Origin & Story')
                        ->helperText('Detailed background story on how our 25 members started giving together.'),

                    FileUpload::make('content.images')
                        ->label('About Showcase Photos')
                        ->disk('public')
                        ->directory('sections/about')
                        ->image()
                        ->imageEditor()
                        ->multiple()
                        ->maxFiles(6)
                        ->maxSize(5120)
                        ->helperText('Photographs illustrating town food and clothing distributions.'),
                ]),

            Section::make('Mission & Vision')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('content.mission')
                            ->label('Mission Statement')
                            ->rows(3)
                            ->placeholder('Our mission is to...')
                            ->helperText('Concise statement of our 25 members purpose.'),

                        Textarea::make('content.vision')
                            ->label('Vision Statement')
                            ->rows(3)
                            ->placeholder('Our vision is a town where...')
                            ->helperText('Long-term aspiration for our town neighbors.'),
                    ]),
                ]),

            Section::make('Core Values')
                ->description('List the ethical principles guiding our 25 members.')
                ->schema([
                    Repeater::make('content.values')
                        ->label('Values')
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->reorderable()
                        ->collapsible()
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('icon')
                                    ->label('Heroicon Name')
                                    ->placeholder('heroicon-o-shield-check')
                                    ->helperText('Heroicon name for value symbol.'),

                                TextInput::make('title')
                                    ->label('Value Title')
                                    ->nullable()
                                    ->placeholder('e.g. Radical Transparency'),

                                Textarea::make('text')
                                    ->label('Description')
                                    ->rows(2)
                                    ->nullable()
                                    ->placeholder('Explain what this value means in practice.'),
                            ]),
                        ]),
                ]),

            Section::make('Section Action')
                ->schema([
                    static::buttonRepeater('content.buttons', 'About Section Buttons'),
                ]),
        ];
    }
}
