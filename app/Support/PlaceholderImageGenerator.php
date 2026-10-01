<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PlaceholderImageGenerator
{
    /**
     * Generate all SVG placeholder assets into public storage.
     */
    public static function generateAll(): void
    {
        $disk = Storage::disk('public');

        // 1. Branding Assets
        $disk->put('branding/logo-light.svg', self::generateLogo(isDark: false));
        $disk->put('branding/logo-dark.svg', self::generateLogo(isDark: true));
        $disk->put('branding/favicon.svg', self::generateFavicon());
        $disk->put('branding/footer-logo.svg', self::generateLogo(isDark: false));

        // 2. Member Groups (Photos & Logos)
        $groups = [
            ['title' => 'Addis Mutual Aid', 'sub' => 'Food Support & Solidarity', 'bg1' => '#1b4332', 'bg2' => '#2d6a4f', 'accent' => '#d97706'],
            ['title' => 'Oromia Community Elders', 'sub' => 'Drought & Livestock Relief', 'bg1' => '#2d6a4f', 'bg2' => '#40916c', 'accent' => '#f59e0b'],
            ['title' => 'Amhara Reconstruction', 'sub' => 'Clinics & Community Centers', 'bg1' => '#1b4332', 'bg2' => '#52b788', 'accent' => '#fbbf24'],
            ['title' => 'Tigray Youth Network', 'sub' => 'Clean Water & Nutrition', 'bg1' => '#081c15', 'bg2' => '#2d6a4f', 'accent' => '#f59e0b'],
            ['title' => 'Sidama Women Forum', 'sub' => 'Family Micro-Grants & Health', 'bg1' => '#2d6a4f', 'bg2' => '#74c69d', 'accent' => '#d97706'],
            ['title' => 'Afar Pastoralist Care', 'sub' => 'Water Trucking & Resilience', 'bg1' => '#1b4332', 'bg2' => '#40916c', 'accent' => '#f59e0b'],
        ];

        foreach ($groups as $index => $group) {
            $num = $index + 1;
            $disk->put("placeholders/groups/group-photo-{$num}.svg", self::generateCardIllustration(
                width: 800,
                height: 500,
                title: $group['title'],
                subtitle: $group['sub'],
                colorStart: $group['bg1'],
                colorEnd: $group['bg2'],
                accent: $group['accent'],
                pattern: 'circles'
            ));

            $disk->put("placeholders/groups/group-logo-{$num}.svg", self::generateEmblem(
                width: 240,
                height: 240,
                initials: self::getInitials($group['title']),
                label: $group['title'],
                color: $group['bg1'],
                accent: $group['accent']
            ));
        }

        // 3. Programs (4 items)
        $programs = [
            ['title' => 'Nutritional Emergency Aid', 'desc' => 'Direct Food Baskets & Grain Silos', 'bg1' => '#1b4332', 'bg2' => '#2d6a4f'],
            ['title' => 'Mobile Health & Medicines', 'desc' => 'Frontline Doctors & Critical Kits', 'bg1' => '#2d6a4f', 'bg2' => '#40916c'],
            ['title' => 'Clean Water & Solar Pumps', 'desc' => 'Deep Boreholes & Pipeline Access', 'bg1' => '#081c15', 'bg2' => '#1b4332'],
            ['title' => 'Youth & Women Livelihoods', 'desc' => 'Vocational Grants & Seed Capital', 'bg1' => '#2d6a4f', 'bg2' => '#52b788'],
        ];

        foreach ($programs as $index => $program) {
            $num = $index + 1;
            $disk->put("placeholders/programs/program-{$num}.svg", self::generateCardIllustration(
                width: 800,
                height: 500,
                title: $program['title'],
                subtitle: $program['desc'],
                colorStart: $program['bg1'],
                colorEnd: $program['bg2'],
                accent: '#d97706',
                pattern: 'mesh'
            ));
        }

        // 4. Hero Collages (3 items)
        $disk->put('placeholders/hero/hero-collage-1.svg', self::generateCardIllustration(
            width: 800,
            height: 600,
            title: 'Many Groups, One Circle',
            subtitle: 'Coalition Aid Distribution',
            colorStart: '#1b4332',
            colorEnd: '#2d6a4f',
            accent: '#d97706',
            pattern: 'interlocking'
        ));

        $disk->put('placeholders/hero/hero-collage-2.svg', self::generateCardIllustration(
            width: 800,
            height: 600,
            title: 'Local Realities, Collective Action',
            subtitle: 'Direct Mutual Aid in Ethiopia',
            colorStart: '#2d6a4f',
            colorEnd: '#40916c',
            accent: '#f59e0b',
            pattern: 'circles'
        ));

        $disk->put('placeholders/hero/hero-collage-3.svg', self::generateCardIllustration(
            width: 800,
            height: 600,
            title: 'Dignity & Solidarity',
            subtitle: 'Grassroots Community Coordination',
            colorStart: '#081c15',
            colorEnd: '#1b4332',
            accent: '#fbbf24',
            pattern: 'mesh'
        ));

        // 5. Testimonial Portraits (3 items)
        $testimonials = [
            ['name' => 'Aster Tesfaye', 'role' => 'Community Elder, Addis', 'initials' => 'AT'],
            ['name' => 'Dr. Dawit Bekele', 'role' => 'Volunteer Physician', 'initials' => 'DB'],
            ['name' => 'Fatuma Mohammed', 'role' => 'Cooperative Leader, Awash', 'initials' => 'FM'],
        ];

        foreach ($testimonials as $index => $test) {
            $num = $index + 1;
            $disk->put("placeholders/testimonials/testimonial-{$num}.svg", self::generateAvatar(
                size: 240,
                initials: $test['initials'],
                name: $test['name'],
                color: '#1b4332',
                accent: '#d97706'
            ));
        }

        // 6. Gallery Items (6 items)
        $gallery = [
            ['title' => 'Emergency Grain Distribution', 'cat' => 'Food Aid'],
            ['title' => 'Solar Water Borehole Launch', 'cat' => 'Clean Water'],
            ['title' => 'Mobile Health Outreach Clinic', 'cat' => 'Healthcare'],
            ['title' => 'Community School Supply Delivery', 'cat' => 'Education'],
            ['title' => 'Women Cooperative Seed Harvest', 'cat' => 'Livelihoods'],
            ['title' => 'Elder Council Relief Coordination', 'cat' => 'Governance'],
        ];

        foreach ($gallery as $index => $item) {
            $num = $index + 1;
            $disk->put("placeholders/gallery/gallery-{$num}.svg", self::generateCardIllustration(
                width: 900,
                height: 650,
                title: $item['title'],
                subtitle: $item['cat'],
                colorStart: '#1b4332',
                colorEnd: '#2d6a4f',
                accent: '#f59e0b',
                pattern: 'interlocking'
            ));
        }

        // 7. Partners (4 items)
        $partners = [
            'Red Cross Solidarity Partner',
            'University Service Network',
            'Horn Humanitarian Alliance',
            'Community Merchants Council',
        ];

        foreach ($partners as $index => $partner) {
            $num = $index + 1;
            $disk->put("placeholders/partners/partner-{$num}.svg", self::generatePartnerLogo(
                width: 300,
                height: 80,
                name: $partner
            ));
        }

        // 8. Donation Methods (CBE and Telebirr)
        $disk->put('placeholders/donate/cbe-logo.svg', self::generateBankLogo('CBE', 'Commercial Bank of Ethiopia', '#6b21a8'));
        $disk->put('placeholders/donate/cbe-qr.svg', self::generateQrPlaceholder('CBE Account 1000-0000-0000-0000 [DEMO]'));
        $disk->put('placeholders/donate/telebirr-logo.svg', self::generateBankLogo('telebirr', 'Ethio Telecom Mobile Money', '#0284c7'));
        $disk->put('placeholders/donate/telebirr-qr.svg', self::generateQrPlaceholder('Telebirr Merchant AIM12345 [DEMO]'));

        // 9. Team Members (4 items)
        $team = [
            ['name' => 'Ato Yohannes Hailemariam', 'role' => 'Steering Committee Chair', 'initials' => 'YH'],
            ['name' => 'Sister Rahel Tadesse', 'role' => 'Logistics & Distribution Lead', 'initials' => 'RT'],
            ['name' => 'Dr. Elias Gidey', 'role' => 'Health Operations Director', 'initials' => 'EG'],
            ['name' => 'W/ro Selamawit Desta', 'role' => 'Community Liaison & Audit', 'initials' => 'SD'],
        ];

        foreach ($team as $index => $member) {
            $num = $index + 1;
            $disk->put("placeholders/team/team-{$num}.svg", self::generateAvatar(
                size: 320,
                initials: $member['initials'],
                name: $member['name'],
                color: '#2d6a4f',
                accent: '#d97706'
            ));
        }

        // 10. News Posts (3 items)
        $news = [
            ['title' => 'Six Grassroots Coalitions Unify Relief Logistics', 'date' => 'May 2026'],
            ['title' => 'Transparent Audit: 142,000 Families Supported in 2025/26', 'date' => 'April 2026'],
            ['title' => 'Expanding Clean Water Infrastructure Across Dry Regions', 'date' => 'March 2026'],
        ];

        foreach ($news as $index => $article) {
            $num = $index + 1;
            $disk->put("placeholders/news/news-{$num}.svg", self::generateCardIllustration(
                width: 800,
                height: 500,
                title: $article['title'],
                subtitle: $article['date'],
                colorStart: '#1b4332',
                colorEnd: '#40916c',
                accent: '#d97706',
                pattern: 'mesh'
            ));
        }
    }

    /**
     * Generate vector Aim Charity logo with interlocking rings motif.
     */
    public static function generateLogo(bool $isDark): string
    {
        $textColor = $isDark ? '#1c1917' : '#ffffff';
        $subColor = $isDark ? '#2d6a4f' : '#d97706';
        $ring1 = $isDark ? '#1b4332' : '#ffffff';
        $ring2 = '#d97706';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 80" width="320" height="80">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.15"/>
    </filter>
  </defs>
  <g transform="translate(10, 10)">
    <!-- Interlocking Circles Motif -->
    <circle cx="26" cy="30" r="22" fill="none" stroke="{$ring1}" stroke-width="6" opacity="0.9" />
    <circle cx="44" cy="30" r="22" fill="none" stroke="{$ring2}" stroke-width="6" opacity="0.85" />
    <circle cx="35" cy="18" r="14" fill="none" stroke="{$ring1}" stroke-width="4" opacity="0.5" />
    <circle cx="35" cy="30" r="6" fill="{$ring2}" />
  </g>
  <text x="88" y="38" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="24" font-weight="800" letter-spacing="-0.5" fill="{$textColor}">AIM CHARITY</text>
  <text x="89" y="56" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="10" font-weight="600" letter-spacing="1.5" fill="{$subColor}">COMMUNITY COALITION</text>
</svg>
SVG;
    }

    /**
     * Generate favicon SVG.
     */
    public static function generateFavicon(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  <rect width="64" height="64" rx="16" fill="#1b4332" />
  <circle cx="26" cy="34" r="16" fill="none" stroke="#ffffff" stroke-width="5" opacity="0.9" />
  <circle cx="38" cy="34" r="16" fill="none" stroke="#d97706" stroke-width="5" opacity="0.9" />
  <circle cx="32" cy="34" r="4.5" fill="#fef3c7" />
</svg>
SVG;
    }

    /**
     * Generate card illustration with geometric patterns.
     */
    protected static function generateCardIllustration(
        int $width,
        int $height,
        string $title,
        string $subtitle,
        string $colorStart,
        string $colorEnd,
        string $accent,
        string $pattern
    ): string {
        $cleanTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $cleanSubtitle = htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8');

        $patternMarkup = match ($pattern) {
            'circles' => <<<XML
              <circle cx="150" cy="120" r="160" fill="none" stroke="{$accent}" stroke-width="1.5" stroke-dasharray="8 8" opacity="0.25"/>
              <circle cx="650" cy="380" r="220" fill="none" stroke="#ffffff" stroke-width="2" opacity="0.1"/>
              <circle cx="400" cy="250" r="90" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.2"/>
XML,
            'interlocking' => <<<XML
              <circle cx="200" cy="200" r="180" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.2"/>
              <circle cx="340" cy="200" r="180" fill="none" stroke="#ffffff" stroke-width="2" opacity="0.15"/>
              <circle cx="270" cy="320" r="140" fill="none" stroke="{$accent}" stroke-width="1.5" opacity="0.2"/>
XML,
            default => <<<XML
              <path d="M0,{$height} Q{$width}/4,100 {$width}/2,{$height}/2 T{$width},0" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.25"/>
              <circle cx="100" cy="80" r="60" fill="none" stroke="#ffffff" stroke-width="1.5" opacity="0.15"/>
              <circle cx="{$width}-100" cy="{$height}-80" r="100" fill="none" stroke="{$accent}" stroke-width="1" opacity="0.2"/>
XML,
        };

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}">
  <defs>
    <linearGradient id="cardGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$colorStart}"/>
      <stop offset="100%" stop-color="{$colorEnd}"/>
    </linearGradient>
    <filter id="blurFilter" x="0" y="0" width="100%" height="100%">
      <feGaussianBlur stdDeviation="30"/>
    </filter>
  </defs>
  <!-- Background -->
  <rect width="{$width}" height="{$height}" fill="url(#cardGrad)"/>
  
  <!-- Subtle Ambient Glow -->
  <circle cx="{$width}" cy="0" r="200" fill="{$accent}" opacity="0.18" filter="url(#blurFilter)"/>
  
  <!-- Patterns -->
  {$patternMarkup}
  
  <!-- Text Label Overlay -->
  <g transform="translate(40, {$height}-70)">
    <rect x="-10" y="-30" width="{$width}-60" height="65" rx="8" fill="#081c15" opacity="0.75"/>
    <text x="10" y="-6" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="18" font-weight="700" fill="#ffffff">{$cleanTitle}</text>
    <text x="10" y="18" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="12" font-weight="500" fill="{$accent}">{$cleanSubtitle}</text>
  </g>
</svg>
SVG;
    }

    /**
     * Generate square emblem logo for member groups.
     */
    protected static function generateEmblem(int $width, int $height, string $initials, string $label, string $color, string $accent): string
    {
        $cleanInitials = htmlspecialchars($initials, ENT_QUOTES, 'UTF-8');
        $cleanLabel = htmlspecialchars(mb_substr($label, 0, 24), ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}">
  <rect width="{$width}" height="{$height}" rx="28" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
  <circle cx="120" cy="100" r="54" fill="{$color}"/>
  <circle cx="120" cy="100" r="48" fill="none" stroke="{$accent}" stroke-width="2" stroke-dasharray="4 4"/>
  <text x="120" y="112" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="28" font-weight="800" text-anchor="middle" fill="#ffffff">{$cleanInitials}</text>
  <text x="120" y="184" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="11" font-weight="700" text-anchor="middle" fill="#334155">{$cleanLabel}</text>
</svg>
SVG;
    }

    /**
     * Generate portrait avatar SVG.
     */
    protected static function generateAvatar(int $size, string $initials, string $name, string $color, string $accent): string
    {
        $cleanInitials = htmlspecialchars($initials, ENT_QUOTES, 'UTF-8');
        $half = (int) ($size / 2);
        $radius = (int) ($half - 8);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$size} {$size}" width="{$size}" height="{$size}">
  <rect width="{$size}" height="{$size}" rx="24" fill="#f8fafc" stroke="#e2e8f0" stroke-width="2"/>
  <circle cx="{$half}" cy="{$half}" r="{$radius}" fill="{$color}"/>
  <circle cx="{$half}" cy="{$half}" r="{$radius}-4" fill="none" stroke="{$accent}" stroke-width="3" opacity="0.8"/>
  <text x="{$half}" y="{$half}+14" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="36" font-weight="800" text-anchor="middle" fill="#ffffff">{$cleanInitials}</text>
