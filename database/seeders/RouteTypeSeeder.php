<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\RouteType;

class RouteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RouteType::create([
            'route_type'  => '行き',
            'sort_order'    => 1,
        ]);
        RouteType::create([
            'route_type'  => '帰り',
            'sort_order'    => 2,
        ]);
    }
}