<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class NewsSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'news';
    }

    public static function getLabel(): string
    {
        return 'News, Reports & Updates';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-newspaper';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Stay informed on recent coalition relief operations, financial accountability reports, and community developments.',
            'layout' => 'grid',
            'items_count' => 3,
            'buttons' => [
                ['label' => 'View All News & Reports', 'link_type' => 'url', 'target' => null, 'url' => '#news', 'style' => 'outline'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('News Articles Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Intro text introducing latest news and dispatches...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('News Layout')
                            ->options([
                                'grid' => 'Article Cards Grid (3 Columns)',
                                'list' => 'Detailed Feed List',
                                'lead' => 'Lead Feature + Two Side Posts',
                            ])
                            ->default('grid'),

                        TextInput::make('content.items_count')
                            ->label('Number of Posts to Display')
                            ->numeric()
                            ->default(3),
                    ]),

                    static::buttonRepeater('content.buttons', 'News Section Buttons'),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Articles, cover images, excerpts, slugs, and rich text bodies are managed in the News Posts resource.'),
                ]),
        ];
    }
}
