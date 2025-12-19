<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\ItemMall;

class ItemMallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemMall::create([
            'item_id'               => 1,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000175',
            'mall_variation_code'   => null,
        ]);
        ItemMall::create([
            'item_id'               => 2,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000174',
            'mall_variation_code'   => null,
        ]);
        ItemMall::create([
            'item_id'               => 3,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000084',
            'mall_variation_code'   => '4532640833817',
        ]);
        ItemMall::create([
            'item_id'               => 4,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000403',
            'mall_variation_code'   => null,
        ]);
        ItemMall::create([
            'item_id'               => 5,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000465',
            'mall_variation_code'   => '4532640834340',
        ]);
        ItemMall::create([
            'item_id'               => 6,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000465',
            'mall_variation_code'   => '4532640834357',
        ]);
        /* ItemMall::create([
            'item_id'               => 7,
            'mall_id'               => 1,
            'mall_item_code'        => '000000000001',
            'mall_variation_code'   => null,
        ]); */
    }
}
