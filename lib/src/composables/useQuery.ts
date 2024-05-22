import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

export interface Query<T = any> extends App.Paginate<T> {
  sort?: string
  filter?: string | number | boolean | string[] | number[] | boolean[]
}

export interface SortItem {
  label: string
  value: string
}

const current = ref<Query>()
const total = ref<number>()
const isCleared = ref<boolean>(false)
const sort = ref<string>()
const limit = ref<number>(10)
const isReversed = ref(false)

export function useQuery<T>(propQuery: App.Paginate<T>, prop: string = 'query') {
  current.value = propQuery
  total.value = propQuery.total
  sort.value = current.value.sort
  limit.value = current.value.per_page

  /**
   * Set the sort value to the query.
   */
  function initializeSort() {
    const query = new URLSearchParams(window?.location.search)
    const querySort = query.get('sort')
    if (querySort)
      sort.value = querySort
    if (sort.value && sort.value.startsWith('-'))
      isReversed.value = true
    setLimit()
  }

  function setLimit() {
    const localLimit = localStorage.getItem('limit') ?? limit.value.toString()
    limit.value = Number.parseInt(localLimit)

    merge({
      limit: limit.value.toString(),
    })
  }

  /**
   * Limit the number of results.
   */
  function limitTo(l: number) {
    limit.value = l
    localStorage.setItem('limit', limit.value.toString())

    merge({
      limit: l.toString(),
    })
  }

  /**
   * Execute the query.
   */
  function execute(q: Record<string, string>) {
    const form = useForm({
      ...q,
    })
    form.get(location.pathname, {
      preserveState: true,
      onSuccess: (page) => {
        // if `defineProps` use different name for query, we need to get it from `page.props`
        const d = page.props[prop] as T
        current.value = undefined
        setTimeout(() => {
          current.value = d as any
        }, 250)
      },
    })
  }

  /**
   * Clear the filter value from the query.
   */
  const clear = () => {
    isCleared.value = true
    execute({})
  }

  /**
   * Compare deep equality of two objects.
   */
  function deepEqual(x: object, y: object): boolean {
    const ok = Object.keys
    const tx = typeof x
    const ty = typeof y
    return (x && y && tx === 'object' && tx === ty)
      ? (
          ok(x).length === ok(y).length
          && ok(x).every(key => deepEqual(x[key], y[key]))
        )
      : (x === y)
  }

  /**
   * Merge the given query into the current query string.
   */
  function merge(queryToAdd: Record<string, string>) {
    const c = new URLSearchParams(location.search)

    const current: Record<string, string> = {}
    c.forEach((value, key) => {
      current[key] = value
    })

    const mergedQuery: Record<string, string> = {
      ...queryToAdd,
    }

    for (const currentKey in current) {
      const currentValue = current[currentKey]

      // If it's an array, we need to merge the values
      if (queryToAdd[currentKey] && currentKey.includes('[')) {
        const existing = currentValue.split(',')
        const values = [queryToAdd[currentKey], ...existing]
        const cleaned = [...new Set(values)].sort()
        mergedQuery[currentKey] = cleaned.join(',')
      }
      // If it's not an array, we just need to override the value
      else {
        mergedQuery[currentKey] = queryToAdd[currentKey] || current[currentKey]
      }
    }

    if (!deepEqual(mergedQuery, current))
      execute(mergedQuery)

    return mergedQuery
  }

  /**
   * Sort by the given field.
   */
  function sortBy(field: string) {
    if (field === sort.value) {
      sortReverse()
      return
    }

    sort.value = field
    merge({
      sort: sort.value,
    })
  }

  /**
   * Reverse the current sort direction.
   */
  function sortReverse() {
    if (sort.value) {
      const isReverse = sort.value?.startsWith('-')
      const s = sort.value?.replace('-', '')
      sort.value = isReverse ? s : `-${s}`

      merge({
        sort: sort.value,
      })

      isReversed.value = !isReversed.value
    }
  }

  initializeSort()

  return {
    request: current,
    total,
    clear,
    sortBy,
    sortReverse,
    isReversed,
    limitTo,
  }
}
