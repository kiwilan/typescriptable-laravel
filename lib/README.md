# @kiwilan/typescriptable-laravel

[Typescriptable Laravel](https://github.com/kiwilan/typescriptable-laravel) is required.

## Installation

```bash
npm install @kiwilan/typescriptable-laravel --save-dev
```

Or

```bash
pnpm add @kiwilan/typescriptable-laravel -D
```

## Usage

In your `vite.config.js`:

```js
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
