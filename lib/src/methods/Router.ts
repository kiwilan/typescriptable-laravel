import { router as inertia } from '@inertiajs/vue3'
import { RouteList } from './RouteList'
import type { RequestPayload } from '@/types'

export class Router {
  protected constructor(
  ) {}

  static make(): Router {
    return new Router()
  }

  private convertURL(name: App.Route.Name): string {
    const list = RouteList.make()
    const route = list.getRoute(name)

    return route.path
  }

  public async get(name: App.Route.Name): Promise<void> {
    const url = this.convertURL(name)
    return await inertia.get(url)
  }

  public async post(name: App.Route.Name, data?: RequestPayload): Promise<void> {
    const url = this.convertURL(name)
    return await inertia.get(url, data)
  }

  public async put(name: App.Route.Name, data?: RequestPayload): Promise<void> {
    const url = this.convertURL(name)
    return await inertia.get(url, data)
  }

  public async patch(name: App.Route.Name, data?: RequestPayload): Promise<void> {
    const url = this.convertURL(name)
    return await inertia.get(url, data)
  }

  public async delete(name: App.Route.Name): Promise<void> {
    const url = this.convertURL(name)
    return await inertia.delete(url)
  }
}
