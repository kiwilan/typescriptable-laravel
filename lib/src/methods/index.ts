import { Router } from './Router'
import { Route } from './Route'
import { Http } from './Http'

type RoutesType = Record<App.Route.Name, App.Route.Link> | undefined

export type {
  RoutesType,
}
export {
  Router,
  Route,
  Http,
}
