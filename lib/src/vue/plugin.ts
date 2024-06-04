import type { Plugin } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useRouter } from '../composables/useRouter'

/**
 * Vue plugin to use Inertia.js with TypeScript.
 *
 * - Add `$route`, `$isRouteEqualTo`, and `$currentRoute` helpers.
 * - Add `<IHead>` and `<ILink>` components.
 *
 * @see https://inertiajs.com/title-and-meta
 * @see https://inertiajs.com/links
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
 *    <ILink :href="$route('home')">Home</ILink>
 *    <div v-if="$isRouteEqualTo('home')">Home page</div>
 *    <div>Current route: {{ $currentRoute.name }}</div>
 *  </div>
 * </template>
 * ```
 */
export const VueTypescriptable: Plugin = {
  install: (app) => {
    const router = useRouter()

    app.config.globalProperties.$route = router.route
    app.config.globalProperties.$isRouteEqualTo = router.isRouteEqualTo
    app.config.globalProperties.$currentRoute = router.currentRoute

    app.provide('inertia', {
      route: app.config.globalProperties.$route,
      isRouteEqualTo: app.config.globalProperties.$isRouteEqualTo,
      currentRoute: app.config.globalProperties.$currentRoute,
    })

    app.component('IHead', Head)
    app.component('ILink', Link)

    return app
  },
}
