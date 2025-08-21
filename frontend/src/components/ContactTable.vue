<template>
  <table class="contacts-table">
    <thead>
      <tr>
        <th>Nome<span class="icon-down"><IconDown/></span></th>
        <th>Email</th>
        <th>Telefone</th>
        <th class="actions-header"></th>
      </tr>
    </thead>
    <tr>
      <td colspan="4" class="separator-row">
        <Separator />
      </td>
    </tr>
    <tbody>
      <tr v-if="contacts.length === 0">
        <td colspan="4">
          <ContactsEmpty @openCreateModal="$emit('create')" />
        </td>
      </tr>
      <tr v-else
        v-for="contact in contacts"
        :key="contact.id"
        class="contact-row"
        :class="{ pressed: false, focused: false, disabled: contact.disabled }"
        tabindex="0"
        @mousedown="pressed = true"
        @mouseup="pressed = false"
        @mouseleave="pressed = false"
        @focus="focused = true"
        @blur="focused = false"
        @click="$emit('view', contact)"
      >
        <td>
          <div style="display: flex; align-items: center;">
            <ContactAvatar :photo="contact.photo" :initials="getInitials(contact.name)" />
            <div class="contact-name" :class="{ disabled: contact.disabled }">
              {{ contact.name }}
            </div>
          </div>
        </td>
        <td class="contact-email" :class="{ disabled: contact.disabled }">
          {{ contact.email }}
        </td>
        <td class="contact-phone" :class="{ disabled: contact.disabled }">
          {{ contact.phone }}
        </td>
        <td class="actions" v-if="!contact.disabled">
          <button class="icon-btn" title="Editar" @click.stop="$emit('edit', contact)">
            <IconEdit />
          </button>
          <button class="icon-btn" title="Excluir" @click.stop="$emit('delete', contact)">
            <IconDelete />
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ContactAvatar from './ContactAvatar.vue'
import IconEdit from './icons/IconEdit.vue'
import IconDelete from './icons/IconDelete.vue'
import IconDown from './icons/IconDown.vue'
import ContactsEmpty from './ContactsEmpty.vue'
import Separator from './Separator.vue'

defineProps<{
  contacts: Array<{
    id: number|string
    name: string
    email: string
    phone: string
    photo?: string
    disabled?: boolean
  }>
}>()

const pressed = ref(false)
const focused = ref(false)

function getInitials(name: string) {
  const names = name.split(' ')
  return names.length > 1
    ? names[0][0] + names[names.length - 1][0]
    : names[0][0]
}
</script>

<style scoped>
th, td {
  text-align: left;
  padding: 8px 12px;
}
thead th {
  font-weight: 500;
  color: #505050;
}
.contact-row {
  align-items: center;
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
.contact-name {
  flex: 2;
  min-width: 120px;
  color: #222;
  font-weight: 400;
  display: flex;
  align-items: center;
  gap: 8px;
}
.contact-email {
  min-width: 180px;
}
.contact-phone {
  min-width: 120px;
}
.separator-row {
  padding: 0
}
.actions {
  display: flex;
  gap: 8px;
  margin-left: 16px;
}
.icon-down {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  vertical-align: middle;
  margin-left: 12px;
  color: #505050;
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
.actions-header {
  width: 80px;
}
.disabled {
  color: #bbb !important;
}
</style>
