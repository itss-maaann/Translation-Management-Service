import { defineStore } from 'pinia';
import * as localesAPI from '@/api/locales';
import type { Locale } from '@/api/locales';

interface State {
  items: Locale[];
  total: number;
  perPage: number;
  page: number;
  loading: boolean;
  error: string;
  current: Locale | null;
}

export const useLocaleStore = defineStore('locale', {
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
        const { data, meta } = await localesAPI.fetchLocales(
          this.perPage,
          page
        );
        this.items = data;
        this.total = meta.total;
        this.page = page;
      } catch (err: any) {
        this.error = err.message || 'Failed to fetch locales';
      } finally {
        this.loading = false;
      }
    },

    async get(id: number) {
      this.loading = true;
      this.error = '';
      try {
        this.current = await localesAPI.getLocale(id);
      } catch (err: any) {
        this.error = err.message || 'Failed to load locale';
      } finally {
        this.loading = false;
      }
    },

    async create(payload: { code: string; name: string }) {
      this.loading = true;
      this.error = '';
      try {
        const created = await localesAPI.createLocale(payload);
        this.items.unshift(created);
      } catch (err: any) {
        this.error = err.message || 'Failed to create locale';
      } finally {
        this.loading = false;
      }
    },

    async update(id: number, payload: Partial<{ code: string; name: string }>) {
      this.loading = true;
      this.error = '';
      try {
        const updated = await localesAPI.updateLocale(id, payload);
        const idx = this.items.findIndex(i => i.id === id);
        if (idx !== -1) this.items.splice(idx, 1, updated);
      } catch (err: any) {
        this.error = err.message || 'Failed to update locale';
      } finally {
        this.loading = false;
      }
    },

    async remove(id: number) {
      this.loading = true;
      this.error = '';
      try {
        await localesAPI.deleteLocale(id);
        this.items = this.items.filter(i => i.id !== id);
      } catch (err: any) {
        this.error = err.message || 'Failed to delete locale';
      } finally {
        this.loading = false;
      }
    }
  }
});
