import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import type { DefineComponent } from 'vue'
import { VueTypescriptable } from './vue-plugin'

async function resolve(name: string, glob: Record<string, () => Promise<unknown>>): Promise<DefineComponent> {
  return resolvePageComponent(`./Pages/${name}.vue`, glob) as Promise<DefineComponent>
}

export type {
  Query,
  SortItem,
} from './composables'

export {
  useDate,
  useFetch,
  useInertia,
  useLazy,
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
