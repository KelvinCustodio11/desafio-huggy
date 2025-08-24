<template>
  <form @submit.prevent="submit" >
    <div>
      <label class="input-label">Nome</label>
      <input
        v-model="localForm.name"
        :class="[inputClass('name'), 'input-text', errors.name ? 'input-error' : '', disabled ? 'disabled' : 'enabled']"
        placeholder="Nome completo"
        :disabled="disabled"
      />
      <span v-if="errors.name" class="text-error">{{ errors.name }}</span>
    </div>
    <div>
      <label class="input-label">Email</label>
      <input
        v-model="localForm.email"
        :class="[inputClass('email'), 'input-text', errors.email ?  'input-error' : '', !disabled ? 'disabled' : '']"
        placeholder="Email"
        :disabled="disabled"
      />
      <span v-if="errors.email" class="text-error">{{ errors.email }}</span>
    </div>
    <div class="row">
      <div>
        <label class="input-label">Telefone</label>
        <input
          v-model="localForm.phone"
            :class="[inputClass('phone'), 'input-text', errors.phone ? 'input-error' : '', disabled ? 'disabled' : 'enabled']"
          placeholder="Telefone"
          :disabled="disabled"
        />
        <span v-if="errors.phone" class="text-error">{{ errors.phone }}</span>
      </div>
      <div>
        <label class="input-label">Celular</label>
        <input
          v-model="localForm.cell"
            :class="[inputClass('cell'), 'input-text', disabled ? 'disabled' : 'enabled']"
          placeholder="Celular"
          :disabled="disabled"
        />
      </div>
    </div>
    <div>
      <label class="input-label">Endereço</label>
      <input
        v-model="localForm.address.street"
        :class="[inputClass('address'), 'input-text', disabled ? 'disabled' : 'enabled']"
        placeholder="Endereço"
        :disabled="disabled"
      />
    </div>
    <row class="row">
      <div>
        <label class="input-label">Bairro</label>
        <input
          v-model="localForm.address.neighborhood"
          :class="[inputClass('neighborhood'), 'input-text', disabled ? 'disabled' : 'enabled']"
          placeholder="Bairro"
          :disabled="disabled"
        />
      </div>
      <div>
        <label class="input-label">Cidade</label>
        <input
          v-model="localForm.address.city"
          :class="[inputClass('city'), 'input-text', disabled ? 'disabled' : 'enabled']"
          placeholder="Cidade"
          :disabled="disabled"
        />
      </div>
      <div>
        <label class="input-label">Estado</label>
        <input
          v-model="localForm.address.state"
          :class="[inputClass('state'), 'input-text', disabled ? 'disabled' : 'enabled']"
          placeholder="Estado"
          :disabled="disabled"
        />
      </div>
    </row>
    <slot name="actions" :submit="submit" />
  </form>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'

type ContactFormData = {
  id: number|string
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
