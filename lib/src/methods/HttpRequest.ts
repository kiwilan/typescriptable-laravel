import { router as inertia } from '@inertiajs/vue3'
import { LaravelRouter } from './LaravelRouter'
import { ServerRoutes } from './ServerRoutes'
import type { HttpMethod, HttpRequestAnonymous, HttpRequestQuery, RequestPayload } from '@/types'

export class HttpRequest {
  protected constructor(
    protected laravel: LaravelRouter,
  ) {}

  static create(): HttpRequest {
    return new HttpRequest(LaravelRouter.create())
  }

  /**
   * Make a raw HTTP request.
   * Useful for API, use `fetch` under the hood.
   */
  public async http(url: string, method: HttpMethod, options: HttpRequestAnonymous = { contentType: 'application/json' }): Promise<Response> {
    let urlBuiltWithQuery = this.urlBuiltWithQuery(url, options.query)

    const isClient = ServerRoutes.isClient()
    if (!isClient) {
      const baseURL = ServerRoutes.getBaseURL()
      urlBuiltWithQuery = `${baseURL}${urlBuiltWithQuery}`
    }

    return await fetch(urlBuiltWithQuery, {
      method,
      headers: {
        'Content-Type': options.contentType || 'application/json',
        ...options.headers,
      },
      body: options.body ? JSON.stringify(options.body) : undefined,
    })
  }

  /**
   * Make an Inertia request with Laravel route name.
   */
  public inertia<T extends App.Route.Name>(name: T, method: HttpMethod, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestQuery): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia(method, url)
  }

  public toUrl(name: App.Route.Name, params?: any, query?: Record<string, any>): string {
    const p = params as Record<string, any>
    const route = this.laravel.getRouteLink(name)
    const url = this.assignParams(route.path, p)

    if (!query)
      return url

    const q = this.queryToString(query)

    return `${url}${q}`
  }

  private assignParams(url: string, params?: Record<string, any>): string {
    if (!params)
      return url

    // detect params in url with braces
    const matches = url.match(/({\w+})/g)
    if (!matches)
      return url

    // replace params in url with values
    const p = params as Record<string, any>
    const u = matches.reduce((url, match) => {
      const key = match.replace('{', '').replace('}', '')
      const value = p[key]
      return url.replace(match, value)
    }, url)

    return u
  }

  private queryToString(query: Record<string, any>): string {
    if (!query)
      return ''

    const q = Object.entries(query).map(([key, value]) => `${key}=${value}`).join('&')
    return `?${q}`
  }

  private urlBuiltWithQuery(url: string, query: Record<string, any> = {}): string {
    const q = this.queryToString(query)
    return `${url}${q}`
  }

  private sendInertia(method: HttpMethod, url: string, body?: Record<string, any>): void {
    return inertia[method.toLowerCase()](url, this.inertiaBody(body))
  }

  private inertiaBody(data?: RequestPayload): Record<string, any> | undefined {
    if (!data)
      return undefined

    if (typeof data.transform === 'function') {
      return data.transform(data => ({
        ...data,
      }))
    }

    return data
  }
}
