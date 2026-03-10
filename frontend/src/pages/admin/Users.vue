<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <!-- Google Font Import for Inter -->
    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- ── Page Header ── -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
          </div>
          <div class="flex items-center gap-4">
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Users Management</h1>
          </div>
        </div>

        <button
          class="inline-flex items-center gap-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-base font-medium px-5 py-3 rounded-lg shadow-sm hover:shadow transition-all duration-150 border-0 cursor-pointer"
          @click="showAddModal = true"
        >
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          Add New User
        </button>
      </div>
    </div>
    
    <!-- ── Main Table Card ── -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Toolbar -->
      <div class="flex flex-col lg:flex-row lg:items-center gap-3 px-5 py-4 border-b border-slate-100">

        <!-- Search -->
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search users by name, email or role…"
            class="w-full pl-10 pr-20 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 hover:border-slate-300 transition-all placeholder-slate-300 bg-slate-50 focus:bg-white"
          />
          <span v-if="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredUsers.length }} found
          </span>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-2 flex-wrap">
          <div class="relative">
            <select
              v-model="roleFilter"
              class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl pl-3 pr-8 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
            >
              <option value="all">All Roles</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

          <div class="relative">
            <select
              v-model="statusFilter"
              class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl pl-3 pr-8 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
            >
              <option value="all">All Statuses</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

          <button
            v-if="hasActiveFilters"
            @click="resetFilters"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer"
          >
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading users…</p>
      </div>

    <!-- Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50/60">
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User</th>
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</th>
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role</th>
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact</th>
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
            <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-24">Actions</th>
          </tr>
        </thead>
        <tbody>

          <!-- Empty -->
          <tr v-if="filteredUsers.length === 0">
            <td colspan="6" class="py-20 text-center">
              <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                  <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24" class="text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-bold text-slate-600">No users found</p>
                  <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search or filter criteria</p>
                </div>
                <button
                  v-if="hasActiveFilters"
                  @click="resetFilters"
                  class="text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-4 py-2 rounded-xl transition-all cursor-pointer"
                >
                  Clear Filters
                </button>
              </div>
            </td>
          </tr>

          <!-- Rows -->
          <tr
            v-for="(user, index) in paginatedUsers"
            :key="user.id"
            class="border-b border-slate-50 hover:bg-blue-50/25 transition-colors duration-100 group"
          >
            <!-- Name -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-9 h-9 rounded-xl flex items-center justify-center text-[11px] font-bold text-white flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-150"
                  :style="{ background: avatarBg(fullName(user)) }"
                >
                  {{ initials(fullName(user)) }}
                </div>
                <p class="text-sm font-semibold text-slate-800">{{ fullName(user) }}</p>
              </div>
            </td>

            <!-- Email -->
            <td class="px-6 py-4 text-sm text-slate-500">{{ user.email }}</td>

            <!-- Role -->
            <td class="px-6 py-4">
              <span
                v-if="user.role?.role_name"
                class="inline-flex items-center text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg whitespace-nowrap"
              >
                {{ user.role.role_name }}
              </span>
              <span v-else class="text-slate-300 text-sm">—</span>
            </td>

            <!-- Contact -->
            <td class="px-6 py-4">
              <span class="text-sm text-slate-500 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ formatPhoneNumber(user.information?.contact_number) ?? '—' }}
              </span>
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span
                class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap"
                :class="user.status?.status
                  ? 'text-emerald-700 bg-emerald-50 border-emerald-200'
                  : 'text-amber-600 bg-amber-50 border-amber-200'"
              >
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                  :class="user.status?.status ? 'bg-emerald-500' : 'bg-amber-400'"></span>
                {{ user.status?.status ? 'Active' : 'Inactive' }}
              </span>
            </td>

            <!-- Actions -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-1">
                <button
                  class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-md hover:shadow-indigo-200 active:scale-95 transition-all duration-150 cursor-pointer group"
                  @click="openEditModal(user)"
                  title="Edit user"
                >
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:scale-110 transition-transform duration-150">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  v-if="!isCurrentUser(user)"
                  class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 bg-red-50 border border-red-100 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md hover:shadow-red-200 active:scale-95 transition-all duration-150 cursor-pointer group"
                  @click="confirmDelete(user)"
                  title="Delete user"
                >
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:scale-110 transition-transform duration-150">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
                <span
                  v-else
                  class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 bg-slate-100 cursor-not-allowed"
                  title="Cannot delete your own account"
                >
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                  </svg>
                </span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

      <!-- ── Pagination Footer ── -->
      <div v-if="!loading && filteredUsers.length > itemsPerPage" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredUsers.length) }}
          <span class="text-slate-300 mx-1">/</span>
          {{ filteredUsers.length }} results
        </p>

        <div class="flex items-center gap-1.5">
          <button
            :disabled="currentPage === 1"
            @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all cursor-pointer"
          >
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <template v-for="page in totalPages" :key="page">
            <button
              v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage
                ? 'bg-blue-600 text-white shadow-sm shadow-blue-200'
                : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600 border border-transparent hover:border-blue-100'"
            >{{ page }}</button>
            <span
              v-else-if="Math.abs(page - currentPage) === 2"
              class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5"
            >…</span>
          </template>

          <button
            :disabled="currentPage === totalPages"
            @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all cursor-pointer"
          >
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Simple result count when 6 or fewer items -->
      <div v-else-if="!loading && filteredUsers.length > 0" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono text-center sm:text-left">
          Showing <span class="font-medium text-slate-700">{{ filteredUsers.length }}</span> {{ filteredUsers.length === 1 ? 'result' : 'results' }}
        </p>
      </div>
    </div>

    <!-- Modals -->
    <AddUserModal
      v-if="showAddModal"
      :roles="roles"
      @close="showAddModal = false"
      @saved="() => handleUserSaved('created')"
    />
    <EditUserModal
      v-if="showEditModal && selectedUser"
      :user="selectedUser"
      :roles="roles"
      @close="showEditModal = false"
      @saved="() => handleUserSaved('updated')"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import UserService from '@/services/Admin/UserService'
