<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = ['mobile', 'desktop', 'web'];

        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
