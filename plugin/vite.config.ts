import { resolve } from 'node:path'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import dts from 'vite-plugin-dts'

export default defineConfig({
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
    },
  },
  build: {
    lib: {
      entry: resolve(__dirname, 'src/index.ts'),
      name: 'vite-plugin-typescriptable-laravel',
      fileName: 'index',
      formats: ['cjs', 'es'],
    },
    outDir: 'dist',
    rollupOptions: {
      external: ['vue', '@inertiajs/vue3', 'node:fs/promises', 'node:child_process'],
    },
  },
  plugins: [
    vue(),
    dts({
      entryRoot: resolve(__dirname, 'src'),
      outDir: resolve(__dirname, 'dist'),
    }),
  ],
})
