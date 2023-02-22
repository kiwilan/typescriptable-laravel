import type { Plugin } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { TypedLinkVue as TypedLink } from './components/index.js'
import { useInertiaTyped } from './index.js'

const InertiaTyped: Plugin = {
  install: (app) => {
    const inertia = useInertiaTyped()

    app.config.globalProperties.$route = inertia.route
    app.config.globalProperties.$isRoute = inertia.isRoute
    app.config.globalProperties.$currentRoute = inertia.currentRoute

    app.provide('inertia', {
      route: app.config.globalProperties.$route,
      isRoute: app.config.globalProperties.$isRoute,
      currentRoute: app.config.globalProperties.$currentRoute,
    })

    // eslint-disable-next-line vue/no-reserved-component-names
    app.component('Head', Head)
    // eslint-disable-next-line vue/no-reserved-component-names
    app.component('Link', Link)
    app.component('Route', TypedLink)

    return app
  },
}

export default InertiaTyped
