import { api } from './axios';

export interface Locale {
  id: number;
  code: string;
  name: string;
}

interface Paginated<T> {
  data: T[];
  meta: { current_page: number; last_page: number; per_page: number; total: number };
}

/**
 * Fetch paginated locales.
 */
export function fetchLocales(
  perPage: number = 15,
  page: number = 1
): Promise<Paginated<Locale>> {
  return api
    .get<{ status: string; message: string; data: Paginated<Locale> }>(
      '/locales',
      { params: { per_page: perPage, page } }
    )
    .then(res => res.data.data);
}

/**
 * Get a single locale by ID.
 */
export function getLocale(id: number): Promise<Locale> {
  return api
    .get<{ status: string; message: string; data: Locale }>(`/locales/${id}`)
    .then(res => res.data.data);
}

/**
 * Create a new locale.
 */
export function createLocale(payload: { code: string; name: string }): Promise<Locale> {
  return api
    .post<{ status: string; message: string; data: Locale }>(
      '/locales',
      payload
    )
    .then(res => res.data.data);
}

/**
 * Update an existing locale.
 */
export function updateLocale(
  id: number,
  payload: Partial<{ code: string; name: string }>
): Promise<Locale> {
  return api
    .put<{ status: string; message: string; data: Locale }>(
      `/locales/${id}`,
      payload
    )
    .then(res => res.data.data);
}

/**
 * Delete a locale.
 */
export function deleteLocale(id: number): Promise<void> {
  return api.delete(`/locales/${id}`).then(() => {});
}
