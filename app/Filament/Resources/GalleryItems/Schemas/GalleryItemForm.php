<?php

declare(strict_types=1);

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image File')
                    ->description('High resolution photo for the photo showcase grid.')
                    ->schema([
                        MediaHelper::image('image', 'gallery', 'Photo')
                            ->required()
                            ->helperText('Photo uploaded to the public media gallery.'),
                    ]),

                Section::make('Context & Attribution')
                    ->description('Captions, accessibility text, and coalition group tagging.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('caption')
                                ->label('Caption')
                                ->maxLength(255)
                                ->placeholder('e.g. Distribution of food parcels in Wollo')
                                ->helperText('Short text displayed beneath the image or in the lightbox view.'),

                            TextInput::make('alt_text')
                                ->label('Alt Text (Accessibility & SEO)')
                                ->maxLength(255)
                                ->placeholder('e.g. Volunteers handing food packages to elderly residents')
                                ->helperText('Screen-reader description explaining what is in the photo.'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('member_group_id')
                                ->label('Associated Member Group')
                                ->relationship('memberGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->placeholder('Coalition-wide (No specific group)')
                                ->helperText('Optionally connect this photo to an organization in the coalition.'),

                            Toggle::make('is_visible')
                                ->label('Visible on Site')
                                ->default(true)
                                ->helperText('Display this photo in the landing page gallery grid.'),
                        ]),
                    ]),
            ]);
    }
}
