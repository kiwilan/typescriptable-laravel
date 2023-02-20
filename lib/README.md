# @kiwilan/vite-plugin-typescriptable-laravel

[Typescriptable Laravel](https://github.com/kiwilan/typescriptable-laravel) is required.

## Installation

```bash
npm install @kiwilan/vite-plugin-typescriptable-laravel --save-dev
```

Or

```bash
pnpm add @kiwilan/vite-plugin-typescriptable-laravel -D
```

## Usage

In your `vite.config.js`:

```js
import { defineConfig } from "vite";
import { Typescriptable } from "@kiwilan/vite-plugin-typescriptable-laravel";

export default defineConfig({
    plugins: [
        Typescriptable({
            // Options
        }),
    ],
});
```
