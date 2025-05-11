import { api } from './axios';

export interface Translation {
  id: number;
  locale: { id: number; code: string };
  key: string;
  value: string;
  tags: { id: number; name: string }[];
  created_at: string;
  updated_at: string;
}

interface Paginated<T> {
  data: T[];
  meta: { current_page: number; last_page: number; per_page: number; total: number };
}

export function getTranslation(id: number): Promise<Translation> {
  return api.get<{ data: Translation }>(`/translations/${id}`)
            .then(res => res.data.data);
}

export function fetchTranslations(perPage = 15, page = 1)
  : Promise<Paginated<Translation>> {
  return api
    .get<{ data: Paginated<Translation> }>('/translations', { params: { per_page: perPage, page } })
    .then(res => res.data.data);
}

export function searchTranslations(q: string, perPage = 15, page = 1)
  : Promise<Paginated<Translation>> {
  return api
    .get<{ data: Paginated<Translation> }>('/translations/search', { params: { q, per_page: perPage, page } })
    .then(res => res.data.data);
}

export function createTranslation(payload: {
  locale_id: number;
  key: string;
  value: string;
  tags: number[];
}): Promise<Translation> {
  return api.post<{ data: Translation }>('/translations', payload).then(res => res.data.data);
}

export function updateTranslation(
  id: number,
  payload: Partial<{ locale_id: number; key: string; value: string; tags: number[] }>
): Promise<Translation> {
  return api.put<{ data: Translation }>(`/translations/${id}`, payload).then(res => res.data.data);
}

export function deleteTranslation(id: number): Promise<void> {
  return api.delete(`/translations/${id}`).then(() => {});
}

export function exportTranslations(): Promise<Blob> {
  return api
    .get('/translations/export', { responseType: 'blob' })
    .then(res => res.data);
}
