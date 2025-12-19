<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\SetItemDetail;

class SetItemDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SetItemDetail::create([
            'set_item_id'               => 1,
            'component_item_id'         => 2,
            'component_quantity'        => 2,
        ]);
        SetItemDetail::create([
            'set_item_id'               => 1,
            'component_item_id'         => 3,
            'component_quantity'        => 4,
        ]);
    }
}
