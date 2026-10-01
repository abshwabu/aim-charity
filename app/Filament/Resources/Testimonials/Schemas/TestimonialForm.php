<?php

declare(strict_types=1);

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial Details')
                    ->description('Community quotes and stories from partners, volunteers, and beneficiaries.')
                    ->schema([
                        Textarea::make('quote')
                            ->label('Quote / Story')
                            ->required()
                            ->rows(4)
                            ->helperText('The beneficiary statement or donor story to feature.'),

                        Grid::make(2)->schema([
                            TextInput::make('author_name')
                                ->label('Author Name')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Name of the person providing this quote.'),

                            TextInput::make('author_role')
                                ->label('Author Role / Title')
                                ->maxLength(255)
                                ->placeholder('e.g. Community Elder, Volunteer Lead, Beneficiary')
                                ->helperText('Role or context for the quote author.'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('member_group_id')
                                ->label('Associated Member Group')
                                ->relationship('memberGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->placeholder('Coalition-wide (No specific group)')
                                ->helperText('Optionally connect this quote to a specific member group.'),

                            Toggle::make('is_visible')
                                ->label('Visible on Site')
                                ->default(true)
                                ->helperText('Display this testimonial in the testimonials carousel/section.'),
                        ]),
                    ]),

                Section::make('Author Portrait')
                    ->description('Headshot or portrait photo of the testimonial author.')
                    ->schema([
                        MediaHelper::avatar('author_photo', 'testimonials', 'Author Photo')
                            ->helperText('Square or circular portrait photo shown alongside the quote.'),
                    ]),
            ]);
    }
}
