import type { RoutesType } from '.'

export class Router {
  protected constructor(
    protected routes: Record<App.Route.Name, App.Route.Link>,
  ) {}

  public static make(routes?: RoutesType): Router {
    // eslint-disable-next-line valid-typeof
    if (!routes && typeof window !== undefined && typeof window.Routes !== undefined)
      routes = window.Routes

    return new Router(routes as Record<App.Route.Name, App.Route.Link>)
  }

  public getRouteLink(name: App.Route.Name): App.Route.Link {
    return this.routes[name]
  }

  public getRouteBinded(name: App.Route.Name, params?: App.Route.Params[App.Route.Name]): string {
    const route = this.routes[name]

    if (!route)
      throw new Error(`Route ${name} not found`)

    if (!params)
      return route.path

    const paramRegex = /{(\w+)}/g

    const current = route.path.replace(paramRegex, (match, paramName) => {
      const paramValue = params[paramName]
      if (paramValue === undefined)
        throw new Error(`Missing parameter value for ${paramName}`)

      return paramValue
    })

    return current
  }

  public getRouteBind<T extends App.Route.Name>(route: App.Route.RouteConfig<T>): string {
    const current = this.getRouteBinded(route.name, route.params)

    return current
  }

  public getAllRoutes(): Record<App.Route.Name, App.Route.Link> {
    return this.routes
  }

  public getCurrentUrl(): string {
    return window.location.pathname
  }

  public matchRoute(route: App.Route.Link, parts: string[], partsRoute: string[]): App.Route.Link | undefined {
    let match = true

    for (let i = 0; i < partsRoute.length; i++) {
      const part = partsRoute[i]
      if (part.startsWith('{') && part.endsWith('}')) {
        // todo
      }
      else if (part !== parts[i]) {
        match = false
      }
    }

    if (match)
      return route
  }

  private parseURL(url: string): string[] {
    const cleanUrl = url.replace(/\/$/, '')

    const parts = cleanUrl.split('/')
    parts.shift()

    return parts
  }

  private getCandidatesFromUrl(url: string): App.Route.Link[] {
    const parts = this.parseURL(url)
    const items: App.Route.Link[] = []

    for (const route of Object.entries(this.routes)) {
      const value = route[1]
      items.push(value)
    }

    const candidates: App.Route.Link[] = []
    if (parts.length === 0) {
      const item = items.find(item => item.path === url)
      if (item)
        candidates.push(item)

      return candidates
    }

    for (const item of items) {
      const first = `/${parts[0]}`

      if (item.path.startsWith(first))
        candidates.push(item)
    }

    return candidates
  }

  public getRouteFromUrl(url: string): App.Route.Link | undefined {
    const parts = this.parseURL(url)
    const candidates = this.getCandidatesFromUrl(url)

    if (parts.length === 0) {
      const item = candidates.find(item => item.path === url)
      return item
    }

    let rightRoute: App.Route.Link | undefined
    for (const candidate of candidates) {
      const partsRoute = candidate.path.split('/')
      partsRoute.shift()
      const partsRouteLength = partsRoute.length
      const partsLength = parts.length

      const hasParamOptional = partsRoute.find(part => part.startsWith('{') && part.endsWith('?}')) !== undefined

      if (hasParamOptional) {
        if (partsRouteLength === partsLength)
          rightRoute = this.matchRoute(candidate, parts, partsRoute)
        else if (partsRouteLength === partsLength + 1)
          rightRoute = this.matchRoute(candidate, parts, partsRoute)
      }
      else {
        if (partsRouteLength === partsLength)
          rightRoute = this.matchRoute(candidate, parts, partsRoute)
      }
    }

    return rightRoute
  }
}
