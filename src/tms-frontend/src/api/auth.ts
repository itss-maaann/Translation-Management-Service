import { api } from './axios'

export interface LoginPayload {
  email: string
  password: string
}

export interface LoginResponse {
  status: string
  message: string
  data: { token: string }
}

/**
 * Log in and store token.
 */
export async function login(payload: LoginPayload): Promise<void> {
  const resp = await api.post<LoginResponse>('/login', payload)
  const token = resp.data.data.token
  localStorage.setItem('tms_token', token)
}

/**
 * Log out (server-side token deletion + local cleanup).
 */
export async function logout(): Promise<void> {
  await api.post('/logout')
  localStorage.removeItem('tms_token')
}
