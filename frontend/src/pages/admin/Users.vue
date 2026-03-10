<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c]"></div>
          <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c] bg-opacity-10 text-[#3d4f7c]">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">User Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage all system users (non-residents).</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search users by name, email or contact number..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c] focus:ring-opacity-20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
          <span v-if="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredUsers.length }} found
          </span>
        </div>
        <div class="flex items-center gap-2">
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer"
          >
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
          </button>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Table Header Gradient -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] border-b border-slate-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white bg-opacity-20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">Users List</h2>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading users...</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Name</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Email</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Role</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Contact</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-28">Actions</th>
            </tr>
          </thead>
          <tbody>

            <!-- Rows -->
            <tr
              v-for="(user, index) in paginatedUsers"
              :key="user.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c] hover:bg-opacity-5 transition-colors duration-100 group"
            >
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white shadow-sm group-hover:scale-105 transition-transform duration-150 flex-shrink-0"
                    :style="{ background: avatarBg(fullName(user)) }"
                  >
                    {{ initials(fullName(user)) }}
                  </div>
                  <p class="text-sm font-semibold text-slate-800">{{ fullName(user) }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">{{ user.email ?? "—" }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg"
                  style="background:#3d4f7c12; color:#3d4f7c">
                  {{ user.role?.role_name ?? "—" }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span v-if="user.information?.contact_number" class="text-sm text-slate-600 inline-flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  {{ user.information.contact_number }}
                </span>
                <span v-else class="text-slate-300 text-sm">—</span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold"
                  :class="user.status?.status ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-600'">
                  <span class="w-1.5 h-1.5 rounded-full"
                    :class="user.status?.status ? 'bg-emerald-500' : 'bg-amber-400'"></span>
                  {{ user.status?.status ? "Active" : "Inactive" }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <button
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c] bg-opacity-10 text-[#3d4f7c] border border-[#3d4f7c] border-opacity-20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                    @click="openEditModal(user)"
                    title="Edit user"
                  >
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform duration-150">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md hover:shadow-red-200 active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                    @click="handleDelete(user)"
                    title="Delete user"
                  >
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform duration-150">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredUsers.length === 0">
              <td colspan="7" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No users found</p>
                    <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search criteria</p>
                  </div>
                  <button
                    v-if="searchQuery"
                    @click="searchQuery = ''"
                    class="text-xs font-semibold text-[#3d4f7c] bg-[#3d4f7c] bg-opacity-10 hover:bg-opacity-20 border border-[#3d4f7c] border-opacity-20 px-4 py-2 rounded-xl transition-all cursor-pointer"
                  >
                    Clear Search
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
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
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] hover:bg-[#3d4f7c] hover:bg-opacity-5 transition-all cursor-pointer"
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
                ? 'bg-[#3d4f7c] text-white shadow-md'
                : 'text-slate-500 hover:bg-[#3d4f7c] hover:bg-opacity-5 hover:text-[#3d4f7c] border border-transparent'"
            >{{ page }}</button>
            <span
              v-else-if="Math.abs(page - currentPage) === 2"
              class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5"
            >…</span>
          </template>
          <button
            :disabled="currentPage === totalPages"
            @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] hover:bg-[#3d4f7c] hover:bg-opacity-5 transition-all cursor-pointer"
          >
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Simple count when no pagination needed -->
      <div v-else-if="!loading && filteredUsers.length > 0" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono">
          Showing <span class="font-medium text-slate-700">{{ filteredUsers.length }}</span> {{ filteredUsers.length === 1 ? 'result' : 'results' }}
        </p>
      </div>
    </div>

    <EditUserModal
      v-if="showEditModal && selectedUser"
      :user="selectedUser"
      :roles="roles"
      @close="showEditModal = false"
      @saved="handleUserUpdated"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import UserService from "@/services/Admin/UserService";
import EditUserModal from "@/components/modals/admin/users/EditUserModal.vue";
import Swal from 'sweetalert2';

const users = ref([]);
const roles = ref([]);
const loading = ref(false);
const showEditModal = ref(false);
const selectedUser = ref(null);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 6;

function fullName(user) {
  const info = user.information;
  if (!info) return "—";
  return [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(" ");
}

function initials(name) {
  if (!name || name === '—') return '?';
  const parts = name.trim().split(' ');
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || '?';
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

const PALETTE = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c'];
function avatarBg(name) {
  if (!name || name === '—') return '#94a3b8';
  let h = 0;
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
  return PALETTE[Math.abs(h) % PALETTE.length];
}

const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value;
  const q = searchQuery.value.toLowerCase();
  return users.value.filter(u =>
    fullName(u).toLowerCase().includes(q) ||
    u.email?.toLowerCase().includes(q) ||
    u.information?.contact_number?.includes(q)
  );
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredUsers.value.length / itemsPerPage)));
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredUsers.value.slice(start, start + itemsPerPage);
});

async function fetchUsers() {
  loading.value = true;
  try {
    users.value = await UserService.getUsers();
  } catch (error) {
    console.error("Failed to fetch users:", error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load users.',
      confirmButtonColor: '#3d4f7c'
    });
  } finally {
    loading.value = false;
  }
}

async function fetchRoles() {
  try {
    roles.value = await UserService.getRoles();
  } catch (error) {
    console.error("Failed to fetch roles:", error);
  }
}

function openEditModal(user) {
  selectedUser.value = user;
  showEditModal.value = true;
}

async function handleUserUpdated() {
  showEditModal.value = false;
  selectedUser.value = null;
  
  loading.value = true;
  
  await Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'User has been updated successfully.',
    timer: 1500,
    showConfirmButton: false
  });
  
  await fetchUsers();
}

async function handleDelete(user) {
  const result = await Swal.fire({
    title: 'Change User Role?',
    html: `Are you sure you want to change <strong>${fullName(user)}</strong> to Resident?<br><br>
           <span class="text-xs text-slate-500">This will remove them from the users list.</span>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3d4f7c',
    confirmButtonText: 'Yes, change role',
    cancelButtonText: 'Cancel'
  });

  if (!result.isConfirmed) return;

  loading.value = true;

  try {
    await UserService.deleteUser(user.id);
    
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'User role has been changed to Resident.',
      timer: 2000,
      showConfirmButton: false
    });
    
    await fetchUsers();
    
  } catch (error) {
    console.error("Failed to change user role:", error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to change user role. Please try again.',
      confirmButtonColor: '#3d4f7c'
    });
  } finally {
    loading.value = false;
  }
}

watch(searchQuery, () => { 
  currentPage.value = 1; 
});

onMounted(() => { 
  fetchUsers(); 
  fetchRoles(); 
});
</script>