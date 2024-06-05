import { ref } from 'vue'

export interface Notification {
  type?: 'success' | 'error' | 'info' | 'warning'
  title: string
  description?: string
  duration?: number
}

export interface NotificationExtended extends Notification {
  id: number
  timeout: number
  timer: number
}

const notifications = ref<NotificationExtended[]>([])

export function useNotification(timeout = 5000) {
  function push(notification: Notification) {
    const n = {
      ...notification,
      id: Date.now(),
      timeout: notification.duration || timeout,
      timer: 0,
    }

    notifications.value.unshift(n)

    setTimeout(() => {
      notifications.value = notifications.value.filter(item => item.id !== n.id)
    }, n.timeout)
  }

  function remove(id: number) {
    notifications.value = notifications.value.filter(item => item.id !== id)
  }

  function clearAll() {
    notifications.value = []
  }

  return {
    notifications,
    push,
    remove,
    clearAll,
  }
}
