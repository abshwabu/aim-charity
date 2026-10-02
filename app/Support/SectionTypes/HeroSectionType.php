<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class HeroSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'hero';
    }

    public static function getLabel(): string
    {
        return 'Hero (Main Introduction)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-home';
    }

    public static function getDefaultContent(): array
    {
        return [
            'headline' => 'Started by Friends. Sustained by Weekly Kindness.',
            'subheadline' => 'A small-town charity association of 25 close friends. We pool weekly donations to provide food, clothes, and direct local support to neighbors in need.',
            'video_url' => null,
            'background_image' => null,
            'stat_chips' => [
                ['value' => '25', 'label' => 'Dedicated Members', 'icon' => 'heroicon-o-user-group'],
                ['value' => '85+', 'label' => 'Town Families Supported', 'icon' => 'heroicon-o-heart'],
                ['value' => '100%', 'label' => 'Direct to Neighbors', 'icon' => 'heroicon-o-check-badge'],
            ],
            'collage_images' => [],
            'buttons' => [
                ['label' => 'Start Weekly Giving', 'link_type' => 'section', 'target' => 'donate', 'url' => null, 'style' => 'primary'],
                ['label' => 'Our Programs', 'link_type' => 'section', 'target' => 'programs', 'url' => null, 'style' => 'outline'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Hero Media & Visuals')
                ->description('Configure the background visual, video stream, and imagery.')
                ->schema([
                    Grid::make(2)->schema([
                        FileUpload::make('content.background_image')
                            ->label('Hero Background Image')
                            ->disk('public')
                            ->directory('sections/hero')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Large hero banner image (recommended 1920x1080).'),

                        TextInput::make('content.video_url')
                            ->label('Background / Showcase Video URL')
                            ->placeholder('https://www.youtube.com/watch?v=... or direct MP4 link')
                            ->url()
                            ->helperText('Optional video embed or link shown in the hero section.'),
                    ]),

                    FileUpload::make('content.collage_images')
                        ->label('Image Collage (Up to 4 Images)')
                        ->disk('public')
                        ->directory('sections/hero/collage')
                        ->image()
                        ->imageEditor()
                        ->multiple()
                        ->maxFiles(4)
                        ->maxSize(5120)
                        ->helperText('Visual photo collage showcasing real community work in Ethiopia.'),
                ]),

            Section::make('Hero Content & Headlines')
                ->schema([
                    TextInput::make('content.headline')
                        ->label('Hero Headline')
                        ->placeholder('Started by Friends. Sustained by Weekly Kindness.')
                        ->helperText('High-impact title displayed prominently at the very top of the page.'),

                    Textarea::make('content.subheadline')
                        ->label('Hero Subheadline')
                        ->rows(3)
                        ->placeholder('A small-town charity association of 25 close friends...')
                        ->helperText('Clear subtitle explaining the purpose of our small-town charity association.'),

                    static::buttonRepeater('content.buttons', 'Hero Action Buttons'),
                ]),

            Section::make('Floating Stat Chips')
                ->description('Small highlight chips showing quick proof-points in the hero.')
                ->schema([
                    Repeater::make('content.stat_chips')
                        ->label('Stat Chips')
                        ->itemLabel(fn (array $state): ?string => isset($state['value']) ? "{$state['value']} — ".($state['label'] ?? '') : null)
                        ->reorderable()
                        ->collapsible()
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('value')
                                    ->label('Metric / Value')
                                    ->nullable()
                                    ->placeholder('e.g. 50K+'),

                                TextInput::make('label')
                                    ->label('Label')
                                    ->nullable()
                                    ->placeholder('e.g. People Helped'),

                                TextInput::make('icon')
                                    ->label('Heroicon Name')
                                    ->placeholder('e.g. heroicon-o-heart')
                                    ->helperText('Valid Heroicon name (e.g. heroicon-o-user-group).'),
                            ]),
                        ]),
                ]),
        ];
    }
}
