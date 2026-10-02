<x-filament-widgets::widget class="fi-quick-links-widget">
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <x-slot name="description">
            Direct shortcuts to manage our small-town charity association and local programs.
        </x-slot>

        <x-slot name="afterHeader">
            <x-filament::button
                href="{{ url('/') }}"
                tag="a"
                target="_blank"
                rel="noopener noreferrer"
                color="primary"
                icon="heroicon-m-arrow-top-right-on-square"
                size="sm"
            >
                View Live Site
            </x-filament::button>
        </x-slot>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Edit Hero -->
            <a
                href="{{ $this->getHeroEditUrl() }}"
                class="group flex items-center gap-3.5 rounded-xl border border-gray-200/80 bg-white p-3.5 transition duration-150 ease-in-out hover:border-emerald-500/50 hover:bg-emerald-50/40 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-emerald-500/40 dark:hover:bg-emerald-500/10"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 transition duration-150 group-hover:bg-emerald-600 group-hover:text-white dark:bg-emerald-950/60 dark:text-emerald-300 dark:group-hover:bg-emerald-600">
                    <x-filament::icon icon="heroicon-o-home" class="h-5 w-5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-950 transition duration-150 group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-300">Edit Hero</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Headline, buttons, visuals</p>
                </div>
            </a>

            <!-- Add Member Group -->
            <a
                href="{{ $this->getAddMemberGroupUrl() }}"
                class="group flex items-center gap-3.5 rounded-xl border border-gray-200/80 bg-white p-3.5 transition duration-150 ease-in-out hover:border-amber-500/50 hover:bg-amber-50/40 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-amber-500/40 dark:hover:bg-amber-500/10"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-800 transition duration-150 group-hover:bg-amber-600 group-hover:text-white dark:bg-amber-950/60 dark:text-amber-300 dark:group-hover:bg-amber-600">
                    <x-filament::icon icon="heroicon-o-user-group" class="h-5 w-5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-950 transition duration-150 group-hover:text-amber-800 dark:text-white dark:group-hover:text-amber-300">Add Member Group</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Manage association membership</p>
                </div>
            </a>

            <!-- Manage Page Sections -->
            <a
                href="{{ $this->getPageSectionsUrl() }}"
                class="group flex items-center gap-3.5 rounded-xl border border-gray-200/80 bg-white p-3.5 transition duration-150 ease-in-out hover:border-sky-500/50 hover:bg-sky-50/40 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-sky-500/40 dark:hover:bg-sky-500/10"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-700 transition duration-150 group-hover:bg-sky-600 group-hover:text-white dark:bg-sky-950/60 dark:text-sky-300 dark:group-hover:bg-sky-600">
                    <x-filament::icon icon="heroicon-o-bars-3-bottom-left" class="h-5 w-5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-950 transition duration-150 group-hover:text-sky-700 dark:text-white dark:group-hover:text-sky-300">Page Sections</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Reorder & customize copy</p>
                </div>
            </a>

            <!-- Site Settings -->
            <a
                href="{{ $this->getSiteSettingsUrl() }}"
                class="group flex items-center gap-3.5 rounded-xl border border-gray-200/80 bg-white p-3.5 transition duration-150 ease-in-out hover:border-purple-500/50 hover:bg-purple-50/40 dark:border-white/10 dark:bg-white/[0.02] dark:hover:border-purple-500/40 dark:hover:bg-purple-500/10"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-50 text-purple-700 transition duration-150 group-hover:bg-purple-600 group-hover:text-white dark:bg-purple-950/60 dark:text-purple-300 dark:group-hover:bg-purple-600">
                    <x-filament::icon icon="heroicon-o-cog-6-tooth" class="h-5 w-5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-950 transition duration-150 group-hover:text-purple-700 dark:text-white dark:group-hover:text-purple-300">Site Settings</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Branding, colors, fonts, SEO</p>
                </div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
