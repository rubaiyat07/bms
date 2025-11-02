// Authentication service
import { api } from './api.js';

export const authService = {
  async login(credentials) {
    return api.post('/auth/login', credentials);
  },

  async register(userData) {
    return api.post('/auth/register', userData);
  },

  async logout() {
    return api.post('/auth/logout');
  },

  async validateToken() {
    return api.get('/auth/user');
  },

  // Add more auth methods as needed
};
