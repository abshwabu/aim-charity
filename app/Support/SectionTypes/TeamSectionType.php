<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class TeamSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'team';
    }

    public static function getLabel(): string
    {
        return 'Team & Coalition Leadership';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'A dedicated steering committee comprised of community organizers, logistics coordinators, and humanitarian workers.',
            'layout' => 'grid',
            'items_count' => 8,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Team Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Intro text introducing leadership and team members...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('Team Layout')
                            ->options([
                                'grid' => 'Member Profile Cards (4 Columns)',
                                'compact' => 'Compact Avatar Grid',
                                'carousel' => 'Team Carousel',
                            ])
                            ->default('grid'),

                        TextInput::make('content.items_count')
                            ->label('Max Team Members to Show')
                            ->numeric()
                            ->default(8),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Team member names, roles, bios, photos, and linked member organizations are managed in the Team Members resource.'),
                ]),
        ];
    }
}
