<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerApplications\Tables;

use App\Models\VolunteerApplication;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VolunteerApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('memberGroup.name')
                    ->label('Preferred Group')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Any Group')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => VolunteerApplication::statuses()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        VolunteerApplication::STATUS_NEW => 'info',
                        VolunteerApplication::STATUS_CONTACTED => 'warning',
                        VolunteerApplication::STATUS_ACCEPTED => 'success',
                        VolunteerApplication::STATUS_DECLINED => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('skills')
                    ->label('Skills')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Applied')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(VolunteerApplication::statuses()),

                SelectFilter::make('member_group_id')
                    ->label('Member Group')
                    ->relationship('memberGroup', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
