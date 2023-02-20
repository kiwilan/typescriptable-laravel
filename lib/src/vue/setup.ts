import type { DefineComponent } from 'vue'

type Pages = Record<string, Promise<DefineComponent> | (() => Promise<DefineComponent>)>
type Page = Promise<DefineComponent>

// resolve: name => resolve(name, import.meta.glob('./Pages/**/*.vue', { eager: true })),

/**
 * Resolve `createInertiaApp`.
 *
 * @example
 * createInertiaApp({
 *   resolve: name => appResolve(name, import.meta.globEager('./Pages/*.vue'))
 * })
 */
const appResolve = (name: string, glob: Record<string, unknown>): Page => {
  const pages = glob as Pages
  return pages[`./Pages/${name}.vue`] as Page
}

/**
 * Title for `createInertiaApp`.
 *
 * @example
 * createInertiaApp({
 *   title: (title) => appTitle(title, 'Override Title')
 * })
 */
const appTitle = (title: string, overrideTitle?: string): string => {
  let appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'
  if (overrideTitle)
    appName = overrideTitle

  return `${title} - ${appName}`
}

export {
  appResolve,
  appTitle,
}
