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
    if (navigator === undefined || navigator?.userAgent === undefined) {
      searchText.value = 'Ctrl+K'
      return
    }

    const isMac = navigator?.userAgent.toUpperCase().includes('MAC')
    searchText.value = isMac ? '⌘+K' : 'Ctrl+K'
  }
  checkSystem()

  async function searching(event: Event) {
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

      loading.value = false
    }, 500)
  }

  function clearSearch() {
    if (searchField.value)
      searchField.value.value = ''
    searchUsed.value = false
    results.value = []
  }

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
