export class RouteModel {
  protected constructor(
    protected type: Route.Type, // Route.Typed.Login | Route.Typed.Logout | Route.Typed.FrontStoriesShow
    protected entity: Route.Entity, // { name: Route.Name; path: Route.Path; params?: Route.Params[Route.Name],  method: Route.Method; }
    protected name: Route.Name, // 'login' | 'logout' | 'front.stories.show'
    protected uri: Route.Path, // '/login' | '/logout' | '/stories/{story}'
    protected params: Route.Params[Route.Name] = {}, // { 'story'?: string | number | boolean }
    protected query: Record<string, string | number | boolean | undefined> | undefined = {},
    protected hash: string = '',
    protected method: Route.Method = 'GET', // 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
    protected path: string = '/',
  ) {}

  static allRoutes() {
    // @ts-expect-error Routes is window defined
    return window.Routes as Record<Route.Name, Route.Entity>
  }

  static make(type: Route.Type): RouteModel {
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
        const key = match.replace('{', '').replace('}', '')
        url = url.replace(match, params[key])
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

  private buildRouteFromUrl(url: string) {
    // const routes = Object.values(RouteModel.allRoutes())
    // const current = routes.find(r => r.path === url)

    // if (current) {
    //   const findRoute = current
    //   // todo params
    //   return new RouteModel(findRoute, findRoute, findRoute.name, findRoute.path, {}, {}, '', findRoute.method)
    // }

    // return new RouteModel({}, {}, '', '', {}, {}, '', '')
  }
}
