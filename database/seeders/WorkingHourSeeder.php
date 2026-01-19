<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\WorkingHour;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkingHour::create([
            'working_type'  => 'daily',
            'working_hour'  => 2.75,
        ]);
        WorkingHour::create([
            'working_type'  => 'daily',
            'working_hour'  => 3.75,
        ]);
        WorkingHour::create([
            'working_type'  => 'daily',
            'working_hour'  => 4.75,
        ]);
        WorkingHour::create([
            'working_type'  => 'daily',
            'working_hour'  => 5.50,
        ]);
        WorkingHour::create([
            'working_type'  => 'daily',
            'working_hour'  => 6.50,
        ]);

        WorkingHour::create([
            'working_type'  => 'half',
            'working_hour'  => 2.75,
        ]);
        WorkingHour::create([
            'working_type'  => 'half',
            'working_hour'  => 3.25,
        ]);
    }
}