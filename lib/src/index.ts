import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import type { DefineComponent } from 'vue'
import { useTypescriptable } from './composables/useTypescriptable'
import { useFetch } from './composables/useFetch'
import { useRouter } from './composables/useRouter'
import { useInertia } from './composables/useInertia'
import { VueTypescriptable } from './vue-plugin'

async function resolve(name: string, glob: Record<string, () => Promise<unknown>>): Promise<DefineComponent> {
  return resolvePageComponent(`./Pages/${name}.vue`, glob) as Promise<DefineComponent>
}

export {
  VueTypescriptable,
  useTypescriptable,
  useFetch,
  useRouter,
  useInertia,
  resolve,
}
