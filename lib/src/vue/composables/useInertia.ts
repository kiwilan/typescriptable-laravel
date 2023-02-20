import { router, usePage } from '@inertiajs/vue3'

type RequestPayload = Record<string, any>
export const useInertia = () => {
  // @ts-expect-error Routes is window defined
  const allRoutes = window.Routes as Record<Route.Name, Route.Entity>
  const convertURL = (route: Route.Type) => {
    const currentRoute = allRoutes[route.name]
    return currentRoute.path
  }

  const ifetch = {
    get: (route: Route.Type, data?: RequestPayload) => router.get(convertURL(route), data),
    post: (route: Route.Type, data?: RequestPayload) => router.post(convertURL(route), data),
    patch: (route: Route.Type, data?: RequestPayload) => router.patch(convertURL(route), data),
    put: (route: Route.Type, data?: RequestPayload) => router.put(convertURL(route), data),
    delete: (route: Route.Type) => router.delete(convertURL(route)),
  }

  const page = usePage<Inertia.PageProps>()

  const findRoute = (): Route.Entity | undefined => {
    const url = location.pathname
    const routes = Object.entries(allRoutes)
    const current = routes.find(r => r[1].path === url)
    if (current) {
      const findRoute = current[1]
      // todo params
      return findRoute
    }

    return undefined
  }

  const isRoute = (route: Route.Name) => {
    const routes = Object.entries(allRoutes)
    const current = routes.find(r => r[1].name === route)
    if (current)
      return true

    return false
  }
  const currentRoute = () => {
    return findRoute()
  }

  const route = (route: Route.Type): string => {
    const currentRoute = allRoutes[route.name]

    if (currentRoute.params) {
      const params: Record<string, string> = {}
      Object.entries(currentRoute.params).forEach(([key]) => {
        if (route.params)
          params[key] = route.params[key]
      })

      let url: string = currentRoute.path
      const matches = currentRoute.path.match(/{(.*?)}/g)

      if (matches) {
        matches.forEach((match) => {
          const key = match.replace('{', '').replace('}', '')
          url = url.replace(match, params[key])
        })
      }

      return url
    }

    if (!currentRoute.params)
      return currentRoute.path as string

    return '/'
  }

  return {
    ifetch,
    route,
    isRoute,
    currentRoute,
    page,
  }
}
