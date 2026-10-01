<?php

declare(strict_types=1);

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Frequently Asked Question')
                    ->description('Questions and informative answers for prospective donors and volunteers.')
                    ->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. How are coalition funds distributed among member groups?')
                            ->helperText('The question headline displayed in the accordion header.'),

                        RichEditor::make('answer')
                            ->label('Answer')
                            ->required()
                            ->helperText('Thorough, helpful response shown when the FAQ accordion is opened.'),

                        Toggle::make('is_visible')
                            ->label('Visible on Site')
                            ->default(true)
                            ->helperText('Toggle whether this question appears in the public FAQ section.'),
                    ]),
            ]);
    }
}
