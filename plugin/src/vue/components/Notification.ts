import type { PropType } from 'vue'
import { h, onMounted, ref } from 'vue'
import type { NotificationExtended } from '../composables/useNotification'

interface Props {
  notification: NotificationExtended
}

export default {
  props: {
    notification: {
      type: Object as PropType<NotificationExtended>,
      required: true,
    },
  },
  setup(props: Props) {
    // const { remove } = useNotification()
    const displayed = ref(false)

    // const icons = {
    //   success: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
    //   error: 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z',
    //   warning: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z',
    //   info: 'm11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z',
    // }

    // const type = computed(() => props.notification.type ?? 'info')
    // const color = computed(() => {
    //   switch (type.value) {
    //     case 'success':
    //       return 'notification-success'
    //     case 'error':
    //       return 'notification-error'
    //     case 'warning':
    //       return 'notification-warning'
    //     case 'info':
    //       return 'notification-info'
    //     default:
    //       return 'notification-info'
    //   }
    // })

    onMounted(() => {
      setTimeout(() => {
        displayed.value = true
        const timeout = (props.notification.timeout || 5000) - 500

        setTimeout(() => {
          displayed.value = false
        }, timeout)
      }, 150)
    })

    return () => h('div', [`notification ${props.notification.title}`])
  },
}
