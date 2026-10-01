<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use App\Models\PageSection;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;

abstract class BaseSectionType
{
    /**
     * Unique identifier key for this section type.
     */
    abstract public static function getKey(): string;

    /**
     * Human-readable label for this section type.
     */
    abstract public static function getLabel(): string;

    /**
     * Heroicon name for this section type.
     */
    abstract public static function getIcon(): string;

    /**
     * Default content structure and initial values for this section type.
     *
     * @return array<string, mixed>
     */
    abstract public static function getDefaultContent(): array;

    /**
     * Filament form schema components specific to this section type.
     *
     * @return array<mixed>
     */
    abstract public static function formSchema(): array;

    /**
     * Reusable action button repeater component.
     */
    public static function buttonRepeater(string $name = 'content.buttons', string $label = 'Action Buttons'): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->helperText('Interactive buttons and call-to-actions displayed in this section.')
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->reorderable()
            ->collapsible()
            ->schema([
                Grid::make(4)->schema([
                    TextInput::make('label')
                        ->label('Button Label')
                        ->nullable()
                        ->placeholder('e.g. Donate Now')
                        ->helperText('Text displayed on the button.'),

                    Select::make('link_type')
                        ->label('Link Type')
                        ->options([
                            'section' => 'Jump to Section',
                            'url' => 'External / Custom URL',
                        ])
                        ->default('section')
                        ->live()
                        ->helperText('Whether the button scrolls to an anchor or navigates to a URL.'),

                    Select::make('target')
                        ->label('Target Section')
                        ->options(fn (): array => PageSection::query()->orderBy('sort_order')->pluck('key', 'key')->all())
                        ->visible(fn (Get $get): bool => ($get('link_type') ?? 'section') === 'section')
                        ->helperText('Select which page section to smoothly scroll to.'),

                    TextInput::make('url')
                        ->label('Target URL')
                        ->placeholder('https://... or /donate')
                        ->url()
                        ->visible(fn (Get $get): bool => $get('link_type') === 'url')
                        ->helperText('Destination web URL or route path.'),

                    Select::make('style')
                        ->label('Button Style')
                        ->options([
                            'primary' => 'Primary (Brand Solid)',
                            'secondary' => 'Secondary (Complementary Solid)',
                            'outline' => 'Outline (Bordered)',
                        ])
                        ->default('primary')
                        ->helperText('Visual button hierarchy style.'),
                ]),
            ]);
    }
}
