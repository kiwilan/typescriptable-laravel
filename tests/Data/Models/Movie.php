<?php

namespace Kiwilan\Typescriptable\Tests\Data\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'title',
        'year',
        'french_title',
        'original_title',
        'release_date',
        'original_language',
        'overview',
        'popularity',
        'is_adult',
        'tagline',
        'homepage',
        'status',
        'certification',
        'tmdb_url',
        'poster',
        'poster_tmdb',
        'poster_color',
        'backdrop',
        'backdrop_tmdb',
        'added_at',
        'fetched_at',
        'fetched_has_failed',
        'slug',
        'budget', // `200000000`
        'revenue', // `1056057273`
        'edition', // `Blu-ray`
        'version', // `Theatrical`
        'library',
        'is_multilingual', // `false`
    ];

    protected $casts = [
        'budget' => 'integer',
        'revenue' => 'integer',
        'is_multilingual' => 'boolean',
    ];

    protected $appends = [
        'show_route',
    ];

    protected $queryDefaultSort = 'title';

    protected $queryAllowedSorts = ['title', 'release_date', 'added_at', 'popularity', 'runtime'];

    protected $queryPagination = 50;

    public function similars(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'similars', 'movie_id', 'similar_id');
    }

    public function recommendations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'recommendations', 'movie_id', 'recommendation_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this
            ->morphToMany(Member::class, 'memberable')
            ->withPivot([
                'character',
                'job',
                'department',
                'order',
                'is_adult',
                'known_for_department',
                'is_crew',
            ])
            ->orderBy('order');
    }
}
