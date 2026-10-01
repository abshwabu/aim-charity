<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\PlaceholderImageGenerator;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PlaceholderImageGenerator::generateAll();

        $this->call([
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            PageSectionSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
