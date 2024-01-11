# @kiwilan/typescriptable-laravel

Add some helpers for your Inertia app with TypeScript.

> [!IMPORTANT]
>
> -   PHP `composer` package [`kiwilan/typescriptable-laravel`](https://github.com/kiwilan/typescriptable-laravel) is required.
> -   Built for [Vite](https://vitejs.dev/) with `laravel-vite-plugin`, [Inertia](https://inertiajs.com/) and [Vue 3](https://vuejs.org/).

## Installation

```bash
# npm
npm install @kiwilan/typescriptable-laravel --save-dev
# pnpm
pnpm add @kiwilan/typescriptable-laravel -D
# yarn
yarn add @kiwilan/typescriptable-laravel -D
```

## Features

-   🦾 Add TypeScript experience into `inertia`
-   💨 Vite plugin to execute automatically [kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel)'s commands :' `typescriptable:models`, `typescriptable:routes` and `typescriptable:routes` with watch mode.
-   📦 Vue composables
    -   `useRouter()` composable with `isRoute()` method, `currentRoute` computed and `route()` method
    -   `useInertia()` composable for `page` computed, `component` computed, `props` computed, `url` computed, `version` computed, `auth` computed, `user` computed and `isDev` computed
    -   `useFetch()` with `http` group methods, `laravel` group methods and `inertia` group methods. Each group has `get()`, `post()`, `put()`, `patch()` and `delete()` methods
        -   `http` is for anonymous HTTP requests with native `fetch`
        -   `laravel` is for Laravel HTTP requests with route name (works for internal API) with native `fetch`
        -   `inertia` is for Inertia HTTP requests with route name
-   💚 Vue plugin to use global methods for `template` into Vue components:
    -   `$route` transform route to `string` with Laravel route name and parameters
    -   `$isRoute` transform route name or path to `boolean`
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
        typescriptable({
            autoreload: true,
            inertia: true,
            inertiaPaths: {
                base: "resources/js",
                pageType: "types-inertia.d.ts",
                globalType: "types-inertia-global.d.ts",
            },
            models: true,
            routes: true,
            settings: true,
        }),
    ],
});
```

### Inertia

In your `resources/js/app.ts`:

```ts
import "./bootstrap";
import "../css/app.css";

import type { DefineComponent } from "vue";
import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { VueTypescriptable } from "@kiwilan/typescriptable-laravel"; // Import VueTypescriptable
import "./routes"; // Import routes

createInertiaApp({
    title: (title) => `${title} - Laravel`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")) as Promise<DefineComponent>,
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueTypescriptable); // Add Vue plugin
            .mount(el)
    },
});
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

const { route, isRoute, currentRoute } = useRouter();
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
