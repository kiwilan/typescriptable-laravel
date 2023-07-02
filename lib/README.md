# @kiwilan/typescriptable-laravel

Add some helpers for your Inertia app with TypeScript.

> **Notes**
>
> If you want to use helpers for Inertia, you have to use TypeScript into your project.

## Installation

> **Warning**
>
> `composer` package [`kiwilan/typescriptable-laravel`](https://github.com/kiwilan/typescriptable-laravel) is required.

```bash
npm install @kiwilan/typescriptable-laravel --save-dev
```

Or

```bash
pnpm add @kiwilan/typescriptable-laravel -D
```

## Features

-   🦾 Add TypeScript experience into Inertia
-   💨 Vite plugin to execute automatically [kiwilan/typescriptable-laravel](https://github.com/kiwilan/typescriptable-laravel)'s commands :' `typescriptable:models`, `typescriptable:routes` and `typescriptable:routes` with watch mode.
-   💚 Vue plugin to use global methods for `template` into Vue components:
    -   `$route` transform route to `string`
    -   `$isRoute` transform route name or path to `boolean`
    -   `$currentRoute` give current route
    -   Auto-import : `Head` from `@inertiajs/vue3`, `Link` from `@inertiajs/vue3`
-   💜 Inertia types for `page` and global properties
-   📦 `useTypescriptable` composable with some features:
    -   `router` with `get`, `post`, `put`, `patch`, `delete` methods typed with selected routes
    -   `route` transform route to `string`
    -   `isRoute` transform route name or path to `boolean`
    -   `currentRoute` give current route
    -   `page` with `usePage` typed

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
import { VueTypescriptable } from "@kiwilan/typescriptable-laravel"; // Import VueTypescriptable
import NProgress from "nprogress"; // `pnpm add nprogress -D`
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import "./routes"; // Import routes

createInertiaApp({
    title: (title) => `${title} - Laravel`,
    resolve: (name) => resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')) as Promise<DefineComponent>,
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueTypescriptable) // Add Vue plugin
            .use(pinia);

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

### useTypescriptable composable

// TOOD

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
