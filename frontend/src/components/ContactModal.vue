<template>
  <BaseModal v-model="open">
    <h2 class="modal-title">{{ title }}</h2>
    <div class="modal-line"></div>
    <ContactForm
      :modelValue="contact"
      :disabled="disabled"
      @submit="handleSubmit"
    >
      <template #actions>
        <div class="modal-line-base"></div>
        <footer class="modal-actions">
          <button type="button" class="cancel-btn" @click="close">Cancelar</button>
          <button type="submit" class="save-btn" :disabled="disabled">Salvar</button>
        </footer>
      </template>
    </ContactForm>
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseModal from './ui/BaseModal.vue'
import ContactForm from './ContactForm.vue'

const props = defineProps<{
  modelValue: boolean
  title?: string
  contact?: {
    id?: number
    name: string
    email: string
    phone?: string
    address: {
      street: string
      neighborhood: string
      city: string
      state: string
    }
    [key: string]: unknown
  }
  disabled?: boolean
}>()
const emit = defineEmits(['update:modelValue', 'save'])

const open = ref(props.modelValue)
watch(() => props.modelValue, v => open.value = v)
watch(open, v => emit('update:modelValue', v))

function handleSubmit(data: {
  id?: number
  name: string
  email: string
  phone?: string
  address: {
    street: string
    neighborhood: string
    city: string
    state: string
  }
  [key: string]: unknown
}) {
  emit('save', data)
  close()
}
function close() {
  open.value = false
}
</script>

<style scoped>
.modal-title {
  font-size: 1.2rem;
  font-weight: 500;
  margin-bottom: 18px;
  margin-top: -10px;
  color: #222;
}
.modal-line {
  height: 0;
  border-top: 2px solid #eee;
  margin-bottom: 18px;
  margin-top: 0;
  margin-left: -24px;
  margin-right: -24px;
}
.modal-line-base {
  height: 0;
  border-top: 2px solid #eee;
  margin-bottom: -10px;
  margin-top: 18px;
  margin-left: -24px;
  margin-right: -24px;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 18px;
}
.cancel-btn {
  background: none;
  border: none;
  color: #3F37C9;
  font-size: 1rem;
  cursor: pointer;
  padding: 8px 16px;
  border-radius: 8px;
  transition: background 0.2s;
}
.cancel-btn:hover {
  background: #f5f5ff;
}
.save-btn {
  background: #3F37C9;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.save-btn:disabled {
  background: #bbb;
  cursor: not-allowed;
}
</style>
