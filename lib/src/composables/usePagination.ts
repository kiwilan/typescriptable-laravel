import { ref } from 'vue'

interface PaginateLink extends App.PaginateLink {
  isLink?: boolean
  class?: string
}

export function usePagination(models: App.Paginate) {
  const onEachSide = 2
  const firstPage = ref<PaginateLink>()
  const lastPage = ref<PaginateLink>()
  const currentPage = ref<PaginateLink>()
  const pages = ref<PaginateLink[]>([])
  const previousPage = ref<string>()
  const nextPage = ref<string>()

  function getQuery() {
    const query = new URLSearchParams(window.location.search)
    query.delete('page')

    return query.toString()
  }

  function paginate() {
    const allPages: PaginateLink[] = []
    const baseURL = models.last_page_url.replace(/\?.*$/, '')

    for (let i = 0; i < models.last_page; i++) {
      allPages.push({
        label: `${i + 1}`,
        url: `${baseURL}?page=${i + 1}`,
        active: i + 1 === models.current_page,
        isLink: true,
        class: `page ${i + 1 === models.current_page ? 'page-active' : 'page-not-active'}`,
      })
    }

    firstPage.value = allPages[0]
    lastPage.value = allPages[allPages.length - 1]
    currentPage.value = allPages.find(page => page.active)

    allPages.shift()
    allPages.pop()

    const activeIndex = allPages.findIndex(page => page.active)
    const seperator: PaginateLink = {
      label: '...',
      url: '',
      active: false,
      isLink: false,
      class: 'page page-disabled',
    }

    const max = onEachSide * 3

    // if (allPages.length < 2) {
    //   pages.value = allPages
    //   return
    // }

    if (activeIndex <= onEachSide && models.current_page !== models.last_page) {
      pages.value = allPages.slice(0, onEachSide + onEachSide)
      if (models.last_page !== max && models.last_page > max) {
        pages.value = [
          ...pages.value,
          seperator,
        ]
      }
    }
    else if (activeIndex >= allPages.length - onEachSide || models.current_page === models.last_page) {
      if (models.last_page !== max || models.last_page > max) {
        pages.value = [
          seperator,
        ]
      }

      pages.value = [
        ...pages.value,
        ...allPages.slice(allPages.length - onEachSide - onEachSide, allPages.length),
      ]
    }
    else {
      pages.value = [
        seperator,
        ...allPages.slice(activeIndex - onEachSide, activeIndex),
        currentPage.value!,
        ...allPages.slice(activeIndex + 1, activeIndex + onEachSide + 1),
      ]

      if (activeIndex === onEachSide)
        pages.value.splice(1, 1)

      if (models.current_page + onEachSide + 1 !== models.last_page) {
        pages.value = [
          ...pages.value,
          seperator,
        ]
      }
    }

    pages.value.unshift(firstPage.value!)
    pages.value.push(lastPage.value!)

    pages.value.forEach((page) => {
      page.url = `${page.url}&${getQuery()}`
    })

    previousPage.value = models.prev_page_url ? `${models.prev_page_url}&${getQuery()}` : undefined
    nextPage.value = models.next_page_url ? `${models.next_page_url}&${getQuery()}` : undefined
  }

  paginate()

  return {
    pages,
    previousPage,
    nextPage,
  }
}
