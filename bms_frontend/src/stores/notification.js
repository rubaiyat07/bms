// Notification store using Pinia
import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
  }),

  actions: {
    addNotification(notification) {
      this.notifications.push(notification);
    },

    removeNotification(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    },
  },
});
