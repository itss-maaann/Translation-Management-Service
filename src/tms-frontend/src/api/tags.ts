import { api } from './axios';

export interface Tag {
  id: number;
  name: string;
}

interface Paginated<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export function fetchTags(perPage: number = 15, page: number = 1)
  : Promise<Paginated<Tag>> {
  return api
    .get<{ status: string; message: string; data: Paginated<Tag> }>(
      '/tags',
      { params: { per_page: perPage, page } }
    )
    .then(res => res.data.data);
}

export function getTag(id: number): Promise<Tag> {
  return api
    .get<{ status: string; message: string; data: Tag }>(`/tags/${id}`)
    .then(res => res.data.data);
}

export function createTag(payload: { name: string }): Promise<Tag> {
  return api
    .post<{ status: string; message: string; data: Tag }>(
      '/tags',
      payload
    )
    .then(res => res.data.data);
}

export function updateTag(
  id: number,
  payload: Partial<{ name: string }>
): Promise<Tag> {
  return api
    .put<{ status: string; message: string; data: Tag }>(
      `/tags/${id}`,
      payload
    )
    .then(res => res.data.data);
}

export function deleteTag(id: number): Promise<void> {
  return api.delete(`/tags/${id}`).then(() => {});
}
