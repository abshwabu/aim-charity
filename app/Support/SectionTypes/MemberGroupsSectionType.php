<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class MemberGroupsSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'member_groups';
    }

    public static function getLabel(): string
    {
        return 'Association Members & Group Showcase';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Aim Charity is a single group of 25 childhood friends and town neighbors dedicated to local food, clothing, and neighbor support.',
            'layout' => 'grid',
            'show_all' => true,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Association Details')
                ->description('Configure display settings for association details.')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory explanation of our 25 members...')
                        ->helperText('Visible paragraph text right under the section heading.'),

                    Select::make('content.layout')
                        ->label('Display Layout')
                        ->options([
                            'grid' => 'Multi-Column Grid (Cards)',
                            'carousel' => 'Interactive Carousel / Slider',
                        ])
                        ->default('grid')
                        ->helperText('How member organization cards are arranged on desktop screens.'),

                    Toggle::make('content.show_all')
                        ->label('Show All Member Groups')
                        ->default(true)
                        ->helperText('If disabled, only the first 6 member groups will be shown with a load more button.'),

                    Placeholder::make('resource_notice')
                        ->label('Organization Management')
                        ->content('Individual member organizations (name, logo, description, focus area, photo, links) are managed separately in the Member Groups resource.'),
                ]),
        ];
    }
}
