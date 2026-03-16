<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 sm:p-6 lg:p-8">
    <component :is="'style'">
      @import
      url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header with Dark Blue Theme -->
    <div
      class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-4 sm:mb-8"
    >
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
      >
        <div class="flex items-center gap-3 sm:gap-4">
          <div class="w-1.5 h-8 sm:h-10 rounded-full bg-[#3d4f7c]"></div>
          <div
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c] bg-opacity-10 text-[#3d4f7c] flex-shrink-0"
          >
            <svg
              width="20"
              height="20"
              sm:width="22"
              sm:height="22"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
              />
            </svg>
          </div>
          <div class="min-w-0 flex-1">
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-800 tracking-tight truncate">
              Resident Management
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 truncate">
              Manage all residents and their information.
            </p>
          </div>
        </div>

        <button
          class="inline-flex items-center justify-center gap-2 text-white text-sm sm:text-base font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-lg shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all duration-150 border-0 cursor-pointer whitespace-nowrap"
          style="background-color: #3d4f7c"
          @mouseenter="
            (e) => (e.currentTarget.style.backgroundColor = '#252b3b')
          "
          @mouseleave="
            (e) => (e.currentTarget.style.backgroundColor = '#3d4f7c')
          "
          @click="showAddModal = true"
        >
          <svg
            width="16"
            height="16"
            sm:width="18"
            sm:height="18"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            class="flex-shrink-0"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4v16m8-8H4"
            />
          </svg>
          <span class="truncate">Add Resident</span>
        </button>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-4 sm:mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1 min-w-0">
          <svg
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search residents..."
            class="w-full pl-10 pr-16 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c] focus:ring-opacity-20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white truncate"
          />
          <span
            v-if="searchQuery"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md whitespace-nowrap"
          >
            {{ filteredResidents.length }} found
          </span>
        </div>

        <div class="flex items-center gap-2">
          <button
            v-if="hasActiveFilters"
            @click="resetFilters"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer whitespace-nowrap"
          >
            <svg
              width="11"
              height="11"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
            <span class="hidden sm:inline">Clear</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div
      class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm"
    >
      <!-- Table Header with Dark Blue Gradient -->
      <div
        class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] border-b border-slate-100"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-1.5 h-8 rounded-full bg-white bg-opacity-20 flex-shrink-0"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight truncate">
              Residents List
            </h2>
          </div>
        </div>
      </div>

      <!-- Table with horizontal scroll for mobile -->
      <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] sm:min-w-full table-auto">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th
                class="text-left px-4 sm:px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest"
              >
                Resident
              </th>
              <th
                class="text-left px-4 sm:px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest"
              >
                Email
              </th>
              <th
                class="text-left px-4 sm:px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest hidden sm:table-cell"
              >
                Contact
              </th>
              <th
                class="text-left px-4 sm:px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell"
              >
                Address
              </th>
              <th
                class="text-left px-4 sm:px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-24 sm:w-28"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Loading State -->
            <tr v-if="loading">
              <td colspan="5" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div
                    class="w-12 h-12 border-4 border-slate-200 border-t-[#3d4f7c] rounded-full animate-spin"
                  ></div>
                  <p class="text-sm font-medium text-slate-600">
                    Loading residents...
                  </p>
                </div>
              </td>
            </tr>
            <!-- Rows -->
            <tr
              v-else
              v-for="(resident, index) in paginatedResidents"
              :key="resident.id"
              class="border-b border-slate-50 hover:bg-[#1a1f2e] hover:bg-opacity-5 transition-colors duration-100 group"
            >
              <!-- Resident with Avatar -->
              <td class="px-4 sm:px-6 py-4">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white shadow-sm group-hover:scale-105 transition-transform duration-150 flex-shrink-0"
                    :style="{ background: avatarBg(fullName(resident)) }"
                  >
                    {{ initials(fullName(resident)) }}
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-800 truncate" :title="fullName(resident)">
                      {{ truncateText(fullName(resident), 20) }}
                    </p>
                    <p
                      v-if="resident.information?.suffix"
                      class="text-xs text-slate-400 truncate"
                      :title="resident.information.suffix"
                    >
                      {{ truncateText(resident.information.suffix, 15) }}
                    </p>
                  </div>
                </div>
              </td>

              <!-- Email with copy button -->
              <td class="px-4 sm:px-6 py-4">
                <div class="flex items-center gap-2 min-w-0">
                  <span v-if="resident.email" class="text-sm text-slate-600 truncate" :title="resident.email">
                    {{ truncateText(resident.email, 20) }}
                  </span>
                  <span v-else class="text-slate-300 text-sm">—</span>
                  <button
                    v-if="resident.email"
                    @click="copyToClipboard(resident.email)"
                    class="opacity-0 group-hover:opacity-100 transition-all text-slate-400 hover:text-[#1a1f2e] cursor-pointer flex-shrink-0"
                    title="Copy email"
                  >
                    <svg
                      width="14"
                      height="14"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"
                      />
                    </svg>
                  </button>
                </div>
              </td>

              <!-- Contact - Hidden on mobile -->
              <td class="px-4 sm:px-6 py-4 hidden sm:table-cell">
                <span
                  v-if="resident.information?.contact_number"
                  class="text-sm text-slate-600 inline-flex items-center gap-1.5"
                  :title="resident.information.contact_number"
                >
                  <svg
                    class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                    />
                  </svg>
                  <span class="truncate">{{ formatPhoneNumber(resident.information.contact_number) }}</span>
                </span>
                <span v-else class="text-slate-300 text-sm">—</span>
              </td>

              <!-- Address - Hidden on tablet and mobile -->
              <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                <div
                  v-if="resident.address"
                  class="text-sm text-slate-600 flex items-start gap-1.5 min-w-0"
                  :title="fullAddress(resident)"
                >
                  <svg
                    class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                  <span class="truncate">{{ truncateText(fullAddress(resident), 30) }}</span>
                </div>
                <span v-else class="text-slate-300 text-sm">—</span>
              </td>

              <!-- Actions -->
              <td class="px-4 sm:px-6 py-4">
                <div class="flex items-center gap-1">
                  <!-- Edit -->
                  <button
                    class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg bg-[#1a1f2e] bg-opacity-10 text-[#3d4f7c] border border-[#3d4f7c] border-opacity-20 hover:bg-[#1a1f2e] hover:text-white hover:border-[#1a1f2e] hover:shadow-md hover:shadow-[#1a1f2e]/20 active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                    @click="openEditModal(resident)"
                    title="Edit resident"
                  >
                    <svg
                      width="13"
                      height="13"
                      sm:width="15"
                      sm:height="15"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      viewBox="0 0 24 24"
                      class="group-hover/btn:scale-110 transition-transform duration-150"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      />
                    </svg>
                  </button>
                  <!-- Delete -->
                  <button
                    class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md hover:shadow-red-200 active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                    @click="handleDelete(resident)"
                    title="Delete resident"
                  >
                    <svg
                      width="13"
                      height="13"
                      sm:width="15"
                      sm:height="15"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      viewBox="0 0 24 24"
                      class="group-hover/btn:scale-110 transition-transform duration-150"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!loading && filteredResidents.length === 0">
              <td colspan="5" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3 px-4">
                  <div
                    class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center flex-shrink-0"
                  >
                    <svg
                      class="w-8 h-8 text-slate-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-600 truncate">
                      No residents found
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                      Try adjusting your search
                    </p>
                  </div>
                  <button
                    v-if="hasActiveFilters"
                    @click="resetFilters"
                    class="text-xs font-semibold text-[#1a1f2e] bg-[#1a1f2e] bg-opacity-10 hover:bg-opacity-20 border border-[#1a1f2e] border-opacity-20 px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap"
                  >
                    Clear Filters
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div
        v-if="!loading && filteredResidents.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
      >
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{
            Math.min(currentPage * itemsPerPage, filteredResidents.length)
          }}
          <span class="text-slate-300 mx-1">/</span>
          {{ filteredResidents.length }} results
        </p>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
          <button
            :disabled="currentPage === 1"
            @click="currentPage--"
            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#1a1f2e] hover:text-[#1a1f2e] hover:bg-[#1a1f2e] hover:bg-opacity-5 transition-all cursor-pointer flex-shrink-0"
          >
            <svg
              width="11"
              height="11"
              sm:width="13"
              sm:height="13"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 19l-7-7 7-7"
              />
            </svg>
          </button>

          <template v-for="page in totalPages" :key="page">
            <button
              v-if="
                page === 1 ||
                page === totalPages ||
                Math.abs(page - currentPage) <= 1
              "
              @click="currentPage = page"
              class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer flex-shrink-0"
              :class="
                page === currentPage
                  ? 'bg-[#1a1f2e] text-white shadow-md shadow-[#1a1f2e]/20'
                  : 'text-slate-500 hover:bg-[#1a1f2e] hover:bg-opacity-5 hover:text-[#1a1f2e] border border-transparent'
              "
            >
              {{ page }}
            </button>
            <span
              v-else-if="Math.abs(page - currentPage) === 2"
              class="w-7 h-7 sm:w-8 sm:h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5 flex-shrink-0"
              >…</span
            >
          </template>

          <button
            :disabled="currentPage === totalPages"
            @click="currentPage++"
            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#1a1f2e] hover:text-[#1a1f2e] hover:bg-[#1a1f2e] hover:bg-opacity-5 transition-all cursor-pointer flex-shrink-0"
          >
            <svg
              width="11"
              height="11"
              sm:width="13"
              sm:height="13"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 5l7 7-7 7"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Simple count -->
      <div
        v-else-if="!loading && filteredResidents.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60"
      >
        <p class="text-xs text-slate-400 font-mono text-center sm:text-left">
          Showing
          <span class="font-medium text-slate-700">{{
            filteredResidents.length
          }}</span>
          {{ filteredResidents.length === 1 ? "result" : "results" }}
        </p>
      </div>
    </div>

    <!-- Modals -->
    <AddResidentModal
      v-if="showAddModal"
      :roles="roles"
      @close="showAddModal = false"
      @saved="handleResidentAdded"
    />
    <EditResidentModal
      v-if="showEditModal && selectedResident"
      :resident="selectedResident"
      :roles="roles"
      @close="showEditModal = false"
      @saved="handleResidentUpdated"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import ResidentService from "@/services/Admin/ResidentService";
