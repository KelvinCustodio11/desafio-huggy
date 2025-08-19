// src/composables/useAuth.ts
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '@/services/api'

const token = ref<string | null>(localStorage.getItem('token'))
const user = ref<object | null>(null)

export function useAuth() {
  const router = useRouter()

  async function login(email: string, password: string) {
    try {
      const { data } = await api.post('/auth/login', { email, password })
      token.value = data.token
      localStorage.setItem('token', data.token)

      user.value = data.user // Laravel pode retornar user logado
      router.push('/contacts')
    } catch (error) {
      console.error('Erro ao fazer login:', error)
      throw error
    }
  }

  function logout() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    router.push('/login')
  }

  return { token, user, login, logout }
}
