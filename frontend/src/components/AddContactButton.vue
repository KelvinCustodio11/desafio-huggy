<template>
  <button class="add-btn">
    <span class="plus-icon">+</span> Adicionar contato
  </button>
</template>

<script setup lang="ts">
</script>

<style scoped>
.add-btn {
  background: #3F37C9;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  gap: 6px;
}
.add-btn:hover {
  background: #2f2499;
}
.plus-icon {
  font-size: 1.4em;
}
</style>
