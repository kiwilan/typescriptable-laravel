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
  const laravelRouter = LaravelRouter.create()
  const currentUrl = laravelRouter.getUrl()

  /**
   * Check if current route is the given route.
   */
  function isRouteEqualTo(route: App.Route.Name): boolean {
    const currentRoute = laravelRouter.urlToRoute(currentUrl)

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
    return laravelRouter.urlToRoute(currentUrl)
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
    return laravelRouter.routeToUrl({
      name,
      params,
    })
  }

  return {
    isRouteEqualTo,
    currentRoute,
    route,
    router: laravelRouter,
  }
}
