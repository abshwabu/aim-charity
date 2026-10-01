<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class TestimonialsSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'testimonials';
    }

    public static function getLabel(): string
    {
        return 'Testimonials & Community Voices';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chat-bubble-bottom-center-text';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Hear directly from the community leaders, elders, and families whose lives have been transformed by collective action.',
            'layout' => 'carousel',
            'items_count' => 6,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Testimonials Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory copy for stories and testimonials...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Display Layout')
                            ->options([
                                'carousel' => 'Interactive Testimonial Slider',
                                'grid' => 'Multi-Column Grid Cards',
                                'quote_block' => 'Featured Highlight Block',
                            ])
                            ->default('carousel'),

                        TextInput::make('content.items_count')
                            ->label('Max Testimonials to Display')
                            ->numeric()
                            ->default(6),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Testimonial quotes, authors, roles, photos, and linked member groups are managed in the Testimonials resource.'),
                ]),
        ];
    }
}
