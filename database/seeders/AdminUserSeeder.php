<?php
// database/seeders/AdminUserSeeder.php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'super@admin.test'],
            ['name' => 'SuperAdmin', 'password' => Hash::make('SuperPass123'), ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@admin.test'],
            ['name' => 'Admin', 'password' => Hash::make('AdminPass123'), ]

        );

        // Survey Team
        User::updateOrCreate(
            ['email' => 'survey@team.test'],
            ['name' => 'SurveyTeam', 'password' => Hash::make('SurveyPass123'), ]
        );
    }
}

