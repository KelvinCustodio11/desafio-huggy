import { ref } from 'vue'
import { ContactsService, Contact } from '@/services/contacts'

const contacts = ref<Contact[]>([])
const loading = ref(false)

export function useContacts() {
  async function fetchContacts() {
    loading.value = true
    try {
      const { data } = await ContactsService.list()
      contacts.value = data
    } finally {
      loading.value = false
    }
  }

  return { contacts, loading, fetchContacts }
}
