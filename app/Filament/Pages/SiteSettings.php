<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\PageSection;
use App\Models\SiteSetting;
use App\Support\Site;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use UnitEnum;

class SiteSettings extends Page
{
    protected string $view = 'filament.pages.site-settings';

    protected static ?string $title = 'Site Settings';

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 1;

    /**
     * Curated list of Google Fonts available for selection.
     *
     * @var array<string, string>
     */
    public const GOOGLE_FONTS = [
        'Fraunces' => 'Fraunces (Warm Editorial Serif)',
        'Plus Jakarta Sans' => 'Plus Jakarta Sans (Contemporary Clean)',
        'Instrument Sans' => 'Instrument Sans (Default Clean)',
        'Inter' => 'Inter (Modern Sans)',
        'Poppins' => 'Poppins (Geometric & Friendly)',
        'Montserrat' => 'Montserrat (Editorial & Bold)',
        'Open Sans' => 'Open Sans (Neutral & Legible)',
        'Roboto' => 'Roboto (Classic Sans)',
        'Lato' => 'Lato (Warm & Professional)',
        'Playfair Display' => 'Playfair Display (Elegant Serif)',
        'Merriweather' => 'Merriweather (Literary Serif)',
        'Outfit' => 'Outfit (Clean Display)',
        'Space Grotesk' => 'Space Grotesk (Modern Tech)',
        'Lexend' => 'Lexend (High Readability)',
        'custom' => 'Custom Font Name...',
    ];

    /**
     * Form state data.
     *
     * @var array<string, mixed>
     */
    public ?array $data = [];

    /**
     * Determine whether the user can access this page.
     */
    public static function canAccess(): bool
    {
        return auth()->check();
    }

    /**
     * Initialize form state with stored settings or default values.
     */
    public function mount(): void
    {
        $settings = SiteSetting::current();
        $state = $settings->toArray();

        $state['branding'] ??= [];
        $state['theme'] ??= [];
        $state['contact'] ??= [];
        $state['social'] ??= [];
        $state['navigation'] ??= [];
        $state['seo'] ??= [];
        $state['footer'] ??= [];

        // Support radius_style -> corner_radius mapping
        if (empty($state['theme']['corner_radius']) && ! empty($state['theme']['radius_style'])) {
            $state['theme']['corner_radius'] = match ($state['theme']['radius_style']) {
                'rounded-none' => 'sharp',
                'rounded-full' => 'pill',
                default => 'soft',
            };
        }

        // If navigation is a list of items, nest under items key
        if (array_is_list($state['navigation'])) {
            $state['navigation'] = [
                'items' => $state['navigation'],
            ];
        }

        $this->form->fill($state);
    }

