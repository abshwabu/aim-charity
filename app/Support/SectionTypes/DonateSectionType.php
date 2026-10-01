<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class DonateSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'donate';
    }

    public static function getLabel(): string
    {
        return 'Donate & Financial Contributions';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-heart';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Your financial support directly fuels relief packages, clean water initiatives, and local community resilience across Ethiopia.',
            'reassurance' => '100% of your contributions go directly to vetted grassroots causes. We publish monthly financial reports and independent audits to guarantee radical transparency.',
            'buttons' => [
                ['label' => 'View Bank Accounts & QR Codes', 'link_type' => 'section', 'target' => 'donate', 'url' => null, 'style' => 'primary'],
            ],
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Donation Messaging')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Explain how donations are utilized...'),

                    Textarea::make('content.reassurance')
                        ->label('Donor Reassurance & Transparency Statement')
                        ->rows(3)
                        ->placeholder('Explain transparency, accountability, and reporting...')
                        ->helperText('Assures donors that funds are handled with ethical integrity and full accounting.'),

                    static::buttonRepeater('content.buttons', 'Donation Action Buttons'),

                    Placeholder::make('management_note')
                        ->label('Donation Methods')
                        ->content('Bank accounts (CBE, Telebirr, Awash, Abyssinia), recipient names, instructions, and QR code graphics are managed separately in the Donation Methods resource.'),
                ]),
        ];
    }
}
