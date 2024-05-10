import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useRouter } from './useRouter'

export function useSearch(limit: number | false = 20, routeName = 'api.search.index') {
  const loading = ref(false)
  const searchUsed = ref(false)
  const searchText = ref('Ctrl+K')
  const searchField = ref<HTMLInputElement>()
  const results = ref<any[]>([])

  const { route } = useRouter()

  function checkSystem() {
    searchText.value = 'Ctrl+K'
    if (typeof navigator !== 'undefined') {
      const isMac = navigator?.userAgent.toUpperCase().includes('MAC')
      searchText.value = isMac ? '⌘+K' : 'Ctrl+K'
    }
  }
  checkSystem()

  /**
   * Search for the given value
   *
   * @param event - The input event
   * @param timeout - The timeout before searching
   * @param logging - Log the search
   */
  async function searching(event: Event, timeout = 500, logging = false) {
    searchUsed.value = true
    loading.value = true
    setTimeout(async () => {
      const e = event.target as HTMLInputElement
      const value = e.value

      let url = route(routeName as any)
      const params = new URLSearchParams()
      params.append('search', value)
      params.append('limit', limit ? limit.toString() : 'false')
      url += `?${params.toString()}`

      const res = await fetch(url)

      const body = await res.json()
      results.value = convertResults(body)
      if (logging) {
        // eslint-disable-next-line no-console
        console.log(results.value)
      }

      loading.value = false
    }, timeout)
  }

  /**
   * Clear the search field and results
   */
  function clearSearch() {
    if (searchField.value)
      searchField.value.value = ''
    searchUsed.value = false
    results.value = []
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
        searchField.value?.focus()
      }
      if (event.ctrlKey && event.key === 'k') {
        event.preventDefault()
        searchField.value?.focus()
      }
      if (event.key === 'Escape')
        closeSearch()

      const element = searchField.value
      if (element && element === document.activeElement && element.value.length > 0) {
        if (event.key === 'Enter') {
          event.preventDefault()
          router.get(`/search?search=${encodeURIComponent(element.value)}`)
        }
      }
    })
  }

  function convertResults(body: any) {
    return Object.values(body)
  }

  function closeSearch() {
    clearSearch()
    loading.value = false
    searchUsed.value = false
    searchField.value?.blur()
  }

  return {
    loading,
    searchText,
    searchField,
    searching,
    searchUsed,
    clearSearch,
    results,
    convertResults,
    keybinding,
    closeSearch,
  }
}
