import { router as irouter, usePage } from '@inertiajs/vue3'
import { RouteModel } from '../shared/RouteModel.js'

type RequestPayload = Record<string, any>
export const useInertiaTypedDev = () => {
  const allRoutes = RouteModel.allRoutes()

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

  const isRoute = (route: Route.Name): boolean => {
    const routes = Object.entries(allRoutes)
    const current = routes.find(r => r[1].name === route)
    if (current)
      return true

    return false
  }

  const currentRoute = (): Route.Entity | undefined => {
    const url = location.pathname // url like `/stories`
    const routes = Object.values(allRoutes)
    const current = routes.find(r => r.path === url)

    if (current) {
      const findRoute = current[1]
      // todo params
      return findRoute
    }

    // const current = RouteModel.make(route)

    return findRoute()
  }

  const route = (route: Route.Type): string => {
    const current = RouteModel.make(route)

    return current.getPath()
  }

  return {
    router,
    route,
    isRoute,
    currentRoute,
    page,
  }
}
