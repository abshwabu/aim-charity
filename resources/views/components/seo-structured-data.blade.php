@props([
    'settings' => null,
    'post' => null,
])

@php
    use App\Models\MemberGroup;
    use App\Support\Site;

    $settings = $settings ?? Site::settings();
    $branding = $settings->branding ?? [];
    $contact = $settings->contact ?? [];
    $social = $settings->social ?? [];

    $siteName = $branding['site_name'] ?? config('app.name', 'Aim Charity');
    $tagline = $branding['tagline'] ?? 'A coalition of grassroots community groups helping people in need in Ethiopia.';
    $logoUrl = filled($branding['logo_light'] ?? null)
        ? Site::imageUrl($branding['logo_light'])
        : (filled($branding['footer_logo'] ?? null) ? Site::imageUrl($branding['footer_logo']) : asset('favicon.ico'));

    $sameAs = [];
    if (is_array($social)) {
        foreach ($social as $item) {
            if (is_array($item) && filled($item['url'] ?? null)) {
                $sameAs[] = $item['url'];
            }
        }
    }

    // Coalition Member Organizations
    $members = [];
    $memberGroups = Site::items()['member_groups'] ?? MemberGroup::query()->visible()->ordered()->get();
    foreach ($memberGroups as $group) {
        $members[] = [
            '@type' => 'Organization',
            'name' => $group->name,
            'description' => $group->short_description ?? null,
            'url' => filled($group->website_url) ? $group->website_url : (url('/') . '#member-groups'),
            'logo' => filled($group->logo) ? Site::imageUrl($group->logo) : null,
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => ['NGO', 'NonprofitOrganization'],
                '@id' => url('/') . '#organization',
                'name' => $siteName,
                'alternateName' => 'Aim Charity Coalition',
                'description' => $tagline,
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logoUrl,
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'General Inquiries & Community Support',
                    'email' => $contact['email'] ?? null,
                    'telephone' => $contact['phone'] ?? null,
                    'availableLanguage' => ['English', 'Amharic', 'Oromo'],
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $contact['address'] ?? 'Addis Ababa, Ethiopia',
                    'addressCountry' => 'ET',
                ],
                'sameAs' => array_values(array_filter($sameAs)),
                'subOrganization' => $members,
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => $siteName,
                'description' => $tagline,
                'publisher' => [
                    '@id' => url('/') . '#organization',
                ],
            ],
        ],
    ];

    if ($post !== null) {
        $articleSchema = [
            '@type' => 'NewsArticle',
            '@id' => route('news.show', $post->slug) . '#article',
            'isPartOf' => [
                '@id' => url('/') . '#website',
            ],
            'headline' => $post->title,
            'description' => $post->excerpt ?? $post->title,
            'datePublished' => $post->published_at?->toIso8601String() ?? $post->created_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String() ?? $post->published_at?->toIso8601String(),
            'mainEntityOfPage' => route('news.show', $post->slug),
            'publisher' => [
                '@id' => url('/') . '#organization',
            ],
            'author' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'url' => url('/'),
            ],
        ];

        if (filled($post->cover_image)) {
            $articleSchema['image'] = [
                Site::imageUrl($post->cover_image),
            ];
        }

        $schema['@graph'][] = $articleSchema;
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
