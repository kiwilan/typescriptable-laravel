<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum;
use Kiwilan\Typescriptable\Tests\Data\Enums\HomePageEnum;
use Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author;

class Movie extends Model
{
    use HasUlids;

    protected $fillable = ['title', 'subtitles', 'homepage', 'revenue', 'is_multilingual', 'added_at', 'fetched_at'];

    protected $casts = [
        'subtitles' => 'array',
        'budget' => BudgetEnum::class,
        'homepage' => HomePageEnum::class,
        'revenue' => 'integer',
        'is_multilingual' => 'boolean',
        'added_at' => 'datetime:Y-m-d',
    ];

    protected $appends = ['show_route', 'api_route'];

    protected $hidden = ['budget', 'edition'];

    public function getShowRouteAttribute(): string
    {
        return 'movies.show';
    }

    /**
     * @return string
     */
    protected function apiRoute(): Attribute
    {
        return Attribute::make(get: fn (?string $value) => ucfirst($value));
    }

    public function recommendations(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'recommendations', 'movie_id', 'recommendation_id');
    }

    public function members(): MorphToMany
    {
        return $this->morphToMany(Member::class, 'memberable');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
