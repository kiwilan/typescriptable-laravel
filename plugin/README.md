# @kiwilan/typescriptable-laravel

Add some helpers for your Inertia app with TypeScript.

> [!IMPORTANT]
>
> -   Built for [Vite](https://vitejs.dev/) with [`laravel-vite-plugin`](https://github.com/laravel/vite-plugin) and [Inertia](https://inertiajs.com/).
> -   Built for [Vue 3](https://vuejs.org/)
> -   Works with SSR (Server Side Rendering) for [Inertia](https://inertiajs.com/server-side-rendering)

## Installation

```bash
# npm
npm install @kiwilan/typescriptable-laravel --save-dev
# pnpm
pnpm add @kiwilan/typescriptable-laravel -D
# yarn
yarn add @kiwilan/typescriptable-laravel -D
```

## Requirements

> [!IMPORTANT]
>
> -   [`tightenco/ziggy`](https://github.com/tighten/ziggy) is required for route helpers.
> -   [`kiwilan/typescriptable-laravel`](https://github.com/kiwilan/typescriptable-laravel) is recommended for better experience with TypeScript.

When you install [Inertia](https://inertiajs.com/) with Laravel, I advice to use [Jetstream](https://jetstream.laravel.com) to setup your project. If you don't want to use Jetstream, you can just manually add `ziggy` to `HandleInertiaRequests.php` middleware (or any other middleware added to `web` middleware) into `share()` method.

> [!NOTE]
> You can see an example of `HandleInertiaRequests.php` middleware with [this gist](https://gist.github.com/ewilan-riviere/f1dbc20669ed2669f745e3e0e0771537).

Middleware `HandleInertiaRequests.php` have to be updated with `tightenco/ziggy`:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
```

## Features

-   🦾 Add TypeScript experience into `inertia`
-   💨 Vite plugin to execute automatically [kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel)'s commands :' `typescriptable:eloquent`, `typescriptable:routes` and `typescriptable:routes` with watch mode.
-   📦 Vue composables
    -   `useRouter()` composable with `isRouteEqualTo()` method, `currentRoute` computed and `route()` method
    -   `useInertia()` composable for `page` computed, `component` computed, `props` computed, `url` computed, `version` computed, `auth` computed, `user` computed and `isDev` computed
    -   `useFetch()` with `http` group methods, `laravel` group methods and `inertia` group methods. Each group has `get()`, `post()`, `put()`, `patch()` and `delete()` methods
        -   `http` is for anonymous HTTP requests with native `fetch`
        -   `laravel` is for Laravel HTTP requests with route name (works for internal API) with native `fetch`
        -   `inertia` is for Inertia HTTP requests with route name
-   💚 Vue plugin to use global methods for `template` into Vue components:
    -   `$route` transform route to `string` with Laravel route name and parameters
    -   `$isRouteEqualTo` transform route name or path to `boolean`
    -   `$currentRoute` give current route
    -   Auto-import : `Head` from `@inertiajs/vue3`, `Link` from `@inertiajs/vue3`

## Setup

### Vite plugin

In your `vite.config.ts`:

```ts
import { defineConfig } from "vite";
import typescriptable from "@kiwilan/typescriptable-laravel/vite";

export default defineConfig({
    plugins: [
        // Default config
        typescriptable({
            autoreload: true,
            inertia: true,
            inertiaPaths: {
                base: "resources/js",
                pageType: "types-inertia.d.ts",
                globalType: "types-inertia-global.d.ts",
            },
            models: true,
            routes: false,
            settings: true,
        }),
    ],
});
```

### Inertia

This below configuration is not required, if you want to use global methods into your `template`, you have to add `VueTypescriptable` into your Vue app.

-   `resolveTitle()` is a helper to resolve title with `title` and `appName` parameters and `seperator` as optional parameter
-   `resolvePages()` is a helper to resolve pages with `name` and `pages` parameters with right return type for TypeScript
-   `VueTypescriptable` is a Vue plugin to add global methods for `template` into Vue components

In your `resources/js/app.ts`:

```ts
import "./bootstrap";
import "../css/app.css";

import type { DefineComponent } from "vue";
import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { VueTypescriptable, resolvePages, resolveTitle } from '@kiwilan/typescriptable-laravel'; // Import VueTypescriptable
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

createInertiaApp({
    title: title => resolveTitle(title, 'My App'), // You can use helper `resolveTitle()`
  resolve: name => resolvePages(name, import.meta.glob('./Pages/**/*.vue')), // You can use helper `resolvePages()`
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueTypescriptable); // Add Vue plugin
            .mount(el)
    },
});
```

For SSR support, you have to add `VueTypescriptable` into your `ssr.ts`:

```ts
import { createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
import { createSSRApp, h } from "vue";
import {
    VueTypescriptable,
    resolvePages,
    resolveTitle,
} from "@kiwilan/typescriptable-laravel";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

createServer((page) =>
    createInertiaApp({
        title: (title) => resolveTitle(title, "My App"), // Optional
        page,
        render: renderToString,
        resolve: (name) =>
            resolvePages(name, import.meta.glob("./Pages/**/*.vue")), // Optional
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(VueTypescriptable) // Add Vue plugin
                .use(ZiggyVue, {
                    ...(page.props.ziggy as any),
                    location: new URL((page.props.ziggy as any).location),
                });
        },
    })
);
```

## Usage

Many options are available into composables

```vue
<script lang="ts" setup>
import {
    useRouter,
    useInertia,
    useFetch,
} from "@kiwilan/typescriptable-laravel";

const { route, isRouteEqualTo, currentRoute } = useRouter();
const { page, component, props, url, version, auth, user, isDev } =
    useInertia();
// HTTP requests, each group has get(), post(), put(), patch() and delete() methods
const { http, laravel, inertia } = useFetch();
</script>
```

With Vue plugin, you can use global methods into your `template`:

```vue
<template>
    <IHead title="Home" />
    <ILink :href="$route('home')">Home</ILink>
    <ILink :href="$route('user.show', { id: 1 })">User</ILink>
</template>
```

## Tests

### Local test

```bash
pnpm package
```

In `package.json`

```json
{
    "devDependencies": {
        "@kiwilan/typescriptable-laravel": "file:~/kiwilan-typescriptable-laravel.tgz"
    }
}
```
