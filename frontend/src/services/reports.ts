import { api } from './api'

export interface ReportClientData {
  clients_by_city: { city: string; total: number }[]
  clients_by_state: { state: string; total: number }[]
  clients_by_age: { age: number; total: number }[]
}

export const ReportsService = {
  getClients: () => api.get<ReportClientData>('/reports?type=clients')
}
