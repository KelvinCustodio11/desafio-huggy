<script setup lang="ts">
import { api } from '@/services/api'

async function loginWithHuggy() {
  try {
    // Chama backend que retorna a authorize_url
    const { data } = await api.get('/auth/huggy/login')
    if (data.authorize_url) {
      window.location.href = data.authorize_url // redireciona para Huggy
    }
  } catch (err) {
    console.error('Erro ao iniciar login com Huggy', err)
  }
}
</script>

<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow w-96 text-center">
      <h1 class="text-xl font-bold mb-6">Login</h1>

      <button
        @click="loginWithHuggy"
        class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition w-full"
      >
        Fazer login com a Huggy
      </button>
    </div>
  </div>
</template>
