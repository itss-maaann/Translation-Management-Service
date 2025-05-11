import { defineStore } from 'pinia';
import * as tagsAPI from '@/api/tags';
import type { Tag } from '@/api/tags';

interface State {
  items: Tag[];
  total: number;
  perPage: number;
  page: number;
  loading: boolean;
  error: string;
  current: Tag | null;
}

export const useTagStore = defineStore('tag', {
  state: (): State => ({
    items: [],
    total: 0,
    perPage: 15,
    page: 1,
    loading: false,
    error: '',
    current: null
  }),
  actions: {
    async fetch(page = 1) {
      this.loading = true;
      this.error = '';
      try {
        const { data, meta } = await tagsAPI.fetchTags(
          this.perPage,
          page
        );
        this.items = data;
        this.total = meta.total;
        this.page = page;
      } catch (err: any) {
        this.error = err.message || 'Failed to fetch tags';
      } finally {
        this.loading = false;
      }
    },
    async get(id: number) {
      this.loading = true;
      this.error = '';
      try {
        this.current = await tagsAPI.getTag(id);
      } catch (err: any) {
        this.error = err.message || 'Failed to load tag';
      } finally {
        this.loading = false;
      }
    },
    async create(payload: { name: string }) {
      this.loading = true;
      this.error = '';
      try {
        const created = await tagsAPI.createTag(payload);
        this.items.unshift(created);
      } catch (err: any) {
        this.error = err.message || 'Failed to create tag';
      } finally {
        this.loading = false;
      }
    },
    async update(id: number, payload: Partial<{ name: string }>) {
      this.loading = true;
      this.error = '';
      try {
        const updated = await tagsAPI.updateTag(id, payload);
        const idx = this.items.findIndex(i => i.id === id);
        if (idx !== -1) this.items.splice(idx, 1, updated);
      } catch (err: any) {
        this.error = err.message || 'Failed to update tag';
      } finally {
        this.loading = false;
      }
    },
    async remove(id: number) {
      this.loading = true;
      this.error = '';
      try {
        await tagsAPI.deleteTag(id);
        this.items = this.items.filter(i => i.id !== id);
      } catch (err: any) {
        this.error = err.message || 'Failed to delete tag';
      } finally {
        this.loading = false;
      }
    }
  }
});
