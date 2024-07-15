import { HttpRequest } from './HttpRequest'
import type { BodyType, HttpMethod, HttpRequestAnonymous } from '@/types/http'

export class HttpResponse {
  private constructor(
    private response: Response,
    private headers: Headers,
    private statusCode: number,
    private statusText: string,
    private type: ResponseType,
    private ok: boolean,
    private redirected: boolean,
    private url: string,
  ) {}

  /**
   * Create a HTTP response from URL and method.
   */
  public static async create(url: string, method: HttpMethod, options?: HttpRequestAnonymous): Promise<HttpResponse> {
    const request = this.getRequest()
    const response = await request.http(url, method, options)

    const httpResponse = new HttpResponse(
      response,
      response.headers,
      response.status,
      response.statusText,
      response.type,
      response.ok,
      response.redirected,
      response.url,
    )

    return httpResponse
  }

  /**
   * Get headers of the response.
   */
  public getHeaders(): Headers {
    return this.headers
  }

  /**
   * Get header of the response.
   *
   * @param name Header name.
   */
  public getHeader(name: string): string | null {
    return this.headers.get(name)
  }

  /**
   * @deprecated Use `getStatusCode()` instead.
   *
   * Get status code of the response (200, 404, 500, etc.)
   */
  public getStatus(): number {
    return this.statusCode
  }

  /**
   * Get status code of the response (200, 404, 500, etc.)
   */
  public getStatusCode(): number {
    return this.statusCode
  }

  /**
   * Get status text of the response.
   *
   */
  public getStatusText(): string {
    return this.statusText
  }

  /**
   * Get type of the response.
   *
   * - `basic`: standard CORS or same-origin request with `fetch`.
   * - `cors`: cross-origin request with `fetch`.
   * - `default`: no-cors request with `fetch`.
   * - `error`: network error.
   * - `opaque`: no-cors request.
   * - `opaqueredirect`: no-cors request redirected.
   */
  public getType(): ResponseType {
    return this.type
  }

  /**
   * Check if the response is OK (status code 200-299).
   */
  public isOk(): boolean {
    return this.ok
  }

  /**
   * Check if the response is redirected.
   */
  public isRedirected(): boolean {
    return this.redirected
  }

  /**
   * Get URL of the response.
   */
  public getUrl(): string {
    return this.url
  }

  /**
   * Get body of the response, default is JSON.
   */
  public async getBody<T = any>(bodyType: BodyType = 'json'): Promise<T | undefined> {
    let body: T | undefined
    switch (bodyType) {
      case 'json':
        body = await this.response.json()
        break
      case 'text':
        body = await this.response.text() as any
        break
      case 'blob':
        body = await this.response.blob() as any
        break
      case 'formData':
        body = await this.response.formData() as any
        break
      case 'arrayBuffer':
        body = await this.response.arrayBuffer() as any
        break

      default:
        body = await this.response.json()
        break
    }

    return body as T
  }

  private static getRequest(): HttpRequest {
    return HttpRequest.create()
  }
}
