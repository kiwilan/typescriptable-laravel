import { router as irouter, usePage } from '@inertiajs/vue3'
import { RouteModel } from '../shared/RouteModel.js'

type RequestPayload = Record<string, any>
export const useInertiaTyped = () => {
  const convertURL = (route: App.Route.Type) => {
    const current = RouteModel.make(route)
    return current.getPath()
  }

  const router = {
    get: (route: App.Route.TypeGet, data?: RequestPayload) => irouter.get(convertURL(route), data),
    post: (route: App.Route.TypePost, data?: RequestPayload) => irouter.post(convertURL(route), data),
    patch: (route: App.Route.TypePatch, data?: RequestPayload) => irouter.patch(convertURL(route), data),
    put: (route: App.Route.TypePut, data?: RequestPayload) => irouter.put(convertURL(route), data),
    delete: (route: App.Route.TypeDelete) => irouter.delete(convertURL(route)),
  }

  const page = usePage<Inertia.PageProps>()

  const isRoute = (route: App.Route.NamePath): boolean => {
    const currentRoute = RouteModel.routeFromUrl()
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

  const currentRoute = (): App.Route.Entity | undefined => {
    return RouteModel.routeFromUrl()
  }

  const route = (route: App.Route.Type): string => {
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
