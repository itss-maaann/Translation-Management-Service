import { api } from './axios';

export function login(payload: { email: string; password: string }) {
  return api.post('/login', payload);
}

export function logout() {
  return api.post('/logout');
}
