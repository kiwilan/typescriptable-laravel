# @kiwilan/typescriptable-laravel

Composer package [`kiwilan/typescriptable-laravel`](https://github.com/kiwilan/typescriptable-laravel) is required.

## Installation

```bash
npm install @kiwilan/typescriptable-laravel --save-dev
```

Or

```bash
yarn install @kiwilan/typescriptable-laravel -D
```

Or

```bash
pnpm add @kiwilan/typescriptable-laravel -D
```

## Features

### Vite

-   Execute automatically [kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel)'s commands :' `typescriptable:models`, `typescriptable:routes` and `typescriptable:routes` with watch mode.

### Vue

-   Plugin to inject all new features.

### Inertia

-   Inertia types for `page` and global properties
-   `useTypescriptable` composable with some features:
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

## Usage

### Vite plugin

In your `vite.config.ts`:

```ts
import { defineConfig } from "vite";
import { ViteTypescriptable } from "@kiwilan/typescriptable-laravel";

export default defineConfig({
    plugins: [
        ViteTypescriptable({
            models: true,
            settings: false,
            routes: false,
            autoreload: true,
            inertia: true,
            inertiaPaths: {
                base: "resources/js",
                pageType: "types-inertia.d.ts",
                globalType: "types-inertia-global.d.ts",
            },
        }),
    ],
});
```

### Inertia setup

In your `resources/js/app.ts`:

```ts
import "./bootstrap";
import "../css/app.css";

import type { DefineComponent } from 'vue'
import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createPinia } from "pinia";
import { VueTypescriptable } from "@kiwilan/typescriptable-laravel";
import NProgress from "nprogress";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import "./routes";

createInertiaApp({
    title: (title) => `${title} - Laravel`,
    resolve: (name) => resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')) as Promise<DefineComponent>,
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueTypescriptable)
            .use(pinia);

        // `pnpm add nprogress -D`
        router.on("start", () => NProgress.start());
        router.on("finish", () => NProgress.done());

        app.mount(el);
    },
    progress: {
        delay: 250,
        color: "#18ba82",
        includeCSS: true,
        showSpinner: false,
    },
});
```

### Composables

#### `useTypescriptable`

```vue
<script lang="ts" setup>
import { useTypescriptable } from "@kiwilan/typescriptable-laravel";

const { router, route, isRoute, currentRoute, page } = useTypescriptable();

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

## Local test

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
