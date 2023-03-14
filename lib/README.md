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
-   `Route` Vue component with `to` prop typed with selected `GET` routes
-   Vue plugin to use global methods for `template` into Vue components:
    -   `$route` transform route to `string`
    -   `$isRoute` transform route name or path to `boolean`
    -   `$currentRoute` give current route
-   Auto import components, you can use it without import
    -   `Head` from `@inertiajs/vue3`
    -   `Link` from `@inertiajs/vue3`
    -   `Route` from `@kiwilan/typescriptable-laravel/vue`

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
import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
// typescriptable helpers
import {
    InertiaTyped,
    appResolve,
    appTitle,
} from "@kiwilan/typescriptable-laravel/vue";
// Import routes
import "./routes";

// Keep this line to use `route` helper
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

createInertiaApp({
    // helper for setup title
    title: (title) => appTitle(title, "App"),
    // helper for setup resolve
    resolve: (name) =>
        appResolve(name, import.meta.glob("./Pages/**/*.vue", { eager: true })),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            // Keep this line to use `route` helper
            .use(ZiggyVue)
            // add this line to use `useInertiaTyped` composable
            .use(InertiaTyped)
            .mount(el);
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

#### `Route`

```vue
<template>
    <div>
        <Route
            :to="{
                name: 'front.stories.index',
            }"
        >
            Stories
        </Route>
    </div>
</template>
```
