<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Prefecture;

class PrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // エリアごとの都道府県を配列に分ける
        $prefectures_by_area = [
            '北海道' => ['北海道'],
            '東北' => ['青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
            '関東' => ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
            '北陸・甲信越' => ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県'],
            '東海' => ['岐阜県', '静岡県', '愛知県', '三重県'],
            '近畿' => ['滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県'],
            '中国' => ['鳥取県', '島根県', '岡山県', '広島県', '山口県'],
            '四国' => ['徳島県', '香川県', '愛媛県', '高知県'],
            '九州' => ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県'],
            '沖縄' => ['沖縄県'],
        ];
        // エリアの分だけループ処理
        foreach($prefectures_by_area as $area => $prefectures){
            // 都道府県の分だけループ処理
            foreach($prefectures as $prefecture){
                Prefecture::create([
                    'prefecture_name'   => $prefecture,
                    'area_name'         => $area,
                    'shipping_base_id'  => '3rd',
                ]);
            }
        }
    }
}