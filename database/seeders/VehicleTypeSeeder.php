<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::create([
            'vehicle_type'  => '社用車',
            'sort_order'    => 1,
        ]);
        VehicleType::create([
            'vehicle_type'  => '自家用車',
            'sort_order'    => 2,
        ]);
    }
}