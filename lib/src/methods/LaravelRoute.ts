import { LaravelRouter } from './LaravelRouter'
import type { RoutesType } from '.'

export class LaravelRoute {
  protected constructor(
    protected name: App.Route.Name, // 'login' | 'logout' | 'front.stories.show'
    protected params: App.Route.Params[App.Route.Name] = {}, // { 'story'?: string | number | boolean }
    protected query: Record<string, string | number | boolean | undefined> | undefined = {},
    protected hash: string = '',
    protected methods: App.Route.Method[] = ['GET'], // 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
    protected path: string = '/',
  ) {}

  static create(name: App.Route.Name, routes?: RoutesType): LaravelRoute {
    const laravelRouter = LaravelRouter.create(routes)
    const route = laravelRouter.getRouteLink(name)

    const self = new LaravelRoute(route.name)
    self.methods = route.methods
    self.path = route.path

    self.path = self.setParams()
    self.path = self.setQuery()
    self.path = self.setHash()

    return self
  }

  public getPath() {
    return this.path
  }

  private setParams(): string {
    let url: string = this.path
    if (!this.params)
      return url

    const params: Record<string, string> = {}
    Object.entries(this.params!).forEach(([key]) => {
      if (this.params)
        params[key] = this.params[key]
    })

    const matches = this.path.match(/{(.*?)}/g)

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
    if (!this.query)
      return url

    const queries: Record<string, string> = {}
    Object.entries(this.query).forEach(([key]) => {
      if (this.query)
        queries[key] = this.query[key] as string
    })

    const query = new URLSearchParams(queries).toString()

    return `${url}?${query}`
  }

  private setHash(): string {
    const url: string = this.path
    if (!this.hash)
      return url

    return `${url}#${this.hash}`
  }

  public static currentUrl(): string {
    return location.pathname
  }
}
