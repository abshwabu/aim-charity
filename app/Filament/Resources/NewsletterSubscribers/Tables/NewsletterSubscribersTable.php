<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use App\Models\NewsletterSubscriber;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),

                TextColumn::make('email')
                    ->label('Subscriber Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->weight('bold'),

                TextColumn::make('subscribed_at')
                    ->label('Subscribed Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Record Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Action::make('exportCsv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (): StreamedResponse {
                        return response()->streamDownload(function (): void {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['ID', 'Email', 'Subscribed At', 'Created At']);

                            NewsletterSubscriber::query()
                                ->orderBy('id')
                                ->chunk(250, function ($subscribers) use ($handle): void {
                                    foreach ($subscribers as $subscriber) {
                                        fputcsv($handle, [
                                            $subscriber->id,
                                            $subscriber->email,
                                            $subscriber->subscribed_at?->toIso8601String() ?? '',
                                            $subscriber->created_at?->toIso8601String() ?? '',
                                        ]);
                                    }
                                });

                            fclose($handle);
                        }, 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv', [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
