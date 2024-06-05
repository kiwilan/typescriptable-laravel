import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

/**
 * Options for the search function
 *
 * - `searchQuery` - The query parameter for the search, default is `search`
 * - `limitQuery` - The query parameter for the limit, default is `limit`
 * - `limit` - The limit of the search, default is `false`
 * - `shortcut` - The keybinding for the search field, default is `Ctrl+K`
 * - `shortcutMacos` - The keybinding for MacOS, default is `⌘+K`
 */
interface SearchOptions {
  searchQuery?: string
  limitQuery?: string
  limit?: number | false
  shortcut?: string
  shortcutMacos?: string
}

export function useSearch<T = any>(searchUrl: string, options: SearchOptions = {}) {
  const loading = ref(false)
  const used = ref(false)
  const shortcut = ref('Ctrl+K')

  const input = ref<HTMLInputElement>()
  const query = ref<string>()
  const response = ref<T>()

  /**
   * Search for the given value
   *
   * @param event - The input event
   * @param timeout - The timeout before searching
   */
  async function search(event: Event, timeout = 500) {
    used.value = true // now search is used
    loading.value = true // start loading

    setTimeout(async () => {
      const e = event.target as HTMLInputElement
      query.value = e.value // set the query

      let url = searchUrl
      const params = new URLSearchParams()
      params.append(options.searchQuery ?? 'search', query.value)
      if (options.limit)
        params.append(options.limitQuery ?? 'limit', options.limit.toString())
      url += `?${params.toString()}`

      const res = await fetch(url)
      const body = await res.json()
      response.value = body

      loading.value = false // stop loading
    }, timeout)
  }

  /**
   * Clear the search field and results
   */
  function clear() {
    if (input.value)
      input.value.value = ''

    query.value = ''
    used.value = false
    response.value = undefined
  }

  /**
   * Close the search field
   */
  function close() {
    clear()
    loading.value = false
    used.value = false
    input.value?.blur()
  }

  /**
   * Keybinding for the search field
   * - `Ctrl+K` or `⌘+K` to focus the search field
   * - `Enter` to search
   * - `Escape` to close the search
   *
   * ```js
   * import { useSearch } from '@kiwilan/typescriptable-laravel'
   * import { onMounted } from 'vue'
   *
   * const { keybinding } = useSearch()
   *
   * onMounted(() => {
   *   keybinding()
   * })
   * ```
   */
  function keybinding() {
    document.addEventListener('keydown', (event) => {
      if (event.metaKey && event.key === 'k') {
        event.preventDefault()
        input.value?.focus()
      }
      if (event.ctrlKey && event.key === 'k') {
        event.preventDefault()
        input.value?.focus()
      }
      if (event.key === 'Escape')
        close()

      const element = input.value
      if (element && element === document.activeElement && element.value.length > 0) {
        if (event.key === 'Enter') {
          event.preventDefault()
          router.get(`/search?search=${encodeURIComponent(element.value)}`)
        }
      }
    })
  }

  function checkSystem() {
    const shortcutOther = options.shortcut ?? 'Ctrl+K'
    const shortcutMacos = options.shortcutMacos ?? '⌘+K'

    if (typeof navigator !== 'undefined') {
      const isMac = navigator?.userAgent.toUpperCase().includes('MAC')
      shortcut.value = isMac ? shortcutMacos : shortcutOther
    }
  }
  checkSystem()

  return {
    search,
    keybinding,
    shortcut,
    input,
    loading,
    used,
    clear,
    close,
    response,
  }
}
