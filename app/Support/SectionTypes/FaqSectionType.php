<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class FaqSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'faq';
    }

    public static function getLabel(): string
    {
        return 'Frequently Asked Questions (FAQ)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-question-mark-circle';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Answers to common questions regarding our single group of 25 friends, weekly donations, food and clothes distribution, and 100% direct accountability.',
            'layout' => 'accordion',
            'items_count' => 8,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('FAQ Configuration')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory copy inviting visitors to learn more...'),

                    Grid::make(2)->schema([
                        Select::make('content.layout')
                            ->label('FAQ Layout')
                            ->options([
                                'accordion' => 'Collapsible Accordion',
                                'two_columns' => 'Two-Column Q&A Grid',
                                'list' => 'Simple List',
                            ])
                            ->default('accordion'),

                        TextInput::make('content.items_count')
                            ->label('Max FAQs to Display')
                            ->numeric()
                            ->default(8),
                    ]),

                    Placeholder::make('management_note')
                        ->label('Item Management')
                        ->content('Questions and rich-text answers are managed in the FAQs resource.'),
                ]),
        ];
    }
}
