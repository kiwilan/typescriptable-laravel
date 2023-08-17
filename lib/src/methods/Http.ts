import { router as inertia } from '@inertiajs/vue3'
import { Router } from './Router'
import type { RequestPayload } from '@/types'

export class Http {
  protected constructor(
  ) {}

  static make(): Http {
    return new Http()
  }

  private convertURL(name: App.Route.Name): string {
    const list = Router.make()
    const route = list.getRouteLink(name)

    return route.path
  }

  public get(name: App.Route.Name): void {
    const url = this.convertURL(name)
    return inertia.get(url)
  }

  public post(name: App.Route.Name, data?: RequestPayload): void {
    const url = this.convertURL(name)
    return inertia.post(url, this.convertData(data))
  }

  public put(name: App.Route.Name, data?: RequestPayload): void {
    const url = this.convertURL(name)
    return inertia.put(url, this.convertData(data))
  }

  public patch(name: App.Route.Name, data?: RequestPayload): void {
    const url = this.convertURL(name)
    return inertia.patch(url, this.convertData(data))
  }

  public delete(name: App.Route.Name): void {
    const url = this.convertURL(name)
    return inertia.delete(url)
  }

  private convertData(data?: RequestPayload): Record<string, any> {
    if (!data)
      return {}

    if (typeof data.transform === 'function') {
      return data.transform(data => ({
        ...data,
      }))
    }

    return data
  }
}
