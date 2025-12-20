<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Base;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Base::create([
            'base_id'               => 'honsha',
            'base_name'             => '本社',
            'sort_order'            => 1,
        ]);
        Base::create([
            'base_id'               => '1st',
            'base_name'             => '第1営業所',
            'sort_order'            => 2,
        ]);
        Base::create([
            'base_id'               => '2nd',
            'base_name'             => '第2営業所',
            'sort_order'            => 3,
        ]);
        Base::create([
            'base_id'               => '3rd',
            'base_name'             => '第3営業所',
            'sort_order'            => 4,
        ]);
    }
}