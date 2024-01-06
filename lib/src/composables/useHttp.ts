import { Http } from '@/methods'
import type { HttpRequest, HttpRequestAnonymous, HttpRequestBody, RouteName } from '@/types'

/**
 * Composable for HTTP requests, using Inertia.
 *
 * @method `raw` Make a HTTP request with URL.
 * @method `get` Make a GET Inertia request with Laravel route name.
 * @method `post` Make a POST Inertia request with Laravel route name.
 * @method `put` Make a PUT Inertia request with Laravel route name.
 * @method `patch` Make a PATCH Inertia request with Laravel route name.
 * @method `delete` Make a DELETE Inertia request with Laravel route name.
 *
 * @example
 *
 * ```vue
 * <script setup lang="ts">
 * import { useHttp } from '@kiwilan/vue-typescriptable-laravel'
 *
 * const http = useHttp()
 *
 * const { data } = http.raw('api.users')
 * </script>
 * ```
 */
export function useHttp() {
  const http = Http.create()

  /**
   * Create an anonymous request, without Laravel route name and Inertia.
   * Useful for API, use `fetch` under the hood.
   *
   * - HTTP method is `GET` by default, override it with `options.method`.
   * - Query and body data are empty by default, override them with `options.query` and `options.body`.
   * - Content type is `application/json` by default, override it with `options.contentType`.
   * - Headers are empty by default, override them with `options.headers`.
   */
  async function raw(url: string, options: HttpRequestAnonymous = { method: 'GET' }): Promise<Response> {
    return await http.raw(url, options)
  }

  /**
   * Make a `GET` Inertia request with Laravel route name.
   *
   * Query data is empty by default, override it with `options.query`.
   */
  function get<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequest): void {
    return http.get(name, params, options)
  }

  /**
   * Make a `POST` Inertia request with Laravel route name.
   *
   * Query and body data are empty by default, override them with `options.query` and `options.body`.
   */
  function post<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    return http.post(name, params, options)
  }

  /**
   * Make a `PUT` Inertia request with Laravel route name.
   *
   * Query and body data are empty by default, override them with `options.query` and `options.body`.
   */
  function put<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    return http.put(name, params, options)
  }

  /**
   * Make a `PATCH` Inertia request with Laravel route name.
   *
   * Query and body data are empty by default, override them with `options.query` and `options.body`.
   */
  function patch<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequestBody): void {
    return http.patch(name, params, options)
  }

  /**
   * Make a `DELETE` Inertia request with Laravel route name.
   *
   * Query data is empty by default, override it with `options.query`.
   */
  function destroy<T extends App.Route.Name>(name: T, params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never, options?: HttpRequest): void {
    return http.delete(name, params, options)
  }

  return {
    raw,
    get,
    post,
    put,
    patch,
    delete: destroy,
  }
}
