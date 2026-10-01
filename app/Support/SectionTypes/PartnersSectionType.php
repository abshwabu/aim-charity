<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class PartnersSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'partners';
    }

    public static function getLabel(): string
    {
        return 'Partners & Institutional Allies';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'We collaborate with civic institutions, humanitarian partners, and civic tech allies to amplify grassroots impact across Ethiopia.',
            'layout' => 'logo_strip',
            'items_count' => 12,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Partners Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Context for institutional alliances and partner logos...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Partner Logo Display')
                            ->options([
                                'logo_strip' => 'Continuous Scrolling Logo Marquee',
                                'grid' => 'Clean Logo Grid',
                                'cards' => 'Cards with Partner Names and Links',
                            ])
                            ->default('logo_strip'),

                        TextInput::make('content.items_count')
                            ->label('Max Partners to Show')
                            ->numeric()
                            ->default(12),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Partner names, logos, and website URLs are managed in the Partners resource.'),
                ]),
        ];
    }
}
