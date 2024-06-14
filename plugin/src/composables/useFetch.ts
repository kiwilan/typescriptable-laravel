import { HttpRequest } from '../shared/http/HttpRequest'
import { HttpResponse } from '../shared/http/HttpResponse'
import type { HttpRequestAnonymous, HttpRequestBody, HttpRequestQuery } from '@/types/http'

/**
 * Composable for HTTP requests, with anonymous requests, Laravel or Inertia.
 *
 * @method `http` Send a HTTP request with URL (for external API or Laravel URL), use `fetch`.
 * @method `laravel` Send a request with Laravel route name (for internal API), use `fetch`.
 * @method `inertia` Send an Inertia request with Laravel route name (for Inertia forms or links).
 *
 * @example
 *
 * ```vue
 * <script setup lang="ts">
 * import { useFetch } from '@kiwilan/vue-typescriptable-laravel'
 *
 * const { http, laravel, inertia } = useFetch()
 *
 * const { data } = await laravel.get('api.users')
 * const { data } = await http.get('https://example.com/api/users')
 * inertia.get('users.index')
 * </script>
 * ```
 */
export function useFetch() {
  const request = HttpRequest.create()

  /**
   * Create an anonymous request, without Laravel route name or Inertia.
   * Useful for external API, use `fetch` under the hood.
   *
   * - Query and body data are empty by default, override them with `options.query` and `options.body`.
   * - Content type is `application/json` by default, override it with `options.contentType`.
   * - Headers are empty by default, override them with `options.headers`.
   */
  const http = {
    async get(url: string, options?: HttpRequestAnonymous): Promise<HttpResponse> {
      return await HttpResponse.create(url, 'GET', options)
    },
    async post(url: string, options?: HttpRequestBody): Promise<HttpResponse> {
      return await HttpResponse.create(url, 'POST', options)
    },
    async put(url: string, options?: HttpRequestBody): Promise<HttpResponse> {
      return await HttpResponse.create(url, 'PUT', options)
    },
    async patch(url: string, options?: HttpRequestBody): Promise<HttpResponse> {
      return await HttpResponse.create(url, 'PATCH', options)
    },
    async delete(url: string, options?: HttpRequestQuery): Promise<HttpResponse> {
      return await HttpResponse.create(url, 'DELETE', options)
    },
  }

  /**
   * Make a HTTP request with Laravel route name.
   * Useful for internal API, use `fetch` under the hood.
   *
   * - Query data are empty by default, override them with `options.query`.
   * - Body data are empty by default, override them with `options.body`.
   * - Content type is `application/json` by default, override it with `options.contentType`.
   * - Headers are empty by default, override them with `options.headers`.
   * - URL is built with Laravel route name, params and query.
   */
  const laravel = {
    async get<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestQuery): Promise<HttpResponse> {
      const url = request.toUrl(name, params, options?.query)
      return await HttpResponse.create(url, 'GET', options)
    },
    async post<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): Promise<HttpResponse> {
      const url = request.toUrl(name, params, options?.query)
      return await HttpResponse.create(url, 'POST', options)
    },
    async put<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): Promise<HttpResponse> {
      const url = request.toUrl(name, params, options?.query)
      return await HttpResponse.create(url, 'PUT', options)
    },
    async patch<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): Promise<HttpResponse> {
      const url = request.toUrl(name, params, options?.query)
      return await HttpResponse.create(url, 'PATCH', options)
    },
    async delete<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestQuery): Promise<HttpResponse> {
      const url = request.toUrl(name, params, options?.query)
      return await HttpResponse.create(url, 'DELETE', options)
    },
  }

  /**
   * Make an Inertia request with Laravel route name.
   * Can't be used for API, use `http` or `laraval` instead.
   *
   * - Query data are empty by default, override them with `options.query`.
   * - Body data are empty by default, override them with `options.body`.
   */
  const inertia = {
    get<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestQuery): void {
      return request.inertia(name, 'GET', params, options)
    },
    post<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
      return request.inertia(name, 'POST', params, options)
    },
    put<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
      return request.inertia(name, 'PUT', params, options)
    },
    patch<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
      return request.inertia(name, 'PATCH', params, options)
    },
    delete<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestQuery): void {
      return request.inertia(name, 'DELETE', params, options)
    },
  }

  return {
    http,
    laravel,
    inertia,
  }
}
