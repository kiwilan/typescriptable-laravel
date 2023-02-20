import type { PluginInertiaTyped } from '../types/index.js'
import InertiaTyped from './plugin.js'
import { appResolve, appTitle } from './setup.js'
import { useInertia } from './composables/useInertia.js'

import { TypedLink } from './components/TypedLink/index.js'

export type { PluginInertiaTyped }
export {
  InertiaTyped,
  appResolve,
  appTitle,
  useInertia,
  TypedLink,
}
