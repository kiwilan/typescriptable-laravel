import { ref } from 'vue'

const layer = ref(false)
const isOpen = ref(false)

export function useSlideover() {
  function toggle() {
    if (isOpen.value)
      close()
    else
      open()
  }

  function open() {
    layer.value = true
    setTimeout(() => {
      isOpen.value = true
    }, 150)
  }

  function close() {
    isOpen.value = false
    setTimeout(() => {
      layer.value = false
    }, 700)
  }

  return {
    layer,
    isOpen,
    toggle,
    open,
    close,
  }
}
