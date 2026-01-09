<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\StatutoryLeave;
use App\Models\User;

class StatutoryLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::select('user_no')->get()->each(function ($user) {
            StatutoryLeave::create([
                'user_no'                         => $user->user_no,
                'statutory_leave_expiration_date' => '2026-03-31',
                'statutory_leave_days'            => 5,
                'statutory_leave_remaining_days'  => 5,
            ]);
        });
    }
}