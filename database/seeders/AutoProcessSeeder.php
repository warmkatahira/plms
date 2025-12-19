<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\AutoProcess;
use App\Models\AutoProcessOrderItem;
use App\Models\AutoProcessCondition;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AutoProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/auto_process_insert.sql');
        // SQLファイルの読み込み
        $sql = File::get($path);
        // SQL実行
        DB::unprepared($sql);
    }
}
