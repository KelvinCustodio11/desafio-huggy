<script setup lang="ts">
import { api } from '@/services/api'

async function loginWithHuggy() {
  try {
    const { data } = await api.get('/auth/huggy/login')
    if (data.authorize_url) {
      window.location.href = data.authorize_url
    }
  } catch (err) {
    console.error('Erro ao iniciar login com Huggy', err)
  }
}
</script>

<template>
  <div class="login-bg">
    <div class="login-card">
      <h1 class="login-title">Login</h1>
      <button class="login-btn" @click="loginWithHuggy">
        Fazer login com a Huggy
      </button>
    </div>
  </div>
</template>

<style scoped>
.login-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.login-title {
  font-size: 2rem;
  font-weight: 400;
  color: #222;
  margin-bottom: 32px;
  text-align: center;
  width: 100%;
}
.login-btn {
  min-width: 180px;
  padding: 8px 16px;
  border-radius: 8px;
  background: #3F37C9;
  color: #fff;
  border: none;
  font-size: 1rem;
  font-weight: 400;
  cursor: pointer;
  transition: background 0.2s;
  text-align: center;
}
.login-btn:hover {
  background: #2f2499;
}
</style>
