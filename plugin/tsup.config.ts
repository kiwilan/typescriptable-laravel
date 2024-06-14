import { defineConfig } from 'tsup'

export default defineConfig({
  name: 'vite-plugin-typescriptable-laravel',
  entry: {
    index: 'src/index.ts',
    vite: 'src/vite.ts',
    vue: 'src/vue.ts',
  },
  outDir: 'dist',
  clean: true,
  minify: true,
  format: ['cjs', 'esm'],
  dts: true,
  treeshake: true,
  splitting: true,
  sourcemap: true,
  // onSuccess: 'npm run build:fix',
  external: [
    'vue',
    'vite',
    '@inertiajs/vue3',
    'node',
    'node:fs',
    'node:fs/promises',
    'node:child_process',
  ],
})
