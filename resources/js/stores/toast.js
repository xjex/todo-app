import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
  state: () => ({
    toasts: [],
  }),

  actions: {
    show(message, type = 'info', duration = 3000) {
      const id = Date.now();
      const toast = {
        id,
        message,
        type, // 'success', 'error', 'warning', 'info'
        duration,
      };

      this.toasts.push(toast);

      if (duration > 0) {
        setTimeout(() => {
          this.remove(id);
        }, duration);
      }

      return id;
    },

    success(message, duration = 3000) {
      return this.show(message, 'success', duration);
    },

    error(message, duration = 4000) {
      return this.show(message, 'error', duration);
    },

    warning(message, duration = 3000) {
      return this.show(message, 'warning', duration);
    },

    info(message, duration = 3000) {
      return this.show(message, 'info', duration);
    },

    remove(id) {
      const index = this.toasts.findIndex((toast) => toast.id === id);
      if (index > -1) {
        this.toasts.splice(index, 1);
      }
    },

    clear() {
      this.toasts = [];
    },
  },
});

