<template>
  <div
    aria-live="polite"
    aria-atomic="true"
    class="fixed top-0 right-0 z-50 w-full sm:w-96 p-4 space-y-4 pointer-events-none"
  >
    <TransitionGroup
      name="toast"
      tag="div"
      class="space-y-2"
    >
      <div
        v-for="toast in toastStore.toasts"
        :key="toast.id"
        class="pointer-events-auto rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in"
        :class="getToastClasses(toast.type)"
        @click="toastStore.remove(toast.id)"
      >
        <div class="flex-shrink-0">
          <svg
            v-if="toast.type === 'success'"
            class="w-6 h-6 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <svg
            v-else-if="toast.type === 'error'"
            class="w-6 h-6 text-red-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <svg
            v-else-if="toast.type === 'warning'"
            class="w-6 h-6 text-yellow-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            />
          </svg>
          <svg
            v-else
            class="w-6 h-6 text-blue-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium" :class="getTextClasses(toast.type)">
            {{ toast.message }}
          </p>
        </div>
        <button
          @click.stop="toastStore.remove(toast.id)"
          class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToastStore } from '../stores/toast';

const toastStore = useToastStore();

const getToastClasses = (type) => {
  const classes = {
    success: 'bg-white border-l-4 border-green-500',
    error: 'bg-white border-l-4 border-red-500',
    warning: 'bg-white border-l-4 border-yellow-500',
    info: 'bg-white border-l-4 border-blue-500',
  };
  return classes[type] || classes.info;
};

const getTextClasses = (type) => {
  const classes = {
    success: 'text-gray-800',
    error: 'text-gray-800',
    warning: 'text-gray-800',
    info: 'text-gray-800',
  };
  return classes[type] || classes.info;
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

@keyframes slide-in {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-slide-in {
  animation: slide-in 0.3s ease-out;
}
</style>

