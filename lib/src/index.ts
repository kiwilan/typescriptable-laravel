import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { VueTypescriptable } from './vue/plugin'

async function resolve(name: string, glob: Record<string, () => Promise<unknown>>): Promise<any> {
  return resolvePageComponent(`./Pages/${name}.vue`, glob) as Promise<any>
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

export {
  VueTypescriptable,
  resolve,
}
