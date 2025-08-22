// src/services/contacts.ts
import { api } from './api'

export interface Contact {
  id: number
  name: string
  email?: string
  phone?: string
  address?: string
  city?: string
  state?: string
  photo?: string
  disabled?: boolean
}

export const ContactsService = {
  list: () => api.get<Contact[]>('/clients'),
  create: (data: Partial<Contact>) => api.post('/clients', data),
  update: (id: number, data: Partial<Contact>) => api.put(`/clients/${id}`, data),
  delete: (id: number) => api.delete(`/clients/${id}`)
}
