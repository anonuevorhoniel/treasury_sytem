<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [ 
            ['name' => "Jhal", "email" => "jhal@gmail.com", "password" => "jhal123123"],
            ['name' => "Encoder1", "email" => "encoder1@gmail.com", "password" => "encoder1123123"],
            ['name' => "Encoder2", "email" => "encoder2@gmail.com", "password" => "encoder2123123"],
        ];

        foreach($users as $user) {
            User::create([
                'email' => $user['email'],
                'name' => $user['name'],
                'password' => Hash::make($user['password']),
            ]);
        }
    }
}
