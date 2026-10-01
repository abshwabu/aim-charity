<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerApplications\Schemas;

use App\Models\VolunteerApplication;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VolunteerApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Applicant Information')
                    ->description('Details submitted by the prospective volunteer.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->disabled(),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->disabled()
                            ->placeholder('Not provided'),

                        Select::make('member_group_id')
                            ->label('Preferred Member Group')
                            ->relationship('memberGroup', 'name')
                            ->disabled()
                            ->placeholder('Open to Any Member Group'),

                        TextInput::make('skills')
                            ->label('Skills & Expertise')
                            ->disabled()
                            ->placeholder('None specified')
                            ->columnSpanFull(),

                        TextInput::make('availability')
                            ->label('Availability')
                            ->disabled()
                            ->placeholder('Flexible')
                            ->columnSpanFull(),

                        Textarea::make('message')
                            ->label('Personal Statement / Message')
                            ->rows(4)
                            ->disabled()
                            ->placeholder('No statement provided')
                            ->columnSpanFull(),
                    ]),

                Section::make('Staff Review & Management')
                    ->description('Update application processing status and keep internal communication notes.')
                    ->columns(1)
                    ->schema([
                        Select::make('status')
                            ->label('Review Status')
                            ->options(VolunteerApplication::statuses())
                            ->required()
                            ->native(false)
                            ->helperText('Update the workflow status as this volunteer is contacted and onboarded.'),

                        Textarea::make('notes')
                            ->label('Internal Notes')
                            ->rows(5)
                            ->placeholder('Add internal staff notes, interview outcomes, or follow-up details...')
                            ->helperText('Visible only to administrators and staff members in this dashboard.'),
                    ]),
            ]);
    }
}
