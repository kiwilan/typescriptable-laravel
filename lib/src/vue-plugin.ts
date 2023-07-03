import type { Plugin } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useTypescriptable } from './composables/useTypescriptable'

export const VueTypescriptable: Plugin = {
  install: (app) => {
    const ts = useTypescriptable()

    app.config.globalProperties.$route = ts.route
    app.config.globalProperties.$isRoute = ts.isRoute
    app.config.globalProperties.$currentRoute = ts.currentRoute
    app.config.globalProperties.$to = ts.to

    app.provide('inertia', {
      route: app.config.globalProperties.$route,
      isRoute: app.config.globalProperties.$isRoute,
      currentRoute: app.config.globalProperties.$currentRoute,
      to: app.config.globalProperties.$to,
    })

    app.component('IHead', Head)
    app.component('ILink', Link)

    return app
  },
}
