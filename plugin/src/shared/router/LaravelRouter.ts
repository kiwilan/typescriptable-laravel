import { usePage } from '@inertiajs/vue3'

interface ZiggyRoute {
  uri: string
  methods?: string[]
  parameters?: string[]
  bindings?: Record<string, string>
}
interface Ziggy {
  defaults: string[] | null
  location: string | null
  url: string | null
  port: number | null
  routes: Record<string, ZiggyRoute>
}

const defaultZiggy: Ziggy = {
  defaults: [],
  location: 'http://localhost',
  port: null,
  routes: {
    '/': {
      methods: ['GET', 'HEAD'],
      uri: '/',
    },
  },
  url: 'http://localhost',
}

export class LaravelRouter {
  protected constructor(
    protected location: string | null = 'http://localhost',
    protected baseURL: string | null = 'http://localhost',
    protected ziggyRoutes = {} as Record<string, ZiggyRoute>,
    protected typedRoutes = {} as Record<App.Route.Name, App.Route.Link>,
    protected ziggy = {} as Ziggy,
  ) {}

  /**
   * Create Laravel router.
   */
  public static create(): LaravelRouter {
    const self = new LaravelRouter()
    self.parseZiggy()

    return self
  }

  /**
   * Get current location.
   */
  public getLocation(): string {
    return this.location ?? 'http://localhost'
  }

  /**
   * Get base URL.
   */
  public getBaseURL(): string {
    return this.baseURL ?? 'http://localhost'
  }

  /**
   * Get current URL.
   */
  public getUrl(): string {
    const url = this.getLocation()
    return url.replace(this.getBaseURL(), '')
  }

  /**
   * Get Ziggy routes.
   */
  public getZiggyRoutes(): Record<string, ZiggyRoute> {
    return this.ziggyRoutes
  }

  /**
   * Get typed routes.
   */
  public getTypedRoutes(): Record<App.Route.Name, App.Route.Link> {
    return this.typedRoutes
  }

  /**
   * Get Ziggy props.
   */
  public getZiggy(): Ziggy {
    return this.ziggy
  }

  /**
   * Get route link from name.
   */
  public routeNameToLink(name: App.Route.Name): App.Route.Link {
    return this.typedRoutes[name]
  }

  /**
   * Route to URL.
   */
  public routeToUrl<T extends App.Route.Name>(route: App.Route.RouteConfig<T>): string {
    const current = this.bindRoute(route.name, route.params)

    return current
  }

  /**
   * URL to route.
   */
  public urlToRoute(url: string): App.Route.Link | undefined {
    if (url === '')
      url = '/'

    url = url.replace(this.getBaseURL(), '')
    const parts = this.splitURL(url)
    const candidates = this.findCandidatesFromUrl(url)

    if (parts.length === 0) {
      const item = candidates.find(item => item.path === url)
      return item
    }

    let rightRoute: App.Route.Link | undefined
    for (const candidate of candidates) {
      const route = candidate.path

      if (route === url) {
        rightRoute = candidate
        break
      }

      const partsRoute = route.split('/')
      partsRoute.shift()
      const partsRouteLength = partsRoute.length
      const partsLength = parts.length

      if (partsRouteLength === partsLength) {
        rightRoute = this.matchRoute(candidate, parts, partsRoute)
        if (rightRoute)
          break
      }
    }

    return rightRoute
  }

  /**
   * Bind route.
   */
  private bindRoute(name: App.Route.Name, params?: App.Route.Params[App.Route.Name]): string {
    const route = this.typedRoutes[name]

    if (!route || !route.path)
      console.error(`Route ${name} not found`)

    if (!params)
      return route.path

    const paramRegex = /\{(\w+)\}/g

    const current = route.path.replace(paramRegex, (match, paramName) => {
      const paramValue = params[paramName]
      if (paramValue === undefined)
        console.error(`Missing parameter value for ${paramName}`)

      return paramValue
    })

    return current
  }

  /**
   * Match route from parts.
   */
  private matchRoute(route: App.Route.Link, parts: string[], partsRoute: string[]): App.Route.Link | undefined {
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

  /**
   * Find possible candidates from URL.
   */
  private findCandidatesFromUrl(url: string): App.Route.Link[] {
    const parts = this.splitURL(url)
    const items: App.Route.Link[] = []

    for (const route of Object.entries(this.ziggyRoutes)) {
      const routeName = route[0]
      const routeConfig = route[1]
      let uri = routeConfig.uri
      if (!uri.startsWith('/'))
        uri = `/${uri}`

      items.push({
        name: routeName as App.Route.Name,
        path: uri as App.Route.Path,
        params: routeConfig.parameters as any,
        methods: routeConfig.methods as App.Route.Method[] ?? ['GET'],
      })
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

  /**
   * Split URL into parts.
   */
  private splitURL(url: string): string[] {
    const cleanUrl = url.replace(/\/$/, '')
    const parts = cleanUrl.split('/')
    parts.shift()

    return parts
  }

  /**
   * Parse `ziggy` shared props.
   */
  private parseZiggy() {
    const page = usePage()

    let ziggy = page.props?.ziggy as Ziggy | undefined
    if (ziggy === undefined) {
      console.warn('`@kiwilan/typescriptable-laravel` error: `ziggy` props is `undefined` into `usePage()` from `@inertiajs/vue3`. You can see an example here: https://gist.github.com/ewilan-riviere/f1dbc20669ed2669f745e3e0e0771537#file-handleinertiarequests-php')
      ziggy = defaultZiggy
    }

    this.location = ziggy.location
    this.baseURL = ziggy.url
    this.ziggyRoutes = ziggy.routes
    this.ziggy = ziggy

    for (const route of Object.entries(this.ziggyRoutes)) {
      const routeName = route[0]
      const routeConfig = route[1]
      let uri = routeConfig.uri
      if (!uri.startsWith('/'))
        uri = `/${uri}`

      this.typedRoutes[routeName as App.Route.Name] = {
        name: routeName as App.Route.Name,
        path: uri as App.Route.Path,
        params: routeConfig.parameters as any,
        methods: routeConfig.methods as App.Route.Method[] ?? ['GET'],
      }
    }
  }
}
