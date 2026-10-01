# Aim Charity Landing Platform

A single-page landing site and content management platform for **Aim Charity**, a coalition ("a group of groups") of grassroots community organizations in Ethiopia united to help people in need.

All landing page content (text, sections, links, media, and styles) is dynamic and fully manageable via the Filament admin panel.

---

## Tech Stack & Architecture

- **Backend Framework**: Laravel 13 (PHP 8.2+)
- **Database**: PostgreSQL
- **Admin Panel**: Filament v5 (`/admin`)
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Vite
- **Media & Image Processing**: Intervention Image v4 (WebP conversion & responsive resizing)
- **Testing**: PHPUnit / Feature & Unit Smoke Tests

---

## Directory Structure

```
├── app/
│   ├── Filament/
│   │   ├── Pages/             # Filament custom admin pages
│   │   ├── Resources/         # Filament resource models and managers
│   │   └── Widgets/           # Admin dashboard widgets
│   ├── Models/
│   │   ├── Concerns/          # FlushesSiteCache, HasSortOrderAndVisibility
│   │   ├── SiteSetting.php    # Singleton settings (branding, theme, contact, social, nav, seo, footer)
│   │   ├── PageSection.php    # Dynamic sections (hero, about, donate, etc.)
│   │   ├── MemberGroup.php    # Coalition partner groups
│   │   ├── Program.php        # Community programs
│   │   ├── ImpactStat.php     # Numerical impact statistics
│   │   ├── Testimonial.php    # Community quotes
│   │   ├── GalleryItem.php    # Media gallery
│   │   ├── Partner.php        # Institutional partners
│   │   ├── Faq.php            # FAQs
│   │   ├── NewsPost.php       # News & updates
│   │   ├── Step.php           # How it works steps
│   │   ├── DonationMethod.php # Bank / Telebirr accounts
│   │   ├── TeamMember.php     # Coalition leadership & staff
│   │   ├── ContactMessage.php # Contact inquiries
│   │   ├── VolunteerApplication.php # Volunteer submissions
│   │   ├── NewsletterSubscriber.php # Newsletter list
│   │   └── User.php           # Filament admin user
│   ├── Providers/
│   │   └── Filament/
│   │       └── AdminPanelProvider.php  # Admin panel routing & branding
│   └── Support/
│       ├── Site.php           # Site::settings(), Site::section(), Site::sections(), Site::imageUrl()
│       ├── helpers.php        # Global imageUrl() helper
│       └── ImageService.php   # Image resizing and WebP conversion helper
├── database/
│   ├── factories/             # Factories for all 17 models
│   ├── migrations/            # Schema definitions for all models
│   └── seeders/
│       ├── AdminUserSeeder.php    # Default admin credentials from env
│       ├── SiteSettingSeeder.php  # Singleton settings seeder
│       ├── PageSectionSeeder.php  # Initial page sections seeder
│       └── DatabaseSeeder.php     # Master seeder
├── resources/
│   ├── css/app.css            # Tailwind CSS source
│   ├── js/app.js              # Alpine.js initialization
│   └── views/
│       ├── components/        # Reusable Blade components (e.g., layout)
│       ├── filament/admin/    # Filament branding templates
│       └── landing/
│           ├── index.blade.php
│           └── sections/      # Dynamic landing page sections
└── tests/
    ├── Feature/
    │   └── SmokeTest.php      # Smoke tests for '/' and '/admin/login'
    └── Unit/
        └── ImageServiceTest.php # Image optimization & WebP tests
```

---

## Prerequisites

Ensure the following are installed on your machine:
- PHP >= 8.2 with `pdo_pgsql`, `gd` or `imagick`, and `mbstring` extensions
- Composer >= 2.x
- PostgreSQL server (running and accessible)
- Node.js >= 18.x and npm

---

## Quickstart & Setup Steps

Follow these steps to set up the project on a clean machine:

### 1. Clone Repository & Install PHP Dependencies

```bash
git clone <repository-url>
cd aim_charity
composer install
```

### 2. Configure Environment

Copy the example environment file and generate the application encryption key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure your PostgreSQL credentials in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aim_charity
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Migrate and Seed Database

Run migrations and seed the default administrator user and initial site content:

```bash
php artisan migrate --seed
```

### 4. Create Public Storage Link

Link the public storage disk for media assets:

```bash
php artisan storage:link
```

### 5. Install & Build Frontend Assets

```bash
npm install
npm run build
```

---

## Default Admin Credentials

The default administrator account is automatically created from `.env` variables during `php artisan migrate --seed`:

