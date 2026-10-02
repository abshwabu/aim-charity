<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\MemberGroup;
use App\Models\NewsletterSubscriber;
use App\Models\VolunteerApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $unreadMessagesCount = ContactMessage::whereNull('read_at')->count();
        $pendingVolunteerCount = VolunteerApplication::where('status', 'pending')->count();
        $subscribersCount = NewsletterSubscriber::count();
        $activeMembersCount = MemberGroup::where('is_visible', true)->count();

        return [
            Stat::make('Unread Inquiries', (string) $unreadMessagesCount)
                ->description('Contact messages awaiting reply')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessagesCount > 0 ? 'warning' : 'gray'),

            Stat::make('Volunteer Applications', (string) $pendingVolunteerCount)
                ->description('Pending review')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($pendingVolunteerCount > 0 ? 'primary' : 'gray'),

            Stat::make('Weekly Supporters', (string) $subscribersCount)
                ->description('Town friends on update list')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make('Friend Circles', (string) $activeMembersCount)
                ->description('Active giving circles & teams')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
