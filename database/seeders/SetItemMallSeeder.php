<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\SetItemMall;

class SetItemMallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SetItemMall::create([
            'set_item_id'           => 1,
            'mall_id'               => 1,
            'mall_set_item_code'    => '000000000001',
        ]);
    }
}
