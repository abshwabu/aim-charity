<?php

declare(strict_types=1);

namespace App\Support\SectionTypes;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class VolunteerSectionType extends BaseSectionType
{
    public static function getKey(): string
    {
        return 'volunteer';
    }

    public static function getLabel(): string
    {
        return 'Volunteer Application';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-hand-raised';
    }

    public static function getDefaultContent(): array
    {
        return [
            'intro' => 'Join our active network of passionate volunteers. Whether you offer medical skills, logistics support, translation, or manual assistance, your effort makes a tangible difference.',
            'labels' => [
                'name' => 'Full Name',
                'email' => 'Email Address',
                'phone' => 'Phone Number',
                'skills' => 'Your Skills & Expertise',
                'availability' => 'Availability (Hours / Days per week)',
                'message' => 'Why do you want to volunteer with Aim Charity?',
            ],
            'button_label' => 'Submit Volunteer Application',
            'success_message' => 'Thank you for stepping up to help! Our volunteer coordinator will reach out to you within 48 hours.',
            'privacy_note' => 'Your personal details will only be used by Aim Charity for coordination purposes and will never be shared.',
        ];
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Volunteer Introduction')
                ->schema([
                    Textarea::make('content.intro')
                        ->label('Section Intro Copy')
                        ->rows(2)
                        ->placeholder('Introductory call-to-action for volunteers...'),
                ]),

            Section::make('Editable Form Field Labels')
                ->description('Every label shown in the volunteer form can be customized here.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.labels.name')
                            ->label('Name Input Label')
                            ->default('Full Name'),

                        TextInput::make('content.labels.email')
                            ->label('Email Input Label')
                            ->default('Email Address'),

                        TextInput::make('content.labels.phone')
                            ->label('Phone Input Label')
                            ->default('Phone Number'),

                        TextInput::make('content.labels.skills')
                            ->label('Skills Input Label')
                            ->default('Your Skills & Expertise'),

                        TextInput::make('content.labels.availability')
                            ->label('Availability Input Label')
                            ->default('Availability (Hours / Days per week)'),

                        TextInput::make('content.labels.message')
                            ->label('Message / Motivation Input Label')
                            ->default('Why do you want to volunteer with Aim Charity?'),
                    ]),
                ]),

            Section::make('Form Submission & Confirmation')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.button_label')
                            ->label('Submit Button Label')
                            ->default('Submit Volunteer Application'),

                        TextInput::make('content.privacy_note')
                            ->label('Privacy Notice')
                            ->default('Your personal details will only be used for coordination purposes.'),
                    ]),

                    Textarea::make('content.success_message')
                        ->label('Success Alert Message')
                        ->rows(2)
                        ->default('Thank you for stepping up to help! Our volunteer coordinator will reach out to you within 48 hours.')
                        ->helperText('Message shown to applicant after successfully submitting the form.'),
                ]),
        ];
    }
}
