<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SeoSettingSeeder::class,
            TestimonialSeeder::class,
            GoInvoicePackageSeeder::class,
            GoBotPackageSeeder::class,
            GoSoftPackageSeeder::class,
            GoQuickPackageSeeder::class,
        ]);
    }
}
