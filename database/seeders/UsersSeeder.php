<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users=[
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('Jalandhar1'),
                'isAdmin' => 1,
            ]
        ];

        foreach($users as $item){
            User::create($item);
        }
    }
}
