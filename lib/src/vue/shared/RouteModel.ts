export class RouteModel {
  protected constructor(
    protected type: App.Route.Type, // App.Route.Typed.Login | App.Route.Typed.Logout | App.Route.Typed.FrontStoriesShow
    protected entity: App.Route.Entity, // { name: App.Route.Name; path: App.Route.Path; params?: App.Route.Params[App.Route.Name],  method: App.Route.Method; }
    protected name: App.Route.Name, // 'login' | 'logout' | 'front.stories.show'
    protected uri: App.Route.Path, // '/login' | '/logout' | '/stories/{story}'
    protected params: App.Route.Params[App.Route.Name] = {}, // { 'story'?: string | number | boolean }
    protected query: Record<string, string | number | boolean | undefined> | undefined = {},
    protected hash: string = '',
    protected method: App.Route.Method = 'GET', // 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
    protected path: string = '/',
  ) {}

  static allRoutes() {
    // @ts-expect-error window.Routes is defined in the view
    return window.Routes as Record<App.Route.Name, App.Route.Entity>
  }

  static make(type: App.Route.Type): RouteModel {
    const entity = RouteModel.allRoutes()[type.name]
    const self = new RouteModel(type, entity, type.name, entity.path, type.params, type.query, type.hash, entity.method)

    self.path = self.setParams()
    self.path = self.setQuery()
    self.path = self.setHash()

    return self
  }

  public getPath() {
    return this.path
  }

  private setParams(): string {
    let url: string = this.entity.path
    if (!this.entity.params)
      return url

    const params: Record<string, string> = {}
    Object.entries(this.type.params!).forEach(([key]) => {
      if (this.type.params)
        params[key] = this.type.params[key]
    })

    const matches = this.entity.path.match(/{(.*?)}/g)

    if (matches) {
      matches.forEach((match) => {
        const key = match.replace('{', '')
          .replace('}', '')
          .replace('?', '')
        const value = params[key]

        if (value)
          url = url.replace(match, value)
        else
          url = url.replace(match, '')
      })
    }

    return url
  }

  private setQuery(): string {
    const url: string = this.path
    if (!this.type.query)
      return url

    const queries: Record<string, string> = {}
    Object.entries(this.type.query).forEach(([key]) => {
      if (this.type.query)
        queries[key] = this.type.query[key] as string
    })

    const query = new URLSearchParams(queries).toString()

    return `${url}?${query}`
  }

  private setHash(): string {
    const url: string = this.path
    if (!this.type.hash)
      return url

    return `${url}#${this.type.hash}`
  }

  private static matchRoute(route: App.Route.Entity, parts: string[], partsRoute: string[]): App.Route.Entity | undefined {
    let match = true
    partsRoute.forEach((part, index) => {
      if (part.startsWith('{') && part.endsWith('}')) {
        // todo
      }
      else if (part !== parts[index]) {
        match = false
      }
    })

    if (match)
      return route
  }

  public static routeFromUrl(): App.Route.Entity | undefined {
    const url = location.pathname
    const cleanUrl = url.replace(/\/$/, '')

    const parts = cleanUrl.split('/')
    parts.shift()

    const routes: App.Route.Entity[] = []
    const all = Object.entries(RouteModel.allRoutes())

    all.forEach((r) => {
      routes.push(r[1])
    })

    const candidates: App.Route.Entity[] = []
    routes.forEach((route) => {
      const first = `/${parts[0]}`
      if (route.path.startsWith(first))
        candidates.push(route)
    })

    // eslint-disable-next-line no-undef-init
    let rightRoute: App.Route.Entity | undefined = undefined
    candidates.forEach((route) => {
      const partsRoute = route.path.split('/')
      partsRoute.shift()
      const partsRouteLength = partsRoute.length
      const partsLength = parts.length

      const hasParamOptional = partsRoute.find(part => part.startsWith('{') && part.endsWith('?}')) !== undefined

      if (hasParamOptional) {
        if (partsRouteLength === partsLength)
          rightRoute = RouteModel.matchRoute(route, parts, partsRoute)
        else if (partsRouteLength === partsLength + 1)
          rightRoute = RouteModel.matchRoute(route, parts, partsRoute)
      }
      else {
        if (partsRouteLength === partsLength)
          rightRoute = RouteModel.matchRoute(route, parts, partsRoute)
      }
    })

    return rightRoute
  }
}
