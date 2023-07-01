import { RouteList } from './RouteList'
import { RouteItem } from './RouteItem'
import { Router } from './Router'

type RoutesType = Record<App.Route.Name, App.Route.Link> | undefined

export type {
  RoutesType,
}
export {
  RouteList,
  RouteItem,
  Router,
}
