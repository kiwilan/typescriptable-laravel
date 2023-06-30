import type { Plugin } from 'vite'
import type { ViteTypescriptableOptions } from './types/index'
import { InertiaType } from './inertia-type'

const DEFAULT_OPTIONS: ViteTypescriptableOptions = {
  models: true,
  settings: false,
  routes: false,
  inertia: {
    basePath: 'resources/js',
    pageType: 'types-inertia.d.ts',
    globalType: 'types-inertia-global.d.ts',
  },
  autoreload: {
    models: true,
    settings: false,
    controllers: true,
    routes: true,
  },
}

async function command(command: string) {
  let exec = (_command: string, _callback: (error: any, stdout: any) => void) => {}
  if (process.env.NODE_ENV !== 'production') {
    const cp = await import('node:child_process')
    exec = cp.exec
  }
  exec(
    command,
    (error) => {
      if (error) {
        console.error(`exec error: ${error}`)
        return
      }
      // eslint-disable-next-line no-console
      console.log(`${command} ready!`)
    },
  )
}

function ViteTypescriptable(userOptions: ViteTypescriptableOptions = {}): Plugin {
  return {
    name: 'vite-plugin-typescriptable-laravel',
    async buildStart() {
      const opts: ViteTypescriptableOptions = Object.assign({}, DEFAULT_OPTIONS, userOptions)

      if (opts.models)
        command('php artisan typescriptable:models')

      if (opts.settings)
        command('php artisan typescriptable:settings')

      if (opts.routes)
        command('php artisan typescriptable:routes')

      if (opts.inertia)
        InertiaType.make(opts)
        // command('php artisan typescriptable:inertia')
    },
    handleHotUpdate({ file, server }) {
      const opts = Object.assign({}, DEFAULT_OPTIONS, userOptions)
      if (opts.autoreload) {
        if (opts.autoreload.models && file.endsWith('app/Models/*.php'))
          server.restart()
        if (opts.autoreload.settings && file.endsWith('app/Settings/*.php'))
          server.restart()
        if (opts.autoreload.controllers && file.endsWith('app/Http/Controllers/**/*.php'))
          server.restart()
        if (opts.autoreload.routes && file.endsWith('routes/**/*.php'))
          server.restart()
      }
    },
  }
}

export type { ViteTypescriptableOptions }
export {
  ViteTypescriptable,
}
