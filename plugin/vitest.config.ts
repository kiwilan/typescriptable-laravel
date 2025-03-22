// import { defineConfig } from 'vitest/config'

// export default defineConfig({
//   test: {},
// })

import path from 'node:path'
import vue from '@vitejs/plugin-vue'

export default {
  plugins: [vue()],
  test: {
    globals: true,
    environment: 'jsdom',
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
}
