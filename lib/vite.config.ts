import { resolve } from 'path'
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
      entry: resolve(__dirname, 'src/vue/index.ts'),
      name: 'vue',
      fileName: 'index',
      formats: ['cjs', 'es'],
    },
    outDir: 'vue',
    rollupOptions: {
      external: ['node', 'vue', '@inertiajs/vue3'],
      output: {
        globals: {
          vue: 'Vue',
        },
      },
    },
  },
  plugins: [
    vue(),
    dts({
      entryRoot: resolve(__dirname, 'src/vue'),
      outputDir: resolve(__dirname, 'vue'),
    }),
  ],
})
