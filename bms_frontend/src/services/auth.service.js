// Authentication service
import { api } from './api.js';

export const authService = {
  async login(credentials) {
    return api.post('/login', credentials);
  },

  async register(userData) {
    return api.post('/register', userData);
  },

  async logout() {
    return api.post('/logout');
  },

  // Add more auth methods as needed
};
