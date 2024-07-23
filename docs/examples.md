# Examples

## Eloquent Models

An example of Eloquent model.

```php
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
        'revenue',
        'edition',
        'version',
        'library',
        'is_multilingual',
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
```

TS file generated at `resources/js/types-eloquent.d.ts`

```ts
declare namespace App.Models {
    export interface Movie {
        id: string;
        tmdb_id?: number;
        title?: string;
        year?: number;
        subtitles: any[];
        slug: string;
        french_title?: string;
        original_title?: string;
        release_date?: string;
        original_language?: string;
        overview?: string;
        popularity?: number;
        is_adult?: number;
        homepage?: "draft" | "scheduled" | "published";
        tagline?: string;
        status?: string;
        certification?: string;
        tmdb_url?: string;
        imdb_id?: string;
        runtime?: number;
        budget: "draft" | "scheduled" | "published";
        revenue?: number;
        edition?: string;
        version?: string;
        library?: string;
        is_multilingual: boolean;
        poster?: string;
        poster_tmdb?: string;
        poster_color?: string;
        backdrop?: string;
        backdrop_tmdb?: string;
        author_id?: number;
        added_at?: string;
        fetched_at?: string;
        fetched_has_failed: number;
        created_at?: string;
        updated_at?: string;
        show_route?: string;
        api_route?: string;
        similars_count?: number;
        recommendations_count?: number;
        members_count?: number;
        media_count?: number;
        similars?: App.Models.Movie[];
        recommendations?: App.Models.Movie[];
        members?: App.Models.Member[];
        author?: App.Models.NestedAuthor;
        media?: any[];
    }
}

declare namespace App {
    export interface PaginateLink {
        url: string;
        label: string;
        active: boolean;
    }
    export interface Paginate<T = any> {
        data: T[];
        current_page: number;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        links: App.PaginateLink[];
        next_page_url: string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: number;
        total: number;
    }
    export interface ApiPaginate<T = any> {
        data: T[];
        links: {
            first?: string;
            last?: string;
            prev?: string;
            next?: string;
        };
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            links: App.PaginateLink[];
            path: string;
            per_page: number;
            to: number;
            total: number;
        };
    }
}
```

## Vue component

Usage in Vue component.

```vue
<script lang="ts" setup>
defineProps<{
    movie?: App.Models.Movie;
}>();
</script>
```

Or with paginate option.

```vue
<script lang="ts" setup>
defineProps<{
    movies?: App.Paginate<App.Models.Movie>;
}>();
</script>
```
