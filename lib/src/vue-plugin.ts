import type { Plugin } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useRouter } from './composables/useRouter'

/**
 * Vue plugin to use Inertia.js with TypeScript.
 *
 * - Add `$route`, `$isRoute`, `$currentRoute` and `$to` helpers.
 * - Add `<IHead>` and `<ILink>` components.
 *
 * @example
 *
 * ```ts
 * import { createApp } from 'vue'
 * import { VueTypescriptable } from '@kiwilan/typescriptable-laravel'
 *
 * createInertiaApp({
 *   title: title => `${title} - MyApp`,
 *   resolve: name => {
 *     const pages = import.meta.glob('./Pages/*.vue', { eager: true })
 *     return pages[`./Pages/${name}.vue`]
 *   },
 *   setup({ el, App, props, plugin }) {
 *     const app = createApp({ render: () => h(App, props) })
 *       .use(plugin)
 *       .use(VueTypescriptable)
 *
 *     app.mount(el)
 *   },
 * })
 * ```
 *
 * In your Vue components:
 *
 * ```vue
 * <template>
 *  <div>
 *    <IHead title="MyApp" />
 *    <ILink :href="$to({ name: 'home' })">Home</ILink>
 *    <div v-if="$isRoute('home')">Home page</div>
 *    <div>Current route: {{ $currentRoute.name }}</div>
 *  </div>
 * </template>
 * ```
 */
export const VueTypescriptable: Plugin = {
  install: (app) => {
    const router = useRouter()

    app.config.globalProperties.$route = router.route
    app.config.globalProperties.$isRoute = router.isRoute
    app.config.globalProperties.$currentRoute = router.currentRoute
    app.config.globalProperties.$to = router.to

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
