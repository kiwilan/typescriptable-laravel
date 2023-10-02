import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Http, Router } from '@/methods'
import type { RequestPayload } from '@/types'

export function useTypescriptable() {
  const http = Http.make()
  const router = Router.make()

  const request = {
    get(name: App.Route.Name): void {
      return http.get(name)
    },
    post(name: App.Route.Name, data?: RequestPayload): void {
      return http.post(name, data)
    },
    put(name: App.Route.Name, data?: RequestPayload): void {
      return http.put(name, data)
    },
    patch(name: App.Route.Name, data?: RequestPayload): void {
      return http.patch(name, data)
    },
    delete(name: App.Route.Name): void {
      return http.delete(name)
    },
  }

  const page = usePage<Inertia.PageProps>()

  const isRoute = (route: App.Route.Name): boolean => {
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

  const isDev = computed(() => {
    return process.env.NODE_ENV === 'development'
  })

  const currentRoute = computed((): App.Route.Link | undefined => {
    const url = router.getCurrentUrl()
    return router.getRouteFromUrl(url)
  })

  function route<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never): string {
    return router.getRouteBind({
      name,
      params,
    })
  }

  function to<T extends App.Route.Name>(route: App.Route.RouteConfig<T>): string {
    return router.getRouteBind(route)
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
    Router,
  }
}
