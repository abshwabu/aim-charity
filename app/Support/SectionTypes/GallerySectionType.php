<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class GallerySectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'gallery';
    }

    public static function getLabel(): string
    {
        return 'Photo Gallery (Field Work)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Moments of solidarity, relief distributions, community workshops, and clean water commissioning across Ethiopia.',
            'layout' => 'grid',
            'items_count' => 8,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Gallery Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory copy describing the gallery imagery...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Gallery Layout Style')
                            ->options([
                                'grid' => 'Square Mosaic Grid (4 Columns)',
                                'masonry' => 'Dynamic Masonry Grid',
                                'carousel' => 'Full-Width Photo Carousel',
                            ])
                            ->default('grid'),

                        TextInput::make('content.items_count')
                            ->label('Max Photos to Display')
                            ->numeric()
                            ->default(8),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Gallery images, captions, and alt texts are managed in the Gallery Items resource.'),
                ]),
        ];
    }
}
