<template>
  <BaseModal v-model="open">
    <div class="view-header">
      <div class="avatar">
        <template v-if="contact.photo">
          <img :src="contact.photo" alt="Avatar" />
        </template>
        <template v-else>
          <span class="avatar-initials">{{ initials }}</span>
        </template>
      </div>
      <span class="view-title">{{ contact.name }}</span>
      <div class="view-actions">
        <button class="icon-btn" @click="$emit('delete', contact)" title="Deletar">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M3 6h18" stroke="#444" stroke-width="2"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V6h12Z" stroke="#444" stroke-width="2"/></svg>
        </button>
        <button class="icon-btn" @click="$emit('edit', contact)" title="Editar">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M4 21h17" stroke="#444" stroke-width="2"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5Z" stroke="#444" stroke-width="2"/></svg>
        </button>
        <button class="icon-btn" @click="close" title="Fechar">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M6 6l12 12M6 18L18 6" stroke="#444" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>
    </div>
    <div class="modal-line"></div>
    <div class="view-fields">
      <div class="view-row"><span class="label">Email</span><span>{{ contact.email }}</span></div>
      <div class="view-row"><span class="label">Endereço</span><span>{{ contact.address }}</span></div>
      <div class="view-row"><span class="label">Bairro</span><span>{{ contact.neighborhood }}</span></div>
      <div class="view-row"><span class="label">Cidade</span><span>{{ contact.city }}</span></div>
      <div class="view-row"><span class="label">Estado</span><span>{{ contact.state }}</span></div>
    </div>
  </BaseModal>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import BaseModal from './ui/BaseModal.vue'

interface Contact {
  name: string
  email: string
  address: string
  city: string
  state: string
  photo?: string
}

const props = defineProps<{
  modelValue: boolean
  contact: Contact
}>()
const emit = defineEmits(['update:modelValue', 'edit', 'delete'])

const open = ref(props.modelValue)
watch(() => props.modelValue, v => open.value = v)
watch(open, v => emit('update:modelValue', v))

const initials = computed(() => {
  if (!props.contact?.name) return ''
  const names = props.contact.name.split(' ')
  return names.length > 1
    ? names[0][0] + names[names.length - 1][0]
    : names[0][0]
})

function close() {
  open.value = false
}
</script>

<style scoped>
.view-header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 8px;
}
.avatar {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.avatar img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}
.avatar-initials {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f5f5ff;
  color: #3F37C9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
  border: 1px solid #e1e1e1;
}
.view-title {
  font-size: 1.2rem;
  font-weight: 500;
  color: #222;
  flex: 1;
}
.view-actions {
  display: flex;
  gap: 8px;
}
.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background 0.2s;
}
.icon-btn:hover {
  background: #f0f0f0;
}
.modal-line {
  height: 0;
  border-top: 2px solid #eee;
  margin-bottom: 18px;
  margin-top: 0;
  margin-left: -24px;
  margin-right: -24px;
}
.view-fields {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.view-row {
  display: flex;
  gap: 24px;
  font-size: 1rem;
}
.label {
  color: #888;
  min-width: 80px;
  width: 100px;
  display: inline-block;
}
</style>
