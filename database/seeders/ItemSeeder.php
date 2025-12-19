<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Item;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/item_item_mall_insert.sql');
        // SQLファイルの読み込み
        $sql = File::get($path);
        // SQL実行
        DB::unprepared($sql);
        // ポストカードを追加
        for($i = 1; $i <= 15; $i++){
            Item::create([
                'item_code' => $i,
                'item_name' => $i,
                'item_category_1' => 'ハイキュー!! 夏祭り POP UP SQUARE 2025',
                'item_category_2' => 'ポストカード',
                'is_stock_managed' => 0,
                'is_shipping_inspection_required' => 0,
                'is_hide_on_delivery_note' => 1,
                'sort_order' => $i + 1000,
            ]);
        }
    }
}
