<?php

namespace App\Models;

class Movie extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;

    protected $fillable = ['title', 'subtitles', 'homepage', 'revenue', 'is_multilingual', 'added_at', 'fetched_at'];

    protected $casts = [
        'subtitles' => 'array',
        'budget' => \Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum::class,
        'homepage' => \Kiwilan\Typescriptable\Tests\Data\Enums\HomePageEnum::class,
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
    protected function apiRoute(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: fn (?string $value) => ucfirst($value));
    }

    public function recommendations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'recommendations', 'movie_id', 'recommendation_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Member::class, 'memberable');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author::class, 'author_id');
    }
}
