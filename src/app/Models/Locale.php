<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locale extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * A locale has many translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }
}
