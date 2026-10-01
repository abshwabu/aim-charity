<?php

declare(strict_types=1);

namespace App\Support;

class IconHelper
{
    /**
     * Curated list of popular Heroicon outline icons for programs, stats, and steps.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'heroicon-o-academic-cap' => 'Academic Cap (Education & Training)',
            'heroicon-o-heart' => 'Heart (Relief, Health & Care)',
            'heroicon-o-hand-raised' => 'Hand Raised (Volunteering & Mutual Aid)',
            'heroicon-o-user-group' => 'User Group (Community & Coalition)',
            'heroicon-o-users' => 'Users (Team & Membership)',
            'heroicon-o-sparkles' => 'Sparkles (Hope & Innovation)',
            'heroicon-o-shield-check' => 'Shield Check (Trust & Transparency)',
            'heroicon-o-check-badge' => 'Check Badge (Verification & Quality)',
            'heroicon-o-building-office' => 'Building (Facilities & Shelter)',
            'heroicon-o-building-office-2' => 'Institutional Building (Partners)',
            'heroicon-o-truck' => 'Truck (Logistics & Relief Distribution)',
            'heroicon-o-home' => 'Home (Shelter & Families)',
            'heroicon-o-banknotes' => 'Banknotes (Donations & Micro-Grants)',
            'heroicon-o-globe-alt' => 'Globe (Global & Diaspora Engagement)',
            'heroicon-o-megaphone' => 'Megaphone (Advocacy & Awareness)',
            'heroicon-o-newspaper' => 'Newspaper (News & Transparency Reports)',
            'heroicon-o-chart-bar' => 'Chart Bar (Impact & Statistics)',
            'heroicon-o-arrow-path' => 'Arrow Path (Process & Sustainability)',
            'heroicon-o-wrench-screwdriver' => 'Wrench & Screwdriver (Infrastructure)',
            'heroicon-o-chat-bubble-bottom-center-text' => 'Chat Bubble (Stories & Feedback)',
            'heroicon-o-envelope' => 'Envelope (Contact & Communication)',
            'heroicon-o-phone' => 'Phone (Helpline & Direct Line)',
            'heroicon-o-map-pin' => 'Map Pin (Regional Branches & Centers)',
            'heroicon-o-clock' => 'Clock (Emergency Response)',
            'heroicon-o-light-bulb' => 'Light Bulb (Innovation & Solutions)',
            'heroicon-o-check-circle' => 'Check Circle (Completed Goal)',
            'heroicon-o-star' => 'Star (Excellence & Distinction)',
            'heroicon-o-sun' => 'Sun (Renewable Energy & Climate)',
        ];
    }
}
