<?php

namespace Kiwilan\Typescriptable\Tests\Data\Models\Nested;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kiwilan\Typescriptable\Tests\Data\Models\Story;

class Author extends Model
{
    use HasFactory;

    protected array $mediables = ['avatar'];

    protected $fillable = [
        'name',
        'avatar',
    ];

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            return '';
        });
    }
}
