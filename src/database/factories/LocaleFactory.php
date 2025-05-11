<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocaleFactory extends Factory
{
    protected $model = Locale::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->lexify('??'),
            'name' => ucfirst($this->faker->unique()->languageCode)
        ];
    }
}
