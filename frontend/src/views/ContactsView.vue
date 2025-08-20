<template>
  <div class="contacts-bg">
    <h1 class="contacts-title">Contatos</h1>
    <div class="contacts-container">
      <div class="contacts-header">
        <input
          class="search-input"
          type="text"
          placeholder="Buscar contato"
          v-model="search"
        />
        <div style="display: flex; gap: 12px;">
          <AddContactButton @click="openCreateModal" />
          <button class="report-btn" @click="generateReport">
            <svg class="report-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M3 3v18h18" stroke="#3F37C9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <rect x="7" y="13" width="2" height="5" fill="#3F37C9"/>
              <rect x="11" y="9" width="2" height="9" fill="#3F37C9"/>
              <rect x="15" y="5" width="2" height="13" fill="#3F37C9"/>
            </svg>
          </button>
        </div>
      </div>
      <div class="contacts-table-header">
        <span class="col-name">Nome</span>
        <span class="col-email">Email</span>
        <span class="col-phone">Telefone</span>
      </div>
      <ContactsEmpty v-if="filteredContacts.length === 0" />
      <div v-else class="contacts-list">
        <ContactRow
          v-for="contact in filteredContacts"
          :key="contact.id"
          :contact="contact"
          @view="openViewModal"
          @edit="openEditModal"
          @delete="openDeleteModal"
        />
      </div>
      <ContactModal
        v-model="showCreateModal"
        title="Adicionar novo contato"
        @save="fetchContacts"
      />
      <ContactModal
        v-model="showEditModal"
        :contact="editingContact ? { ...editingContact, email: editingContact.email ?? '' } : undefined"
        title="Editar contato"
        @save="fetchContacts"
        @update:modelValue="val => { if (!val) closeEditModal() }"
      />
      <ContactViewModal
        v-model="showViewModal"
        :contact="viewingContact ? {
          ...viewingContact,
          email: viewingContact.email ?? '',
          address: viewingContact.address ?? '',
          city: viewingContact.city ?? '',
          state: viewingContact.state ?? ''
        } : undefined"
        @edit="openEditModal"
        @delete="deleteContact"
      />
      <ContactDeleteModal
        v-model="showDeleteModal"
        :contact="deletingContact"
        @deleted="fetchContacts"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ContactsService, type Contact as BaseContact } from '@/services/contacts'
import ContactRow from '@/components/ContactRow.vue'
import ContactsEmpty from '@/components/ContactsEmpty.vue'
import AddContactButton from '@/components/AddContactButton.vue'
import { useRouter } from 'vue-router'
import ContactModal from '@/components/ContactModal.vue'
import ContactViewModal from '@/components/ContactViewModal.vue'
import ContactDeleteModal from '@/components/ContactDeleteModal.vue'

interface Contact extends BaseContact {
  photo?: string
  disabled?: boolean
}

const search = ref('')
const contacts = ref<Contact[]>([])
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)
const showDeleteModal = ref(false)
const editingContact = ref<Contact | null>(null)
const viewingContact = ref<Contact | null>(null)
const deletingContact = ref<Contact | null>(null)

const fetchContacts = async () => {
  const { data } = await ContactsService.list()
  contacts.value = data
}

onMounted(fetchContacts)

const filteredContacts = computed(() =>
  contacts.value
    .filter(
      contact =>
        (!!contact.name && contact.name.toLowerCase().includes(search.value.toLowerCase())) ||
        (!!contact.email && contact.email.toLowerCase().includes(search.value.toLowerCase())) ||
        (!!contact.phone && contact.phone.toLowerCase().includes(search.value.toLowerCase()))
    )
    .map(contact => ({
      id: contact.id,
      name: contact.name,
      email: contact.email!,
      phone: contact.phone!,
      photo: contact.photo,
      disabled: contact.disabled
    }))
)
const router = useRouter()
const generateReport = () => {
  router.push({ name: 'reports' })
}
function openCreateModal() {
  showCreateModal.value = true
}
function openEditModal(contact: Contact) {
  editingContact.value = contact
  showEditModal.value = true
}
function closeEditModal() {
  showEditModal.value = false
  editingContact.value = null
}
function openViewModal(contact: Contact) {
  viewingContact.value = contact
  showViewModal.value = true
}
function closeViewModal() {
  showViewModal.value = false
  viewingContact.value = null
}
function openDeleteModal(contact: Contact) {
  deletingContact.value = contact
  showDeleteModal.value = true
}
function closeDeleteModal() {
  showDeleteModal.value = false
  deletingContact.value = null
}
function deleteContact(contact: Contact) {
  // lógica para deletar
  showViewModal.value = false
}
</script>

<style scoped>
.contacts-bg {
  min-height: 100vh;
  background: #f5f5f5;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 10px;
}
.contacts-title {
  font-size: 1.5rem;
  font-weight: 400;
  color: #222;
  margin-bottom: 10px;
  width: 930px;
  text-align: left;
}
.contacts-container {
  background: #fff;
  border-radius: 16px;
  border: 1px solid #e1e1e1;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  width: 930px;
  height: 100%;
  max-height: 930px;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.contacts-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}
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
.contacts-toolbar {
  margin-bottom: 8px;
}
.search-input {
  width: 320px;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 1rem;
  background: #fafafa;
}
.contacts-table-header {
  display: flex;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #e1e1e1;
  color: #888;
  font-size: 1rem;
  font-weight: 500;
  gap: 0;
}
.col-name {
  flex: 2;
  min-width: 120px;
}
.col-email {
  flex: 2;
  min-width: 180px;
}
.col-phone {
  flex: 1;
  min-width: 120px;
}
.contacts-list {
  display: flex;
  flex-direction: column;
  gap: 0;
}
.contact-row {
  display: flex;
  align-items: center;
  padding: 16px 0;
  border-bottom: 1px solid #f0f0f0;
  font-size: 1rem;
  color: #222;
}
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
.report-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}
</style>
