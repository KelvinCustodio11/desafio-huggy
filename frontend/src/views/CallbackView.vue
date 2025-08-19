<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { api } from '@/services/api'

const router = useRouter()
const route = useRoute()

onMounted(async () => {
  const code = route.query.code as string
  if (code) {
    const { data } = await api.post('/auth/huggy/exchange', { code })
    localStorage.setItem('token', data.api_token)
    router.push('/contacts')
  } else {
    router.push('/login')
  }
})
</script>

<template>
  <div class="flex items-center justify-center h-screen">
    <p>Processando login com a huggy...</p>
  </div>
</template>
