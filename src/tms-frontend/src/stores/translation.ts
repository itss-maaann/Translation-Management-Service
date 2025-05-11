import { defineStore } from 'pinia';
import * as translationsAPI from '@/api/translations';
import type { Translation } from '@/api/translations';

interface State {
  items: Translation[];
  total: number;
  perPage: number;
  page: number;
  loading: boolean;
  error: string;
  current: Translation | null;
}

export const useTranslationStore = defineStore('translation', {
  state: (): State => ({
    items: [], total: 0, perPage: 15, page: 1, loading: false, error: '', current: null
  }),
  actions: {
    async fetch(page = 1) {
      this.loading = true; this.error = '';
      try {
        const { data, meta } = await translationsAPI.fetchTranslations(this.perPage, page);
        this.items = data; this.total = meta.total; this.page = page;
      } catch (err: any) { this.error = err.message || 'Failed fetching'; }
      finally { this.loading = false; }
    },
    async search(q: string, page = 1) {
      this.loading = true; this.error = '';
      try {
        const { data, meta } = await translationsAPI.searchTranslations(q, this.perPage, page);
        this.items = data; this.total = meta.total; this.page = page;
      } catch (err: any) { this.error = err.message || 'Search failed'; }
      finally { this.loading = false; }
    },
    async get(id: number) {
      this.loading = true; this.error = '';
      try { this.current = await translationsAPI.getTranslation(id); }
      catch (err: any) { this.error = err.message || 'Load failed'; }
      finally { this.loading = false; }
    },
    async create(payload: any) {
      this.loading = true; this.error = '';
      try { const created = await translationsAPI.createTranslation(payload); this.items.unshift(created); }
      catch (err: any) { this.error = err.message || 'Create failed'; }
      finally { this.loading = false; }
    },
    async update(id: number, payload: any) {
      this.loading = true; this.error = '';
      try { const updated = await translationsAPI.updateTranslation(id, payload);
        const idx = this.items.findIndex(i => i.id === id); if (idx !== -1) this.items.splice(idx, 1, updated);
      } catch (err: any) { this.error = err.message || 'Update failed'; }
      finally { this.loading = false; }
    },
    async remove(id: number) {
      this.loading = true; this.error = '';
      try { await translationsAPI.deleteTranslation(id); this.items = this.items.filter(i => i.id !== id); }
      catch (err: any) { this.error = err.message || 'Delete failed'; }
      finally { this.loading = false; }
    },
    async export() {
      try {
        const blob = await translationsAPI.exportTranslations();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'translations.json';
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
      } catch (e) {
        console.error('Export failed', e);
      }
    }
  }
});
