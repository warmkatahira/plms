<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\PaidLeave;
use App\Models\User;

class PaidLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::select('user_no')->get()->each(function ($user) {
            PaidLeave::create([
                'user_no'                   => $user->user_no,
                'paid_leave_granted_days'   => 10,
                'paid_leave_remaining_days' => 8,
                'paid_leave_used_days'      => 2,
                'daily_working_hours'       => 5.50,
                'half_day_working_hours'    => 2.75,
            ]);
        });
    }
}