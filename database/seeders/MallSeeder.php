<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Mall;

class MallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mall::create([
            'mall_name'             => 'makeshop',
            'mall_image_file_name'  => 'makeshop.svg',
        ]);
    }
}
