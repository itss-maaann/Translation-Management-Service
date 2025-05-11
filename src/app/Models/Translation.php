<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale_id',
        'key',
        'value',
    ];

    /**
     * A translation belongs to one locale.
     */
    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    /**
     * A translation may have many tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'translation_tag',
            'translation_id',
            'tag_id'
        );
    }
}
