import { defineStore } from 'pinia';
import * as authAPI from '@/api/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({ token: localStorage.getItem('token') || '' }),
  actions: {
    async login(credentials: { email: string; password: string }) {
      const { data } = await authAPI.login(credentials);
      this.token = data.data.token;
      localStorage.setItem('token', this.token);
    },
    async logout() {
      try {
        await authAPI.logout();
      } catch (e) {
        console.warn('Logout API failed', e);
      }
      localStorage.removeItem('token');
      this.token = '';
    }
  }
});
