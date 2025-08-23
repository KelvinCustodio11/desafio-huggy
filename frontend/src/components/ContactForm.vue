<template>
  <form class="modal-form" @submit.prevent="submit">
    <div class="form-group">
      <label>Nome</label>
      <input
        v-model="localForm.name"
        :class="inputClass('name')"
        placeholder="Nome completo"
        :disabled="disabled"
      />
      <span v-if="errors.name" class="error-msg">{{ errors.name }}</span>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input
        v-model="localForm.email"
        :class="inputClass('email')"
        placeholder="Email"
        :disabled="disabled"
      />
      <span v-if="errors.email" class="error-msg">{{ errors.email }}</span>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Telefone</label>
        <input
          v-model="localForm.phone"
          :class="inputClass('phone')"
          placeholder="Telefone"
          :disabled="disabled"
        />
        <span v-if="errors.phone" class="error-msg">{{ errors.phone }}</span>
      </div>
      <div class="form-group">
        <label>Celular</label>
        <input
          v-model="localForm.cell"
          :class="inputClass('cell')"
          placeholder="Celular"
          :disabled="disabled"
        />
      </div>
    </div>
    <div class="form-group">
      <label>Endereço</label>
      <input
        v-model="localForm.address.street"
        :class="inputClass('address')"
        placeholder="Endereço"
        :disabled="disabled"
      />
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Bairro</label>
        <input
          v-model="localForm.address.neighborhood"
          :class="inputClass('neighborhood')"
          placeholder="Bairro"
          :disabled="disabled"
        />
      </div>
      <div class="form-group">
        <label>Cidade</label>
        <input
          v-model="localForm.address.city"
          :class="inputClass('city')"
          placeholder="Cidade"
          :disabled="disabled"
        />
      </div>
      <div class="form-group">
        <label>Estado</label>
        <input
          v-model="localForm.address.state"
          :class="inputClass('state')"
          placeholder="Estado"
          :disabled="disabled"
        />
      </div>
    </div>
    <slot name="actions" :submit="submit" />
  </form>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'

type ContactFormData = {
  id: string,
  name: string,
  email: string,
  phone: string,
  cell: string,
  address: {
    street: string,
    neighborhood: string,
    city: string,
    state: string,
  }
}

const props = defineProps<{
  modelValue?: Partial<ContactFormData>
  disabled?: boolean
}>()
const emit = defineEmits(['submit'])

const localForm = reactive<ContactFormData>({
  id: '',
  name: '',
  email: '',
  phone: '',
  cell: '',
  address: {
    street: '',
    neighborhood: '',
    city: '',
    state: ''
  }
})
const errors = reactive<{ [k: string]: string }>({})

watch(
  () => props.modelValue,
  (val) => {
    if (val) {
      localForm.id = val.id ?? ''
      localForm.name = val.name ?? ''
      localForm.email = val.email ?? ''
      localForm.phone = val.phone ?? ''
      localForm.cell = val.cell ?? ''
      localForm.address.street = val.address?.street ?? ''
      localForm.address.neighborhood = val.address?.neighborhood ?? ''
      localForm.address.city = val.address?.city ?? ''
      localForm.address.state = val.address?.state ?? ''
    } else {
      localForm.id = ''
      localForm.name = ''
      localForm.email = ''
      localForm.phone = ''
      localForm.cell = ''
      localForm.address.street = ''
      localForm.address.neighborhood = ''
      localForm.address.city = ''
      localForm.address.state = ''
    }
  },
  { immediate: true }
)

function inputClass(field: string) {
  return [
    'modal-input',
    errors[field] ? 'error' : '',
    props.disabled ? 'disabled' : ''
  ]
}

function validate() {
  errors.name = localForm.name ? '' : 'Campo obrigatório'
  errors.email = localForm.email ? '' : 'Campo obrigatório'
  errors.phone = localForm.phone ? '' : 'Campo obrigatório'
  return !errors.name && !errors.email && !errors.phone
}

function submit() {
  if (validate()) {
    emit('submit', localForm )
  }
}
</script>

<style scoped>
/* Reaproveite o CSS do seu modal-form, form-group, etc */
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.form-row {
  display: flex;
  gap: 12px;
}
.form-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
label {
  font-size: 0.95rem;
  color: #888;
}
.modal-input {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 8px 12px;
  font-size: 1rem;
  background: #fafafa;
  transition: border 0.2s;
}
.modal-input:focus {
  border-color: #3F37C9;
  outline: none;
}
.modal-input.error {
  border-color: #d32f2f;
  background: #fff0f0;
}
.modal-input.disabled {
  background: #f5f5f5;
  color: #bbb;
}
.error-msg {
  color: #d32f2f;
  font-size: 0.85rem;
}
</style>
