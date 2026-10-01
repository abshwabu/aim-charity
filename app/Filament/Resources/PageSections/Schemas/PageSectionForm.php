<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageSections\Schemas;

use App\Models\PageSection;
use App\Support\SectionTypes;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        $contentSchemas = [];

        foreach (SectionTypes::all() as $typeKey => $typeClass) {
            $contentSchemas[] = Group::make($typeClass::formSchema())
                ->visible(fn (Get $get, ?PageSection $record): bool => ($record?->type ?? $get('type')) === $typeKey);
        }

        return $schema
            ->components([
                Tabs::make('Section Settings')
                    ->tabs([
                        Tab::make('General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Identification & Visibility')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('nav_label')
                                                ->label('Navigation Label')
                                                ->placeholder('e.g. About Us')
                                                ->helperText('Text displayed in header navigation links.'),

                                            TextInput::make('anchor')
                                                ->label('HTML Anchor ID')
                                                ->placeholder('about')
                                                ->helperText('Section ID for on-page anchor navigation (e.g. #about).'),

                                            Toggle::make('is_visible')
                                                ->label('Visible on Site')
                                                ->default(true)
                                                ->helperText('Toggle whether this section appears on the live page.'),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('key')
                                                ->label('Unique Section Key')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                ->helperText('Internal identifier used to query this section in code.'),

                                            Select::make('type')
                                                ->label('Section Type')
                                                ->options(SectionTypes::options())
                                                ->required()
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                ->live()
                                                ->helperText('Select template type to determine available content fields.'),
                                        ]),
                                    ]),

                                Section::make('Header & Body Copy')
                                    ->schema([
                                        TextInput::make('content.eyebrow')
                                            ->label('Eyebrow / Category Tag')
                                            ->placeholder('e.g. Our Mission')
                                            ->helperText('Small accent label displayed above the main heading.'),

                                        TextInput::make('content.heading')
                                            ->label('Main Heading')
                                            ->placeholder('e.g. Empowering Grassroots Communities')
                                            ->helperText('Primary title of this section.'),

                                        TextInput::make('content.subheading')
                                            ->label('Subheading')
                                            ->placeholder('e.g. Uniting community organizations across Ethiopia...')
                                            ->helperText('Subtitle supporting the main heading.'),

                                        RichEditor::make('content.body')
                                            ->label('Rich Text Body Copy')
                                            ->helperText('Detailed section description or narrative body text.'),
                                    ]),
                            ]),

                        Tab::make('Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                ...$contentSchemas,
                            ]),

                        Tab::make('Style')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Background Configuration')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('style.background_type')
                                                ->label('Background Type')
                                                ->options([
                                                    'none' => 'Transparent / Default Site Background',
                                                    'color' => 'Custom Solid Color',
                                                    'image' => 'Background Image with Overlay',
                                                    'gradient' => 'CSS Gradient',
                                                ])
                                                ->default('none')
                                                ->live()
                                                ->helperText('Choose how the background of this section should be rendered.'),

                                            ColorPicker::make('style.background_color')
                                                ->label('Background Color')
                                                ->visible(fn (Get $get): bool => in_array($get('style.background_type'), ['color', 'image', 'gradient'], true))
                                                ->helperText('Hex color applied to section background.'),
                                        ]),

                                        FileUpload::make('style.background_image')
                                            ->label('Background Image')
                                            ->disk('public')
                                            ->directory('sections/backgrounds')
                                            ->image()
                                            ->imageEditor()
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->visible(fn (Get $get): bool => $get('style.background_type') === 'image')
                                            ->helperText('Full-width image for section background.'),

                                        Grid::make(2)->schema([
                                            TextInput::make('style.background_gradient')
                                                ->label('CSS Gradient (Tailwind or CSS)')
                                                ->placeholder('from-emerald-900 via-slate-900 to-black')
                                                ->visible(fn (Get $get): bool => $get('style.background_type') === 'gradient')
                                                ->helperText('CSS or Tailwind gradient classes.'),

                                            Select::make('style.overlay_opacity')
                                                ->label('Dark Image Overlay')
                                                ->options([
                                                    'none' => 'None (0%)',
                                                    'light' => 'Light Tint (20%)',
                                                    'medium' => 'Medium Tint (50%)',
                                                    'dark' => 'Dark Tint (75%)',
                                                    'heavy' => 'Heavy Tint (90%)',
                                                ])
                                                ->default('none')
                                                ->visible(fn (Get $get): bool => $get('style.background_type') === 'image')
                                                ->helperText('Dark overlay opacity to enhance text contrast.'),
                                        ]),
                                    ]),

                                Section::make('Layout & Typography Spacing')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Select::make('style.text_theme')
                                                ->label('Text Contrast Theme')
                                                ->options([
                                                    'light' => 'Dark Text (for Light Background)',
                                                    'dark' => 'Light / White Text (for Dark Background)',
                                                ])
                                                ->default('light')
                                                ->helperText('Text color scheme suited for the chosen background.'),

                                            Select::make('style.vertical_padding')
                                                ->label('Vertical Padding (Spacing)')
                                                ->options([
                                                    'none' => 'None (py-0)',
                                                    's' => 'Small (py-8)',
                                                    'm' => 'Medium (py-16)',
                                                    'l' => 'Large (py-24)',
                                                    'xl' => 'Extra Large (py-32)',
                                                ])
                                                ->default('m')
                                                ->helperText('Top and bottom padding for this section.'),

                                            Select::make('style.alignment')
                                                ->label('Header & Content Alignment')
                                                ->options([
                                                    'left' => 'Left Aligned',
                                                    'center' => 'Center Aligned',
                                                    'right' => 'Right Aligned',
                                                ])
                                                ->default('center')
                                                ->helperText('Default text alignment of section headings and descriptions.'),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->persistTab()
                    ->id('page-section-tabs'),
            ]);
    }
}
