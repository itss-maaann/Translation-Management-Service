<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $localeIds = Locale::pluck('id')->toArray();

        return [
            'locale_id' => $this->faker->randomElement($localeIds),
            'key'       => $this->faker->unique()->words(3, true),
            'value'     => $this->faker->sentence(6),
        ];
    }

    /**
     * Configure the factory to attach random tags after creation.
     */
    public function configure(): self
    {
        return $this->afterCreating(function (Translation $translation) {
            $tagIds = Tag::pluck('id')->toArray();
            $available = count($tagIds);
            $count = $this->faker->numberBetween(1, max(1, $available));
            $translation->tags()->attach(
                $this->faker->randomElements($tagIds, $count)
            );
        });
    }
}
