import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import type { DefineComponent } from 'vue'
import {
  useDate,
  useFetch,
  useInertia,
  useLazy,
  usePaginate,
  usePagination,
  useQuery,
  useRouter,
  useSearch,
  useSidebar,
  useSlideover,
  useTypescriptable,
} from './composables'
import type {
  Query,
  SortItem,
} from './composables'
import { VueTypescriptable } from './vue-plugin'

async function resolve(name: string, glob: Record<string, () => Promise<unknown>>): Promise<DefineComponent> {
  return resolvePageComponent(`./Pages/${name}.vue`, glob) as Promise<DefineComponent>
}

export type {
  Query,
  SortItem,
}

export {
  VueTypescriptable,
  resolve,
  useDate,
  useFetch,
  useInertia,
  useLazy,
  usePaginate,
  usePagination,
  useQuery,
  useRouter,
  useSearch,
  useSidebar,
  useSlideover,
  useTypescriptable,
}
