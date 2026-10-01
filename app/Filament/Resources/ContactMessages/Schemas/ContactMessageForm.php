<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sender Information')
                    ->description('Contact details submitted by the visitor.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Sender Name')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->disabled(),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->disabled()
                            ->placeholder('Not provided'),

                        DateTimePicker::make('created_at')
                            ->label('Received At')
                            ->disabled(),

                        DateTimePicker::make('read_at')
                            ->label('Read At')
                            ->disabled()
                            ->placeholder('Unread'),
                    ]),

                Section::make('Message')
                    ->schema([
                        Textarea::make('message')
                            ->label('Message Content')
                            ->rows(8)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
