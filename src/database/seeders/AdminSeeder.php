<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name'     => 'Majid Shahzeb',
            'email'    => 'itssmaaann@gmail.com',
            'password' => bcrypt('Majid123'),
        ]);
    }
}
