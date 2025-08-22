<template>
  <div class="loader-container">
    <div class="loader"></div>
  </div>
</template>

<script setup lang="ts">
// Nenhuma lógica necessária para o loader simples
</script>

<style scoped>
.loader-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80px;
  width: 100%;
}
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3F37C9;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>