import AddResidentModal from "@/components/modals/admin/residents/AddResidentModal.vue";
import EditResidentModal from "@/components/modals/admin/residents/EditResidentModal.vue";
import Swal from "sweetalert2";
import {
  getResidents,
  getRoles,
  hasResidentsData,
  hasData,
  setResidents,
  setRoles,
} from "@/utils/dataStore";

// Helper function to truncate long text
function truncateText(text, maxLength = 30) {
  if (!text) return text;
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

const residents = ref([]);
const roles = ref([]);
const loading = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedResident = ref(null);
const searchQuery = ref("");
const purokFilter = ref("all");
const currentPage = ref(1);
const itemsPerPage = 6;

function fullName(resident) {
  const info = resident.information;
  if (!info) return "—";
  return [info.first_name, info.middle_name, info.last_name]
    .filter(Boolean)
    .join(" ");
}

function fullAddress(resident) {
  const addr = resident.address;
  if (!addr) return "—";
  const parts = [];
  if (addr.house_no) parts.push(`#${addr.house_no}`);
  if (addr.purok) parts.push(`Purok ${addr.purok}`);
  if (addr.sitio) parts.push(`Sitio ${addr.sitio}`);
  if (addr.barangay) parts.push(addr.barangay);
  if (addr.city) parts.push(addr.city);
  if (addr.province) parts.push(addr.province);
  return parts.join(", ");
}

function formatPhoneNumber(phone) {
  if (!phone) return "—";
  const cleaned = phone.replace(/\D/g, "");
  if (cleaned.length === 11 && cleaned.startsWith("0")) {
    return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, "$1 $2 $3");
  }
  return phone;
}

