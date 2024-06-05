<script lang="ts" setup>
import { useNotification } from '../../composables/useNotification'

const { notifications } = useNotification()
</script>

<template>
  <div
    aria-live="assertive"
    class="notifications-container"
  >
    <div class="notifications-container_wrapper">
      <template v-if="notifications.length">
        <Notification
          v-for="notification in notifications"
          :key="notification.id"
          :notification="notification"
        />
      </template>
    </div>
  </div>
</template>

<style lang="css" scoped>
.notifications-container {
  @apply pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6;
  z-index: 9999;
}

.notifications-container_wrapper {
  @apply flex w-full flex-col items-center space-y-4 sm:items-end;
}
</style>