import AddUserModal from '@/components/modals/admin/users/AddUserModal.vue'
import EditUserModal from '@/components/modals/admin/users/EditUserModal.vue'
import Swal from 'sweetalert2'

const users         = ref([])
const roles         = ref([])
const loading       = ref(false)
const showAddModal  = ref(false)
const showEditModal = ref(false)
const selectedUser  = ref(null)

// Get current user ID from localStorage/session
const currentUserId = ref(null)

const searchQuery  = ref('')
const roleFilter   = ref('all')
const statusFilter = ref('all')
const currentPage  = ref(1)
const itemsPerPage = 6

// Get current user on mount
onMounted(() => {
  fetchUsers()
  fetchRoles()
  getCurrentUser()
})

function getCurrentUser() {
  // Try to get user from localStorage or session
  const userStr = localStorage.getItem('user') || sessionStorage.getItem('user')
  if (userStr) {
    try {
      const user = JSON.parse(userStr)
      currentUserId.value = user.id
    } catch (e) {
      console.error('Failed to parse user data', e)
    }
  }
}

const hasActiveFilters = computed(() =>
  searchQuery.value || roleFilter.value !== 'all' || statusFilter.value !== 'all'
)

const filteredUsers = computed(() => {
  let list = users.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(u =>
      fullName(u).toLowerCase().includes(q) ||
      u.email?.toLowerCase().includes(q) ||
      u.role?.role_name?.toLowerCase().includes(q)
    )
  }
  if (roleFilter.value !== 'all')
    list = list.filter(u => u.role_id === roleFilter.value)
  if (statusFilter.value !== 'all')
    list = list.filter(u => Boolean(u.status?.status) === (statusFilter.value === 'active'))
  return list
})

