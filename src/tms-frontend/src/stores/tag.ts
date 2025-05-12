import { defineStore } from 'pinia'
import * as tagsAPI from '@/api/tags'
import type { Tag } from '@/api/tags'

interface Pagination {
  page: number
  perPage: number
  total: number
}

interface State {
  items: Tag[]
  pagination: Pagination
  loading: boolean
  error: string | null
}

export const useTagStore = defineStore('tag', {
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
        const { data, meta } = await tagsAPI.fetchTags(perPage, page)
        this.items = data
        this.pagination = {
          page: meta.current_page,
          perPage: meta.per_page,
          total: meta.total,
        }
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Failed to load tags'
      } finally {
        this.loading = false
      }
    },

    async save(payload: { id?: number; name: string }) {
      this.loading = true
      this.error = null
      try {
        if (payload.id) {
          const updated = await tagsAPI.updateTag(payload.id, { name: payload.name })
          const idx = this.items.findIndex((t) => t.id === updated.id)
          if (idx !== -1) this.items.splice(idx, 1, updated)
          return updated
        } else {
          const created = await tagsAPI.createTag({ name: payload.name })
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
        await tagsAPI.deleteTag(id)
        this.items = this.items.filter((t) => t.id !== id)
      } catch (err: any) {
        this.error = err.response?.data?.message || err.message || 'Delete failed'
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
