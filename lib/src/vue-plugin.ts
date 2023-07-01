import type { Plugin } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useTypescriptable } from './composables/useTypescriptable'

export const VueTypescriptable: Plugin = {
  install: (app) => {
    const inertia = useTypescriptable()

    app.config.globalProperties.$route = inertia.route as any
    app.config.globalProperties.$isRoute = inertia.isRoute as any
    app.config.globalProperties.$currentRoute = inertia.currentRoute as any

    app.provide('inertia', {
      route: app.config.globalProperties.$route,
      isRoute: app.config.globalProperties.$isRoute,
      currentRoute: app.config.globalProperties.$currentRoute,
    })

    // eslint-disable-next-line vue/no-reserved-component-names
    app.component('Head', Head)
    // eslint-disable-next-line vue/no-reserved-component-names
    app.component('Link', Link)

    return app
  },
}
