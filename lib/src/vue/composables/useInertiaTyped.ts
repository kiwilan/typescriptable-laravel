import { router as irouter, usePage } from '@inertiajs/vue3'

type RequestPayload = Record<string, any>
export const useInertiaTyped = () => {
  // @ts-expect-error Routes is window defined
  const allRoutes = window.Routes as Record<Route.Name, Route.Entity>
  const convertURL = (route: Route.Type) => {
    const currentRoute = allRoutes[route.name]
    return currentRoute.path
  }

  const router = {
    get: (route: Route.TypeGet, data?: RequestPayload) => irouter.get(convertURL(route), data),
    post: (route: Route.TypePost, data?: RequestPayload) => irouter.post(convertURL(route), data),
    patch: (route: Route.TypePatch, data?: RequestPayload) => irouter.patch(convertURL(route), data),
    put: (route: Route.TypePut, data?: RequestPayload) => irouter.put(convertURL(route), data),
    delete: (route: Route.TypeDelete) => irouter.delete(convertURL(route)),
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
    router,
    route,
    isRoute,
    currentRoute,
    page,
  }
}
