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
        return 'About the Coalition';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-information-circle';
    }

    public static function getDefaultContent(): array
    {
        return [
            'coalition_explainer' => 'Aim Charity is a coalition ("a group of groups") uniting localized Ethiopian community organizations to pool resources, prevent duplicate aid, and deliver direct emergency assistance and development.',
            'mission' => 'To empower vulnerable Ethiopian communities by coordinating grassroots efforts, optimizing charitable resources, and championing self-reliance.',
            'vision' => 'An Ethiopia where every community possesses the collective resilience, resources, and solidarity to support those in need.',
            'story' => '<p>Born out of grassroots neighborhood initiatives, Aim Charity recognized that isolated community groups achieve far greater impact when united. We coordinate logistics, verify needs, and ensure transparent distribution of every donation.</p>',
            'images' => [],
            'values' => [
                ['icon' => 'heroicon-o-shield-check', 'title' => 'Radical Transparency', 'text' => 'Full public accountability for every birr received and distributed.'],
                ['icon' => 'heroicon-o-user-group', 'title' => 'Grassroots Solidarity', 'text' => 'Local leadership knows their communities best; we listen and support.'],
                ['icon' => 'heroicon-o-sparkles', 'title' => 'Dignity in Relief', 'text' => 'Aid provided with respect, compassion, and a focus on long-term empowerment.'],
            ],
            'buttons' => [
                ['label' => 'Learn About Member Groups', 'link_type' => 'section', 'target' => 'member_groups', 'url' => null, 'style' => 'primary'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('The Coalition Identity')
                ->description('Describe the "group of groups" structure and overarching story.')
                ->schema([
                    Textarea::make('content.coalition_explainer')
                        ->label('"Group of Groups" Explainer')
                        ->rows(3)
                        ->placeholder('Explain how Aim Charity operates as an alliance of local community organizations...')
                        ->helperText('Explains the unique collaborative model uniting grassroots groups in Ethiopia.'),

                    RichEditor::make('content.story')
                        ->label('Coalition Origin & Narrative Story')
                        ->helperText('Detailed background story on how and why the coalition was founded.'),

                    FileUpload::make('content.images')
                        ->label('About Showcase Photos')
                        ->disk('public')
                        ->directory('sections/about')
                        ->image()
                        ->imageEditor()
                        ->multiple()
                        ->maxFiles(6)
                        ->maxSize(5120)
                        ->helperText('Photographs illustrating coalition field work and volunteer assemblies.'),
                ]),

            Section::make('Mission & Vision')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('content.mission')
                            ->label('Mission Statement')
                            ->rows(3)
                            ->placeholder('Our mission is to...')
                            ->helperText('Concise statement of the coalition purpose.'),

                        Textarea::make('content.vision')
                            ->label('Vision Statement')
                            ->rows(3)
                            ->placeholder('Our vision is an Ethiopia where...')
                            ->helperText('Long-term aspiration for the communities we serve.'),
                    ]),
                ]),

            Section::make('Core Values')
                ->description('List the ethical principles guiding all coalition members.')
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
