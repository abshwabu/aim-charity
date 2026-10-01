<?php

declare(strict_types=1);

namespace App\Filament\Resources\Programs\Schemas;

use App\Support\IconHelper;
use App\Support\MediaHelper;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Program Details')
                    ->description('Primary description and visual icon for this initiative.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Program Title')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Name of the program or initiative, e.g. "Community Health Outreach".'),

                            Select::make('icon')
                                ->label('Program Icon')
                                ->options(IconHelper::options())
                                ->searchable()
                                ->placeholder('Select an icon')
                                ->helperText('Heroicon displayed on program badges and cards.'),
                        ]),

                        RichEditor::make('description')
                            ->label('Description')
                            ->helperText('Detailed overview of goals, scope, and impact.'),

                        Grid::make(3)->schema([
                            TextInput::make('link_url')
                                ->label('Action / Learn More URL')
                                ->url()
                                ->placeholder('https://...')
                                ->helperText('Optional destination link for the program CTA button.'),

                            TextInput::make('link_label')
                                ->label('Action Button Label')
                                ->placeholder('e.g. Learn More / Support Program')
                                ->helperText('Button text displayed when a link is specified.'),

                            Toggle::make('is_visible')
                                ->label('Visible on Site')
                                ->default(true)
                                ->helperText('Show or hide this program on the public landing page.'),
                        ]),
                    ]),

                Section::make('Media')
                    ->description('Feature imagery representing this program.')
                    ->schema([
                        MediaHelper::image('image', 'programs', 'Cover Image')
                            ->helperText('High-resolution photo displayed on program cards and modals.'),
                    ]),
            ]);
    }
}
