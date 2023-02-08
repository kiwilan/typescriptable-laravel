# typescriptable-laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/typescriptable-laravel.svg?style=flat-square)](https://packagist.org/packages/kiwilan/typescriptable-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/typescriptable-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/typescriptable-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/typescriptable-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/typescriptable-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/typescriptable-laravel.svg?style=flat-square)](https://packagist.org/packages/kiwilan/typescriptable-laravel)

PHP package for Laravel to type Eloquent models and routes with autogenerated TypeScript, ready for Inertia.

> PHP 8.1 is required and Laravel 9 is recommended.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/typescriptable-laravel
```

## Models

-   Generate TypeScript types for [Eloquent models](https://laravel.com/docs/9.x/eloquent)
-   Generate TypeScript types for [Eloquent relations](https://laravel.com/docs/9.x/eloquent-relationships) (except `morphTo`)
    -   [ ] Generate TypeScript types for `morphTo`
-   Generate TypeScript types for `casts` (include native `enum` support)
-   Generate TypeScript types for `dates`
-   Generate TypeScript types for `appends` (partial for [`Casts\Attribute`](https://laravel.com/docs/9.x/eloquent-mutators#defining-an-accessor), you can use old way to define `get*Attribute` methods)
    -   [ ] Use `appends` to define type for `Casts\Attribute` methods
-   Generate TypeScript types `counts`
-   Generate pagination types for [Laravel pagination](https://laravel.com/docs/9.x/pagination) with option `paginate`

### Command

```bash
php artisan typescriptable:models
```

-   --`O`|`output=` : Output path for Typescript file, default is `resources/js`.
-   --`F`|`output-file=` : Output name for Typescript file, default is `types-models.d.ts`.
-   --`M`|`models-path=` : The path to the models, default is `app/Models`.
-   --`P`|`paginate` : Add paginate type for Laravel pagination.
-   --`T`|`fake-team` : For Jetstream, add fake Team model if you choose to not install teams to prevent errors in components.

## Ziggy

-   Router feature (can be disable with `skip-router` option): generate TypeScript interface for Laravel routes as `ZiggyLaravelRoutes`.
-   Page feature (can be disable with `skip-page` option): generate TypeScript interface for `usePage` Inertia composable as `InertiaPage`.
-   `embed` option (default to `false`): generate some global components methods, you need to install `@kiwilan/vite-plugin-steward-laravel`, `ziggy-js` package and `InertiaTyped` Vue plugin from `@kiwilan/vite-plugin-steward-laravel`.
    -   `$route`
    -   `$isRoute`
    -   `$currentRoute`
-   default generate some global components methods:
    -   `$page`
    -   `sessions`

### Requirements

> -   [tightenco/ziggy](https://github.com/tighten/ziggy) for `composer`: `composer require tightenco/ziggy`
> -   [ziggy-js](https://www.npmjs.com/package/ziggy-js) for `npm` (optional for `embed` option): `npm install ziggy-js --save-dev`
> -   [@kiwilan/vite-plugin-steward-laravel](https://www.npmjs.com/package/@kiwilan/vite-plugin-steward-laravel) for `npm` (optional for `embed` option, `kiwilan/steward-laravel` package is not required): `npm install @kiwilan/vite-plugin-steward-laravel --save-dev`

### Command

```bash
php artisan typescriptable:ziggy
```

-   --`O`|`output=` : Output path for Typescript file, default is `resources/js`.
-   --`F`|`output-file=` : Output name for Ziggy Typescript file, default is `types-ziggy.d.ts`.
-   --`skip-router` : Skip generation of Laravel routes with `ZiggyLaravelRoutes` interface.
-   --`skip-page` : Skip generation of `usePage` interface with `InertiaPage` interface.
-   --`E`|`embed` : For Vue plugin `InertiaTyped` only, generate global methods interface (default is `false`). Require `ziggy-js` package.

## Examples

### Models

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

Usage in Vue component.

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

#### Ziggy

```typescript
import {
    Config,
    InputParams,
    Router,
    RouteParamsWithQueryOverload,
} from "ziggy-js";

declare type LaravelRoutes = {
    login: { uri: "login"; methods: ["GET", "HEAD"] };
    // ...
};

declare interface IPage {
    props: {
        user: App.Models.User;
        jetstream: {
            canCreateTeams: boolean;
            hasTeamFeatures: boolean;
            managesProfilePhotos: boolean;
            hasApiFeatures: boolean;
            canUpdateProfileInformation: boolean;
            canUpdatePassword: boolean;
            canManageTwoFactorAuthentication: boolean;
            hasAccountDeletionFeatures: boolean;
        };
        [x: string]: unknown;
        errors: import("@inertiajs/core").Errors &
            import("@inertiajs/core").ErrorBag;
    };
    url?: string;
    version?: string;
    scrollRegions?: { top: number; left: number }[];
    rememberedState?: Record<string, unknown>;
    resolvedErrors?: import("@inertiajs/core").Errors;
}

declare global {
    declare interface ZiggyLaravelRoutes extends LaravelRoutes {}
    declare interface InertiaPage extends IPage {}
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $route: (
            name: keyof ZiggyLaravelRoutes,
            params?: RouteParamsWithQueryOverload | RouteParam,
            absolute?: boolean,
            customZiggy?: Config
        ) => string;
        $isRoute: (
            name: keyof ZiggyLaravelRoutes,
            params?: RouteParamsWithQueryOverload
        ) => boolean;
        $currentRoute: () => string;
        $page: InertiaPage;
        sessions: {
            agent: { is_desktop: boolean; browser: string; platform: string };
            ip_address: string;
            is_current_device: boolean;
            last_active: string;
        }[];
    }
}

export { LaravelRoutes };
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
