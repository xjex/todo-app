import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
  },

  actions: {
    setAuth(token, user) {
      this.token = token;
      this.user = user;
      localStorage.setItem('auth_token', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('auth_token');
      delete axios.defaults.headers.common['Authorization'];
    },

    async register(userData) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post('/register', userData);
        this.setAuth(response.data.data.token, response.data.data.user);
        return { success: true };
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed';
        return {
          success: false,
          errors: error.response?.data?.errors || {},
        };
      } finally {
        this.loading = false;
      }
    },

    async login(credentials) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post('/login', credentials);
        this.setAuth(response.data.data.token, response.data.data.user);
        return { success: true };
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed';
        return {
          success: false,
          errors: error.response?.data?.errors || {},
        };
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      this.loading = true;

      try {
        await axios.post('/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.clearAuth();
        this.loading = false;
      }
    },

    async fetchUser() {
      if (!this.token) return;

      try {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        const response = await axios.get('/me');
        this.user = response.data.data;
      } catch (error) {
        this.clearAuth();
      }
    },
  },
});

