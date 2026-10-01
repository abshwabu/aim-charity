<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class ContactSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'contact';
    }

    public static function getLabel(): string
    {
        return 'Contact Us & Location';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-envelope';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Have questions about our coalition, want to partner with us, or need assistance? Reach out to our team directly.',
            'labels' => [
                'name' => 'Your Full Name',
                'email' => 'Email Address',
                'phone' => 'Phone Number',
                'message' => 'Your Message or Inquiry',
            ],
            'button_label' => 'Send Message',
            'success_message' => 'Thank you for reaching out! Your message has been sent to our coordination team.',
            'privacy_note' => 'We treat all inquiries with strict confidentiality.',
            'show_map' => true,
            'show_direct_contacts' => true,
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Contact Section Intro')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory copy inviting visitors to send a message...'),

                    Grid::make(2)->schema([
                        Toggle::make('content.show_map')
                            ->label('Display Google Maps Location')
                            ->default(true)
                            ->helperText('Uses the map embed URL defined in Site Settings.'),

                        Toggle::make('content.show_direct_contacts')
                            ->label('Display Direct Contact Info Cards')
                            ->default(true)
                            ->helperText('Displays email, telephone, address, and office hours from Site Settings.'),
                    ]),
                ]),

            Section::make('Editable Form Field Labels')
                ->description('Every label shown in the contact form can be customized here.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.labels.name')
                            ->label('Name Input Label')
                            ->default('Your Full Name'),

                        TextInput::make('content.labels.email')
                            ->label('Email Input Label')
                            ->default('Email Address'),

                        TextInput::make('content.labels.phone')
                            ->label('Phone Input Label')
                            ->default('Phone Number'),

                        TextInput::make('content.labels.message')
                            ->label('Message Input Label')
                            ->default('Your Message or Inquiry'),
                    ]),
                ]),

            Section::make('Form Submission & Confirmation')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.button_label')
                            ->label('Submit Button Label')
                            ->default('Send Message'),

                        TextInput::make('content.privacy_note')
                            ->label('Privacy Notice')
                            ->default('We treat all inquiries with strict confidentiality.'),
                    ]),

                    Textarea::make('content.success_message')
                        ->label('Success Alert Message')
                        ->rows(2)
                        ->default('Thank you for reaching out! Your message has been sent to our coordination team.')
                        ->helperText('Message shown to visitor after successfully sending their message.'),
                ]),
        ];
    }
}
