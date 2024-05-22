import { h, ref } from 'vue'
import { useNotification } from '../composables/useNotification'
import Notification from './Notification'

interface Props {
  msg: string
}

export default {
  props: {
    msg: {
      type: String, // Object as PropType<Podcast>
      required: false,
      default: 'default message',
    },
  },
  setup(props: Props) {
    const { notifications } = useNotification()
    return () => h('div', { 'aria-live': 'assertive', 'class': 'notifications-container' }, [
      h('div', { class: 'notifications-container_wrapper' }, [
        h('div', `notifications ${props.msg}`),
        h('div', [notifications.value.length
          ? h(
            'div',
            notifications.value.map((notification) => {
              return h(Notification as any, { key: notification.id, notification })
            }),
          )
          : h('span'),
        ]),
      ]),
    ])
  },
}
