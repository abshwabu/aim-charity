<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageSection;
use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.quick-links-widget';

    protected int|string|array $columnSpan = 'full';

    /**
     * Resolve the edit URL for the hero section.
     */
    public function getHeroEditUrl(): string
    {
        $hero = PageSection::query()->where('key', 'hero')->first();

        return $hero !== null
            ? route('filament.admin.resources.page-sections.edit', ['record' => $hero->id])
            : route('filament.admin.resources.page-sections.index');
    }

    /**
     * Resolve the create URL for member groups.
     */
    public function getAddMemberGroupUrl(): string
    {
        return route('filament.admin.resources.member-groups.create');
    }

    /**
     * Resolve the site settings URL.
     */
    public function getSiteSettingsUrl(): string
    {
        return route('filament.admin.pages.site-settings');
    }

    /**
     * Resolve the page sections manager URL.
     */
    public function getPageSectionsUrl(): string
    {
        return route('filament.admin.resources.page-sections.index');
    }
}
