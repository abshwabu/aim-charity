<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Information')
                    ->description('Details of coalition leaders, advisors, and executive directors.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Full name of the team member or leader.'),

                            TextInput::make('role')
                                ->label('Title / Role')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g. Coalition Director / Healthcare Lead')
                                ->helperText('Organizational role or responsibility within the coalition.'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('member_group_id')
                                ->label('Associated Member Group')
                                ->relationship('memberGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->placeholder('Central Coalition (No specific group)')
                                ->helperText('Optionally link this team member to an affiliate organization.'),

                            Toggle::make('is_visible')
                                ->label('Visible on Site')
                                ->default(true)
                                ->helperText('Show or hide this team member in the Leadership section.'),
                        ]),

                        Textarea::make('bio')
                            ->label('Biography')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief background or impact summary.'),
                    ]),

                Section::make('Portrait')
                    ->description('Headshot or profile picture.')
                    ->schema([
                        MediaHelper::avatar('photo', 'team', 'Profile Photo')
                            ->helperText('Square portrait photo shown in the leadership card grid.'),
                    ]),
            ]);
    }
}
