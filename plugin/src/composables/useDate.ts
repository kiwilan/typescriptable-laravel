export function useDate() {
  const defaultDTO: Intl.DateTimeFormatOptions = {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  }
  const language = navigator?.language ? navigator.language : 'en-US'
  function toDate(date?: string | Date): Date {
    if (!date)
      return new Date()

    return new Date(date)
  }

  /**
   * Format date to human readable format.
   */
  function formatDate(date?: string | Date, options: Intl.DateTimeFormatOptions = defaultDTO): string | undefined {
    if (!date)
      return undefined

    return toDate(date).toLocaleDateString(language, options)
  }

  /**
   * Format date to human readable format.
   * @example 01/01/2021
   */
  function dateSlash(date?: string | Date): string | undefined {
    return formatDate(date, defaultDTO)
  }

  /**
   * Format date to human readable format.
   * @example Jan 1, 2021
   */
  function dateString(date?: string | Date): string | undefined {
    return formatDate(date, {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    })
  }

  return {
    formatDate,
    dateSlash,
    dateString,
  }
}
