<template>
  <div class="w-full overflow-x-auto">
    <table class="contacts-table min-w-[600px] w-full">
      <thead>
        <tr>
          <th @click="$emit('toggleOrder')" style="cursor:pointer;">
            Nome
            <span class="icon-down">
              <component :is="order === 'asc' ? IconUp : IconDown" />
            </span>
          </th>
          <th>Email</th>
          <th>Telefone</th>
          <th></th>
        </tr>
      </thead>
      <tr>
        <td colspan="4" class="p-0 overflow-hidden">
          <Separator />
        </td>
      </tr>
      <tbody>
        <tr v-if="loading">
          <td colspan="4">
            <Loader />
          </td>
        </tr>
        <tr v-else-if="contacts.length === 0">
          <td colspan="4">
            <ContactsEmpty @openCreateModal="$emit('create')" />
          </td>
        </tr>
        <tr v-else
          v-for="contact in contacts"
          :key="contact.id"
          class="contact-row"
          :class="{ pressed: false, focused: false, disabled: contact.disabled }"
          @mousedown="pressed = true"
          @mouseup="pressed = false"
          @mouseleave="pressed = false"
          @focus="focused = true"
          @blur="focused = false"
          @click="$emit('view', contact)"
        >
          <td>
            <div style="display: flex;">
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
            <span
              v-if="contact.phone && !contact.disabled"
              class="phone-link"
              @click.stop="$emit('call', contact)"
            >
              {{ contact.phone }}
            </span>
            <span v-else>
              {{ contact.phone }}
            </span>
          </td>
          <td>
            <div class="actions-inner" v-if="!contact.disabled">
              <button class="icon-btn" title="Editar" @click.stop="$emit('edit', contact)">
                <IconEdit />
              </button>
              <button class="icon-btn" title="Excluir" @click.stop="$emit('delete', contact)">
                <IconDelete />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ContactAvatar from './ui/Avatar.vue'
import IconEdit from './icons/IconEdit.vue'
import IconDelete from './icons/IconDelete.vue'
import IconDown from './icons/IconDown.vue'
import IconUp from './icons/IconUp.vue'
import ContactsEmpty from './ContactsEmpty.vue'
import Separator from './ui/Separator.vue'
import Loader from './ui/Loader.vue'

defineProps<{
  contacts: Array<{
    id: number|string
    name: string
    email: string
    phone: string
    photo?: string
    disabled?: boolean
  }>,
  order: 'asc' | 'desc',
  loading: boolean
}>()

defineEmits(['toggleOrder', 'create', 'edit', 'delete', 'view', 'call'])

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
  padding: 8px 8px;
  font-size: 0.95rem;
}
@media (max-width: 640px) {
  th, td {
    padding: 6px 4px;
    font-size: 0.85rem;
  }
}
thead th {
  font-weight: 500;
  color: #505050;
}
.contacts-table {
  width: 100%;
  border-collapse: collapse;
}
.contact-row {
  align-items: center;
  font-size: 1rem;
  color: #222;
  background: transparent;
  border-radius: 12px;
  transition: background 0.2s;
  outline: none;
  height: 20px;
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
.contact-row td:first-child {
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}
.contact-row td:last-child {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}
.contact-name {
  flex: 2;
  min-width: 120px;
  color: #222;
  font-weight: 400;
  display: flex;
  align-items: center;
  margin-left:8px;
}
.contact-email {
  min-width: 180px;
}
.contact-phone {
  min-width: 120px;
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
.icon-btn svg {
  width: 16px;
  height: 16px;
}
.actions-header {
  width: 80px;
}
.actions {
  text-align: center;
  vertical-align: middle;
}
.actions-inner {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}
.disabled {
  color: #bbb !important;
}
.phone-link {
  cursor: pointer;
}
.phone-link:hover {
  color: #1d1d70;
  text-decoration: underline;
}
</style>
