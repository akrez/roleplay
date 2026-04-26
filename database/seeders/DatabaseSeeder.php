<?php

namespace Database\Seeders;

use App\Enums\UserFriendshipStatusEnum;
use App\Models\User;
use App\Services\UserFriendshipService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $akrez = User::factory()->create([
            'username' => 'akrez',
            'name' => 'aliakbar',
            'email' => 'akrez'.'@gmail.com',
            'password' => Hash::make('secret1013'),
        ]);

        foreach ([
            'bahare' => ['name' => 'بهاره'],
            'alireza' => ['name' => 'علی رضا'],
            'haniye' => ['name' => 'هانیه'],
            'maleking' => ['name' => 'ملکینگ'],
            'mshoureshi' => ['name' => 'محمد.ش'],
        ] as $username => $userData) {
            $user = User::factory()->create($userData + [
                'username' => $username,
                'email' => $username.'@gmail.com',
                'password' => Hash::make($username),
            ]);
            $id = UserFriendshipService::new()->create($akrez->id, $user->username)->getData('user_friendship.id');
            UserFriendshipService::new()->status($id, $user->id, UserFriendshipStatusEnum::ACCEPTED->name);
        }
    }
}
