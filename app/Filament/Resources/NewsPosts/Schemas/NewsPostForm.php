<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsPosts\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Content')
                    ->description('Headlines, permalink, and article editorial body.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, string $operation): void {
                                    if ($operation === 'create' && filled($state)) {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->helperText('Headline of the news article or coalition project update.'),

                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Unique URL slug automatically derived from the title.'),
                        ]),

                        Grid::make(2)->schema([
                            DateTimePicker::make('published_at')
                                ->label('Publish Date & Time')
                                ->default(now())
                                ->helperText('Date stamp displayed on news cards and filters.'),

                            Toggle::make('is_visible')
                                ->label('Published (Visible on Site)')
                                ->default(true)
                                ->helperText('Disable to save this article as a draft without displaying on the public page.'),
                        ]),

                        Textarea::make('excerpt')
                            ->label('Excerpt / Summary')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Short teaser summary displayed in news feed preview cards.'),

                        RichEditor::make('body')
                            ->label('Full Article Body')
                            ->required()
                            ->helperText('Complete story text, images, updates, and quotes.'),
                    ]),

                Section::make('Feature Imagery')
                    ->description('Lead cover photo shown in previews and article headers.')
                    ->schema([
                        MediaHelper::image('cover_image', 'news', 'Cover Image')
                            ->helperText('Horizontal cover photo for news feed cards and social shares.'),
                    ]),
            ]);
    }
}
