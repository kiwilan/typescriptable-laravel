import { router as irouter, usePage } from '@inertiajs/vue3'
import { RouteModel } from '../shared/RouteModel.js'

type RequestPayload = Record<string, any>
export const useInertiaTyped = () => {
  const convertURL = (route: Route.Type) => {
    const current = RouteModel.make(route)
    return current.getPath()
  }

  const router = {
    get: (route: Route.TypeGet, data?: RequestPayload) => irouter.get(convertURL(route), data),
    post: (route: Route.TypePost, data?: RequestPayload) => irouter.post(convertURL(route), data),
    patch: (route: Route.TypePatch, data?: RequestPayload) => irouter.patch(convertURL(route), data),
    put: (route: Route.TypePut, data?: RequestPayload) => irouter.put(convertURL(route), data),
    delete: (route: Route.TypeDelete) => irouter.delete(convertURL(route)),
  }

  const page = usePage<Inertia.PageProps>()

  const isRoute = (route: Route.Name): boolean => {
    const currentRoute = RouteModel.routeFromUrl()
    if (currentRoute && currentRoute.name === route)
      return true

    return false
  }

  const currentRoute = (): Route.Entity | undefined => {
    return RouteModel.routeFromUrl()
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