- **Admin Login URL**: [http://localhost:8000/admin](http://localhost:8000/admin)
- **Email**: `admin@aimcharity.org` (configured via `ADMIN_EMAIL`)
- **Password**: `password` (configured via `ADMIN_PASSWORD`)

---

## Running the Application

### Development Server

Start the Laravel development server:

```bash
php artisan serve
```

In a separate terminal, start the Vite development server for hot-module reloading:

```bash
npm run dev
```

Visit the application:
- **Landing Page**: [http://localhost:8000/](http://localhost:8000/)
- **Admin Panel**: [http://localhost:8000/admin](http://localhost:8000/admin)

---

## Running Tests & Code Quality

Run the test suite:

```bash
php artisan test
```

Check code standards with Laravel Pint:

```bash
./vendor/bin/pint --test
```

---

## Key Guidelines

1. **Database-Driven Content**: No landing page text, copy, image paths, links, or theme colors are hardcoded. Everything is stored in and retrieved from the database.
2. **Code Standards**: Strict typing (`declare(strict_types=1);`), PSR-12 standards, and Laravel conventions.

---

## Operations & Production Maintenance

### 1. Storage & Media Backups
User-uploaded media (logos, gallery images, member photos) are stored on the `public` disk under `storage/app/public`.

- **Backup Media**:
  ```bash
  tar -czf aim_storage_backup_$(date +%F).tar.gz storage/app/public
  ```
- **Backup PostgreSQL Database**:
  ```bash
  pg_dump -U postgres -h 127.0.0.1 -d aim_charity -F c -b -v -f aim_db_backup_$(date +%F).dump
  ```
- **Restore Media**:
  ```bash
  tar -xzf aim_storage_backup_YYYY-MM-DD.tar.gz -C ./
  php artisan storage:link
  ```

### 2. Background Queue Worker
Incoming contact and volunteer application notification emails are queued asynchronously via Laravel queues.

- **Run Worker Locally**:
  ```bash
  php artisan queue:work --tries=3 --timeout=90
  ```
- **Production Supervisor Configuration** (`/etc/supervisor/conf.d/aim-worker.conf`):
  ```ini
  [program:aim-worker]
  process_name=%(program_name)s_%(process_num)02d
  command=php /var/www/aim_charity/artisan queue:work --sleep=3 --tries=3 --max-time=3600
  autostart=true
  autorestart=true
  user=www-data
  numprocs=2
  redirect_stderr=true
  stdout_logfile=/var/log/supervisor/aim-worker.log
  stopwaitsecs=3600
  ```

### 3. Task Scheduler Cron
To run scheduled maintenance and queued recurring tasks, configure the standard Laravel crontab:

```bash
* * * * * cd /var/www/aim_charity && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Cache Management & Clearing
The platform caches site settings, sections, and items. When updates occur in Filament, caches flush automatically. For manual flushing or deployments:

```bash
# Flush site content cache (triggers re-querying and warm caching on next visit)
php artisan tinker --execute 'App\Support\Site::flushCache();'

# Clear application framework caches (routes, views, config)
php artisan optimize:clear

# Warm route and config caches for production
php artisan optimize
```

---

## Frontend Editability & Audit System

To guarantee that 100% of visible landing page text, imagery, and styling is driven by the database with zero hardcoded strings, the application provides an automated audit command:

```bash
# Run automated editability audit
php artisan site:audit

# View full slot-by-slot database mapping report
php artisan site:audit --detail
```

### Audit Capabilities
1. **Zero Hardcoded Text Enforcement**: Parses and scans all Blade templates in `resources/views/landing/` and `resources/views/layouts/`, failing if any literal visible text nodes exist outside the strict allowlist (e.g. standard screen-reader ARIA labels and HTML entities).
2. **Frontend Slot Mapping**: Verifies that all 167+ frontend slots (branding, typography, theme colors, navigation links, contact info, SEO, footer, section headlines/subheadings/rich-text, and repeatable model attributes) map to concrete database columns or JSON properties.
3. **Database Schema Integrity**: Asserts that all 16 content and configuration database tables and JSON structures are present and consistent.

---

## Manual QA & Verification Checklist

Follow this checklist to verify that all administrative modifications reflect immediately and accurately across the frontend:

- [ ] **Change Logos & Branding**:
  1. Open **Site > Site Settings > Branding**.
  2. Upload a new `Logo (Light Background)`, `Logo (Dark Background)`, `Favicon`, and `Footer Logo`.
  3. Change the `Organization / Site Name` and `Tagline`.
  4. Save and visit `/` to verify header, footer, browser title, and admin portal bar display the new logos.
- [ ] **Change Theme Colors & Swatches**:
  1. Open **Site > Site Settings > Theme**.
  2. Modify `Primary Brand Color`, `Secondary Color`, `Accent Color`, `Page Background`, `Surface`, and `Text Color`.
  3. Verify the live swatch preview updates in real-time.
  4. Save and visit `/` to confirm buttons, badges, background tints, and card surfaces adopt the new palette.
- [ ] **Change Typography (Google Fonts)**:
  1. Select a different `Heading Font` (e.g., `Fraunces`, `Instrument Sans`, `Noto Serif Ethiopic`) and `Body Font` (e.g., `Plus Jakarta Sans`, `Noto Sans Ethiopic`).
  2. Save and verify that Google Fonts `<link>` tags load the selected fonts and typography renders across all headings and paragraphs.
- [ ] **Change Border Radius Style**:
  1. Switch `Corner Radius Style` between `sharp` (`rounded-none`), `soft` (`rounded-2xl`), and `pill` (`rounded-full`).
  2. Save and verify button and card rounding on `/`.
- [ ] **Reorder Page Sections**:
  1. Open **Site > Page Sections**.
  2. Drag and drop rows (e.g., move `Programs` above `About`).
  3. Visit `/` and verify the section ordering on the landing page matches the new sequence immediately.
- [ ] **Toggle Section Visibility**:
  1. In **Site > Page Sections**, toggle the `Visible` switch off for any section (e.g., `Gallery` or `Team`).
  2. Visit `/` to confirm the section is removed from the DOM and header anchor links no longer target it.
  3. Re-enable visibility to confirm it reappears.
- [ ] **Edit Section Headlines & Copy**:
  1. Edit any section in **Site > Page Sections** (e.g., `Hero`, `About`, `How It Works`).
  2. Change `Eyebrow`, `Headline`, `Subheading`, and rich-text `Body`.
  3. Save and refresh `/` to verify updated copy.
- [ ] **Manage Repeatable Content**:
  1. **Member Groups**: Add a new member organization with name, focus area, logo, photo, and website. Verify it appears in the coalition grid and filter modal.
  2. **Programs**: Edit program title, icon, and description. Confirm changes appear on `/`.
  3. **Impact Stats**: Update counters and labels. Verify stat cards display the new values.
  4. **Testimonials**: Update quotes and authors. Confirm the testimonials slider reflects the changes.
  5. **Donation Methods**: Add or update bank/mobile money accounts. Verify account numbers and QR codes update in the donation tabs.
  6. **FAQs**: Add a new question and rich-text answer. Verify accordion expansion on `/`.
- [ ] **Hide Items & Verify Clean Fallbacks**:
  1. Hide an individual item (e.g., a specific FAQ or program). Verify only visible items render.
  2. Hide all items in a section (e.g., all programs). Verify the entire section cleanly omits itself without leaving an empty shell.
- [ ] **Interactive Forms & Notifications**:
  1. Submit the Contact form. Verify inline success message, inbox record creation in `/admin/contact-messages`, and notification email dispatch.
  2. Submit the Volunteer application. Verify status defaults to `pending` in `/admin/volunteer-applications`.
  3. Submit Newsletter subscription. Verify unique email record in `/admin/newsletter-subscribers`.

---

## Admin Portal Polish & Custom Dashboard

The administrative portal (`/admin`) features:
1. **Dynamic Branding**: The top bar uses the organization's own uploaded logo and site name configured in `Site Settings`.
2. **Quick Actions Widget**: One-click shortcuts to:
   - **Edit Hero**: Direct access to edit the main landing hero headline and visuals.
   - **Add Member Group**: Shortcut to register a new coalition member organization.
   - **Page Sections**: Reorder and customize layout and copy.
   - **Site Settings**: Customize branding, fonts, colors, and SEO.
   - **View Live Site**: Opens the public landing page in a new browser tab.
3. **Interactive How-To Guide**: Embedded onboarding card outlining the 4-step content lifecycle and automatic cache invalidation.

---

## Multilingual Support Architecture Plan (Amharic & English)

Aim Charity is designed to support both **English (`en`)** and **Amharic (`am`)**. Below is the architectural implementation roadmap:

### 1. Package & Model Layer
- Use **`spatie/laravel-translatable`** and **`filament/spatie-laravel-translatable-plugin`**.
- Models implement the `HasTranslations` trait with translatable columns:
  - `PageSection`: `content` (translated keys for `eyebrow`, `heading`, `subheading`, `body`).
  - `MemberGroup`: `name`, `short_description`, `long_description`, `focus_area`.
  - `Program`: `title`, `description`, `link_label`.
  - `ImpactStat`: `label`, `suffix`.
  - `Step`: `title`, `description`.
  - `Testimonial`: `quote`, `author_role`.
  - `Faq`: `question`, `answer`.
  - `NewsPost`: `title`, `excerpt`, `body`.
  - `DonationMethod`: `label`, `instructions`.

### 2. Database Schema
- Translatable columns are stored as JSONB/JSON columns containing locale key-value pairs:
  ```json
  {
    "en": "When Communities Unite, Hope Becomes Real",
    "am": "ማህበረሰቦች ሲተባበሩ ተስፋ እውን ይሆናል"
  }
  ```

### 3. Ethiopic Typography
- Google Fonts `Noto Sans Ethiopic` and `Noto Serif Ethiopic` are pre-registered in `SiteSettings::GOOGLE_FONTS`.
- When the active locale is `am`, typography automatically falls back to `Noto Sans Ethiopic` / `Noto Serif Ethiopic` for crisp, native Fidäl script rendering.

### 4. Locale Resolution & Header Switcher
- A locale switch component in the header sets the session locale via `GET /locale/{lang}` (`en` or `am`).
- Middleware `SetLocale` sets `app()->setLocale(session('locale', config('app.locale')))`.
- `Site` helper methods resolve the active locale with automatic fallback to English (`config('app.fallback_locale')`).

### 5. Filament Admin Integration
- In Filament resources, register `SpatieLaravelTranslatablePlugin` so every resource form provides a tabbed language switcher at the top (`[English] [አማርኛ]`), enabling content editors to manage both language editions side-by-side.

