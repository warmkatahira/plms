<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\OrderCategory;

class OrderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderCategory::create([
            'order_category_name'               => 'F-SQUARE',
            'mall_id'                           => 1,
            'shipper_id'                        => 1,
            'nifuda_product_name_1'             => 'ハイキュー!!グッズ',
            'nifuda_product_name_2'             => '[受注管理ID]',
            'app_id'                            => '253',
            'access_token'                      => 'Bearer PAT.6fdd556b96704f52ec8b0e8c84c7c286d76b0133cc316b703bf921a697d4cc85',
            'api_key'                           => '4811fc438711b7a9a56e8c99eb27e005929a770695ae137b6f33481d472372db',
            'sort_order'                        => 1,
        ]);
    }
}
