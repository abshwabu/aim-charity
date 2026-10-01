<?php

declare(strict_types=1);

namespace App\Filament\Resources\DonationMethods\Schemas;

use App\Support\MediaHelper;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DonationMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Channel & Account')
                    ->description('Bank, mobile money, or wire transfer credentials for donors.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('label')
                                ->label('Provider / Bank Label')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g. Telebirr / Commercial Bank of Ethiopia')
                                ->helperText('Name of the bank, financial institution, or payment app.'),

                            TextInput::make('account_name')
                                ->label('Account Name')
                                ->maxLength(255)
                                ->placeholder('e.g. Aim Charity Coalition')
                                ->helperText('Official account holder name registered at the bank.'),

                            TextInput::make('account_number')
                                ->label('Account / Till / Merchant Number')
                                ->maxLength(255)
                                ->placeholder('e.g. 1000234567891')
                                ->helperText('Exact number donors copy to initiate a transfer.'),
                        ]),

                        Toggle::make('is_visible')
                            ->label('Visible on Site')
                            ->default(true)
                            ->helperText('Display this donation option on the public donate section.'),

                        RichEditor::make('instructions')
                            ->label('Transfer Instructions & Guidance')
                            ->helperText('Optional deposit guidelines, swift codes, or reference codes for donors.'),
                    ]),

                Section::make('Visual Assets')
                    ->description('Bank logo and scannable QR code.')
                    ->schema([
                        Grid::make(2)->schema([
                            MediaHelper::logo('logo', 'donations/logos', 'Bank / App Logo')
                                ->helperText('Logo displayed prominently on the donation channel card.'),

                            MediaHelper::image('qr_image', 'donations/qrs', 'QR Code Image')
                                ->helperText('Scannable QR code for instant mobile banking transfers.'),
                        ]),
                    ]),
            ]);
    }
}
