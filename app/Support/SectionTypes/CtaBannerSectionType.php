<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class CtaBannerSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'cta_banner';
    }

    public static function getLabel(): string
    {
        return 'Call-to-Action Banner (Highlight Strip)';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-megaphone';
    }

    public static function getDefaultContent(): array
    {
        return [
            'badge_text' => 'Take Action Today',
            'heading' => 'Together, We Can Deliver Hope and Relief Across Ethiopia',
            'text' => 'Join hundreds of donors, volunteers, and community groups standing united for collective impact.',
            'buttons' => [
                ['label' => 'Make a Donation', 'link_type' => 'section', 'target' => 'donate', 'url' => null, 'style' => 'primary'],
                ['label' => 'Volunteer With Us', 'link_type' => 'section', 'target' => 'volunteer', 'url' => null, 'style' => 'outline'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Call-to-Action Messaging')
                ->schema([
                    TextInput::make('content.badge_text')
                        ->label('Banner Eyebrow Badge')
                        ->placeholder('e.g. Take Action Today')
                        ->helperText('Accent badge above the main banner headline.'),

                    TextInput::make('content.heading')
                        ->label('Banner Headline')
                        ->placeholder('Together, We Can Deliver Hope and Relief...')
                        ->nullable()
                        ->helperText('Prominent heading displayed in large typography.'),

                    Textarea::make('content.text')
                        ->label('Banner Subtext / Description')
                        ->rows(3)
                        ->placeholder('Join hundreds of donors, volunteers, and community groups...')
                        ->helperText('Supporting sentence inspiring action.'),

                    static::buttonRepeater('content.buttons', 'Banner Action Buttons'),
                ]),
        ];
    }
}
