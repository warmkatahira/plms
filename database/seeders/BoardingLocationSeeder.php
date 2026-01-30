<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\BoardingLocation;

class BoardingLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BoardingLocation::create([
            'location_name' => 'IMP三郷',
            'location_memo' => null,
            'sort_order'    => 1,
        ]);
        BoardingLocation::create([
            'location_name' => '本社前',
            'location_memo' => '路駐',
            'sort_order'    => 2,
        ]);
        BoardingLocation::create([
            'location_name' => '本社反対',
            'location_memo' => '路駐',
            'sort_order'    => 3,
        ]);
        BoardingLocation::create([
            'location_name' => 'ロジポート',
            'location_memo' => null,
            'sort_order'    => 4,
        ]);
        BoardingLocation::create([
            'location_name' => '第1営業所裏',
            'location_memo' => '路駐',
            'sort_order'    => 5,
        ]);
        BoardingLocation::create([
            'location_name' => '第1営業所反対側',
            'location_memo' => '路駐',
            'sort_order'    => 6,
        ]);
    }
}