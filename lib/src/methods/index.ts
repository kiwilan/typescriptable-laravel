import { LaravelRouter } from './LaravelRouter'
import { LaravelRoute } from './LaravelRoute'
import { Http } from './Http'

type RoutesType = Record<App.Route.Name, App.Route.Link> | undefined

export type {
  RoutesType,
}
export {
  LaravelRouter,
  LaravelRoute,
  Http,
}
