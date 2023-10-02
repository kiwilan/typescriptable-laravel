import type { Plugin } from 'vite'
import type { ViteTypescriptableOptions } from './types/index'
import { execute } from './server'
import { InertiaType } from './inertia-type'

const DEFAULT_OPTIONS: ViteTypescriptableOptions = {
  models: true,
  settings: false,
  routes: false,
  inertia: true,
  inertiaPaths: {
    base: 'resources/js',
    pageType: 'types-inertia.d.ts',
    globalType: 'types-inertia-global.d.ts',
  },
  autoreload: true,
}

function ViteTypescriptable(userOptions: ViteTypescriptableOptions = {}): Plugin {
  return {
    name: 'vite-plugin-typescriptable-laravel',
    async buildStart() {
      const opts: ViteTypescriptableOptions = Object.assign({}, DEFAULT_OPTIONS, userOptions)

      if (opts.models)
        await execute('php artisan typescriptable:models')

      if (opts.settings)
        await execute('php artisan typescriptable:settings')

      if (opts.routes)
        await execute('php artisan typescriptable:routes')

      if (opts.inertia)
        await InertiaType.make(opts)
    },
    async handleHotUpdate({ file, server }) {
      const opts = Object.assign({}, DEFAULT_OPTIONS, userOptions)

      if (opts.autoreload) {
        const patterns = [
          /^app\/Models\/[^\/]+\.php$/,
          /^app\/Settings\/[^\/]+\.php$/,
          /^app\/Http\/Controllers\/[^\/]+\.php$/,
          /^routes\/[^\/]+\.php$/,
        ]

        const root = process.cwd()
        file = file.replace(root, '')
        file = file.substring(1)

        for (const pattern of patterns) {
          if (pattern.test(file))
            server.restart()
        }
      }
    },
  }
}

export type { ViteTypescriptableOptions }
export default ViteTypescriptable
