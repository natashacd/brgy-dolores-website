<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/45" @click.self="$emit('close')">
    <div class="bg-white rounded-lg w-full max-w-2xl shadow-xl overflow-hidden">

      <!-- Header -->
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
        <h2 class="text-base font-semibold text-gray-900">Add User</h2>
        <button class="text-gray-400 hover:text-gray-700 text-xl leading-none" @click="$emit('close')">&times;</button>
      </div>

      <!-- Body -->
      <div class="px-5 py-4 flex flex-col gap-4">

        <div class="grid grid-cols-3 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
            <input v-model="form.first_name" type="text" placeholder="First name"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
            <span v-if="errors.first_name" class="text-xs text-red-500">{{ errors.first_name }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Middle Name</label>
            <input v-model="form.middle_name" type="text" placeholder="Middle name (optional)"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
            <input v-model="form.last_name" type="text" placeholder="Last name"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
            <span v-if="errors.last_name" class="text-xs text-red-500">{{ errors.last_name }}</span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
            <input v-model="form.email" type="email" placeholder="Email address"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
            <span v-if="errors.email" class="text-xs text-red-500">{{ errors.email }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Contact Number <span class="text-red-500">*</span></label>
            <input v-model="form.contact_number" type="text" placeholder="Contact number"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
            <span v-if="errors.contact_number" class="text-xs text-red-500">{{ errors.contact_number }}</span>
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
          <input v-model="form.address" type="text" placeholder="Address"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500" />
          <span v-if="errors.address" class="text-xs text-red-500">{{ errors.address }}</span>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
            <select v-model="form.role_id"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
              <option value="" disabled>Select a role</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
            </select>
            <span v-if="errors.role_id" class="text-xs text-red-500">{{ errors.role_id }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Password</label>
            <input type="text" value="adminadmin" disabled
              class="border border-gray-200 bg-gray-100 text-gray-500 rounded-md px-3 py-2 text-sm cursor-not-allowed" />
            <span class="text-xs text-gray-400">Temporary default password</span>
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
          <div class="flex gap-5">
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
              <input type="radio" v-model="form.status" :value="1" /> Active
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
              <input type="radio" v-model="form.status" :value="0" /> Inactive
            </label>
          </div>
        </div>

      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-2 px-5 py-3 border-t border-gray-200 bg-gray-50">
        <button
          class="border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-md"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium px-4 py-2 rounded-md"
          @click="submit"
          :disabled="loading"
        >
          {{ loading ? 'Saving...' : 'Add User' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import UserService from '@/services/Admin/UserService'

defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const errors = ref({})

const form = ref({
  first_name: '',
  middle_name: '',
  last_name: '',
  email: '',
  contact_number: '',
  address: '',
  role_id: '',
  status: 1,
})

function validate() {
  errors.value = {}
  if (!form.value.first_name) errors.value.first_name = 'First name is required.'
  if (!form.value.last_name) errors.value.last_name = 'Last name is required.'
  if (!form.value.email) errors.value.email = 'Email is required.'
  if (!form.value.contact_number) errors.value.contact_number = 'Contact number is required.'
  if (!form.value.address) errors.value.address = 'Address is required.'
  if (!form.value.role_id) errors.value.role_id = 'Role is required.'
  return Object.keys(errors.value).length === 0
}

async function submit() {
  if (!validate()) return
  loading.value = true
  try {
    await UserService.createUser(form.value)
    emit('saved')
    emit('close')
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = Object.fromEntries(
        Object.entries(err.response.data.errors).map(([k, v]) => [k, v[0]])
      )
    }
  } finally {
    loading.value = false
  }
}
</script>