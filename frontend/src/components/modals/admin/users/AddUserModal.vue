<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl shadow-gray-300/50 overflow-hidden">

      <!-- ── Header ── -->
      <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-indigo-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h2 class="text-base font-bold text-gray-900 leading-tight">Add New User</h2>
          <p class="text-xs text-gray-400 mt-0.5">Fill in the details to create a new user account.</p>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all cursor-pointer border-0 bg-transparent"
          @click="$emit('close')"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- ── Body ── -->
      <div class="px-6 py-5 flex flex-col gap-5 max-h-[70vh] overflow-y-auto">

        <!-- Section: Personal Info -->
        <div>
          <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Personal Information</p>
          <div class="grid grid-cols-4 gap-3">
            <div class="flex flex-col gap-1.5 col-span-1">
              <label class="text-xs font-semibold text-gray-600">First Name <span class="text-red-400">*</span></label>
              <input
                v-model="form.first_name"
                type="text"
                placeholder="First name"
                class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all"
                :class="errors.first_name
                  ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                  : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
              />
              <span v-if="errors.first_name" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.first_name }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5 col-span-1">
              <label class="text-xs font-semibold text-gray-600">Middle Name <span class="text-gray-300 font-normal">(optional)</span></label>
              <input
                v-model="form.middle_name"
                type="text"
                placeholder="Middle name"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300 transition-all"
              />
            </div>
            <div class="flex flex-col gap-1.5 col-span-1">
              <label class="text-xs font-semibold text-gray-600">Last Name <span class="text-red-400">*</span></label>
              <input
                v-model="form.last_name"
                type="text"
                placeholder="Last name"
                class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all"
                :class="errors.last_name
                  ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                  : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
              />
              <span v-if="errors.last_name" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.last_name }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5 col-span-1">
              <label class="text-xs font-semibold text-gray-600">Suffix <span class="text-gray-300 font-normal">(optional)</span></label>
              <input
                v-model="form.suffix"
                type="text"
                placeholder="e.g., Jr., Sr., III"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300 transition-all"
              />
            </div>
          </div>
        </div>

        <!-- Section: Contact Info -->
        <div>
          <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Contact Information</p>
          <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-gray-600">Email Address <span class="text-red-400">*</span></label>
              <input
                v-model="form.email"
                type="email"
                placeholder="name@example.com"
                class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all"
                :class="errors.email
                  ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                  : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
              />
              <span v-if="errors.email" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.email }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-gray-600">Contact Number <span class="text-red-400">*</span></label>
              <input
                v-model="form.contact_number"
                type="text"
                placeholder="0912 345 6789"
                @input="formatPhoneNumberInput"
                maxlength="13"
                class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all"
                :class="errors.contact_number
                  ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                  : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
              />
              <span v-if="errors.contact_number" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.contact_number }}
              </span>
            </div>
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-600">Address <span class="text-red-400">*</span></label>
            <input
              v-model="form.address"
              type="text"
              placeholder="Full address"
              class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all"
              :class="errors.address
                ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
            />
            <span v-if="errors.address" class="text-[11px] text-red-500 flex items-center gap-1">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
              {{ errors.address }}
            </span>
          </div>
        </div>

        <!-- Section: Account Settings -->
        <div>
          <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Account Settings</p>
          <div class="grid grid-cols-2 gap-3 mb-3">
            <!-- Role -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-gray-600">Role <span class="text-red-400">*</span></label>
              <div class="relative">
                <select
                  v-model="form.role_id"
                  class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all pr-9"
                  :class="errors.role_id
                    ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400 text-gray-800'
                    : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300 text-gray-800'"
                >
                  <option value="" disabled class="text-gray-400">Select a role</option>
                  <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
              <span v-if="errors.role_id" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.role_id }}
              </span>
            </div>

            <!-- Default Password -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-gray-600">Password</label>
              <div class="relative">
                <input
                  type="text"
                  value="adminadmin"
                  disabled
                  class="w-full border border-gray-100 bg-gray-50 text-gray-400 rounded-xl px-3.5 py-2.5 text-sm cursor-not-allowed"
                />
                <span class="absolute right-3 top-1/2 -translate-y-1/2">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-gray-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </span>
              </div>
              <span class="text-[11px] text-gray-400 flex items-center gap-1">
                <svg width="9" height="9" fill="currentColor" viewBox="0 0 20 20" class="text-gray-300 flex-shrink-0"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                Temporary default password
              </span>
            </div>
          </div>

          <!-- Status -->
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-gray-600">Status <span class="text-red-400">*</span></label>
            <div class="flex gap-3">
              <label
                class="flex items-center gap-2.5 flex-1 border rounded-xl px-4 py-3 cursor-pointer transition-all"
                :class="form.status === 1
                  ? 'border-emerald-300 bg-emerald-50 ring-1 ring-emerald-300'
                  : 'border-gray-200 bg-white hover:border-gray-300'"
              >
                <input type="radio" v-model="form.status" :value="1" class="accent-emerald-500" />
                <div>
                  <p class="text-sm font-semibold" :class="form.status === 1 ? 'text-emerald-700' : 'text-gray-700'">Active</p>
                  <p class="text-[11px]" :class="form.status === 1 ? 'text-emerald-500' : 'text-gray-400'">User can log in immediately</p>
                </div>
                <span v-if="form.status === 1" class="ml-auto w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                  <svg width="8" height="8" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
              </label>

              <label
                class="flex items-center gap-2.5 flex-1 border rounded-xl px-4 py-3 cursor-pointer transition-all"
                :class="form.status === 0
                  ? 'border-gray-400 bg-gray-50 ring-1 ring-gray-300'
                  : 'border-gray-200 bg-white hover:border-gray-300'"
                @click="checkInactiveStatus"
              >
                <input type="radio" v-model="form.status" :value="0" class="accent-gray-500" />
                <div>
                  <p class="text-sm font-semibold" :class="form.status === 0 ? 'text-gray-700' : 'text-gray-700'">Inactive</p>
                  <p class="text-[11px]" :class="form.status === 0 ? 'text-gray-500' : 'text-gray-400'">Account will be disabled</p>
                </div>
                <span v-if="form.status === 0" class="ml-auto w-4 h-4 rounded-full bg-gray-400 flex items-center justify-center flex-shrink-0">
                  <svg width="8" height="8" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- ── Footer ── -->
      <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-gray-100 bg-gray-50/60">
        <button
          class="text-sm font-semibold text-gray-500 hover:text-gray-800 bg-white hover:bg-gray-100 border border-gray-200 px-5 py-2.5 rounded-xl transition-all cursor-pointer"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-200 transition-all cursor-pointer border-0"
          @click="submit"
          :disabled="loading"
        >
          <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
          <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
          {{ loading ? 'Saving…' : 'Add User' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import UserService from '@/services/Admin/UserService'
import Swal from 'sweetalert2'

const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
  currentUserId: {
    type: [Number, String],
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const errors  = ref({})

const form = ref({
  first_name:     '',
  middle_name:    '',
  last_name:      '',
  suffix:         '',
  email:          '',
  contact_number: '',
  address:        '',
  role_id:        '',
  status:         1,
})

// Format phone number as user types
const formatPhoneNumberInput = (e) => {
  let value = e.target.value.replace(/\D/g, '') // Remove non-digits
  
  // Limit to 11 digits
  if (value.length > 11) {
    value = value.slice(0, 11)
  }
  
  // Format as 0912 345 6789
  if (value.length >= 5) {
    value = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7, 11)
  } else if (value.length >= 4) {
    value = value.slice(0, 4) + ' ' + value.slice(4)
  }
  
  form.value.contact_number = value.trim()
}

// Validate phone number (must be 11 digits starting with 0)
const isValidPhoneNumber = (phone) => {
  const cleaned = phone.replace(/\D/g, '')
  return cleaned.length === 11 && cleaned.startsWith('0')
}

// Check if trying to deactivate own account
const checkInactiveStatus = async () => {
  // This is for AddUserModal, so we're creating a new user
  // No need to check for current user since it's a new account
  // This function is kept for consistency with EditUserModal
  return true
}

function validate() {
  errors.value = {}
  
  if (!form.value.first_name) errors.value.first_name = 'First name is required.'
  if (!form.value.last_name) errors.value.last_name = 'Last name is required.'
  if (!form.value.email) errors.value.email = 'Email is required.'
  
  if (!form.value.contact_number) {
    errors.value.contact_number = 'Contact number is required.'
  } else if (!isValidPhoneNumber(form.value.contact_number)) {
    errors.value.contact_number = 'Must be 11 digits starting with 0 (e.g., 0912 345 6789)'
  }
  
  if (!form.value.address) errors.value.address = 'Address is required.'
  if (!form.value.role_id) errors.value.role_id = 'Role is required.'
  
  return Object.keys(errors.value).length === 0
}

async function submit() {
  if (!validate()) return
  
  loading.value = true
  
  // Clean phone number before sending (remove spaces)
  const formData = {
    ...form.value,
    contact_number: form.value.contact_number.replace(/\s/g, '')
  }
  
  try {
    await UserService.createUser(formData)
    
    // Show success message
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'User has been created successfully.',
      timer: 2000,
      showConfirmButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    })
    
    emit('saved')
    emit('close')
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = Object.fromEntries(
        Object.entries(err.response.data.errors).map(([k, v]) => [k, v[0]])
      )
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to create user. Please try again.',
        confirmButtonColor: '#3085d6'
      })
    }
  } finally {
    loading.value = false
  }
}
</script>