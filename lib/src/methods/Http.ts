import { router as inertia } from '@inertiajs/vue3'
import { LaravelRouter } from './LaravelRouter'
import type { HttpMethod, HttpRequest, HttpRequestAnonymous, HttpRequestBody, RequestPayload, RouteName } from '@/types'

export class Http {
  protected constructor(
    protected laravel: LaravelRouter,
  ) {}

  static create(): Http {
    return new Http(LaravelRouter.create())
  }

  private toUrl(name: App.Route.Name, params?: any, query?: Record<string, any>): string {
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

  /**
   * Make a raw HTTP request.
   * Useful for API, use `fetch` under the hood.
   */
  public async raw(url: string, options: HttpRequestAnonymous = { method: 'GET', contentType: 'application/json' }): Promise<Response> {
    const urlBuiltWithQuery = this.urlBuiltWithQuery(url, options.query)

    return await fetch(urlBuiltWithQuery, {
      method: options.method,
      headers: {
        'Content-Type': options.contentType || 'application/json',
        ...options.headers,
      },
      body: options.body ? JSON.stringify(options.body) : undefined,
    })
  }

  /**
   * Make a `GET` Inertia request with Laravel route name.
   */
  public get<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequest): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia('GET', url)
  }

  /**
   * Make a `POST` Inertia request with Laravel route name.
   */
  public post<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia('POST', url, options?.body)
  }

  /**
   * Make a `PUT` Inertia request with Laravel route name.
   */
  public put<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia('PUT', url, options?.body)
  }

  /**
   * Make a `PATCH` Inertia request with Laravel route name.
   */
  public patch<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia('PATCH', url, options?.body)
  }

  /**
   * Make a `DELETE` Inertia request with Laravel route name.
   */
  public delete<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequest): void {
    const url = this.toUrl(name, params, options?.query)
    return this.sendInertia('DELETE', url)
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