    /**
     * Build the form schema containing the 7 settings tabs.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Site Settings')
                    ->tabs([
                        $this->getBrandingTab(),
                        $this->getThemeTab(),
                        $this->getNavigationTab(),
                        $this->getContactTab(),
                        $this->getSocialTab(),
                        $this->getSeoTab(),
                        $this->getFooterTab(),
                    ])
                    ->persistTab()
                    ->id('site-settings-tabs'),
            ]);
    }

    /**
     * Build the page content schema wrapping the form and submit action.
     */
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    /**
     * Get the form content component with embedded form schema and actions.
     */
    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->sticky(static::$formActionsAreSticky)
                    ->key('form-actions'),
            ]);
    }

    /**
     * Get the primary form actions.
     *
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    /**
     * Save the settings to database and flush the Site cache.
     */
    public function save(): void
    {
        /** @var array<string, mixed> $state */
        $state = $this->form->getState();

        // Harmonize corner radius with radius style token
        if (isset($state['theme']['corner_radius'])) {
            $state['theme']['radius_style'] = match ($state['theme']['corner_radius']) {
                'sharp' => 'rounded-none',
                'pill' => 'rounded-full',
                default => 'rounded-xl',
            };
        }

        // Harmonize navigation CTA structure
        if (isset($state['navigation'])) {
            $state['navigation']['cta'] = [
                'label' => $state['navigation']['cta_label'] ?? null,
                'link_type' => $state['navigation']['cta_link_type'] ?? null,
                'section_key' => $state['navigation']['cta_section_key'] ?? null,
                'url' => $state['navigation']['cta_url'] ?? null,
                'open_in_new_tab' => $state['navigation']['cta_open_in_new_tab'] ?? false,
            ];
        }

        /** @var SiteSetting|null $settings */
        $settings = SiteSetting::query()->first();

        if ($settings !== null) {
            $settings->update($state);
        } else {
            SiteSetting::query()->create([
                'id' => 1,
                ...$state,
            ]);
        }

        // Ensure cache is completely flushed
        Site::flushCache();

        Notification::make()
            ->title('Site settings saved successfully.')
            ->success()
            ->send();
    }

    /**
     * Tab 1: Branding configuration.
     */
    protected function getBrandingTab(): Tab
    {
        return Tab::make('Branding')
            ->icon('heroicon-o-sparkles')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('branding.site_name')
                        ->label('Organization / Site Name')
                        ->nullable()
                        ->placeholder('Aim Charity')
                        ->helperText('Appears in the browser title, header logo area, and throughout the landing page.'),

                    TextInput::make('branding.tagline')
                        ->label('Tagline / Slogan')
                        ->nullable()
                        ->placeholder('A Coalition of Community Organizations in Ethiopia')
                        ->helperText('Appears beneath the logo and in the hero headline area.'),
                ]),

                Grid::make(2)->schema([
                    FileUpload::make('branding.logo_light')
                        ->label('Logo (for Light Backgrounds)')
                        ->disk('public')
                        ->directory('branding')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->nullable()
                        ->helperText('Primary logo displayed over white or light header and page backgrounds.'),

                    FileUpload::make('branding.logo_dark')
                        ->label('Logo (for Dark Backgrounds)')
                        ->disk('public')
                        ->directory('branding')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->nullable()
                        ->helperText('Inverted / light-colored logo displayed over dark header backgrounds and dark sections.'),
                ]),

                Grid::make(2)->schema([
                    FileUpload::make('branding.footer_logo')
                        ->label('Footer Logo')
                        ->disk('public')
                        ->directory('branding')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->nullable()
                        ->helperText('Logo displayed specifically in the bottom footer column.'),

                    FileUpload::make('branding.favicon')
                        ->label('Favicon')
                        ->disk('public')
                        ->directory('branding')
                        ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml', 'image/webp', 'image/jpeg'])
                        ->maxSize(2048)
                        ->nullable()
                        ->helperText('Small icon displayed in browser tabs and bookmarks.'),
                ]),
            ]);
    }

    /**
     * Tab 2: Theme configuration.
     */
    protected function getThemeTab(): Tab
    {
        return Tab::make('Theme')
            ->icon('heroicon-o-paint-brush')
            ->schema([
                Section::make('Color Palette')
                    ->description('Customize the visual palette applied across buttons, cards, headings, and accents.')
                    ->schema([
                        Grid::make(3)->schema([
                            ColorPicker::make('theme.primary')
                                ->label('Primary Brand Color')
                                ->default('#059669')
                                ->nullable()
                                ->live()
                                ->helperText('Main brand color used for primary CTA buttons, links, and badges.'),

                            ColorPicker::make('theme.secondary')
                                ->label('Secondary Color')
                                ->default('#0d9488')
                                ->nullable()
                                ->live()
                                ->helperText('Complementary accent used for secondary buttons, borders, and subheadings.'),

                            ColorPicker::make('theme.accent')
                                ->label('Accent Color')
                                ->default('#f59e0b')
                                ->nullable()
                                ->live()
                                ->helperText('Vibrant color used for urgency badges, donation highlights, and stats.'),

                            ColorPicker::make('theme.background')
                                ->label('Page Background')
                                ->default('#f8fafc')
                                ->nullable()
                                ->live()
                                ->helperText('Default body background color across alternating page sections.'),

                            ColorPicker::make('theme.surface')
                                ->label('Surface / Card Background')
                                ->default('#ffffff')
                                ->nullable()
                                ->live()
                                ->helperText('Elevated cards, testimonials, modals, and container backgrounds.'),

                            ColorPicker::make('theme.text')
                                ->label('Text Color')
                                ->default('#0f172a')
                                ->nullable()
                                ->live()
                                ->helperText('Primary typography color used across headings and paragraphs.'),
                        ]),
                    ]),

                Section::make('Typography & Shape')
                    ->description('Select Google Fonts and border rounding style.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('theme.heading_font')
                                ->label('Heading Font (Google Fonts)')
                                ->options(self::GOOGLE_FONTS)
                                ->default('Instrument Sans')
                                ->nullable()
                                ->live()
                                ->helperText('Typography font applied to all main section headings and card titles.'),

                            TextInput::make('theme.heading_font_custom')
                                ->label('Custom Heading Font Name')
                                ->placeholder('e.g. Noto Sans Ethiopic')
                                ->nullable()
                                ->visible(fn (Get $get): bool => $get('theme.heading_font') === 'custom')
                                ->helperText('Specify the exact Google Font name to load for headings.'),

                            Select::make('theme.body_font')
                                ->label('Body Font (Google Fonts)')
                                ->options(self::GOOGLE_FONTS)
                                ->default('Instrument Sans')
                                ->nullable()
                                ->live()
                                ->helperText('Typography font applied to paragraph body copy and descriptions.'),

                            TextInput::make('theme.body_font_custom')
                                ->label('Custom Body Font Name')
                                ->placeholder('e.g. Roboto')
                                ->nullable()
                                ->visible(fn (Get $get): bool => $get('theme.body_font') === 'custom')
                                ->helperText('Specify the exact Google Font name to load for body copy.'),

                            Select::make('theme.corner_radius')
                                ->label('Corner Radius')
                                ->options([
                                    'sharp' => 'Sharp (Square / 0px)',
                                    'soft' => 'Soft (Rounded Modern / 12px)',
                                    'pill' => 'Pill (Full Capsule / 9999px)',
                                ])
                                ->default('soft')
                                ->nullable()
                                ->live()
                                ->helperText('Controls border curvature across buttons, cards, inputs, and badges.'),
                        ]),
                    ]),

                View::make('filament.components.theme-preview')
                    ->viewData(fn (Get $get): array => [
                        'theme' => $get('theme') ?? [],
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Tab 3: Navigation configuration.
     */
    protected function getNavigationTab(): Tab
    {
        return Tab::make('Navigation')
            ->icon('heroicon-o-bars-3')
            ->schema([
                Section::make('Header Menu Items')
                    ->description('Configure links displayed in the desktop navigation bar and mobile drawer.')
                    ->schema([
                        Repeater::make('navigation.items')
                            ->label('Menu Items')
                            ->helperText('Ordered list of menu links appearing in the site navigation bar.')
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->reorderable()
                            ->collapsible()
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('label')
                                        ->label('Menu Label')
                                        ->placeholder('e.g. About')
                                        ->nullable()
                                        ->helperText('Label visible to visitors in the navigation bar.'),

                                    Select::make('type')
                                        ->label('Link Type')
                                        ->options([
                                            'section' => 'Page Section (Smooth Scroll)',
                                            'url' => 'External or Custom URL',
                                        ])
                                        ->default('section')
                                        ->live()
                                        ->nullable()
                                        ->helperText('Whether this link jumps to an on-page section or opens a URL.'),

                                    Select::make('section_key')
                                        ->label('Target Section')
                                        ->options(fn (): array => PageSection::query()->orderBy('sort_order')->pluck('key', 'key')->all())
                                        ->visible(fn (Get $get): bool => ($get('type') ?? 'section') === 'section')
                                        ->nullable()
                                        ->helperText('Select which landing page section this menu item scrolls to.'),

                                    TextInput::make('url')
                                        ->label('Destination URL')
                                        ->placeholder('https://... or /donate')
                                        ->url()
                                        ->nullable()
                                        ->visible(fn (Get $get): bool => $get('type') === 'url')
                                        ->helperText('Custom URL or path destination when clicked.'),

                                    Toggle::make('open_in_new_tab')
                                        ->label('Open in New Tab')
                                        ->default(false)
                                        ->helperText('Check to open destination link in a new browser tab.'),
                                ]),
                            ]),
                    ]),

                Section::make('Header Call-to-Action (CTA)')
                    ->description('Prominent button placed in the top right of the navigation header.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('navigation.cta_label')
                                ->label('CTA Button Label')
                                ->placeholder('Donate Now')
                                ->nullable()
                                ->helperText('Button text for the prominent action button in the header.'),

                            Select::make('navigation.cta_link_type')
                                ->label('CTA Link Type')
                                ->options([
                                    'section' => 'Page Section (Smooth Scroll)',
                                    'url' => 'External or Custom URL',
                                ])
                                ->default('section')
                                ->live()
                                ->nullable()
                                ->helperText('Whether CTA jumps to an on-page section or custom URL.'),

                            Select::make('navigation.cta_section_key')
                                ->label('Target Section')
                                ->options(fn (): array => PageSection::query()->orderBy('sort_order')->pluck('key', 'key')->all())
                                ->visible(fn (Get $get): bool => ($get('navigation.cta_link_type') ?? 'section') === 'section')
                                ->nullable()
                                ->helperText('Select which section to jump to when user clicks CTA.'),

                            TextInput::make('navigation.cta_url')
                                ->label('Destination URL')
                                ->placeholder('https://... or /donate')
                                ->url()
                                ->nullable()
                                ->visible(fn (Get $get): bool => $get('navigation.cta_link_type') === 'url')
                                ->helperText('Custom URL or path for the header CTA button.'),

                            Toggle::make('navigation.cta_open_in_new_tab')
                                ->label('Open CTA in New Tab')
                                ->default(false)
                                ->helperText('Open CTA destination in a new browser tab.'),
                        ]),
                    ]),
            ]);
    }

    /**
     * Tab 4: Contact configuration.
     */
    protected function getContactTab(): Tab
    {
        return Tab::make('Contact')
            ->icon('heroicon-o-phone')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('contact.email')
                        ->label('Public Contact Email')
                        ->email()
                        ->nullable()
                        ->placeholder('contact@aimcharity.org')
                        ->helperText('Displayed in the header top bar, contact section, and footer.'),

                    TextInput::make('contact.notification_email')
                        ->label('Internal Notification Recipient Email')
                        ->email()
                        ->nullable()
                        ->placeholder('notifications@aimcharity.org')
                        ->helperText('Incoming contact messages and volunteer applications are dispatched to this address. Falls back to Public Contact Email if left empty.'),

                    TextInput::make('contact.phone')
                        ->label('Primary Phone Number')
                        ->tel()
                        ->nullable()
                        ->placeholder('+251 11 000 0000')
                        ->helperText('Main telephone number displayed for general public inquiries.'),

                    TextInput::make('contact.secondary_phone')
                        ->label('Secondary / Alternative Phone')
                        ->tel()
                        ->nullable()
                        ->placeholder('+251 91 000 0000')
                        ->helperText('Optional secondary phone or emergency contact number.'),

                    TextInput::make('contact.working_hours')
                        ->label('Working Hours')
                        ->nullable()
                        ->placeholder('Mon - Fri: 8:30 AM - 5:30 PM')
                        ->helperText('Office visiting and helpline operational hours displayed on the contact card.'),
                ]),

                TextInput::make('contact.address')
                    ->label('Physical Office Address')
                    ->nullable()
                    ->placeholder('Bole Sub-City, Addis Ababa, Ethiopia')
                    ->helperText('Physical location address rendered on the contact card and footer column.'),

                TextInput::make('contact.map_embed_url')
                    ->label('Google Maps Embed URL')
                    ->url()
                    ->nullable()
                    ->placeholder('https://www.google.com/maps/embed?...')
                    ->helperText('Google Maps iframe embed URL displayed in the interactive location section.'),
            ]);
    }

    /**
     * Tab 5: Social media configuration.
     */
    protected function getSocialTab(): Tab
    {
        return Tab::make('Social Links')
            ->icon('heroicon-o-share')
            ->schema([
                Repeater::make('social')
                    ->label('Social Media Profiles')
                    ->helperText('Channels and profiles displayed in header bar, footer, and member community cards.')
                    ->itemLabel(fn (array $state): ?string => isset($state['platform']) ? ucfirst($state['platform']).(isset($state['url']) ? " — {$state['url']}" : '') : null)
                    ->reorderable()
                    ->collapsible()
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('platform')
                                ->label('Social Platform')
                                ->options([
                                    'facebook' => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'x' => 'X (formerly Twitter)',
                                    'telegram' => 'Telegram',
                                    'tiktok' => 'TikTok',
                                    'youtube' => 'YouTube',
                                    'linkedin' => 'LinkedIn',
                                    'whatsapp' => 'WhatsApp',
                                    'custom' => 'Custom Platform',
                                ])
                                ->default('telegram')
                                ->live()
                                ->nullable()
                                ->helperText('Select the social network platform.'),

                            TextInput::make('custom_name')
                                ->label('Custom Platform Name')
                                ->placeholder('e.g. Discord')
                                ->nullable()
                                ->visible(fn (Get $get): bool => $get('platform') === 'custom')
                                ->helperText('Name of the custom social platform.'),

                            TextInput::make('url')
                                ->label('Profile / Channel URL')
                                ->url()
                                ->nullable()
                                ->placeholder('https://t.me/aimcharity')
                                ->helperText('Full URL to your profile, channel, or community group.'),
                        ]),
                    ]),
            ]);
    }

    /**
     * Tab 6: SEO and analytics configuration.
     */
    protected function getSeoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                TextInput::make('seo.meta_title')
                    ->label('Meta Title')
                    ->nullable()
                    ->placeholder('Aim Charity — Coalition of Community Organizations in Ethiopia')
                    ->helperText('Appears in the browser tab title and as the primary heading in Google search results.'),

                Textarea::make('seo.meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->nullable()
                    ->placeholder('Aim Charity brings together community groups across Ethiopia to help people in need.')
                    ->helperText('Summary blurb displayed under the page title in search engine result snippets.'),

                FileUpload::make('seo.og_image')
                    ->label('Social Share Image (Open Graph / Twitter Card)')
                    ->disk('public')
                    ->directory('seo')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->nullable()
                    ->helperText('Preview thumbnail image displayed when sharing the site URL on social platforms (1200x630 recommended).'),

                TextInput::make('seo.twitter_handle')
                    ->label('Twitter / X Handle')
                    ->nullable()
                    ->placeholder('@aimcharity')
                    ->helperText('Twitter account username credited on Twitter Card shares.'),

                Textarea::make('seo.analytics_snippet')
                    ->label('Head Tracking & Analytics Snippet (Advanced)')
                    ->rows(5)
                    ->nullable()
                    ->placeholder('<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXX"></script>')
                    ->helperText('ADVANCED: Raw HTML or JavaScript tags injected directly into the document <head> (e.g. GA4, Google Tag Manager, Plausible, Meta Pixel). Leave empty if unsure.'),
            ]);
    }

    /**
     * Tab 7: Footer configuration.
     */
    protected function getFooterTab(): Tab
    {
        return Tab::make('Footer')
            ->icon('heroicon-o-document-text')
            ->schema([
                Textarea::make('footer.about_blurb')
                    ->label('Footer About Blurb')
                    ->rows(3)
                    ->nullable()
                    ->placeholder('Aim Charity is a coalition of grassroots organizations dedicated to mutual aid...')
                    ->helperText('Introductory organizational blurb rendered in the first column of the footer.'),

                TextInput::make('footer.copyright_text')
                    ->label('Copyright Notice')
                    ->nullable()
                    ->placeholder('Aim Charity. All rights reserved.')
                    ->helperText('Copyright statement shown at the base of the page. Supports {year} token for dynamic current year.'),

                Repeater::make('footer.legal_links')
                    ->label('Legal & Policy Links')
                    ->helperText('Footer links for legal terms, privacy policies, financial transparency, etc.')
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->reorderable()
                    ->collapsible()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('label')
                                ->label('Link Label')
                                ->placeholder('e.g. Privacy Policy')
                                ->nullable()
                                ->helperText('Visible text for the footer link.'),

                            TextInput::make('url')
                                ->label('Destination URL or Path')
                                ->placeholder('/privacy or https://...')
                                ->nullable()
                                ->helperText('Relative internal path (e.g. /privacy) or full external URL.'),

                            Toggle::make('open_in_new_tab')
                                ->label('Open in New Tab')
                                ->default(false)
                                ->helperText('Open link in a new browser tab when clicked.'),
                        ]),
                    ]),
            ]);
    }
}
