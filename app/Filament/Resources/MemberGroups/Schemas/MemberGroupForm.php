<?php

declare(strict_types=1);

namespace App\Filament\Resources\MemberGroups\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->description('Primary details identifying this coalition member organization.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Organization Name')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Official name of the community organization or group.'),

                            TextInput::make('focus_area')
                                ->label('Focus Area / Sector')
                                ->placeholder('e.g. Healthcare, Youth, Food Security')
                                ->maxLength(255)
                                ->helperText('Primary cause or sector this organization focuses on.'),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('founded_year')
                                ->label('Founded Year')
                                ->placeholder('e.g. 2018')
                                ->maxLength(20)
                                ->helperText('Year this organization was originally founded.'),

                            TextInput::make('website_url')
                                ->label('Website URL')
                                ->url()
                                ->placeholder('https://...')
                                ->maxLength(255)
                                ->helperText('External website or profile URL for this member group.'),

                            Toggle::make('is_visible')
                                ->label('Visible on Site')
                                ->default(true)
                                ->helperText('Show or hide this organization across the landing page.'),
                        ]),

                        Textarea::make('short_description')
                            ->label('Short Description / Summary')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Brief overview displayed in coalition member cards and grids.'),

                        RichEditor::make('long_description')
                            ->label('Full Description & Mission')
                            ->helperText('Detailed history, mission, projects, and impact story of the group.'),
                    ]),

                Section::make('Visual Assets')
                    ->description('Logos and photography representing the organization.')
                    ->schema([
                        Grid::make(2)->schema([
                            MediaHelper::logo('logo', 'member-groups/logos', 'Organization Logo')
                                ->helperText('Square or horizontal logo shown in coalition grids and badges.'),

                            MediaHelper::image('photo', 'member-groups/photos', 'Feature Photo')
                                ->helperText('Field photo showcasing community work in action.'),
                        ]),
                    ]),

                Section::make('Social & Community Links')
                    ->description('Social media profiles for direct community engagement.')
                    ->schema([
                        Repeater::make('social_links')
                            ->label('Social Media Profiles')
                            ->schema([
                                Select::make('platform')
                                    ->label('Platform')
                                    ->options([
                                        'facebook' => 'Facebook',
                                        'x' => 'X (Twitter)',
                                        'instagram' => 'Instagram',
                                        'telegram' => 'Telegram',
                                        'linkedin' => 'LinkedIn',
                                        'youtube' => 'YouTube',
                                        'tiktok' => 'TikTok',
                                        'whatsapp' => 'WhatsApp',
                                        'custom' => 'Website / Other',
                                    ])
                                    ->required(),

                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->placeholder('https://...'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->helperText('Add social channels for this specific member group.'),
                    ]),
            ]);
    }
}
