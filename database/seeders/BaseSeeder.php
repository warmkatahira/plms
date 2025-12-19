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
            'base_id'               => '3rd',
            'base_name'             => '第3営業所',
            'base_color_code'       => '#afeeee',
            'mieru_customer_code'   => 'bluing_jigo_3rd',
            'sort_order'            => 1,
        ]);
    }
}
