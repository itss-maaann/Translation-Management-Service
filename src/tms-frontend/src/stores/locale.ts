import { defineStore } from 'pinia'
import * as localesAPI from '@/api/locales'
import type { Locale } from '@/api/locales'

interface Pagination {
  page: number
  perPage: number
  total: number
}

interface State {
  items: Locale[]
  pagination: Pagination
  loading: boolean
  error: string | null
}

export const useLocaleStore = defineStore('locale', {
  state: (): State => ({
    items: [],
    pagination: { page: 1, perPage: 15, total: 0 },
    loading: false,
    error: null,
  }),

  actions: {
    async fetch(opts: { page?: number; perPage?: number } = {}) {
      this.loading = true
      this.error = null
      try {
        const page = opts.page ?? this.pagination.page
        const perPage = opts.perPage ?? this.pagination.perPage
        const { data, meta } = await localesAPI.fetchLocales(perPage, page)
        this.items = data
        this.pagination = {
          page: meta.current_page,
          perPage: meta.per_page,
          total: meta.total,
        }
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Failed to load locales'
      } finally {
        this.loading = false
      }
    },

    async save(payload: { id?: number; code: string; name: string }) {
      this.loading = true
      this.error = null
      try {
        if (payload.id) {
          const updated = await localesAPI.updateLocale(payload.id, { code: payload.code, name: payload.name })
          const idx = this.items.findIndex((l) => l.id === updated.id)
          if (idx !== -1) this.items.splice(idx, 1, updated)
          return updated
        } else {
          const created = await localesAPI.createLocale({ code: payload.code, name: payload.name })
          this.items.unshift(created)
          return created
        }
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Save failed'
        throw err
      } finally {
        this.loading = false
      }
    },

    async remove(id: number) {
      this.loading = true
      this.error = null
      try {
        await localesAPI.deleteLocale(id)
        this.items = this.items.filter((l) => l.id !== id)
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Delete failed'
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
