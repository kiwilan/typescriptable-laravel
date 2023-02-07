# typescriptable-laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/typescriptable-laravel.svg?style=flat-square)](https://packagist.org/packages/kiwilan/typescriptable-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/typescriptable-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/typescriptable-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/typescriptable-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/typescriptable-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/typescriptable-laravel.svg?style=flat-square)](https://packagist.org/packages/kiwilan/typescriptable-laravel)

Laravel package to type Eloquent models with full TypeScript type.

> PHP 8.1 is required and Laravel 9 is recommended.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/typescriptable-laravel
```

## Features

-   Generate TypeScript types for [Eloquent models](https://laravel.com/docs/9.x/eloquent)
-   Generate TypeScript types for [Eloquent relations](https://laravel.com/docs/9.x/eloquent-relationships) (except `morphTo`)
    -   [ ] Generate TypeScript types for `morphTo`
-   Generate TypeScript types for `casts` (include native `enum` support)
-   Generate TypeScript types for `dates`
-   Generate TypeScript types for `appends` (partial for [`Casts\Attribute`](https://laravel.com/docs/9.x/eloquent-mutators#defining-an-accessor), you can use old way to define `get*Attribute` methods)
    -   [ ] Use `appends` to define type for `Casts\Attribute` methods
-   Generate TypeScript types `counts`
-   Generate pagination types for [Laravel pagination](https://laravel.com/docs/9.x/pagination) with option `paginate`

## Usage

```bash
php artisan typescriptable:models
```

### Options

-   `-M|--models-path=`: The path to the models. (default: `app/Models`)
-   `-O|--output=`: Output path for Typescript file. (default: `resources/js`)
-   `-F|--output-file=`: Output name for Typescript file. (default: `types-models.d.ts`)
-   `-T|--fake-team`: For [Jetstream](https://jetstream.laravel.com/2.x/introduction.html), add fake Team model if you choose to not install teams to prevent errors in components. (default: `false`)
-   `-P|--paginate`: Add paginate type for Laravel pagination.

## Example

An example of Eloquent model.

```php
<?php

namespace App\Models;

use Kiwilan\Steward\Enums\PublishStatusEnum;

class Story extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'title',
        'slug',
        'abstract',
        'original_link',
        'picture',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $appends = [
        'time_to_read',
    ];

    protected $withCount = [
        'chapters',
    ];

    protected $casts = [
        'status' => PublishStatusEnum::class,
        'published_at' => 'datetime:Y-m-d',
    ];

    public function getTimeToReadAttribute(): int

    public function chapters(): HasMany

    public function category(): BelongsTo

    public function author(): BelongsTo

    public function tags(): BelongsToMany
}
```

TS file generated at `resources/js/types-models.d.ts`

```typescript
declare namespace App {
    declare namespace Models {
        export type Story = {
            id: number;
            title: string;
            slug?: string;
            abstract?: string;
            original_link?: string;
            picture?: string;
            status: "draft" | "scheduled" | "published";
            published_at?: Date;
            meta_title?: string;
            meta_description?: string;
            created_at?: Date;
            updated_at?: Date;
            author_id?: number;
            category_id?: number;
            time_to_read?: number;
            chapters?: Chapter[];
            category?: Category;
            author?: Author;
            tags?: Tag[];
            chapters_count?: number;
            tags_count?: number;
        };
    }
    // With `paginate` option.
    export type PaginateLink = {
        url: string;
        label: string;
        active: boolean;
    };
    export type Paginate<T = any> = {
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
    };
}
```

```vue
<script lang="ts" setup>
defineProps<{
    story?: App.Models.Story;
}>();
</script>
```

Or with paginate option.

```vue
<script lang="ts" setup>
defineProps<{
    stories?: App.Paginate<App.Models.Story>;
}>();
</script>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Kiwilan](https://github.com/kiwilan)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
