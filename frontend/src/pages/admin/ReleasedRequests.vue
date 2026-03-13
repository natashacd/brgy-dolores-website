<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex items-center gap-4">
        <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c]"></div>
        <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c]">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Released Requests</h1>
          <p class="text-sm text-slate-500 mt-1">View all completed requests that have been released to residents</p>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by resident name or service type…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.timeframe"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
          </select>
          <select v-model="filters.type"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Types</option>
            <option value="barangay_clearance">Barangay Clearance</option>
            <option value="certificate_of_indigency">Certificate of Indigency</option>
            <option value="certificate_of_residency">Certificate of Residency</option>
            <option value="business_permit">Business Permit</option>
          </select>
          <button v-if="hasActiveFilters" @click="resetFilters"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer">
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
      <!-- Table Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">Released Requests List</h2>
          </div>
          <span class="text-xs text-white/50">
            {{ filteredRequests.length }} result{{ filteredRequests.length !== 1 ? 's' : '' }}
          </span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading released requests...</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Request ID</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Resident</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Service Type</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Completed Date</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Released Date</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Released By</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Release Notes</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-28">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="req in paginatedRequests" :key="req.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">

              <!-- ID -->
              <td class="px-6 py-4">
                <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">#SR-{{ String(req.id).padStart(4, '0') }}</span>
              </td>

              <!-- Resident -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-sm"
                    :style="{ background: getAvatarColor(req.resident_name) }">
                    {{ getInitials(req.resident_name) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-800">{{ req.resident_name }}</p>
                    <p class="text-xs text-slate-400">{{ req.resident_email }}</p>
                  </div>
                </div>
              </td>

              <!-- Type -->
              <td class="px-6 py-4">
                <span class="text-sm text-slate-600">{{ formatType(req.type) }}</span>
              </td>

              <!-- Completed Date -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ formatDate(req.completed_at) }}
                </div>
              </td>

              <!-- Released Date -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ formatDate(req.released_at) }}
                </div>
              </td>

              <!-- Released By -->
              <td class="px-6 py-4">
                <span class="text-sm text-slate-600">{{ req.released_by || 'Admin' }}</span>
              </td>

              <!-- Release Notes -->
              <td class="px-6 py-4 max-w-xs">
                <div v-if="req.release_remarks" class="flex items-start gap-1.5">
                  <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                  </svg>
                  <span class="text-sm text-slate-600 line-clamp-2">{{ req.release_remarks }}</span>
                </div>
                <span v-else class="text-sm text-slate-400 italic">—</span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <!-- View Details -->
                  <button @click="openViewModal(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!loading && filteredRequests.length === 0">
              <td colspan="8" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No released requests found</p>
                    <p class="text-xs text-slate-400 mt-0.5">Completed requests that are marked as released will appear here</p>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredRequests.length > itemsPerPage"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }}
          <span class="text-slate-300 mx-1">/</span>
          {{ filteredRequests.length }} results
        </p>
        <div class="flex items-center gap-1.5">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] hover:bg-[#3d4f7c]/5 transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage ? 'text-white shadow-md' : 'text-slate-500 hover:bg-[#3d4f7c]/5 hover:text-[#3d4f7c] border border-transparent'"
              :style="page === currentPage ? 'background:#3d4f7c' : ''">{{ page }}</button>
            <span v-else-if="Math.abs(page - currentPage) === 2" class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5">…</span>
          </template>
          <button :disabled="currentPage === totalPages" @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] hover:bg-[#3d4f7c]/5 transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>
      <div v-else-if="!loading && filteredRequests.length > 0"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono">
          Showing <span class="font-medium text-slate-700">{{ filteredRequests.length }}</span> result{{ filteredRequests.length !== 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- View Details Modal -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
                <h3 class="text-base font-semibold text-slate-800">Released Request Details</h3>
                <span class="px-2 py-0.5 bg-[#3d4f7c]/10 text-[#3d4f7c] text-xs rounded-full font-mono">#SR-{{ selectedRequest ? String(selectedRequest.id).padStart(4, '0') : '' }}</span>
              </div>
              <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            
            <!-- Body -->
            <div v-if="selectedRequest" class="px-6 py-5 space-y-4">
              <!-- Release Banner -->
              <div class="bg-[#3d4f7c]/5 border border-[#3d4f7c]/20 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#3d4f7c]/10 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-semibold text-[#3d4f7c]">Request Released</p>
                  <p class="text-xs text-[#3d4f7c]/70">This request was released on {{ formatDate(selectedRequest.released_at) }}</p>
                </div>
              </div>

              <!-- Resident Info -->
              <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold text-white shadow-md"
                  :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                  {{ getInitials(selectedRequest.resident_name) }}
                </div>
                <div>
                  <p class="font-bold text-slate-800">{{ selectedRequest.resident_name }}</p>
                  <p class="text-xs text-slate-400">{{ selectedRequest.resident_email }}</p>
                </div>
              </div>

              <!-- Details Grid -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-xs text-slate-400">Service Type</p>
                  <p class="text-sm font-medium text-slate-700">{{ formatType(selectedRequest.type) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Completed Date</p>
                  <p class="text-sm text-slate-700">{{ formatDate(selectedRequest.completed_at) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Released Date</p>
                  <p class="text-sm text-slate-700">{{ formatDate(selectedRequest.released_at) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Released By</p>
                  <p class="text-sm text-slate-700">{{ selectedRequest.released_by || 'Admin' }}</p>
                </div>
              </div>

              <!-- Purpose -->
              <div v-if="selectedRequest.purpose">
                <p class="text-xs text-slate-400">Purpose</p>
                <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg mt-1">{{ selectedRequest.purpose }}</p>
              </div>

              <!-- Release Notes -->
              <div v-if="selectedRequest.release_remarks">
                <p class="text-xs text-slate-400">Release Notes</p>
                <p class="text-sm text-slate-700 bg-[#3d4f7c]/5 p-3 rounded-lg mt-1">{{ selectedRequest.release_remarks }}</p>
              </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end">
              <button @click="showViewModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import Swal from 'sweetalert2'

// ── State ────────────────────────────────────────────────────────
const loading = ref(false)
const currentPage = ref(1)
const itemsPerPage = 8
const showViewModal = ref(false)
const selectedRequest = ref(null)

const filters = reactive({
  search: '',
  timeframe: '',
  type: ''
})

// ── Sample Data ──────────────────────────────────────────────────
const releasedRequests = ref([
  {
    id: 101,
    resident_name: 'Maria Santos',
    resident_email: 'maria.santos@email.com',
    resident_contact: '0912 345 6789',
    type: 'certificate_of_indigency',
    purpose: 'For financial assistance application',
    completed_at: '2024-03-18',
    released_at: '2024-03-20',
    released_by: 'Admin',
    release_remarks: 'Released to resident personally',
    address: { house_no: '123', purok: '3', sitio: 'Maligaya' }
  },
  {
    id: 102,
    resident_name: 'Pedro Reyes',
    resident_email: 'pedro.r@email.com',
    resident_contact: '0945 678 9012',
    type: 'certificate_of_residency',
    purpose: 'For bank requirement',
    completed_at: '2024-03-15',
    released_at: '2024-03-16',
    released_by: 'Admin',
    release_remarks: 'Certificate picked up by representative',
    address: { house_no: '101', purok: '5', sitio: 'Masunurin' }
  }
])

// ── Helpers ──────────────────────────────────────────────────────
const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']

function getAvatarColor(name = '') {
  let h = 0
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h)
  return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length]
}

function getInitials(name = '') {
  const p = name.trim().split(' ')
  return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase()
}

function formatType(type = '') {
  return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function getMonthName() {
  return new Date().toLocaleDateString('en-US', { month: 'long' })
}

function formatAddress(req) {
  const addr = req.address
  if (!addr) return '—'
  const parts = []
  if (addr.house_no) parts.push(`#${addr.house_no}`)
  if (addr.purok) parts.push(`Purok ${addr.purok}`)
  if (addr.sitio) parts.push(addr.sitio)
  return parts.join(', ') || '—'
}

// ── Computed ─────────────────────────────────────────────────────
const hasActiveFilters = computed(() => filters.search || filters.timeframe || filters.type)

const filteredRequests = computed(() => {
  let filtered = [...releasedRequests.value]
  
  if (filters.search) {
    const s = filters.search.toLowerCase()
    filtered = filtered.filter(r => 
      r.resident_name?.toLowerCase().includes(s) || 
      r.type?.toLowerCase().includes(s)
    )
  }
  
  if (filters.type) {
    filtered = filtered.filter(r => r.type === filters.type)
  }
  
  if (filters.timeframe) {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    
    filtered = filtered.filter(r => {
      const date = new Date(r.released_at)
      switch (filters.timeframe) {
        case 'today':
          return date >= today
        case 'week':
          const weekAgo = new Date(today)
          weekAgo.setDate(weekAgo.getDate() - 7)
          return date >= weekAgo
        case 'month':
          return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
        case 'year':
          return date.getFullYear() === now.getFullYear()
        default:
          return true
      }
    })
  }
  
  return filtered
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredRequests.value.slice(start, start + itemsPerPage)
})

const stats = computed(() => {
  const total = filteredRequests.value.length
  const thisMonth = filteredRequests.value.filter(r => {
    const date = new Date(r.released_at)
    const now = new Date()
    return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
  }).length
  const withRemarks = filteredRequests.value.filter(r => r.release_remarks).length
  
  return { total, thisMonth, withRemarks }
})

// ── Methods ──────────────────────────────────────────────────────
function openViewModal(req) {
  selectedRequest.value = req
  showViewModal.value = true
}

function resetFilters() {
  filters.search = ''
  filters.timeframe = ''
  filters.type = ''
  currentPage.value = 1
}

watch(filters, () => { currentPage.value = 1 }, { deep: true })
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-clamp: 2;
}
</style>