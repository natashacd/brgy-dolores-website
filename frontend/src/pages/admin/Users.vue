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
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Barangay Officials</h1>
            <p class="text-sm text-slate-500 mt-1">S.Y. 2026-2027</p>
          </div>
        </div>
        <button
          class="inline-flex items-center gap-2.5 text-white text-base font-medium px-5 py-3 rounded-lg shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all duration-150 border-0 cursor-pointer"
          style="background-color: #3d4f7c;"
          @mouseenter="e => e.currentTarget.style.backgroundColor = '#252b3b'"
          @mouseleave="e => e.currentTarget.style.backgroundColor = '#3d4f7c'"
          @click="showAddModal = true"
        >
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          Appoint New Official
        </button>
      </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name, email or contact number..."
            class="w-full pl-10 pr-10 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
          <span v-if="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredUsers.length }} found
          </span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <select
              v-model="roleFilter"
              class="pl-8 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white cursor-pointer text-slate-600 font-medium appearance-none"
              :class="roleFilter ? 'border-[#3d4f7c]/40 bg-[#3d4f7c]/5 text-[#3d4f7c]' : ''"
            >
              <option value="">All Roles</option>
              <option v-for="role in roles" :key="role.id" :value="String(role.id)">{{ role.role_name }}</option>
            </select>
          </div>
          <div class="relative">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <select
              v-model="statusFilter"
              class="pl-8 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white cursor-pointer text-slate-600 font-medium appearance-none"
              :class="statusFilter === 'active' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : statusFilter === 'inactive' ? 'border-amber-300 bg-amber-50 text-amber-700' : ''"
            >
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div v-if="activeFilterCount > 0">
            <button
              @click="clearFilters"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer"
            >
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Cards Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Gradient Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">Barangay Officials</h2>
          </div>
          <span class="text-xs font-semibold text-white/60 bg-white/10 px-3 py-1 rounded-full">
            {{ filteredUsers.length }} {{ filteredUsers.length === 1 ? 'official' : 'officials' }}
          </span>
        </div>
      </div>
      <!-- Grid Body -->
      <div class="p-6">

        <!-- Empty State -->
        <div v-if="filteredUsers.length === 0" class="flex flex-col items-center justify-center py-20 gap-3">
          <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <p class="text-sm font-bold text-slate-600">No officials found</p>
          <p class="text-xs text-slate-400">Try adjusting your search or filter criteria</p>
          <button @click="clearFilters" class="text-xs font-semibold text-[#3d4f7c] bg-[#3d4f7c]/10 hover:bg-[#3d4f7c]/20 border border-[#3d4f7c]/20 px-4 py-2 rounded-xl transition-all cursor-pointer">
            Clear Filters
          </button>
        </div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <div
          v-for="user in filteredUsers"
          :key="user.id"
          class="bg-white rounded-2xl border border-slate-200 hover:border-[#3d4f7c]/40 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col cursor-pointer group"
          @click="openViewModal(user)"
        >
          <!-- Photo -->
          <div class="w-full bg-slate-50 relative overflow-hidden" style="padding-top: 60%;">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 16px 16px;"></div>
            <img
              v-if="user.information?.image_path"
              :src="`${baseUrl}/storage/${user.information.image_path}`"
              alt="Profile"
              class="absolute inset-0 w-full h-full object-cover object-top z-10 group-hover:scale-105 transition-transform duration-300"
              @error="e => e.target.style.display = 'none'"
            />
            <img
              v-else
              src="@/assets/images/icons/profile.png"
              alt="Profile"
              class="absolute inset-0 w-full h-full object-contain z-10 p-4"
            />
            <div class="absolute top-2 right-2 z-20">
              <span
                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-semibold"
                :class="user.status?.status ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-amber-50 text-amber-500 border border-amber-200'"
              >
                <span class="w-1 h-1 rounded-full" :class="user.status?.status ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'"></span>
                {{ user.status?.status ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
          <!-- Info -->
          <div class="px-3 py-3 border-t border-slate-100 flex-1 flex flex-col items-center text-center">
            <p class="text-m font-bold text-slate-800 leading-tight line-clamp-2">{{ fullName(user) }}</p>
            <span class="mt-1.5 inline-block text-[9px] font-semibold px-2 py-0.5 rounded-full" style="background:#3d4f7c12; color:#3d4f7c;">
              {{ user.role?.role_name ?? '—' }}
            </span>
          </div>

          <!-- Remove button -->
          <div class="px-3 pb-3">
            <button
              class="w-full flex items-center justify-center gap-1 text-[10px] font-semibold py-1.5 rounded-lg bg-red-50 text-red-500 border border-red-100 hover:bg-red-500 hover:text-white hover:border-red-500 active:scale-95 transition-all duration-150 cursor-pointer"
              @click.stop="handleDelete(user)"
            >
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Remove
            </button>
          </div>
        </div>
      </div>

            </div>
          </div>

    <AddUserModal
      v-if="showAddModal"
      :roles="roles"
      @close="showAddModal = false"
      @saved="handleUserAdded"
    />

    <EditUserModal
      v-if="showEditModal && selectedUser"
      :user="selectedUser"
      :roles="roles"
      @close="showEditModal = false"
      @saved="handleUserUpdated"
    />

    <ViewUserModal
      v-if="showViewModal && viewUser"
      :user="viewUser"
      @close="showViewModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import UserService from "@/services/Admin/UserService";
import EditUserModal from "@/components/modals/admin/users/EditUserModal.vue";
import AddUserModal from "@/components/modals/admin/users/AddUserModal.vue";
import ViewUserModal from "@/components/modals/admin/users/ViewUserModal.vue";
import Swal from 'sweetalert2';

const baseUrl = import.meta.env.VITE_API_URL;

// ── Module-level cache ────────────────────────────────────────────
const _users  = ref([]);
const _roles  = ref([]);
const _loaded = ref(false);

const users = _users;
const roles = _roles;

const loading       = ref(false);
const showEditModal = ref(false);
const showAddModal  = ref(false);
const selectedUser  = ref(null);
const searchQuery   = ref('');
const roleFilter    = ref('');
const statusFilter  = ref('');
const sortBy        = ref('name_asc');
const showViewModal = ref(false);
const viewUser      = ref(null);


const activeFilterCount = computed(() => {
  let c = 0;
  if (searchQuery.value) c++;
  if (roleFilter.value) c++;
  if (statusFilter.value) c++;
  if (sortBy.value !== 'name_asc') c++;
  return c;
});

function clearFilters() {
  searchQuery.value  = '';
  roleFilter.value   = '';
  statusFilter.value = '';
  sortBy.value       = 'name_asc';
}

function fullName(user) {
  const info = user.information;
  if (!info) return '—';
  return [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(' ');
}

function openViewModal(user) {
  viewUser.value      = user;
  showViewModal.value = true;
}

const filteredUsers = computed(() => {
  let list = [...users.value];
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    list = list.filter(u =>
      fullName(u).toLowerCase().includes(q) ||
      u.email?.toLowerCase().includes(q) ||
      u.information?.contact_number?.includes(q)
    );
  }
  if (roleFilter.value) {
    list = list.filter(u =>
      String(u.role?.id) === String(roleFilter.value) ||
      String(u.role_id)  === String(roleFilter.value)
    );
  }
  if (statusFilter.value) {
    list = list.filter(u =>
      statusFilter.value === 'active' ? !!u.status?.status : !u.status?.status
    );
  }
  return list;
});

const orgTiers = computed(() => {
  const groups = {};
  filteredUsers.value.forEach(u => {
    const name = u.role?.role_name ?? 'Other';
    if (!groups[name]) groups[name] = [];
    groups[name].push(u);
  });
  return Object.keys(groups)
    .sort((a, b) => a.localeCompare(b))
    .map(label => ({ label, users: groups[label] }));
});

async function fetchUsers() {
  loading.value = true;
  try {
    users.value = await UserService.getUsers();
    _loaded.value = true;
  } catch {
    _loaded.value = false;
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load users.', confirmButtonColor: '#3d4f7c' });
  } finally {
    loading.value = false;
  }
}

async function fetchRoles() {
  try { roles.value = await UserService.getRoles(); } catch {}
}

function openEditModal(user) {
  selectedUser.value  = user;
  showEditModal.value = true;
}

async function handleUserUpdated() {
  showEditModal.value = false;
  selectedUser.value  = null;
  await Swal.fire({ icon: 'success', title: 'Success!', text: 'User has been updated successfully.', timer: 1500, showConfirmButton: false });
  await fetchUsers();
}

async function handleUserAdded() {
  showAddModal.value = false;
  await Swal.fire({ icon: 'success', title: 'Success!', text: 'Official has been added successfully.', timer: 1500, showConfirmButton: false });
  await fetchUsers();
}

async function handleDelete(user) {
  const result = await Swal.fire({
    title: 'Change User Role?',
    html: `Are you sure you want to change <strong>${fullName(user)}</strong> to Resident?<br><br>
           <span class="text-xs text-slate-500">This will remove them from the officials list.</span>`,
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
    await Swal.fire({ icon: 'success', title: 'Success!', text: 'User role has been changed to Resident.', timer: 2000, showConfirmButton: false });
    await fetchUsers();
  } catch {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to change user role. Please try again.', confirmButtonColor: '#3d4f7c' });
  } finally {
    loading.value = false;
  }
}

watch([searchQuery, roleFilter, statusFilter, sortBy], () => {});
onMounted(() => {
  if (!_loaded.value) {
    fetchUsers();
    fetchRoles();
  }
});
</script>