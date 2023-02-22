# @kiwilan/typescriptable-laravel

[kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel) is required.

## Installation

```bash
npm install @kiwilan/typescriptable-laravel --save-dev
```

Or

```bash
pnpm add @kiwilan/typescriptable-laravel -D
```

## Features

-   Inertia types for `page` and global properties
-   `vite` plugin to generate typescript files from Laravel models and routes with [kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel)
-   Add some methods for setup `appResolve`, `appTitle`, `appName`
-   `useInertiaTyped` composable with some features:
    -   `router` with `get`, `post`, `put`, `patch`, `delete` methods typed with selected routes
    -   `route` transform route to `string`
    -   `isRoute` transform route name or path to `boolean`
    -   `currentRoute` give current route
    -   `page` with `usePage` typed
-   `TypedLink` Vue component with `to` prop typed with selected `GET` routes
-   Vue plugin to use global methods for `template` into Vue components:
    -   `$route` transform route to `string`
    -   `$isRoute` transform route name or path to `boolean`
    -   `$currentRoute` give current route
-   Auto import components, you can use it without import
    -   `Head` from `@inertiajs/vue3`
    -   `Link` from `@inertiajs/vue3`
    -   `TypedLink` from `@kiwilan/typescriptable-laravel/vue`

## Usage

### Vite plugin

In your `vite.config.ts`:

```ts
import { defineConfig } from "vite";
import { Typescriptable } from "@kiwilan/typescriptable-laravel";

export default defineConfig({
    plugins: [
        Typescriptable({
            // Options
        }),
    ],
});
```

### Inertia setup

In your `resources/js/app.ts`:

```ts
import "./routes"; // add this line to import routes from `routes.ts` generated with `php artisan typescriptable:routes`

import { createInertiaApp } from "@inertiajs/vue3";
import {
    InertiaTyped,
    appResolve,
    appTitle,
} from "@kiwilan/typescriptable-laravel/vue";

createInertiaApp({
    // helper for setup title
    title: (title) => appTitle(title, "App"),
    // helper for setup resolve
    resolve: (name) =>
        appResolve(name, import.meta.glob("./Pages/**/*.vue", { eager: true })),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            // add this line to use `useInertiaTyped` composable
            .use(InertiaTyped);

        app.mount(el);
    },
    progress: {
        delay: 250,
        color: "#4B5563",
        includeCSS: true,
        showSpinner: false,
    },
});
```

### Composables

#### `useInertiaTyped`

```vue
<script lang="ts" setup>
import { useInertiaTyped } from "@kiwilan/typescriptable-laravel/vue";

const { router, route, isRoute, currentRoute, page } = useInertiaTyped();

console.log(currentRoute());

const form = reactive({
    name: page.props.user.name,
    email: page.props.user.email,
});

const pushToStories = () => {
    if (isRoute({ name: "front.stories.index" })) {
        return;
    }

    router.get({
        name: "front.stories.index",
    });
};

const logout = () => {
    router.post({
        name: "logout",
    });
};
</script>
```

### Components

#### `TypedLink`

```vue
<template>
    <div>
        <TypedLink
            :to="{
                name: 'front.stories.index',
            }"
        >
            Stories
        </TypedLink>
    </div>
</template>
```
