<template>
  <CardTitle>Contatos</CardTitle>
  <CardBase>
    <CardHeader>
      <div class="search">
        <input
          class="search-input"
          type="text"
          placeholder="Buscar contato"
          v-model="search"
        />
        <span class="search-icon">
          <IconSearch />
        </span>
      </div>
      <div style="display: flex; gap: 12px;">
        <AddContactButton @click="openCreateModal" />
        <button class="report-btn" @click="generateReport">
          <IconReport />
        </button>
      </div>
    </CardHeader>
    <ContactTable
      :contacts="sortedContactsWithRequiredFields"
      :order="order"
      :loading="loading"
      @toggleOrder="toggleOrder"
      @edit="openEditModal"
      @delete="openDeleteModal"
      @view="openViewModal"
      @create="openCreateModal"
    />
    <ContactModal
      v-model="showCreateModal"
      title="Adicionar novo contato"
      @save="createContact"
    />
    <ContactModal
      v-model="showEditModal"
      :contact="editingContact ? { ...editingContact } : undefined"
      title="Editar contato"
      @save="editContact"
      @update:modelValue="val => { if (!val) closeEditModal() }"
    />
    <ContactViewModal
      v-model="showViewModal"
      :contact="viewingContact ? { ...viewingContact } : undefined"
      @edit="openEditModal"
      @delete="openDeleteModal"
      @update:modelValue="val => { if (!val) closeViewModal() }"
    />
    <ContactDeleteModal
      v-model="showDeleteModal"
      :contact="deletingContact"
      @deleted="deleteContact"
      @update:modelValue="val => { if (!val) closeDeleteModal() }"
    />
  </CardBase>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ContactsService, Contact } from '@/services/contacts'
import AddContactButton from '@/components/AddContactButton.vue'
import { useRouter } from 'vue-router'
import ContactModal from '@/components/ContactModal.vue'
import ContactViewModal from '@/components/ContactViewModal.vue'
import ContactDeleteModal from '@/components/ContactDeleteModal.vue'
import IconReport from '@/components/icons/IconReport.vue'
import IconSearch from '@/components/icons/IconSearch.vue'
import ContactTable from '@/components/ContactTable.vue'
import CardBase from '@/components/ui/CardBase.vue'
import CardTitle from '@/components/ui/CardTitle.vue'
import CardHeader from '@/components/ui/CardHeader.vue'

const search = ref('')
const contacts = ref<Contact[]>([])
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)
const showDeleteModal = ref(false)
const editingContact = ref<Contact | null>(null)
const viewingContact = ref<Contact | null>(null)
const deletingContact = ref<Contact | null>(null)
const order = ref<'asc' | 'desc'>('asc')
const loading = ref(false)

const fetchContacts = async () => {
  loading.value = true
  try {
    const { data } = await ContactsService.list()
    contacts.value = data
  } finally {
    loading.value = false
  }
}
const createContact = async (contact: Contact) => {
  try {
    await ContactsService.create(contact)
    await fetchContacts()
  } catch (error) {
    console.error('Erro ao criar contato:', error)
    alert('Erro ao criar contato. Por favor, tente novamente.')
  }
}
const editContact = async (contact: Contact) => {
  try {
    await ContactsService.update(contact.id, contact)
    await fetchContacts()
  } catch (error) {
    console.error('Erro ao editar contato:', error)
    alert('Erro ao editar contato. Por favor, tente novamente.')
  }
}
const deleteContact = async (contact: Contact) => {
  if (!contact?.id) return
  try {
    await ContactsService.delete(contact.id)
    await fetchContacts()
    closeDeleteModal()
  } catch (error) {
    console.error('Erro ao deletar contato:', error)
    alert('Erro ao deletar contato. Por favor, tente novamente.')
  }
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
      address: contact.address,
      disabled: contact.disabled
    }))
)

const sortedContacts = computed(() => {
  return [...filteredContacts.value].sort((a, b) => {
    if (order.value === 'asc') {
      return a.name.localeCompare(b.name)
    } else {
      return b.name.localeCompare(a.name)
    }
  })
})

const sortedContactsWithRequiredFields = computed(() =>
  sortedContacts.value.map(contact => ({
    id: contact.id,
    name: contact.name,
    email: contact.email ?? '',
    phone: contact.phone ?? '',
    photo: contact.photo ?? '',
    address: contact.address ?? { street: '', neighborhood: '', city: '', state: '' },
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
  closeAllModels()
  editingContact.value = contact
  showEditModal.value = true
}
function closeEditModal() {
  showEditModal.value = false
  editingContact.value = null
}
function openViewModal(contact: Contact) {
  closeAllModels()
  viewingContact.value = contact
  showViewModal.value = true
}
function closeViewModal() {
  showViewModal.value = false
  viewingContact.value = null
}
function openDeleteModal(contact: Contact) {
  closeAllModels()
  deletingContact.value = contact
  showDeleteModal.value = true
}
function closeDeleteModal() {
  showDeleteModal.value = false
  deletingContact.value = null
}
function closeAllModels() {
  closeEditModal()
  closeDeleteModal()
  closeViewModal()
}
function toggleOrder() {
  order.value = order.value === 'asc' ? 'desc' : 'asc'
}
</script>

<style scoped>
.contacts-bg {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 10px;
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
.search {
  position: relative;
  display: flex;
  align-items: center;
}
.search-icon {
  position: absolute;
  left: 12px;
  top: 55%;
  transform: translateY(-50%);
}
.plus-icon {
  font-size: 1.4em;
}
.contacts-toolbar {
  margin-bottom: 8px;
}
.search-input {
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #ddd;
  padding-left: 38px;
  background-color: #f8f8f8;
}
.col-name {
  width: 30%;
  min-width: 120px;
}
.col-email {
  width: 35%;
  min-width: 180px;
}
.col-phone {
  width: 20%;
  min-width: 120px;
}
.col-action {
  width: 15%;
  min-width: 120px;
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
