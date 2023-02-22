<script lang="ts" setup>
import type { PropType } from 'vue'
import { computed, useAttrs } from 'vue'
import { mergeDataIntoQueryString, shouldIntercept } from '@inertiajs/core'
import { useInertiaTyped } from '../../index.js'

type RequestPayload = Record<string, any>
type Method = 'get' | 'post' | 'put' | 'patch' | 'delete'
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

const props = defineProps({
  to: Object as PropType<App.Route.TypeGet>,
  data: Object as PropType<RequestPayload>,
  as: {
    type: String,
    default: 'a',
  },
  queryStringArrayFormat: {
    type: String as PropType<'brackets' | 'indices'>,
    default: 'brackets',
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
})

const { route, router } = useInertiaTyped()

const as = props.as.toLowerCase()
const method = props.method.toLowerCase() as Method
const routeStr = computed((): string => {
  const to = props.to as App.Route.Type
  return route(to)
})
const dataRaw = computed((): RequestPayload => {
  return props.data || {}
})

const attrs = useAttrs() as Attrs
const [href, data] = mergeDataIntoQueryString(method, routeStr.value || '', dataRaw.value, props.queryStringArrayFormat)

if (as === 'a' && method !== 'get') {
  console.warn(
          `Creating POST/PUT/PATCH/DELETE <a> links is discouraged as it causes "Open Link in New Tab/Window" accessibility issues.\n\nPlease specify a more appropriate element using the "as" attribute. For example:\n\n<Link href="${href}" method="${method}" as="button">...</Link>`,
  )
}

const pushTo = (event) => {
  if (shouldIntercept(event)) {
    event.preventDefault()

    const route = props.to as App.Route.TypeGet
    router.get(route)
  }
}
</script>

<template>
  <component :is="as" :href="href" @click.stop="pushTo">
    <slot />
  </component>
</template>
