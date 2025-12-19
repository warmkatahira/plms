<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\SetItem;

class SetItemSeeder extends Seeder
{
    public function run(): void
    {
        SetItem::create([
            'set_item_code'             => '4532640832971',
            'set_item_name'             => 'AAAAAA',
        ]);
    }
}
