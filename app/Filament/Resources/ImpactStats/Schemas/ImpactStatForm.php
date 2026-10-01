<?php

declare(strict_types=1);

namespace App\Filament\Resources\ImpactStats\Schemas;

use App\Support\IconHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ImpactStatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Impact Metric')
                    ->description('Public statistic demonstrating real-world reach and achievements.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('value')
                                ->label('Metric Value')
                                ->required()
                                ->placeholder('e.g. 50, 100, 25')
                                ->helperText('Numerical figure or base count for this statistic.'),

                            TextInput::make('suffix')
                                ->label('Suffix / Unit')
                                ->placeholder('e.g. +, %, k, M')
                                ->maxLength(20)
                                ->helperText('Symbol displayed directly after the value (e.g. "+", "k").'),

                            Select::make('icon')
                                ->label('Icon')
                                ->options(IconHelper::options())
                                ->searchable()
                                ->placeholder('Select an icon')
                                ->helperText('Graphic icon highlighting the statistic.'),
                        ]),

                        TextInput::make('label')
                            ->label('Metric Label / Description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. People Helped Across Regions')
                            ->helperText('Descriptive label displayed below the count.'),

                        Toggle::make('is_visible')
                            ->label('Visible on Site')
                            ->default(true)
                            ->helperText('Display this statistic in the public impact section.'),
                    ]),
            ]);
    }
}
