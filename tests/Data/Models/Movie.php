<?php

namespace Kiwilan\Typescriptable\Tests\Data\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum;
use Kiwilan\Typescriptable\Tests\Data\Models\Nested\Author;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Movie extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'year',
        'subtitles',
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
        'revenue', // `1056057273`
        'edition', // `Blu-ray`
        'version', // `Theatrical`
        'library',
        'is_multilingual', // `false`
    ];

    protected $casts = [
        'subtitles' => 'array',
        'budget' => PublishStatusEnum::class,
        'homepage' => PublishStatusEnum::class,
        'revenue' => 'integer',
        'is_multilingual' => 'boolean',
        'added_at' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'show_route',
        'api_route',
    ];

    protected $hidden = [
        'budget',
        'edition',
    ];

    protected $queryDefaultSort = 'title';

    protected $queryAllowedSorts = ['title', 'release_date', 'added_at', 'popularity', 'runtime'];

    protected $queryPagination = 50;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getShowRouteAttribute(): string
    {
        return 'movies.show';
    }

    /**
     * @return string
     */
    protected function apiRoute(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucfirst($value),
        );
    }

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
