# Hyperframes Composition Brief: Aim Charity

## Objective
Create a short, warm, polished launch-style brag video for Aim Charity, a platform uniting grassroots community organizations across Ethiopia.

## Output
- Composition directory: `brag-output/composition/`
- Rendered video: `brag-output/brag.mp4`
- Format: landscape — 1920x1080 (30 fps)
- Duration: 20 seconds (600 frames)

## Source Material
- Project root: `/home/abshewabu/Documents/projects/laravel/aim_charity`
- Primary files read: `resources/views/layouts/landing.blade.php`, `resources/views/landing/sections/*.blade.php`, `app/Models/*.php`, `database/seeders/*.php`
- Product name: Aim Charity
- Tagline / strongest claim: "When Communities Unite, Hope Becomes Real — 142,000+ individuals assisted with 100% direct aid and transparent public audits."
- Key UI or visual moments to recreate:
  - Interlocking circles emblem ("Many Groups, One Circle")
  - Floating stat chips (6 Coalitions, 142K+ People Helped, 100% Direct Aid)
  - Member organization showcase cards with custom badge emblems
  - Large animated impact counter with verified ledger badge
  - Direct CBE and Telebirr donation payment tabs
- Copy that must appear verbatim:
  - "When Communities Unite, Hope Becomes Real"
  - "Many Groups, One Unbroken Circle"
  - "142,000+ Individuals Supported"
  - "100% Direct Grassroots Allocation"
  - "Aim Charity — Community Coalition"

## Creative Direction
- Tone preset: `polished`
- Creative direction: `warm editorial product showcase celebrating grassroots unity and radical transparency`
- Interpretation: Dignified pacing, warm cream and forest green palette, generous whitespace, Fraunces serif display titles, clean Plus Jakarta Sans body, and confident cinematic motion.
- Angle: Ethiopia's grassroots organizations working not in silos, but united as one circle, coordinating grain warehouses, mobile clinics, and water wells with complete openness.
- Hook: The golden-green interlocking circles motif expanding into the Fraunces headline and floating stat chips.
- Outro / punchline: The centered brand mark and direct community action buttons fading into warmth.
- Avoid:
  - Stock charity clichés (no generic hands shaking or cheesy stock footage)
  - Dark cynical sarcasm or corporate SaaS buzzwords
  - Rushed transitions that obscure readability

## Visual Identity
- Background: `#fbf9f5` (warm cream)
- Primary Brand Color: `#1b4332` (deep forest green)
- Accent Color: `#d97706` (warm amber / gold)
- Text Color: `#1c1917` (deep charcoal)
- Surface Color: `#ffffff` (crisp white card containers)
- Display font: `Fraunces`, Georgia, serif
- Body font: `Plus Jakarta Sans`, system-ui, sans-serif
- Border Radius: `1.25rem` (rounded-2xl)

## Storyboard
1. **Scene 1: The Hook & Calling** (0.0s – 4.5s / frames 0–135) — 4.5s
   - Background warm cream with subtle ambient glow.
   - Interlocking circles icon expands.
   - Eyebrow: "COALITION OF GRASSROOTS ORGANIZATIONS".
   - Headline: "When Communities Unite, Hope Becomes Real".
   - 3 floating chips pop in: "6 Community Coalitions", "142K+ People Helped", "100% Direct Aid".
2. **Scene 2: The Coalition "Group of Groups"** (4.5s – 9.5s / frames 135–285) — 5.0s
   - Headline: "Many Groups, One Unbroken Circle".
   - Subtitle: "Grassroots organizations across Ethiopian towns and woredas working together as one."
   - 4 cascading member cards:
     - Addis Mutual Aid Association (Food & Emergency Support)
     - Oromia Community Elders Committee (Pastoralist & Drought Relief)
     - Tigray Youth Solidarity Network (Clean Water & Solar Pumps)
     - Sidama Women & Family Forum (Micro-Grants & Maternal Care)
3. **Scene 3: Direct Programs & Radical Transparency** (9.5s – 15.0s / frames 285–450) — 5.5s
   - Eyebrow: "DIRECT IMPACT & OPEN AUDITS".
   - 3 Program pill cards: Emergency Grain Reserves, 85+ Restored Solar Boreholes, Mobile Health Clinics.
   - Giant animated counter: "142,000+" ticking up smoothly.
   - Subtitle: "Families assisted directly without bureaucratic waste."
   - Seal badge: "✔ 100% Public Ledger Transparency".
4. **Scene 4: Call to Action & Outro** (15.0s – 20.0s / frames 450–600) — 5.0s
   - Centered vector Aim Charity logo with glowing interlocking rings.
   - "AIM CHARITY" (Fraunces serif) + "A Coalition of Community Organizations in Ethiopia".
   - Primary CTA: "Stand With Ethiopian Communities".
   - Domestic Donation Badges: Commercial Bank of Ethiopia (CBE) & Telebirr Mobile Money.
   - Tech credit: "Built with Laravel 13 • Filament v5 • 100% Database-Driven".

## Audio
- Audio role: Warm, uplifting, organic acoustic/electronic bed.
- Audio arc: Opens with warm organic acoustic tones, gathers momentum during the member cards reveal, swells on the 142K counter, and resolves peacefully into a warm fade.
- Music: `happy-beats-business-moves-vol-10-by-ende-dot-app.mp3`
- Music treatment: Plays from 0.0s at volume 0.75, subtle fade-out over 18.5s–20.0s.
- Audio-coupled moments:
  - 0.8s: Stat chips popping in one by one.
  - 5.0s–6.5s: Member cards cascading in.
  - 10.2s: Counter ticking up to 142,000+.
  - 15.5s: Final brand logo lockup.
- Audio files: Copied into `brag-output/composition/assets/music/`.

## Hyperframes Instructions
- Use Hyperframes HTML/CSS/JS composition format.
- Ensure all text passes WCAG contrast and readability tests.
- Keep video within exactly 20.0 seconds (600 frames at 30fps).
- Run `npx hyperframes check` and fix any issues before rendering.
