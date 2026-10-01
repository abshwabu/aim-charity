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

