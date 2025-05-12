import { defineStore } from 'pinia'
import router from '@/router'
import * as authAPI from '@/api/auth'

interface State {
  token: string | null
  userEmail: string | null
  loading: boolean
  error: string | null
}

export const useAuthStore = defineStore('auth', {
  state: (): State => ({
    token: localStorage.getItem('tms_token'),
    userEmail: null,
    loading: false,
    error: null,
  }),

  actions: {
    async login(email: string, password: string) {
      this.loading = true
      this.error = null
      try {
        await authAPI.login({ email, password })
        this.token = localStorage.getItem('tms_token')
        this.userEmail = email
        router.push({ path: '/locales' })
      } catch (err: any) {
        this.error = err.response?.data?.message || 'Login failed'
        throw err
      } finally {
        this.loading = false
      }
    },

    async logout() {
      this.loading = true
      this.error = null
      try {
        await authAPI.logout()
      } catch {
      } finally {
        localStorage.removeItem('tms_token')
        this.token = null
        this.userEmail = null
        this.loading = false
        router.push({ path: '/login' })
      }
    },
  },

  getters: {
    isAuthenticated: (state) => !!state.token,
  },
})
