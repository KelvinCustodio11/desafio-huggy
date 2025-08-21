<template>
  <BaseModal v-model="open">
    <div class="delete-modal-content">
      <h2 class="delete-title">Excluir este contato?</h2>
      <p class="delete-message">
        Tem certeza que deseja excluir <b>{{ contact?.name }}</b>?<br>
        Esta ação não poderá ser desfeita.
      </p>
      <div class="delete-actions">
        <button class="cancel-btn" @click="close">Cancelar</button>
        <button class="delete-btn" @click="handleDelete" :disabled="loading">
          Excluir
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseModal from './ui/BaseModal.vue'
import { ContactsService } from '@/services/contacts'

interface Contact {
  id: number | string
  name: string
}

const props = defineProps<{
  modelValue: boolean
  contact: Contact
}>()
const emit = defineEmits(['update:modelValue', 'deleted'])

const open = ref(props.modelValue)
const loading = ref(false)

watch(() => props.modelValue, v => open.value = v)
watch(open, v => emit('update:modelValue', v))

function close() {
  open.value = false
}

async function handleDelete() {
  if (!props.contact?.id) return
  loading.value = true
  try {
    await ContactsService.delete(Number(props.contact.id)) //TODO: LEVAR LOGICA PARA VIEW???
    emit('deleted', props.contact)
    close()
  } catch {
    loading.value = false
  }
}
</script>

<style scoped>
.delete-modal-content {
  min-width: 340px;
  text-align: left;
  padding: 16px 0 0 0;
}
.delete-title {
  font-size: 1.25rem;
  font-weight: 500;
  margin-top: -18px;
  margin-bottom: 18px;
  color: #222;
  text-align: left;
}
.delete-message {
  color: #444;
  margin-bottom: 32px;
  font-size: 1rem;
  text-align: left;
}
.delete-actions {
  display: flex;
  justify-content: flex-end;
  gap: 32px;
  margin-right: 16px;
  margin-bottom: 8px;
}
.cancel-btn {
  background: none;
  border: none;
  color: #222;
  font-size: 1rem;
  cursor: pointer;
  padding: 0;
  border-radius: 8px;
  transition: background 0.2s;
}
.cancel-btn:hover {
  background: #f5f5ff;
}
.delete-btn {
  background: none;
  border: none;
  color: #d32f2f;
  font-size: 1rem;
  cursor: pointer;
  padding: 0;
  border-radius: 8px;
  font-weight: 500;
  transition: background 0.2s;
}
.delete-btn:disabled {
  color: #bbb;
  cursor: not-allowed;
}
</style>
