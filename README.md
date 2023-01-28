# laravel-typeable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/laravel-typeable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typeable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typeable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/laravel-typeable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typeable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/laravel-typeable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/laravel-typeable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typeable)

Laravel package to types Eloquent models with full TypeScript type.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/laravel-typeable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-typeable-config"
```

This is the contents of the published config file:

```php
return [
  'models' => [
    'path' => resource_path('js'),
    'file' => 'types-models.d.ts',
  ],
];
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
php artisan typeable:models
```

## Example

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
    {
        $chapters = $this->chapters()->get();
        $times = array_map(
            fn ($chapter) => $chapter['time_to_read'],
            $chapters->toArray()
        );

        return array_sum($times);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
```

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
        mediable?: { picture: string };
    };
}
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