const totalPages     = computed(() => Math.max(1, Math.ceil(filteredUsers.value.length / itemsPerPage)))
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredUsers.value.slice(start, start + itemsPerPage)
})

watch([searchQuery, roleFilter, statusFilter], () => { currentPage.value = 1 })

// Format phone number helper
const formatPhoneNumber = (phoneNumber) => {
  if (!phoneNumber) return null
  // Remove all non-numeric characters
  const cleaned = phoneNumber.replace(/\D/g, '')
  // Check if it's a valid Philippine mobile number (11 digits starting with 0)
  if (cleaned.length === 11 && cleaned.startsWith('0')) {
    return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3')
  }
  return phoneNumber // Return original if not matching format
}

// Validate phone number (must be 11 digits starting with 0)
const isValidPhoneNumber = (phoneNumber) => {
  const cleaned = phoneNumber.replace(/\D/g, '')
  return cleaned.length === 11 && cleaned.startsWith('0')
}

function fullName(user) {
  const info = user.information
  if (!info) return '—'
  
  let name = [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(' ')
  
  // Add suffix if exists
  if (info.suffix) {
    name += `, ${info.suffix}`
  }
  
  return name || '—'
}

function initials(name) {
  if (!name || name === '—') return '?'
  const parts = name.trim().split(' ')
  return parts.length === 1 ? parts[0][0].toUpperCase() : (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}

const PALETTE = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#db2777','#0891b2','#9333ea','#0284c7','#65a30d','#ea580c','#4f46e5']

function avatarBg(name) {
  if (!name || name === '—') return '#94a3b8'
  let h = 0
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h)
  return PALETTE[Math.abs(h) % PALETTE.length]
}

function isCurrentUser(user) {
  return user.id === currentUserId.value
}

function resetFilters() {
  searchQuery.value  = ''
  roleFilter.value   = 'all'
  statusFilter.value = 'all'
  currentPage.value  = 1
}

async function fetchUsers() {
  try { 
    users.value = await UserService.getUsers() 
  }
  catch (e) { 
    console.error(e) 
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load users.',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    })
  }
}

async function fetchRoles() {
  try { 
    roles.value = await UserService.getRoles() 
  }
  catch (e) { 
    console.error(e) 
  }
}

function openEditModal(user) {
  selectedUser.value = user
  showEditModal.value = true
}

async function handleUserSaved(action) {
  // Show loading alert
  Swal.fire({
    title: 'Please wait...',
    text: action === 'created' ? 'Creating user...' : 'Updating user...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading()
    }
  })

  await fetchUsers()
  
  Swal.close()
  
  const messages = {
    created: 'User has been created successfully.',
    updated: 'User has been updated successfully.'
  }
  
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: messages[action] || 'User has been saved successfully.',
    timer: 2000,
    showConfirmButton: true,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK'
  })
}

async function confirmDelete(user) {
  // Check if trying to delete own account
  if (isCurrentUser(user)) {
    await Swal.fire({
      icon: 'error',
      title: 'Cannot Delete',
      text: 'You cannot delete your own account.',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Got it'
    })
    return
  }

  const result = await Swal.fire({
    title: 'Delete User?',
    html: `Are you sure you want to delete <strong>${fullName(user)}</strong>?<br><br>
           <span class="text-xs text-slate-500">This action cannot be undone.</span>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel'
  })

  if (result.isConfirmed) {
    await deleteUser(user)
  }
}

async function deleteUser(user) {
  // Show loading alert
  Swal.fire({
    title: 'Please wait...',
    text: 'Deleting user...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading()
    }
  })

  try {
    await UserService.deleteUser(user.id)
    await fetchUsers()
    
    Swal.close()
    
    Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'User has been deleted.',
      timer: 2000,
      showConfirmButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    })
  } catch (error) {
    Swal.close()
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to delete user.',
      confirmButtonColor: '#3085d6'
    })
  }
}
</script>