function initials(name) {
  if (!name || name === "—") return "?";
  const parts = name.trim().split(" ");
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || "?";
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

const PALETTE = [
  "#2563eb",
  "#7c3aed",
  "#059669",
  "#d97706",
  "#dc2626",
  "#0891b2",
  "#9333ea",
  "#ea580c",
];
function avatarBg(name) {
  if (!name || name === "—") return "#94a3b8";
  let h = 0;
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
  return PALETTE[Math.abs(h) % PALETTE.length];
}

async function copyToClipboard(text) {
  try {
    await navigator.clipboard.writeText(text);
    Swal.fire({
      icon: "success",
      title: "Copied!",
      text: "Email copied to clipboard",
      timer: 1500,
      showConfirmButton: false,
      position: "top-end",
      toast: true,
    });
  } catch (err) {
    console.error("Failed to copy:", err);
  }
}

const hasActiveFilters = computed(
  () => searchQuery.value || purokFilter.value !== "all",
);

const filteredResidents = computed(() => {
  let filtered = residents.value;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (r) =>
        fullName(r).toLowerCase().includes(q) ||
        r.email?.toLowerCase().includes(q) ||
        r.information?.contact_number?.includes(q),
    );
  }
  if (purokFilter.value !== "all") {
    filtered = filtered.filter(
      (r) => r.address?.purok?.toString() === purokFilter.value.toString(),
    );
  }
  return filtered;
});

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredResidents.value.length / itemsPerPage)),
);
const paginatedResidents = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredResidents.value.slice(start, start + itemsPerPage);
});

