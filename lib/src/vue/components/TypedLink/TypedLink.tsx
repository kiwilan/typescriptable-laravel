import { mergeDataIntoQueryString, router, shouldIntercept } from '@inertiajs/core'
import type { DefineComponent, PropType } from 'vue'
import { defineComponent, h } from 'vue'

export interface InertiaLinkProps {
  as?: string
  data?: RequestPayload
  href: string
  method?: Method
  headers?: object
  onClick?: (event: MouseEvent | KeyboardEvent) => void
  preserveScroll?: boolean | ((props: any) => boolean)
  preserveState?: boolean | ((props: any) => boolean) | null
  replace?: boolean
  only?: string[]
  onCancelToken?: (cancelToken: any) => void
  onBefore?: () => void
  onStart?: () => void
  onProgress?: (progress: any) => void
  onFinish?: () => void
  onCancel?: () => void
  onSuccess?: () => void
  queryStringArrayFormat?: 'brackets' | 'indices'
}

interface Attrs {
  onCancelToken?: (cancel: () => void) => void
  onBefore?: (visit: any) => void
  onStart?: (visit: any) => void
  onProgress?: (event: any) => void
  onFinish?: (visit: any) => void
  onCancel?: (visit: any) => void
  onSuccess?: (page: any) => void
  onError?: (error: any) => void
}
type RequestPayload = Record<string, any>
type Method = 'get' | 'post' | 'put' | 'patch' | 'delete'
type InertiaLink = DefineComponent<InertiaLinkProps>

// @ts-expect-error Vue props to PropType
const TypedLink: InertiaLink = defineComponent({
  name: 'TypedLink',
  props: {
    as: {
      type: String,
      default: 'a',
    },
    data: {
      type: Object as PropType<RequestPayload>,
      default: () => ({}),
    },
    href: {
      type: String,
      required: true,
    },
    method: {
      type: String as PropType<Method>,
      default: 'get',
    },
    replace: {
      type: Boolean,
      default: false,
    },
    preserveScroll: {
      type: Boolean,
      default: false,
    },
    preserveState: {
      type: Boolean,
      default: null,
    },
    only: {
      type: Array<string>,
      default: () => [],
    },
    headers: {
      type: Object,
      default: () => ({}),
    },
    queryStringArrayFormat: {
      type: String as PropType<'brackets' | 'indices'>,
      default: 'brackets',
    },
  },
  setup(props, { slots, attrs }: { slots: any; attrs: Attrs }) {
    return () => {
      const as = props.as.toLowerCase()
      const method = props.method.toLowerCase() as Method
      const [href, data] = mergeDataIntoQueryString(method, props.href || '', props.data, props.queryStringArrayFormat)

      if (as === 'a' && method !== 'get') {
        console.warn(
          `Creating POST/PUT/PATCH/DELETE <a> links is discouraged as it causes "Open Link in New Tab/Window" accessibility issues.\n\nPlease specify a more appropriate element using the "as" attribute. For example:\n\n<Link href="${href}" method="${method}" as="button">...</Link>`,
        )
      }

      return h(
        props.as,
        {
          ...attrs,
          ...(as === 'a' ? { href } : {}),
          onClick: (event) => {
            if (shouldIntercept(event)) {
              event.preventDefault()

              router.visit(href, {
                data,
                method,
                replace: props.replace,
                preserveScroll: props.preserveScroll,
                preserveState: props.preserveState ?? method !== 'get',
                only: props.only,
                headers: props.headers,
                onCancelToken: attrs.onCancelToken || (() => ({})),
                onBefore: attrs.onBefore || (() => ({})),
                onStart: attrs.onStart || (() => ({})),
                onProgress: attrs.onProgress || (() => ({})),
                onFinish: attrs.onFinish || (() => ({})),
                onCancel: attrs.onCancel || (() => ({})),
                onSuccess: attrs.onSuccess || (() => ({})),
                onError: attrs.onError || (() => ({})),
              })
            }
          },
        },
        slots,
      )
    }
  },
})

export default TypedLink
