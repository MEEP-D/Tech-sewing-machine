<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
            SettingSeeder::class,
            PartnerSeeder::class,
            SectionSeeder::class,
            MenuSeeder::class,
            BannerSeeder::class,
            SeoMetaSeeder::class,
            TagSeeder::class,
            TaggableSeeder::class,
            MediaSeeder::class,
            LeadSeeder::class,
            HomePageSampleSeeder::class,
        ]);
    }
}
