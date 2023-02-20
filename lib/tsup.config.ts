import { defineConfig } from 'tsup'

export default defineConfig({
  name: 'vite-plugin-typescriptable-laravel',
  entry: {
    index: 'src/vite/index.ts',
  },
  format: ['cjs', 'esm'],
  external: ['vue', '@inertiajs/vue3'],
  outDir: 'dist',
  dts: true,
  minify: true,
  treeshake: true,
  splitting: true,
})
