<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\ApiAction;

class ApiActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiAction::create([
            'api_action_name' => '受注取得',
        ]);
        ApiAction::create([
            'api_action_name' => '商品画像更新',
        ]);
        ApiAction::create([
            'api_action_name' => '在庫更新',
        ]);
        ApiAction::create([
            'api_action_name' => '注文キャンセル',
        ]);
    }
}
