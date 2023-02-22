import InertiaTyped from './plugin.js'
import { appResolve, appTitle } from './setup.js'
import { useInertiaTyped } from './composables/useInertiaTyped.js'
import { RouteModel } from './shared/RouteModel.js'

import { TypedLinkVue } from './components/TypedLink/index.js'

export {
  InertiaTyped,
  appResolve,
  appTitle,
  useInertiaTyped,
  RouteModel,
  TypedLinkVue as TypedLink,
}
