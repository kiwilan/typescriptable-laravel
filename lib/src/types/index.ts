export interface ViteTypescriptableOptions {
  /**
   * Enable types for Eloquent models.
   *
   * @default true
   */
  models?: boolean
  /**
   * Enable types for Spatie
   *
   * @default false
   */
  settings?: boolean
  /**
   * Enable types for Laravel Routes.
   *
   * @default true
   */
  routes?: boolean
  /**
   * Enable types for Inertia.
   *
   * @default true
   */
  inertia?: boolean
  /**
   * Update paths for Inertia's types.
   *
   * You have to install Vue plugin to use this.
   *
   * ```ts
   * import { VueTypescriptable } from '@kiwilan/typescriptable-laravel'
   *
   * app.use(VueTypescriptable)
   * ```
   *
   * @default {
   *   base: 'resources/js',
   *   pageType: 'types-inertia.d.ts',
   *   globalType: 'types-inertia-global.d.ts',
   * }
   */
  inertiaPaths?: {
    base?: string
    pageType?: string
    globalType?: string
  }
  /**
   * Enable Vite autoreload on PHP files changes.
   *
   * @default true
   */
  autoreload?: boolean
}