async function fetchResidents(showLoading = true) {
  if (showLoading) {
    loading.value = true;
  }

  try {
    const data = await ResidentService.getResidents();
    residents.value = data;
    setResidents(data);
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Failed to load residents",
      confirmButtonColor: "#1a1f2e",
    });
  } finally {
    if (showLoading) {
      loading.value = false;
    }
  }
}

async function fetchRoles() {
  try {
    const data = await ResidentService.getRoles();
    roles.value = data;
    setRoles(data);
  } catch (error) {
    console.error("Failed to fetch roles:", error);
  }
}

function openEditModal(resident) {
  selectedResident.value = resident;
  showEditModal.value = true;
}

function resetFilters() {
  searchQuery.value = "";
  purokFilter.value = "all";
  currentPage.value = 1;
}

async function handleDelete(resident) {
  const result = await Swal.fire({
    title: "Delete Resident?",
    html: `Are you sure you want to delete <strong>${truncateText(fullName(resident), 30)}</strong>?<br><br><span class="text-xs text-slate-500">This action cannot be undone.</span>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3d4f7c",
    confirmButtonText: "Yes, delete",
    cancelButtonText: "Cancel",
  });
  if (result.isConfirmed) {
    try {
      await ResidentService.deleteResident(resident.id);
      await fetchResidents(false);
      Swal.fire({
        icon: "success",
        title: "Deleted!",
        text: "Resident has been deleted.",
        timer: 2000,
        showConfirmButton: false,
      });
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Failed to delete resident.",
      });
    }
  }
}

async function handleResidentAdded() {
  showAddModal.value = false;
  await Swal.fire({
    icon: "success",
    title: "Success!",
    text: "Resident has been added successfully.",
    timer: 1500,
    showConfirmButton: false,
  });
  await fetchResidents(false);
}

async function handleResidentUpdated() {
  showEditModal.value = false;
  selectedResident.value = null;
  await Swal.fire({
    icon: "success",
    title: "Success!",
    text: "Resident has been updated successfully.",
    timer: 1500,
    showConfirmButton: false,
  });
  await fetchResidents(false);
}

watch([searchQuery, purokFilter], () => {
  currentPage.value = 1;
});

onMounted(async () => {
  if (hasResidentsData()) {
    residents.value = getResidents();
  } else {
    await fetchResidents(true);
  }

  if (hasData()) {
    roles.value = getRoles();
  } else {
    await fetchRoles();
  }
});
</script>