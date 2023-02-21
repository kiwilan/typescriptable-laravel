import type { PluginInertiaTyped } from '../types/index.js'
import InertiaTyped from './plugin.js'
import { appName, appResolve, appTitle } from './setup.js'
import { useInertiaTyped } from './composables/useInertiaTyped.js'
import { RouteModel } from './shared/RouteModel.js'

import { TypedLink } from './components/TypedLink/index.js'

export type { PluginInertiaTyped }
export {
  InertiaTyped,
  appResolve,
  appTitle,
  appName,
  useInertiaTyped,
  RouteModel,
  TypedLink,
}
