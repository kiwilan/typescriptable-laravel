import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

export function useInertia<T = App.Models.User>() {
  /**
   * Shortcut to the page object, same than Inertia's composable `usePage()` with types.
   *
   * @see https://inertiajs.com/the-protocol#the-page-object
   */
  const page = usePage<Inertia.PageProps>()

  const component = computed((): Inertia.PageProps['component'] => {
    return page.component
  })

  const props = computed(() => {
    return page.props as Inertia.PageProps
  })

  const url = computed((): string => {
    return page.url
  })

  const version = computed((): string | null => {
    return page.version
  })

  const auth = computed((): { user: T } => {
    return props.value.auth as { user: T }
  })

  const user = computed((): T => {
    return auth.value?.user as T
  })

  const isAuth = computed((): boolean => {
    return !!user.value
  })

  const csrfToken = computed((): string | null => {
    const element = document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null
    if (!element)
      return null

    return element.content
  })

  /**
   * Check if the app is running in development mode.
   */
  const isDev = computed(() => {
    return process.env.NODE_ENV === 'development'
  })

  const isClient = computed(() => {
    return typeof window !== 'undefined'
  })

  return {
    page,
    component,
    props,
    url,
    version,
    auth,
    user,
    isAuth,
    csrfToken,
    isDev,
    isClient,
  }
}
