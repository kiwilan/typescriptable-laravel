import { computed } from 'vue'
import { LaravelRouter } from '@/methods'

/**
 * Composable for advanced usage of Laravel router.
 *
 * @method `isRoute` Check if current route is the given route.
 * @method `currentRoute` Get current route.
 * @method `route` Get route URL from route name and params, can be used into template with `$route` helper.
 * @method `to` Get route URL from route config, to use with `href` prop of `<inertia-link>`, can be used into template with `$to` helper.
 */
export function useRouter() {
  /**
   * Check if current route is the given route.
   */
  function isRoute(route: App.Route.Name): boolean {
    const router = LaravelRouter.create()
    const url = router.getCurrentUrl()
    const currentRoute = router.getRouteFromUrl(url)

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
    const router = LaravelRouter.create()
    const url = router.getCurrentUrl()

    return router.getRouteFromUrl(url)
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
    const router = LaravelRouter.create()
    return router.getRouteBind({
      name,
      params,
    })
  }

  /**
   * Get route URL from route config, to use with `href` prop of `<inertia-link>`, can be used into template with `$to` helper.
   *
   * @example
   *
   * ```vue
   * <script setup lang="ts">
   * import { Link } from '@inertiajs/vue3'
   * </script>
   *
   * <template>
   *  <Link :href="to({ name: 'home' })">Home</Link>
   * </template>
   * ```
   */
  function to<T extends App.Route.Name>(route: App.Route.RouteConfig<T>): string {
    const router = LaravelRouter.create()
    return router.getRouteBind(route)
  }

  return {
    isRoute,
    currentRoute,
    route,
    to,
  }
}