</svg>
SVG;
    }

    /**
     * Generate partner logo badge.
     */
    protected static function generatePartnerLogo(int $width, int $height, string $name): string
    {
        $clean = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}">
  <rect width="{$width}" height="{$height}" rx="12" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>
  <circle cx="30" cy="40" r="16" fill="#1b4332"/>
  <circle cx="44" cy="40" r="16" fill="#d97706" opacity="0.75"/>
  <text x="75" y="46" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="12" font-weight="700" fill="#334155">{$clean}</text>
</svg>
SVG;
    }

    /**
     * Generate bank / payment method logo badge.
     */
    protected static function generateBankLogo(string $short, string $fullName, string $brandColor): string
    {
        $cleanShort = htmlspecialchars($short, ENT_QUOTES, 'UTF-8');
        $cleanFull = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 260 80" width="260" height="80">
  <rect width="260" height="80" rx="16" fill="{$brandColor}"/>
  <text x="24" y="44" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="24" font-weight="900" fill="#ffffff">{$cleanShort}</text>
  <text x="24" y="62" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="9" font-weight="600" fill="#e0e7ff" opacity="0.9">{$cleanFull}</text>
</svg>
SVG;
    }

    /**
     * Generate QR code placeholder SVG.
     */
    protected static function generateQrPlaceholder(string $label): string
    {
        $clean = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240" width="240" height="240">
  <rect width="240" height="240" rx="16" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
  <!-- Top Left Marker -->
  <rect x="24" y="24" width="56" height="56" rx="8" fill="#1e293b"/>
  <rect x="36" y="36" width="32" height="32" rx="4" fill="#ffffff"/>
  <rect x="44" y="44" width="16" height="16" fill="#1e293b"/>

  <!-- Top Right Marker -->
  <rect x="160" y="24" width="56" height="56" rx="8" fill="#1e293b"/>
  <rect x="172" y="36" width="32" height="32" rx="4" fill="#ffffff"/>
  <rect x="180" y="44" width="16" height="16" fill="#1e293b"/>

  <!-- Bottom Left Marker -->
  <rect x="24" y="160" width="56" height="56" rx="8" fill="#1e293b"/>
  <rect x="36" y="172" width="32" height="32" rx="4" fill="#ffffff"/>
  <rect x="44" y="180" width="16" height="16" fill="#1e293b"/>

  <!-- Center Abstract Data Blocks -->
  <rect x="100" y="24" width="16" height="32" fill="#1e293b"/>
  <rect x="124" y="44" width="24" height="16" fill="#d97706"/>
  <rect x="100" y="100" width="40" height="40" rx="6" fill="#1b4332"/>
  <rect x="160" y="100" width="20" height="24" fill="#1e293b"/>
  <rect x="44" y="100" width="28" height="20" fill="#d97706"/>
  <rect x="100" y="160" width="32" height="24" fill="#1e293b"/>
  <rect x="156" y="156" width="48" height="48" fill="#1e293b"/>

  <!-- Label -->
  <rect x="16" y="208" width="208" height="24" rx="4" fill="#f1f5f9"/>
  <text x="120" y="224" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="9" font-weight="700" text-anchor="middle" fill="#475569">{$clean}</text>
</svg>
SVG;
    }

    /**
     * Get initials from a string.
     */
    protected static function getInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if ($w !== '') {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if (mb_strlen($initials) >= 3) {
                break;
            }
        }

        return $initials ?: 'AC';
    }
}
