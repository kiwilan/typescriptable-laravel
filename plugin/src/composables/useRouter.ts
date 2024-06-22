import { computed } from 'vue'
import { LaravelRouter } from '../shared/router/LaravelRouter'

/**
 * Composable for advanced usage of Laravel router.
 *
 * @method `isRouteEqualTo` Check if current route is the given route.
 * @method `currentRoute` Get current route.
 * @method `route` Get route URL from route name and params, can be used into template with `$route` helper.
 * @method `router` Get router instance.
 */
export function useRouter() {
  /**
   * Check if current route is the given route.
   */
  function isRouteEqualTo(route: App.Route.Name): boolean {
    const laravelRouter = LaravelRouter.create() // keep it here for Vue plugin provider
    const currentRoute = laravelRouter.urlToRoute(laravelRouter.getUrl())

    const current: string = route
    if (currentRoute) {
      const currentRouteName: string = currentRoute.name
      const currentRoutePath: string = currentRoute.path

      if (currentRouteName === current)
        return true

      if (currentRoutePath === current)
        return true
    }

    return false
  }

  /**
   * Get current route.
   */
  const currentRoute = computed((): App.Route.Link | undefined => {
    const laravelRouter = LaravelRouter.create() // keep it here for Vue plugin provider
    return laravelRouter.urlToRoute(laravelRouter.getUrl())
  })

  /**
   * Get route URL from route name and params, can be used into template with `$route` helper.
   *
   * @example
   *
   * ```vue
   * <script setup lang="ts">
   * const { route } = useRouter()
   *
   * const url = route('home')
   * </script>
   * ```
   */
  function route<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never): string {
    const laravelRouter = LaravelRouter.create() // keep it here for Vue plugin provider
    return laravelRouter.routeToUrl({
      name,
      params,
    })
  }

  const laravelRouter = computed(() => LaravelRouter.create())

  const baseURL = computed(() => laravelRouter.value.getBaseURL())

  return {
    isRouteEqualTo,
    currentRoute,
    route,
    router: laravelRouter,
    baseURL,
  }
}
