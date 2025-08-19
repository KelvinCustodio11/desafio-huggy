// src/services/contacts.ts
import { api } from './api'

export interface Contact {
  id: number
  name: string
  email: string
  phone: string
  address?: string
  city?: string
  state?: string
}

export const ContactsService = {
  list: () => api.get<Contact[]>('/contacts'),
  create: (data: Partial<Contact>) => api.post('/contacts', data),
  update: (id: number, data: Partial<Contact>) => api.put(`/contacts/${id}`, data),
  remove: (id: number) => api.delete(`/contacts/${id}`)
}
