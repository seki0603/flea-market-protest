<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProfile;

class UserProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserProfile::create([
            'user_id' => 1,
            'avatar_path' => 'avatars/user1.png',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区西新宿1-1-1',
            'building' => 'テストマンション101',
        ]);

        UserProfile::create([
            'user_id' => 2,
            'avatar_path' => 'avatars/user2.png',
            'postal_code' => '234-5678',
            'address' => '大阪府大阪市北区梅田2-2-2',
            'building' => null,
        ]);

        UserProfile::create([
            'user_id' => 3,
            'avatar_path' => 'avatars/user3.png',
            'postal_code' => '345-6789',
            'address' => '福岡県福岡市中央区天神3-3-3',
            'building' => '中央ビル3F',
        ]);
    }
}
