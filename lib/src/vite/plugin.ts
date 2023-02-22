import type { Plugin } from 'vite'
import type { TypescriptableOptions } from '../types/index.js'
import { InertiaType } from './inertia-type.js'

const DEFAULT_OPTIONS: TypescriptableOptions = {
  models: true,
  routes: true,
  inertia: {
    basePath: 'resources/js',
    pageType: 'types-inertia.d.ts',
    globalType: 'types-inertia-global.d.ts',
  },
  autoreload: {
    models: true,
    controllers: true,
    routes: true,
  },
}

const command = (command: string) => {
  let exec = (_command: string, _callback: (error: any, stdout: any) => void) => {}
  if (process.env.NODE_ENV !== 'production') {
    // eslint-disable-next-line @typescript-eslint/no-var-requires
    exec = require('child_process').exec
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

const Typescriptable = (userOptions: TypescriptableOptions = {}): Plugin => {
  return {
    name: 'vite-plugin-typescriptable-laravel',
    async buildStart() {
      const opts: TypescriptableOptions = Object.assign({}, DEFAULT_OPTIONS, userOptions)

      if (opts.models)
        command('php artisan typescriptable:models')

      if (opts.routes)
        command('php artisan typescriptable:routes')

      if (opts.inertia)
        InertiaType.make(opts)
        // command('php artisan typescriptable:inertia')
    },
    handleHotUpdate({ file, server }) {
      const opts = Object.assign({}, DEFAULT_OPTIONS, userOptions)
      if (opts.autoreload) {
        if (opts.autoreload.models && file.endsWith('app/Models/**/*.php'))
          server.restart()
        if (opts.autoreload.controllers && file.endsWith('app/Http/Controllers/**/*.php'))
          server.restart()
        if (opts.autoreload.routes && file.endsWith('routes/**/*.php'))
          server.restart()
      }
    },
  }
}

export type { TypescriptableOptions }
export {
  Typescriptable,
}
