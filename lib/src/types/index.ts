export interface TypescriptableOptions {
  /**
   * Enable types for Eloquent models.
   *
   * @default true
   */
  models?: boolean
  /**
   * Enable types for Laravel Routes.
   *
   * @default true
   */
  routes?: boolean
  /**
   * Enable types for Inertia.
   *
   * You have to install Vue plugin to use this.
   *
   * ```ts
   * import { InertiaTyped } from '@kiwilan/typescriptable-laravel/vue'
   *
   * app.use(InertiaTyped)
   * ```
   *
   * @default {
   *   basePath: 'resources/js',
   *   pageType: 'types-inertia.d.ts',
   *   globalType: 'types-inertia-global.d.ts',
   * }
   */
  inertia?: {
    basePath?: string
    pageType?: string | false
    globalType?: string | false
  }
  /**
   * Enable Vite autoreload on PHP files changes.
   *
   * @default {
   *  models: true,
   *  controllers: true,
   *  routes: true,
   * }
   */
  autoreload?: {
    models?: boolean
    controllers?: boolean
    routes?: boolean
  } | false
}

export type Route = App.Route.Name
export type RouteParam = App.Route.Params[App.Route.Name]
export type RequestPayload = Record<string, any>
