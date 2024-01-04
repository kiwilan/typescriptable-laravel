import type { InertiaForm } from '@inertiajs/vue3'

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

export type Route = App.Route.Name
export type RouteParam = App.Route.Params[App.Route.Name]
export type RequestPayload = Record<string, any> | InertiaForm<any>

// Http types
export type RouteName = App.Route.Name
export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'

export interface HttpRequest {
  /**
   * Add query data to URL.
   */
  query?: Record<string, any>
}

export interface HttpRequestBody extends HttpRequest {
  /**
   * Body data.
   *
   * @default {}
   */
  body?: RequestPayload
  /**
   * Send request with Inertia (useful for forms)
   *
   * @default true
   */
  isInertia?: boolean
}

export interface HttpRequestAnonymous {
  /**
   * Query data.
   *
   * @default {}
   */
  query?: Record<string, any>
  /**
   * Body data.
   *
   * @default {}
   */
  body?: RequestPayload
  /**
   * HTTP method.
   *
   * @default 'GET'
   */
  method: HttpMethod
  /**
   * HTTP headers.
   *
   * @default {}
   */
  headers?: Record<string, string>
  /**
   * HTTP Content-Type header.
   *
   * @default 'application/json'
   */
  contentType?: string
  /**
   * Send request with Inertia (can't be used with `options.contentType` and `options.headers`).
   *
   * @default false
   */
  isInertia?: boolean
}
