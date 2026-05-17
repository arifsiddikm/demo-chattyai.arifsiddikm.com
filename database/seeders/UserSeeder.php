<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Demo User',
                'username' => 'demo',
                'email'    => 'demo@chattyai.com',
                'password' => Hash::make('demo1234'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Budi Santoso',
                'username' => 'budi',
                'email'    => 'budi@chattyai.com',
                'password' => Hash::make('budi1234'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Siti Rahayu',
                'username' => 'siti',
                'email'    => 'siti@chattyai.com',
                'password' => Hash::make('siti1234'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Andi Pratama',
                'username' => 'andi',
                'email'    => 'andi@chattyai.com',
                'password' => Hash::make('andi1234'),
                'role'     => 'user',
            ],
            [
                'name'     => 'Admin ChattyAI',
                'username' => 'admin',
                'email'    => 'admin@chattyai.com',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
