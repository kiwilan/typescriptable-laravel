import type { PluginInertiaTyped } from '../types/index.js'
import InertiaTyped from './plugin.js'
import { appResolve, appTitle } from './setup.js'
import { useInertiaTyped } from './composables/useInertiaTyped.js'

import { TypedLink } from './components/TypedLink/index.js'

export type { PluginInertiaTyped }
export {
  InertiaTyped,
  appResolve,
  appTitle,
  useInertiaTyped,
  TypedLink,
}
