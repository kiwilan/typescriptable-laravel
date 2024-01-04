import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Http, LaravelRouter } from '@/methods'
import type { RequestPayload } from '@/types'

/**
 * @deprecated Use `useHttp()` or `useRouter()` instead.
 */
export function useTypescriptable() {
  const http = Http.create()
  const laravelRouter = LaravelRouter.create()

  /**
   * @deprecated Use `useHttp()` instead.
   */
  const request = {
    /**
     * @deprecated Use `useHttp()` instead.
     */
    get(name: App.Route.Name): void {
      return http.get(name)
    },
    /**
     * @deprecated Use `useHttp()` instead.
     */
    post(name: App.Route.Name, data?: RequestPayload): void {
      return http.post(name, data)
    },
    /**
     * @deprecated Use `useHttp()` instead.
     */
    put(name: App.Route.Name, data?: RequestPayload): void {
      return http.put(name, data)
    },
    /**
     * @deprecated Use `useHttp()` instead.
     */
    patch(name: App.Route.Name, data?: RequestPayload): void {
      return http.patch(name, data)
    },
    /**
     * @deprecated Use `useHttp()` instead.
     */
    delete(name: App.Route.Name): void {
      return http.delete(name)
    },
  }

  /**
   * @deprecated Use `useInertia()` instead.
   */
  const page = usePage<Inertia.PageProps>()

  /**
   * @deprecated Use `useRouter()` instead.
   */
  const isRoute = (route: App.Route.Name): boolean => {
    const url = laravelRouter.getCurrentUrl()
    const currentRoute = laravelRouter.getRouteFromUrl(url)

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
   * @deprecated Use `useInertia()` instead.
   */
  const isDev = computed(() => {
    return process.env.NODE_ENV === 'development'
  })

  /**
   * @deprecated Use `useRouter()` instead.
   */
  const currentRoute = computed((): App.Route.Link | undefined => {
    const url = laravelRouter.getCurrentUrl()
    return laravelRouter.getRouteFromUrl(url)
  })

  /**
   * @deprecated Use `useRouter()` instead.
   */
  function route<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never): string {
    return laravelRouter.getRouteBind({
      name,
      params,
    })
  }

  /**
   * @deprecated Use `useRouter()` instead.
   */
  function to<T extends App.Route.Name>(route: App.Route.RouteConfig<T>): string {
    return laravelRouter.getRouteBind(route)
  }

  return {
    request,
    isRoute,
    page,
    isDev,
    currentRoute,
    route,
    to,
    Http,
    Router: laravelRouter,
  }
}
