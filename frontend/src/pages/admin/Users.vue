<template>
  <div class="p-6">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-bold text-gray-900">Users Management</h1>
        <p class="text-sm text-gray-500 mt-1">
          Manage all system users (non-residents).
        </p>
      </div>
    </div>

    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <table v-if="!loading" class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">#</th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Name
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Email
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Role
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Contact
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Status
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="users.length === 0">
            <td colspan="7" class="text-center text-gray-400 py-10">
              No users found.
            </td>
          </tr>
          <tr
            v-for="(user, index) in users"
            :key="user.id"
            class="border-t border-gray-100 hover:bg-gray-50"
          >
            <td class="px-4 py-3 text-gray-700">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-gray-900">{{ fullName(user) }}</td>
            <td class="px-4 py-3 text-gray-700">{{ user.email }}</td>
            <td class="px-4 py-3 text-gray-700">
              {{ user.role?.role_name ?? "—" }}
            </td>
            <td class="px-4 py-3 text-gray-700">
              {{ user.information?.contact_number ?? "—" }}
            </td>
            <td class="px-4 py-3">
              <span
                :class="
                  user.status?.status
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
                "
                class="px-2 py-0.5 rounded-full text-xs font-semibold"
              >
                {{ user.status?.status ? "Active" : "Inactive" }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex gap-2">
                <button
                  class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-medium px-3 py-1.5 rounded-md"
                  @click="openEditModal(user)"
                >
                  Edit
                </button>
                <button
                  class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-medium px-3 py-1.5 rounded-md"
                  @click="handleDelete(user)"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="text-center text-gray-500 text-sm py-10">
        Loading users...
      </div>
    </div>

    <EditUserModal
      v-if="showEditModal && selectedUser"
      :user="selectedUser"
      :roles="roles"
      @close="showEditModal = false"
      @saved="fetchUsers"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import UserService from "@/services/Admin/UserService";
import EditUserModal from "@/components/modals/admin/users/EditUserModal.vue";

const users = ref([]);
const roles = ref([]);
const loading = ref(false);
const showEditModal = ref(false);
const selectedUser = ref(null);

function fullName(user) {
  const info = user.information;
  if (!info) return "—";
  return [info.first_name, info.middle_name, info.last_name]
    .filter(Boolean)
    .join(" ");
}

async function fetchUsers() {
  loading.value = true;
  try {
    users.value = await UserService.getUsers();
  } finally {
    loading.value = false;
  }
}

async function fetchRoles() {
  roles.value = await UserService.getRoles();
}

function openEditModal(user) {
  selectedUser.value = user;
  showEditModal.value = true;
}

async function handleDelete(user) {
  if (
    !confirm(
      `Are you sure you want to change "${fullName(user)}" role to Resident? This will remove them from this list.`,
    )
  )
    return;
  try {
    await UserService.deleteUser(user.id);
    await fetchUsers();
  } catch {
    alert("Failed to change user role.");
  }
}

onMounted(() => {
  fetchUsers();
  fetchRoles();
});
</script>
