<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_profiles')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'id' => 1,
            'name' => 'ユーザー1',
            'email' => 'test1@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 2,
            'name' => 'ユーザー2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 3,
            'name' => 'ユーザー3',
            'email' => 'test3@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
