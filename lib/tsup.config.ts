import { defineConfig } from 'tsup'

export default defineConfig({
  name: 'vite-plugin-typescriptable-laravel',
  entry: {
    index: 'src/index.ts',
    vite: 'src/vite-plugin.ts',
  },
  format: ['cjs', 'esm'],
  external: ['vue', 'vite', '@inertiajs/vue3', 'node', 'node:fs', 'node:fs/promises', 'node:child_process'],
  outDir: 'dist',
  dts: true,
  minify: true,
  treeshake: true,
  splitting: true,
})
