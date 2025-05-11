<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'itssmaaann@gmail.com'],
            [
                'name'              => 'Majid Shahzeb',
                'password'          => Hash::make('Majid123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
