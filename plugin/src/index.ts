import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import type { DefineComponent } from 'vue'
import { VueTypescriptable } from './vue/plugin'

function resolveTitle(title: string, appName: string, seperator = '·'): string {
  return title ? `${title} ${seperator} ${appName}` : `${appName}`
}

async function resolvePages(name: string, glob: Record<string, () => Promise<unknown>>): Promise<DefineComponent> {
  return resolvePageComponent(`./Pages/${name}.vue`, glob) as Promise<DefineComponent>
}

export type {
  Query,
  SortItem,
} from './composables'

export {
  useClickOutside,
  useDate,
  useFetch,
  useInertia,
  useLazy,
  useNotification,
  usePagination,
  useQuery,
  useRouter,
  useSearch,
  useSidebar,
  useSlideover,
} from './composables'

export type {
  RoutesType,
} from './shared'

export {
  HttpRequest,
  HttpResponse,
  LaravelRouter,
} from './shared'

export {
  VueTypescriptable,
  resolveTitle,
  resolvePages,
}
