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

            Stat::make('Newsletter Subscribers', (string) $subscribersCount)
                ->description('Audience size')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make('Coalition Members', (string) $activeMembersCount)
                ->description('Active member organizations')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
