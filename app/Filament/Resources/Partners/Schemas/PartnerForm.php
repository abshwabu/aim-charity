<?php

declare(strict_types=1);

namespace App\Filament\Resources\Partners\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Partner Information')
                    ->description('Corporate, NGO, or philanthropic partners supporting the coalition.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Partner / Organization Name')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Name of the supporting partner organization.'),

                            TextInput::make('url')
                                ->label('Partner Website URL')
                                ->url()
                                ->placeholder('https://...')
                                ->maxLength(255)
                                ->helperText('External link to the partner website or initiative page.'),
                        ]),

                        Toggle::make('is_visible')
                            ->label('Visible on Site')
                            ->default(true)
                            ->helperText('Display this partner logo in the partners & allies section.'),
                    ]),

                Section::make('Brand Asset')
                    ->description('Partner logo for the logo ribbon/grid.')
                    ->schema([
                        MediaHelper::logo('logo', 'partners', 'Partner Logo')
                            ->required()
                            ->helperText('Horizontal or vector/PNG logo displayed on light or neutral backgrounds.'),
                    ]),
            ]);
    }
}
