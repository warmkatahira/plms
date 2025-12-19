<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingMethod::create([
            'shipping_method'               => 'ネコポス',
            'delivery_company_id'           => 1,
            'makeshop_shipping_method_no'   => 29,
        ]);
        ShippingMethod::create([
            'shipping_method'               => 'コンパクト',
            'delivery_company_id'           => 1,
            'makeshop_shipping_method_no'   => 28,
        ]);
        ShippingMethod::create([
            'shipping_method'               => '宅急便',
            'delivery_company_id'           => 1,
            'makeshop_shipping_method_no'   => 7,
        ]);
        ShippingMethod::create([
            'shipping_method'               => '宅配便',
            'delivery_company_id'           => 2,
            'makeshop_shipping_method_no'   => 13,
        ]);
    }
}
