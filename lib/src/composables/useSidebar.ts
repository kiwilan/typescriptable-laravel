import { ref } from 'vue'

const overlay = ref(false)
const layer = ref(false)
const isOpen = ref(false)

export function useSidebar() {
  function toggle() {
    if (isOpen.value)
      close()
    else
      open()
  }

  function open() {
    layer.value = true
    setTimeout(() => {
      overlay.value = true
      isOpen.value = true
    }, 150)
  }

  function close() {
    overlay.value = false
    isOpen.value = false
    setTimeout(() => {
      layer.value = false
    }, 150)
  }

  return {
    overlay,
    layer,
    isOpen,
    toggle,
    open,
    close,
  }
}
