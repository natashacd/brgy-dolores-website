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
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Service Requests</h1>
          <p class="text-sm text-slate-500 mt-1">View, track, and manage all service requests across the barangay</p>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div v-for="stat in statsCards" :key="stat.label"
        class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ stat.label }}</p>
            <p class="text-2xl font-bold mt-1" :class="stat.color">{{ stat.value }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ stat.sub }}</p>
          </div>
          <div class="w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform"
            :class="stat.bg">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" :class="stat.iconColor">
              <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon"/>
            </svg>
          </div>
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
          <select v-model="filters.status"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
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

      <!-- Gradient Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">All Service Requests</h2>
          </div>
          <span class="text-xs text-white/50">
            {{ requests.length }} result{{ requests.length !== 1 ? 's' : '' }}
          </span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading requests...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Request ID</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Resident</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Service Type</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Assigned To</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Submitted</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-36">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="req in paginatedRequests" :key="req.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">

              <!-- ID -->
              <td class="px-6 py-4">
                <span class="text-xs font-mono text-slate-400">#SR-{{ String(req.id).padStart(4, '0') }}</span>
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

              <!-- Status -->
              <td class="px-6 py-4">
                <span :class="statusBadge(req.status)"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                  <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                      :class="statusDot(req.status)"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5"
                      :class="statusDotSolid(req.status)"></span>
                  </span>
                  {{ formatStatus(req.status) }}
                </span>
              </td>

              <!-- Assigned To -->
              <td class="px-6 py-4">
                <div v-if="req.staff_name" class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white"
                    :style="{ background: getAvatarColor(req.staff_name) }">
                    {{ getInitials(req.staff_name) }}
                  </div>
                  <span class="text-sm text-slate-600">{{ req.staff_name }}</span>
                </div>
                <span v-else class="text-sm text-slate-400 italic">Unassigned</span>
              </td>

              <!-- Date -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ formatDate(req.created_at) }}
                </div>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <!-- View -->
                  <button @click="openViewModal(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>

                  <!-- Reassign -->
                  <button @click="openReassignModal(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-500 hover:text-white hover:border-amber-500 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Reassign">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </button>

                  <!-- Approve (not completed/cancelled) -->
                  <button v-if="req.status !== 'completed' && req.status !== 'cancelled'"
                    @click="confirmApprove(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Approve / Complete">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </button>

                  <!-- Cancel (not completed/cancelled) -->
                  <button v-if="req.status !== 'completed' && req.status !== 'cancelled'"
                    @click="confirmCancel(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Cancel">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!loading && filteredRequests.length === 0">
              <td colspan="7" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <p class="text-sm font-bold text-slate-600">No service requests found</p>
                  <p class="text-xs text-slate-400">Try adjusting your search or filter criteria</p>
                  <button v-if="hasActiveFilters" @click="resetFilters"
                    class="text-xs font-semibold px-4 py-2 rounded-xl transition-all cursor-pointer"
                    style="color:#3d4f7c; background:#3d4f7c15; border: 1px solid #3d4f7c30">
                    Clear Filters
                  </button>
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

    <!-- ── View Details Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <!-- Header -->
            <div class="px-6 py-4 rounded-t-2xl bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
              <h3 class="text-base font-semibold text-white">Request Details</h3>
              <button @click="showViewModal = false" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-1.5 transition-all cursor-pointer">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <!-- Body -->
            <div v-if="selectedRequest" class="px-6 py-5 space-y-4">
              <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold text-white shadow"
                  :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                  {{ getInitials(selectedRequest.resident_name) }}
                </div>
                <div>
                  <p class="font-bold text-slate-800">{{ selectedRequest.resident_name }}</p>
                  <p class="text-xs text-slate-400">{{ selectedRequest.resident_email }}</p>
                </div>
                <span :class="statusBadge(selectedRequest.status)"
                  class="ml-auto inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                  {{ formatStatus(selectedRequest.status) }}
                </span>
              </div>
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Request ID</p>
                  <p class="font-mono text-slate-700">#SR-{{ String(selectedRequest.id).padStart(4, '0') }}</p>
                </div>
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Service Type</p>
                  <p class="text-slate-700">{{ formatType(selectedRequest.type) }}</p>
                </div>
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Contact</p>
                  <p class="text-slate-700">{{ selectedRequest.resident_contact || '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Assigned To</p>
                  <p class="text-slate-700">{{ selectedRequest.staff_name || 'Unassigned' }}</p>
                </div>
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Submitted</p>
                  <p class="text-slate-700">{{ formatDate(selectedRequest.created_at) }}</p>
                </div>
                <div>
                  <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Last Updated</p>
                  <p class="text-slate-700">{{ formatDate(selectedRequest.updated_at) }}</p>
                </div>
              </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
              <button @click="showViewModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all cursor-pointer">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Reassign Modal ── -->
    <Transition name="modal">
      <div v-if="showReassignModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showReassignModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 rounded-t-2xl bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
              <h3 class="text-base font-semibold text-white">Reassign Request</h3>
              <button @click="showReassignModal = false" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-1.5 transition-all cursor-pointer">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="px-6 py-5">
              <p class="text-sm text-slate-500 mb-4">
                Reassigning <span class="font-semibold text-slate-700">#SR-{{ selectedRequest ? String(selectedRequest.id).padStart(4, '0') : '' }}</span>
                — select a staff member below.
              </p>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1.5">Assign To</label>
              <select v-model="selectedStaffId"
                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white">
                <option value="">— Select staff member —</option>
                <option v-for="s in staff" :key="s.id" :value="s.id">
                  {{ s.name }} ({{ s.role }})
                </option>
              </select>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
              <button @click="showReassignModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all cursor-pointer">
                Cancel
              </button>
              <button @click="submitReassign" :disabled="!selectedStaffId || saving"
                class="px-5 py-2 text-sm font-semibold text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all cursor-pointer"
                style="background:#3d4f7c"
                @mouseenter="e => e.currentTarget.style.backgroundColor='#252b3b'"
                @mouseleave="e => e.currentTarget.style.backgroundColor='#3d4f7c'">
                {{ saving ? 'Reassigning…' : 'Confirm Reassign' }}
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
import ServiceRequestService from '@/services/Admin/ServiceRequestService'
import Swal from 'sweetalert2'

// ── State ────────────────────────────────────────────────────────
const requests    = ref([])
const staff       = ref([])
const loading     = ref(false)
const saving      = ref(false)
const currentPage = ref(1)
const itemsPerPage = 6

const showViewModal     = ref(false)
const showReassignModal = ref(false)
const selectedRequest   = ref(null)
const selectedStaffId   = ref('')

const filters = reactive({ search: '', status: '', type: '' })

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
function formatStatus(status = '') {
  return status.charAt(0).toUpperCase() + status.slice(1)
}
function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
function statusBadge(status) {
  return {
    pending:    'bg-amber-50 text-amber-700 border border-amber-200',
    processing: 'bg-blue-50 text-blue-700 border border-blue-200',
    completed:  'bg-emerald-50 text-emerald-700 border border-emerald-200',
    cancelled:  'bg-slate-100 text-slate-600 border border-slate-200',
  }[status] || 'bg-slate-100 text-slate-600'
}
function statusDot(status) {
  return { pending: 'bg-amber-400', processing: 'bg-blue-400', completed: 'bg-emerald-400', cancelled: 'bg-slate-400' }[status] || 'bg-slate-400'
}
function statusDotSolid(status) {
  return { pending: 'bg-amber-500', processing: 'bg-blue-500', completed: 'bg-emerald-500', cancelled: 'bg-slate-500' }[status] || 'bg-slate-500'
}

// ── Computed ─────────────────────────────────────────────────────
const hasActiveFilters = computed(() => filters.search || filters.status || filters.type)

const filteredRequests = computed(() => {
  let r = requests.value
  if (filters.search) {
    const s = filters.search.toLowerCase()
    r = r.filter(x => x.resident_name?.toLowerCase().includes(s) || x.type?.toLowerCase().includes(s))
  }
  if (filters.status) r = r.filter(x => x.status === filters.status)
  if (filters.type)   r = r.filter(x => x.type === filters.type)
  return r
})

const totalPages       = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredRequests.value.slice(start, start + itemsPerPage)
})

