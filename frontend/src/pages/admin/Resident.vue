<template>
  <div class="p-6">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-bold text-gray-900">Resident Management</h1>
        <p class="text-sm text-gray-500 mt-1">
          Manage all residents and their information.
        </p>
      </div>
      <button
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md"
        @click="showAddModal = true"
      >
        + Add Resident
      </button>
    </div>

    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <table v-if="!loading" class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">#</th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Full Name
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Email
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Contact Number
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Address
            </th>
            <th class="text-left px-4 py-3 font-semibold text-gray-600">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="residents.length === 0">
            <td colspan="6" class="text-center text-gray-400 py-10">
              No residents found.
            </td>
          </tr>
          <tr
            v-for="(resident, index) in residents"
            :key="resident.id"
            class="border-t border-gray-100 hover:bg-gray-50"
          >
            <td class="px-4 py-3 text-gray-700">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-gray-900">{{ fullName(resident) }}</td>
            <td class="px-4 py-3 text-gray-700">{{ resident.email }}</td>
            <td class="px-4 py-3 text-gray-700">
              {{ resident.information?.contact_number ?? "—" }}
            </td>
            <td class="px-4 py-3 text-gray-700">{{ fullAddress(resident) }}</td>
            <td class="px-4 py-3">
              <div class="flex gap-2">
                <button
                  class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-medium px-3 py-1.5 rounded-md"
                  @click="openEditModal(resident)"
                >
                  Edit
                </button>
                <button
                  class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-medium px-3 py-1.5 rounded-md"
                  @click="handleDelete(resident)"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="text-center text-gray-500 text-sm py-10">
        Loading residents...
      </div>
    </div>

    <AddResidentModal
      v-if="showAddModal"
      :roles="roles"
      @close="showAddModal = false"
      @saved="fetchResidents"
    />

    <EditResidentModal
      v-if="showEditModal && selectedResident"
      :resident="selectedResident"
      :roles="roles"
      @close="showEditModal = false"
      @saved="fetchResidents"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ResidentService from "@/services/Admin/ResidentService";
import AddResidentModal from "@/components/modals/admin/residents/AddResidentModal.vue";
import EditResidentModal from "@/components/modals/admin/residents/EditResidentModal.vue";

const residents = ref([]);
const roles = ref([]);
const loading = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedResident = ref(null);

function fullName(resident) {
  const info = resident.information;
  if (!info) return "—";
  const parts = [
    info.first_name,
    info.middle_name,
    info.last_name,
    info.suffix,
  ];
  return parts.filter(Boolean).join(" ");
}

function fullAddress(resident) {
  const addr = resident.address;
  if (!addr) return "—";
  const parts = [addr.house_no, addr.purok, addr.sitio];
  return parts.filter(Boolean).join(" - ");
}

async function fetchResidents() {
  loading.value = true;
  try {
    residents.value = await ResidentService.getResidents();
  } finally {
    loading.value = false;
  }
}

async function fetchRoles() {
  roles.value = await ResidentService.getRoles();
}

function openEditModal(resident) {
  selectedResident.value = resident;
  showEditModal.value = true;
}

async function handleDelete(resident) {
  if (
    !confirm(
      `Are you sure you want to delete resident "${fullName(resident)}"? This action cannot be undone.`,
    )
  )
    return;
  try {
    await ResidentService.deleteResident(resident.id);
    await fetchResidents();
  } catch {
    alert("Failed to delete resident.");
  }
}

onMounted(() => {
  fetchResidents();
  fetchRoles();
});
</script>
