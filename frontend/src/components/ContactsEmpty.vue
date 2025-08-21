<template>
  <div class="empty-state">
    <div class="empty-icon">
      <img src="@/assets/empty-book.png" alt="Sem contatos" width="120" height="120" />
    </div>
    <div class="empty-text">Ainda não há contatos</div>
    <AddContactButton @click="$emit('openCreateModal')" />
  </div>
</template>

<script setup lang="ts">
import AddContactButton from './AddContactButton.vue';

</script>

<style scoped>
.empty-state {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  gap: 24px;
}
.empty-icon {
  margin-bottom: 8px;
}
.empty-text {
  color: #888;
  font-size: 1.1rem;
  margin-bottom: 8px;
}
.empty-btn {
  margin-top: 0;
}
</style>