const statsCards = computed(() => {
  const total      = requests.value.length
  const pending    = requests.value.filter(r => r.status === 'pending').length
  const processing = requests.value.filter(r => r.status === 'processing').length
  const completed  = requests.value.filter(r => r.status === 'completed').length
  return [
    { label: 'Total Requests', value: total,      sub: 'All time',         color: 'text-slate-800',   bg: 'bg-blue-50',    iconColor: 'text-blue-600',    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
    { label: 'Pending',        value: pending,    sub: 'Awaiting action',  color: 'text-amber-500',   bg: 'bg-amber-50',   iconColor: 'text-amber-500',   icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'In Progress',    value: processing, sub: 'Being processed',  color: 'text-blue-500',    bg: 'bg-blue-50',    iconColor: 'text-blue-500',    icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
    { label: 'Completed',      value: completed,  sub: 'Successfully done', color: 'text-emerald-600', bg: 'bg-emerald-50', iconColor: 'text-emerald-600', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
  ]
})

async function fetchRequests() {
  loading.value = true
  try {
    const data = await ServiceRequestService.getRequests({ per_page: 1000 })
    requests.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch (e) {
    showError('Failed to load service requests')
  } finally {
    loading.value = false
  }
}

async function fetchStaff() {
  try {
    const data = await ServiceRequestService.getStaff()
    staff.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch (e) {
    console.error('Failed to load staff', e)
  }
}

function openViewModal(req) {
  selectedRequest.value = req
  showViewModal.value   = true
}

function openReassignModal(req) {
  selectedRequest.value = req
  selectedStaffId.value = ''
  showReassignModal.value = true
}

async function submitReassign() {
  if (!selectedStaffId.value) return
  saving.value = true
  try {
    await serviceRequestService.reassign(selectedRequest.value.id, selectedStaffId.value)
    showSuccess('Request reassigned successfully')
    showReassignModal.value = false
    await fetchRequests()
  } catch { showError('Failed to reassign request') }
  finally { saving.value = false }
}

async function confirmApprove(req) {
  const result = await Swal.fire({
    title: 'Complete Request?',
    text: `Mark "${formatType(req.type)}" for ${req.resident_name} as completed?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#059669',
    cancelButtonColor: '#3d4f7c',
    confirmButtonText: 'Yes, complete it',
  })
  if (!result.isConfirmed) return
  try {
    await serviceRequestService.approve(req.id)
    showSuccess('Request marked as completed')
    await fetchRequests()
  } catch { showError('Failed to approve request') }
}

async function confirmCancel(req) {
  const result = await Swal.fire({
    title: 'Cancel Request?',
    text: `Cancel "${formatType(req.type)}" for ${req.resident_name}? This cannot be undone.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#3d4f7c',
    confirmButtonText: 'Yes, cancel it',
  })
  if (!result.isConfirmed) return
  try {
    await serviceRequestService.cancel(req.id)
    showSuccess('Request cancelled')
    await fetchRequests()
  } catch { showError('Failed to cancel request') }
}

function resetFilters() {
  filters.search = ''
  filters.status = ''
  filters.type   = ''
  currentPage.value = 1
}

const showSuccess = (msg) => Swal.fire({ icon: 'success', title: 'Success', text: msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })
const showError   = (msg) => Swal.fire({ icon: 'error',   title: 'Error',   text: msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })

watch(filters, () => { currentPage.value = 1 }, { deep: true })

onMounted(() => {
  fetchRequests()
  fetchStaff()
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>