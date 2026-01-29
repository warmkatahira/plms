<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\VehicleCategory;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleCategory::create([
            'vehicle_category'  => 'バス',
            'sort_order'        => 1,
        ]);
        VehicleCategory::create([
            'vehicle_category'  => '車',
            'sort_order'        => 2,
        ]);
    }
}