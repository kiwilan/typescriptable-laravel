import type { Ref } from 'vue'
import { onBeforeUnmount, onMounted } from 'vue'

export function useClickOutside(element: Ref<EventTarget | HTMLElement | undefined>, callback: () => void) {
  if (!element)
    return

  const listener = (e: MouseEvent) => {
    const el = element.value as EventTarget
    if (typeof el === 'undefined')
      return

    if (e.target === el || e.composedPath().includes(el))
      return

    if (typeof callback === 'function')
      callback()
  }

  onMounted(() => {
    window.addEventListener('click', listener)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('click', listener)
  })

  return {
    listener,
  }
}
