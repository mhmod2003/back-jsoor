<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RefugeeUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Refugee User',
            'email' => 'refugee@example.com',
            'phone' => '0999999999',
            'type' => 'refugee',
            'role' => 'user',
            'password' => Hash::make('refugee123'),
        ]);
    }
}
