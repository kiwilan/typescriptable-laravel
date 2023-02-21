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
   * @default true
   */
  inertia?: boolean
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

export interface PluginInertiaTyped {
  route: (
    name: Route,
    params?: RouteParam,
  ) => string
  isRoute: (name: App.Route.Name, params?: App.Route.Params[App.Route.Name]) => boolean
  currentRoute: () => string
  page: Inertia.Page<Inertia.PageProps>
}
