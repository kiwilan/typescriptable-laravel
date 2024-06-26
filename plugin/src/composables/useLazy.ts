import { ref } from 'vue'

/**
 * Lazy load images
 */
export function useLazy(loadingColor?: string, fallbackPath = '/placeholder.webp') {
  let url = 'http://localhost'
  let domain = ''
  let fallbackError = false
  const fallbackDefault = 'https://raw.githubusercontent.com/kiwilan/steward-laravel/main/public/no-image-available.jpg'

  if (isClient()) {
    url = window?.location.href
    const baseURL = url.split('/')
    baseURL.pop()
    url = baseURL.join('/')

    const newUrl = new URL(url)
    domain = newUrl.origin
  }

  if (!loadingColor)
    loadingColor = '#1f2937'

  const src = ref<string>()
  const alt = ref<string>()
  const classes = ref<string>()
  const styles = ref<string>()

  function createWrapper(el: HTMLElement) {
    const styleWrapper = [
      'position: relative;',
    ]

    const wrapper = document.createElement('div')
    wrapper.setAttribute('class', el.getAttribute('class') ?? '')
    wrapper.setAttribute('style', `${styleWrapper.join(' ')}`)

    return wrapper
  }

  function createImg() {
    const img = document.createElement('img')
    img.setAttribute('alt', alt.value!)
    img.setAttribute('class', classes.value!)
    img.setAttribute('style', styles.value!)
    img.setAttribute('loading', 'lazy')

    return img
  }

  function isClient(): boolean {
    return typeof window !== 'undefined'
  }

  async function checkFallbackUrl(url: string) {
    const response = await fetch(url)
    fallbackError = !response.ok
  }

  const vLazy = {
    beforeMount(el: HTMLElement) {
      src.value = el.getAttribute('src') ?? ''
      alt.value = el.getAttribute('alt') ?? ''
      classes.value = el.getAttribute('class') ?? ''
      styles.value = el.getAttribute('style') ?? ''
      el.setAttribute('src', '')
      el.setAttribute('alt', '')

      if (!src.value.startsWith('http'))
        src.value = `${url}${src.value}`
    },
    mounted(el: HTMLElement) {
      const stylePlaceholder = [
        `background-color: ${loadingColor};`,
        'height: 100%;',
        'width: 100%;',
        'position: absolute;',
        'top: 0;',
        'left: 0;',
        'right: 0;',
        'bottom: 0;',
        'opacity: 1;',
        'transition: opacity 0.3s ease-in-out;',
      ]

      const wrapper = createWrapper(el)

      const placeholder = document.createElement('div')
      placeholder.setAttribute('style', `${stylePlaceholder.join(' ')}`)
      placeholder.setAttribute('class', el.getAttribute('class') ?? '')

      const img = createImg()

      el.replaceWith(wrapper)
      wrapper.appendChild(placeholder)
      wrapper.appendChild(img)

      setTimeout(() => {
        img.setAttribute('src', src.value!)
        img.setAttribute('style', `height: 100%;`)

        img.onload = () => {
          placeholder.setAttribute('style', `${stylePlaceholder.join(' ')} opacity: 0;`)
          setTimeout(() => {
            placeholder.remove()
          }, 500)
        }

        img.onerror = () => {
          let fallbackUrl = `${domain}${fallbackPath}`
          if (fallbackError)
            fallbackUrl = fallbackDefault

          img.setAttribute('src', fallbackUrl)
          checkFallbackUrl(fallbackUrl)

          placeholder.setAttribute('style', `${stylePlaceholder.join(' ')} opacity: 0;`)
          setTimeout(() => {
            placeholder.remove()
          }, 500)
        }
      }, 50)
    },
  }

  return {
    vLazy,
  }
}
