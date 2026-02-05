<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Ride;
use App\Models\RideDetail;
use App\Models\BoardingLocation;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ride::create([
            'route_type_id'         => 1,
            'schedule_date'         => '2026-02-10',
            'driver_user_no'        => 1,
            'use_vehicle_id'        => 2,
            'ride_memo'             => 'test',
            'is_active'             => 1,
        ]);
    }
}
