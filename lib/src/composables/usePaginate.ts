import { computed } from 'vue'

export function usePaginate(models: App.Paginate) {
  function convertUrl(queryName: string, queryValue: number | string) {
    let currentUrl = window.location.href
    if (currentUrl.includes(`${queryName}=`))
      currentUrl = currentUrl.replace(/page=\d+/, `${queryName}=${queryValue}`)
    else if (currentUrl.includes('?'))
      currentUrl += `&${queryName}=${queryValue}`
    else
      currentUrl += `?${queryName}=${queryValue}`

    return currentUrl
  }

  const nextPage = computed((): string | undefined => {
    if (models.current_page === models.last_page)
      return undefined

    return convertUrl('page', models.current_page + 1)
  })

  const previousPage = computed((): string | undefined => {
    if (models.current_page === 1)
      return undefined

    return convertUrl('page', models.current_page - 1)
  })

  return {
    nextPage,
    previousPage,
  }
}
