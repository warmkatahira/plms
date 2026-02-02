<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Route;
use App\Models\RouteDetail;
use App\Models\BoardingLocation;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Route::create([
            'route_name'            => '朝一番便',
            'vehicle_category_id'   => 1,
            'route_type_id'         => 1,
            'is_active'             => 1,
            'sort_order'            => 1,
        ]);
        RouteDetail::create([
            'route_id'              => 1,
            'boarding_location_id'  => 4,
            'stop_order'            => 1,
            'departure_time'        => '08:45',
        ]);
        RouteDetail::create([
            'route_id'              => 1,
            'boarding_location_id'  => 5,
            'stop_order'            => 2,
            'departure_time'        => '08:50',
        ]);
        RouteDetail::create([
            'route_id'              => 1,
            'boarding_location_id'  => 2,
            'stop_order'            => 3,
            'departure_time'        => '08:53',
        ]);
        RouteDetail::create([
            'route_id'              => 1,
            'boarding_location_id'  => 1,
            'stop_order'            => 4,
            'departure_time'        => '09:08',
        ]);

        Route::create([
            'route_name'            => '朝二番便',
            'vehicle_category_id'   => 1,
            'route_type_id'         => 1,
            'is_active'             => 1,
            'sort_order'            => 2,
        ]);
        RouteDetail::create([
            'route_id'              => 2,
            'boarding_location_id'  => 4,
            'stop_order'            => 1,
            'departure_time'        => '09:45',
        ]);
        RouteDetail::create([
            'route_id'              => 2,
            'boarding_location_id'  => 5,
            'stop_order'            => 2,
            'departure_time'        => '09:50',
        ]);
        RouteDetail::create([
            'route_id'              => 2,
            'boarding_location_id'  => 2,
            'stop_order'            => 3,
            'departure_time'        => '09:53',
        ]);
        RouteDetail::create([
            'route_id'              => 2,
            'boarding_location_id'  => 1,
            'stop_order'            => 4,
            'departure_time'        => '10:08',
        ]);

    }
}
