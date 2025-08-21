<template>
  <div
    class="contact-row"
    :class="{
      pressed: pressed,
      focused: focused,
      disabled: disabled
    }"
    tabindex="0"
    @mousedown="pressed = true"
    @mouseup="pressed = false"
    @mouseleave="pressed = false"
    @focus="focused = true"
    @blur="focused = false"
    @click="$emit('view', contact)"
  >
    <ContactAvatar :photo="contact.photo" :initials="initials" />
    <span class="contact-name" :class="{ disabled }">
      {{ contact.name }}
    </span>
    <span class="contact-email" :class="{ disabled }">{{ contact.email }}</span>
    <span class="contact-phone" :class="{ disabled }">{{ contact.phone }}</span>
    <div class="actions" v-if="!disabled">
      <button class="icon-btn" title="Editar" @click.stop="$emit('edit', contact)">
        <IconEdit />
      </button>
      <button class="icon-btn" title="Excluir" @click.stop="$emit('delete', contact)">
        <IconDelete />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import IconEdit from './icons/IconEdit.vue';
import IconDelete from './icons/IconDelete.vue';
import ContactAvatar from './ContactAvatar.vue';
const props = defineProps<{
  contact: {
    name: string
    email: string
    phone: string
    photo?: string
    disabled?: boolean
  }
}>()

const pressed = ref(false)
const focused = ref(false)
const disabled = computed(() => props.contact.disabled)

const initials = computed(() => {
  const names = props.contact.name.split(' ')
  return names.length > 1
    ? names[0][0] + names[names.length - 1][0]
    : names[0][0]
})
</script>

<style scoped>
.contact-row {
  display: flex;
  align-items: center;
  padding: 12px 0;
  font-size: 1rem;
  color: #222;
  background: transparent;
  border-radius: 12px;
  transition: background 0.2s;
  outline: none;
  height: 60px;
}
.contact-row:hover {
  background: #f5f5ff;
}
.contact-row.pressed,
.contact-row.focused {
  background: #ededed;
}
.contact-row.disabled {
  pointer-events: none;
  opacity: 0.6;
}
.avatar {
  margin-left: 16px;
  width: 40px;
  height: 40px;
  margin-right: 16px;
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
  background: #ffffff;
  color: #3F37C9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
}
.contact-name {
  flex: 2;
  min-width: 120px;
  color: #222;
  font-weight: 400;
  display: flex;
  align-items: center;
  gap: 8px;
}
.contact-name b {
  color: #3F37C9;
  margin-right: 6px;
}
.contact-email {
  flex: 2;
  min-width: 180px;
}
.contact-phone {
  flex: 1;
  min-width: 120px;
}
.actions {
  display: flex;
  gap: 8px;
  margin-left: 16px;
}
.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background 0.2s;
  margin-right: 12px;
}
.icon-btn:hover {
  background: #f0f0f0;
}
.disabled {
  color: #bbb !important;
}
</style>
