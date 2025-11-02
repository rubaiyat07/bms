// Auth store using Pinia
import { defineStore } from 'pinia';
import { authService } from '../services/auth.service.js';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    isInitialized: false,
  }),

  actions: {
    async initialize() {
      // Initialize auth state from localStorage
      const token = localStorage.getItem('auth_token');
      const userData = localStorage.getItem('user_data');
      if (token && userData) {
        // Validate token before setting auth state
        try {
          const isValid = await this.validateToken();
          if (isValid) {
            this.isAuthenticated = true;
            this.user = JSON.parse(userData);
          } else {
            // Token is invalid, clear localStorage
            this.logout();
          }
        } catch (error) {
          console.error('Token validation failed:', error);
          this.logout();
        }
      }
      // Mark initialization as complete
      this.isInitialized = true;
    },

    async validateToken() {
      try {
        const response = await authService.validateToken();
        return response.success;
      } catch (error) {
        console.error('Token validation error:', error);
        return false;
      }
    },

    async login(credentials) {
      try {
        const response = await authService.login(credentials);

        if (response.success) {
          this.isAuthenticated = true;
          this.user = response.data.user;

          // Persist to localStorage
          localStorage.setItem('auth_token', response.data.token);
          localStorage.setItem('user_data', JSON.stringify(this.user));

          return { success: true };
        } else {
          return { success: false, message: response.message };
        }
      } catch (error) {
        console.error('Login error:', error);
        return { success: false, message: error.message || 'Login failed' };
      }
    },

    logout() {
      this.user = null;
      this.isAuthenticated = false;
      // Clear localStorage
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user_data');
    },
  },
});
