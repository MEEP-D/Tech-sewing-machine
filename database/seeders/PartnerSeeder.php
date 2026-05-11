<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Juki', 'logo' => 'https://via.placeholder.com/200x100?text=Juki', 'url' => 'https://www.juki.co.jp', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Brother', 'logo' => 'https://via.placeholder.com/200x100?text=Brother', 'url' => 'https://www.brother.com', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Siruba', 'logo' => 'https://via.placeholder.com/200x100?text=Siruba', 'url' => 'https://www.siruba.com', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Jack', 'logo' => 'https://via.placeholder.com/200x100?text=Jack', 'url' => 'https://www.jacksew.com', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Pegasus', 'logo' => 'https://via.placeholder.com/200x100?text=Pegasus', 'url' => 'https://www.pegasus.co.jp', 'sort_order' => 5, 'is_active' => true],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(['name' => $partner['name']], $partner);
        }
    }
}
