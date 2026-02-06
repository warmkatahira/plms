<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Ride;
use App\Models\RideDetail;
use App\Models\RideUser;

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
            'route_name'            => 'test便',
            'driver_user_no'        => 1,
            'use_vehicle_id'        => 2,
            'ride_memo'             => 'test',
            'is_active'             => 1,
        ]);
        RideDetail::create([
            'ride_id'               => 1,
            'boarding_location_id'  => 4,
            'location_name'         => 'ロジポート',
            'location_memo'         => null,
            'stop_order'            => 1,
            'departure_time'        => '08:45',
        ]);
        RideDetail::create([
            'ride_id'               => 1,
            'boarding_location_id'  => 5,
            'location_name'         => '第1営業所裏',
            'location_memo'         => '路駐',
            'stop_order'            => 2,
            'arrival_time'          => '08:50',
            'departure_time'        => '08:51',
        ]);
        RideDetail::create([
            'ride_id'               => 1,
            'boarding_location_id'  => 2,
            'location_name'         => '本社前',
            'location_memo'         => '路駐',
            'stop_order'            => 3,
            'arrival_time'          => '08:54',
            'departure_time'        => '08:55',
        ]);
        RideDetail::create([
            'ride_id'               => 1,
            'boarding_location_id'  => 1,
            'location_name'         => 'IMP三郷',
            'location_memo'         => null,
            'stop_order'            => 4,
            'arrival_time'          => '09:10',
        ]);
        for($i = 3; $i < 15; $i++){
            RideUser::create([
                'ride_detail_id'        => 1,
                'user_no'               => $i,
            ]);
        }
        RideUser::create([
            'ride_detail_id'        => 2,
            'user_no'               => 20,
        ]);
    }
}
