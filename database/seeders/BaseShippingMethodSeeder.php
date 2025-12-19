<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\BaseShippingMethod;

class BaseShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BaseShippingMethod::create([
            'shipping_method_id' => 1,
            'base_id' => '3rd',
            'setting_1' => '0489300033',
            'setting_2' => '01',
            'setting_3' => 'A',
        ]);
        BaseShippingMethod::create([
            'shipping_method_id' => 2,
            'base_id' => '3rd',
            'setting_1' => '0489300033',
            'setting_2' => '01',
            'setting_3' => '8',
        ]);
        BaseShippingMethod::create([
            'shipping_method_id' => 3,
            'base_id' => '3rd',
            'setting_1' => '0489300033',
            'setting_2' => '01',
            'setting_3' => '0',
        ]);
        BaseShippingMethod::create([
            'shipping_method_id' => 4,
            'base_id' => '3rd',
            'setting_1' => '116894510676',
            'e_hiden_version_id' => 2,
        ]);
    }
}
