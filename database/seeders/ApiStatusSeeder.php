<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\ApiStatus;

class ApiStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiStatus::create([
            'api_status_name' => '成功',
        ]);
        ApiStatus::create([
            'api_status_name' => '失敗',
        ]);
        ApiStatus::create([
            'api_status_name' => '警告',
        ]);
    }
}
