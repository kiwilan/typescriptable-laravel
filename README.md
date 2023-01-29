# laravel-typescriptable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/laravel-typescriptable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typescriptable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typescriptable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/laravel-typescriptable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typescriptable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/laravel-typescriptable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/laravel-typescriptable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typescriptable)

Laravel package to types Eloquent models with full TypeScript type.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/laravel-typescriptable
```

## Features

-   [x] Generate TypeScript types for [Eloquent models](https://laravel.com/docs/9.x/eloquent)
-   [x] Generate TypeScript types for [Eloquent relations](https://laravel.com/docs/9.x/eloquent-relationships) (except `morphTo`)
-   [x] Generate TypeScript types for `casts` (include native `enum` support)
-   [x] Generate TypeScript types for `dates`
-   [x] Generate TypeScript types for `appends` (partial for [Attribute Casting](https://laravel.com/docs/9.x/eloquent-mutators))
-   [x] Generate TypeScript types `counts`

## Usage

```bash
php artisan typescriptable:models
```

### Options

-   `-M|--models-path=`: The path to the models. (default: `app/Models`)
-   `-O|--output=`: Output path for Typescript file. (default: `resources/js`)
-   `-F|--output-file=`: Output name for Typescript file. (default: `types-models.d.ts`)
-   `-T|--fake-team`: For Jetstream, add fake Team model if you choose to not install teams to prevent errors in components. (default: `false`)

## Example

An example of Eloquent model.

```php
<?php

namespace App\Models;

class Story extends Model
{
    use HasFactory;
    use HasSlug;
    use HasSearchableName;
    use HasSeo;
    use Publishable;
    use Mediable;
    use Searchable;

    protected $fillable = [
        'title',
        'abstract',
        'original_link',
        'picture',
    ];

    protected $appends = [
        'time_to_read',
    ];

    protected $withCount = [
        'chapters',
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
declare namespace App.Models {
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
        seo?: string[];
        mediables_list?: string[];
        chapters?: Chapter[];
        category?: Category;
        author?: Author;
        tags?: Tag[];
        mediable?: { picture: string };
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
