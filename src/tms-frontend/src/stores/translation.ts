import { defineStore } from 'pinia'
import * as translationsAPI from '@/api/translations'
import type { Translation } from '@/api/translations'

interface Pagination {
  page: number
  perPage: number
  total: number
}

interface State {
  items: Translation[]
  pagination: Pagination
  loading: boolean
  error: string | null
  current: Translation | null
}

export const useTranslationStore = defineStore('translation', {
  state: (): State => ({
    items: [],
    pagination: { page: 1, perPage: 15, total: 0 },
    loading: false,
    error: null,
    current: null,
  }),

  actions: {
    async fetch(opts: { page?: number; perPage?: number; sortBy?: string; sortOrder?: string } = {}) {
      this.loading = true
      this.error = null
      try {
        const page = opts.page ?? this.pagination.page
        const perPage = opts.perPage ?? this.pagination.perPage
        const response = await translationsAPI.fetchTranslations(perPage, page)
        this.items = response.data
        this.pagination = {
          page: response.meta.current_page,
          perPage: response.meta.per_page,
          total: response.meta.total,
        }
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Failed to load translations'
      } finally {
        this.loading = false
      }
    },

    async search(q: string, opts: { page?: number; perPage?: number } = {}) {
      this.loading = true
      this.error = null
      try {
        const page = opts.page ?? this.pagination.page
        const perPage = opts.perPage ?? this.pagination.perPage
        const response = await translationsAPI.searchTranslations(q, perPage, page)
        this.items = response.data
        this.pagination = {
          page: response.meta.current_page,
          perPage: response.meta.per_page,
          total: response.meta.total,
        }
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Failed to search translations'
      } finally {
        this.loading = false
      }
    },

    async get(id: number) {
      this.loading = true
      this.error = null
      try {
        this.current = await translationsAPI.getTranslation(id)
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Failed to load translation'
      } finally {
        this.loading = false
      }
    },

    async save(payload: { id?: number; locale_id: number; key: string; value: string; tags: number[] }) {
      this.loading = true
      this.error = null
      try {
        if (payload.id) {
          const updated = await translationsAPI.updateTranslation(payload.id, payload)
          const idx = this.items.findIndex((t) => t.id === updated.id)
          if (idx !== -1) this.items.splice(idx, 1, updated)
          return updated
        } else {
          const created = await translationsAPI.createTranslation(payload)
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
        await translationsAPI.deleteTranslation(id)
        this.items = this.items.filter((t) => t.id !== id)
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Delete failed'
        throw err
      } finally {
        this.loading = false
      }
    },

    async export() {
      this.loading = true
      this.error = null
      try {
        const blob = await translationsAPI.exportTranslations()
        const url = URL.createObjectURL(blob)
        const a = document.createElement('a')
        a.href = url
        a.download = 'translations.json'
        document.body.appendChild(a)
        a.click()
        a.remove()
        URL.revokeObjectURL(url)
      } catch (err: any) {
        this.error = err.message || 'Export failed'
      } finally {
        this.loading = false
      }
    },
  },
})
