<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id'               => 'katahira',
            'last_name'             => '片平',
            'first_name'            => '貴順',
            'password'              => bcrypt('katahira134'),
            'role_id'               => 'system_admin',
            'is_driver_eligible'    => true,
            'chatwork_id'           => '3281641',
            'must_change_password'  => false,
        ]);
        User::create([
            'user_id'               => 'dirver-A',
            'last_name'             => 'ドライバー',
            'first_name'            => 'A',
            'password'              => bcrypt('katahira134'),
            'role_id'               => 'part',
            'is_driver_eligible'    => true,
            'must_change_password'  => false,
        ]);
        User::factory()->count(20)->create();
    }
}