import type { InertiaForm } from '@inertiajs/vue3'

export type RequestPayload = Record<string, any> | InertiaForm<any>
export type RouteName = App.Route.Name
export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
export type BodyType = 'json' | 'text' | 'blob' | 'formData' | 'arrayBuffer'

export interface HttpRequestBody extends HttpRequestQuery {
  /**
   * Body data.
   *
   * @default {}
   */
  body?: RequestPayload
}

export interface HttpRequestQuery extends HttpRequestAnonymous {
  /**
   * Add query data to URL.
   */
  query?: Record<string, any>
}

export interface HttpRequestAnonymous {
  /**
   * Query data.
   *
   * @default {}
   */
  query?: Record<string, any>
  /**
   * Body data.
   *
   * @default {}
   */
  body?: RequestPayload
  /**
   * HTTP headers.
   *
   * @default {}
   */
  headers?: Record<string, string>
  /**
   * HTTP Content-Type header.
   *
   * @default 'application/json'
   */
  contentType?: string
}
