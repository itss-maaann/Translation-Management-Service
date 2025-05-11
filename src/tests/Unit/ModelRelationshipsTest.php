<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_has_many_translations(): void
    {
        $locale = Locale::factory()->create(['code'=>'en','name'=>'English']);
        Tag::factory()->create(['name'=>'web']);

        $translations = Translation::factory()->count(2)->create([
            'locale_id' => $locale->id,
        ]);

        $this->assertCount(2, $locale->translations);
        $this->assertInstanceOf(Translation::class, $locale->translations->first());
    }

    public function test_tag_belongs_to_many_translations(): void
    {
        $locale = Locale::factory()->create(['code' => 'en', 'name' => 'English']);
        $tag    = Tag::factory()->create(['name' => 'mobile']);

        $t1 = Translation::create([
            'locale_id' => $locale->id,
            'key'       => 'k1',
            'value'     => 'v1',
        ]);
        $t2 = Translation::create([
            'locale_id' => $locale->id,
            'key'       => 'k2',
            'value'     => 'v2',
        ]);

        $t1->tags()->sync([$tag->id]);
        $t2->tags()->sync([$tag->id]);

        $this->assertCount(2, $tag->translations);
        $this->assertInstanceOf(Translation::class, $tag->translations->first());
    }
}
