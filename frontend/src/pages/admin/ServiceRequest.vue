<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">
    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-6">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c] flex-shrink-0"></div>
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c] flex-shrink-0">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Service Requests</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">View, track, and manage all service requests across the barangay</p>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-5 shadow-sm">
      <div class="flex flex-col gap-2.5">
        <div class="relative">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="filters.search" type="text" placeholder="Search requests…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"/>
        </div>
        <div class="flex items-center gap-2">
          <select v-model="filters.type"
            class="flex-1 appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Types</option>
            <option value="barangay_clearance">Barangay Clearance</option>
            <option value="certificate_of_indigency">Certificate of Indigency</option>
            <option value="certificate_of_residency">Certificate of Residency</option>
            <option value="business_permit">Business Permit</option>
          </select>
          <button v-if="hasActiveFilters" @click="resetFilters"
            class="w-9 h-9 flex items-center justify-center text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl transition-all cursor-pointer flex-shrink-0">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">All Service Requests</h2>
          </div>
          <span class="text-xs text-white/50">{{ filteredRequests.length }} result{{ filteredRequests.length !== 1 ? 's' : '' }}</span>
        </div>
      </div>

      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading requests...</p>
      </div>

      <div v-else>
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/60">
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Request ID</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Resident</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Service Type</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Submitted</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-24">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="req in paginatedRequests" :key="req.id"
                class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100">
                <td class="px-6 py-4"><span class="text-xs font-mono text-slate-400">#SR-{{ String(req.id).padStart(4, '0') }}</span></td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                      :style="{ background: getAvatarColor(req.resident_name) }">{{ getInitials(req.resident_name) }}</div>
                    <div>
                      <p class="text-sm font-semibold text-slate-800">{{ req.resident_name }}</p>
                      <p class="text-xs text-slate-400">{{ req.resident_email }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ formatType(req.type) }}</span></td>
                <td class="px-6 py-4">
                  <span :class="statusBadge(req.status)" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                    <span class="relative flex h-1.5 w-1.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(req.status)"></span>
                      <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(req.status)"></span>
                    </span>
                    {{ formatStatus(req.status) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(req.created_at) }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <button @click="openViewModal(req)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white active:scale-95 transition-all cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </td>
              </tr>
              <tr v-if="filteredRequests.length === 0">
                <td colspan="6" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No service requests found</p>
                    <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden">
          <div v-if="filteredRequests.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
              <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No service requests found</p>
            <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
          </div>
          <div class="p-3 space-y-3">
            <div v-for="req in paginatedRequests" :key="req.id"
                 class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
                 style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);"
                 @click="openViewModal(req)">
              <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Service Type</p>
                  <p class="text-base font-bold text-slate-800 leading-tight">{{ formatType(req.type) }}</p>
                </div>
                <span :class="statusBadge(req.status)"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0 mt-0.5">
                  <span class="w-1.5 h-1.5 rounded-full" :class="statusDotSolid(req.status)"></span>
                  {{ formatStatus(req.status) }}
                </span>
              </div>
              <div class="mx-4 h-px bg-slate-100"></div>
              <div class="grid grid-cols-2 gap-0 p-3">
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c12">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  <div class="min-w-0"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Resident</p><p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ req.resident_name }}</p></div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#05966912">
                    <svg width="11" height="11" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                  </div>
                  <div class="min-w-0"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Request ID</p><p class="text-xs font-semibold text-slate-700 mt-0.5 font-mono">#SR-{{ String(req.id).padStart(4, '0') }}</p></div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#d9770612">
                    <svg width="11" height="11" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  </div>
                  <div class="min-w-0"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Submitted</p><p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(req.created_at) }}</p></div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#7c3aed12">
                    <svg width="11" height="11" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <div class="min-w-0"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Preferred Date</p><p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(req.preferred_date) }}</p></div>
                </div>
              </div>
              <div class="mx-3 mb-3 px-3 py-2 bg-[#3d4f7c]/5 rounded-xl flex items-center justify-between border border-[#3d4f7c]/10">
                <span class="text-[11px] font-semibold text-[#3d4f7c]">View Full Details</span>
                <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredRequests.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">{{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }} / {{ filteredRequests.length }}</p>
        <p class="text-xs text-slate-400 sm:hidden">Page {{ currentPage }} of {{ totalPages }}</p>
        <div class="flex items-center gap-1.5">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage ? 'text-white' : 'text-slate-500 hover:bg-[#3d4f7c]/5 border border-transparent'"
              :style="page === currentPage ? 'background:#3d4f7c' : ''">{{ page }}</button>
            <span v-else-if="Math.abs(page - currentPage) === 2" class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5">…</span>
          </template>
          <button :disabled="currentPage === totalPages" @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
      <div v-else-if="!loading && filteredRequests.length > 0" class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ filteredRequests.length }}</span> result{{ filteredRequests.length !== 1 ? 's' : '' }}</p>
      </div>
    </div>

    <!-- ── View Details Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>

        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-3xl sm:shadow-[0_32px_64px_-12px_rgba(0,0,0,0.18)] sm:max-w-4xl lg:max-w-5xl xl:max-w-6xl sm:max-h-[94vh] flex flex-col overflow-hidden">

          <!-- Subtle top accent line -->
          <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>

          <!-- Modal Header -->
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-base font-bold text-slate-800">Request Details</h3>
                <p class="text-xs text-slate-400 font-mono mt-0.5">#SR-{{ selectedRequest ? String(selectedRequest.id).padStart(4, '0') : '' }}</p>
              </div>
            </div>
            <button @click="showViewModal = false"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div v-if="selectedRequest" class="flex-1 overflow-y-auto px-6 py-6 space-y-5">

            <!-- Status + Submitted Row -->
            <div class="flex items-center justify-between bg-slate-50 border border-slate-200/80 px-4 py-3 rounded-2xl">
              <span :class="statusBadge(selectedRequest.status)"
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[11px] font-bold tracking-wide uppercase">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60" :class="statusDot(selectedRequest.status)"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(selectedRequest.status)"></span>
                </span>
                {{ formatStatus(selectedRequest.status) }}
              </span>
              <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium text-slate-500">{{ formatDate(selectedRequest.created_at) }}</span>
              </div>
            </div>

            <!-- Resident Basic Info -->
            <div class="flex items-center gap-4 p-4 border border-slate-200/80 rounded-2xl bg-white shadow-sm shadow-slate-100">
              <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-100 flex-shrink-0 shadow-sm">
                <img v-if="selectedRequest.resident_image"
                  :src="`${baseUrl}/storage/${selectedRequest.resident_image}`"
                  class="w-full h-full object-cover object-top"
                  @error="e => e.target.style.display='none'"/>
                <div v-else class="w-full h-full flex items-center justify-center text-base font-bold text-white"
                  :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                  {{ getInitials(selectedRequest.resident_name) }}
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 truncate tracking-tight">{{ selectedRequest.resident_name }}</p>
                <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ selectedRequest.resident_email }}</p>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span v-if="selectedRequest.resident_sex" class="text-[10px] font-semibold text-slate-500 bg-slate-100 border border-slate-200/80 px-2.5 py-0.5 rounded-full">{{ selectedRequest.resident_sex }}</span>
                  <span v-if="calculateAge(selectedRequest.resident_birth_date)" class="text-[10px] font-semibold text-slate-500 bg-slate-100 border border-slate-200/80 px-2.5 py-0.5 rounded-full">{{ calculateAge(selectedRequest.resident_birth_date) }} yrs</span>
                  <span v-if="selectedRequest.resident_civil_status" class="text-[10px] font-semibold text-slate-500 bg-slate-100 border border-slate-200/80 px-2.5 py-0.5 rounded-full">{{ selectedRequest.resident_civil_status }}</span>
                </div>
              </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

              <!-- Left Column -->
              <div class="space-y-4">

                <!-- Contact Information -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
                  <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact Information</h4>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Contact Number</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_contact || '—' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Birth Date</span>
                      <span class="text-xs font-semibold text-slate-700">{{ formatDate(selectedRequest.resident_birth_date) || '—' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Birth Place</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_birth_place || '—' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Occupation</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_occupation || '—' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Address Information -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
                  <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Address Information</h4>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">House No.</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_house_no || '—' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Purok</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_purok || '—' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Sitio</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_sitio || '—' }}</span>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Right Column -->
              <div class="space-y-4">

                <!-- Service Information -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
                  <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Service Information</h4>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Service Type</span>
                      <span class="text-xs font-semibold text-slate-700">{{ formatType(selectedRequest.type) }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Preferred Date</span>
                      <span class="text-xs font-semibold text-slate-700">{{ formatDate(selectedRequest.preferred_date) }}</span>
                    </div>
                    <div v-if="selectedRequest.purpose" class="px-4 py-3 hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium block mb-2">Purpose</span>
                      <p class="text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200/80 px-3 py-2.5 rounded-xl leading-relaxed">{{ selectedRequest.purpose }}</p>
                    </div>
                  </div>
                </div>

                <!-- Personal Details -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
                  <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Personal Details</h4>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Nationality</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_nationality || 'Filipino' }}</span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                      <span class="text-xs text-slate-400 font-medium">Religion</span>
                      <span class="text-xs font-semibold text-slate-700">{{ selectedRequest.resident_religion || '—' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Supporting Document -->
                <div v-if="selectedRequest.image_path" class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
                  <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                      Supporting Document
                    </p>
                  </div>
                  <div class="p-3 bg-[repeating-linear-gradient(45deg,#f8fafc,#f8fafc_5px,#f1f5f9_5px,#f1f5f9_10px)]">
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                      <img
                        :src="`${baseUrl}/storage/${selectedRequest.image_path}`"
                        alt="Supporting Document"
                        class="w-full h-auto object-contain max-h-36"
                        @error="e => e.target.style.display='none'"
                      />
                    </div>
                  </div>
                </div>

                <!-- Official Remarks -->
                <div v-if="selectedRequest.remarks" class="border border-amber-200/70 bg-amber-50/40 rounded-2xl p-4 shadow-sm">
                  <p class="text-[10px] font-bold text-amber-600/80 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Official Remarks
                  </p>
                  <p class="text-xs text-slate-600 leading-relaxed">{{ selectedRequest.remarks }}</p>
                </div>

              </div>
            </div>

            <!-- Action Buttons -->
            <div v-if="selectedRequest.status !== 'completed' && selectedRequest.status !== 'cancelled' && selectedRequest.status !== 'rejected'"
                class="border-t border-slate-100 pt-4">

              <div v-if="!showRejectInput" class="flex items-center justify-end gap-2">
                <button @click="confirmApprove(selectedRequest)"
                  class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-[11px] font-bold rounded-xl hover:bg-emerald-700 transition-all duration-150 active:scale-95 cursor-pointer shadow-sm shadow-emerald-200 tracking-wide">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  Approve
                </button>
                <button @click="confirmReject(selectedRequest)"
                  class="flex items-center gap-1.5 px-4 py-2 bg-white text-red-500 text-[11px] font-bold rounded-xl border border-red-200 hover:bg-red-50 hover:border-red-300 transition-all duration-150 active:scale-95 cursor-pointer tracking-wide">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Disapprove
                </button>
              </div>

              <div v-if="showRejectInput" class="space-y-2.5">
                <textarea v-model="rejectionRemarks" rows="2" placeholder="Reason for disapproval (optional)..."
                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 bg-slate-50 focus:bg-white resize-none transition-all placeholder-slate-300"></textarea>
                <div class="flex justify-end gap-2">
                  <button @click="showRejectInput = false; rejectionRemarks = ''"
                    class="px-4 py-2 text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">Cancel</button>
                  <button @click="confirmReject(selectedRequest)"
                    class="px-4 py-2 text-[11px] font-bold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-all cursor-pointer shadow-sm shadow-red-200">Confirm Disapprove</button>
                </div>
              </div>
            </div>

            <!-- Status Messages -->
            <div v-else-if="selectedRequest.status === 'completed'" class="flex items-center gap-3 bg-emerald-50/60 border border-emerald-200/70 rounded-2xl px-4 py-3">
              <div class="w-7 h-7 rounded-xl bg-emerald-100 border border-emerald-200/80 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-[11px] font-bold text-emerald-700 tracking-wide">Request Completed</p>
                <p class="text-[10px] text-emerald-500/80 mt-0.5">This request has been successfully processed.</p>
              </div>
            </div>

            <div v-else-if="selectedRequest.status === 'cancelled'" class="flex items-center gap-3 bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3">
              <div class="w-7 h-7 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-600 tracking-wide">Request Cancelled</p>
                <p class="text-[10px] text-slate-400 mt-0.5">This request was cancelled by the resident.</p>
              </div>
            </div>

            <div v-else-if="selectedRequest.status === 'rejected'" class="flex items-center gap-3 bg-red-50/60 border border-red-200/70 rounded-2xl px-4 py-3">
              <div class="w-7 h-7 rounded-xl bg-red-100 border border-red-200/80 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </div>
              <div>
                <p class="text-[11px] font-bold text-red-700 tracking-wide">Request Disapproved</p>
                <p class="text-[10px] text-red-400/80 mt-0.5">This request has been declined.</p>
              </div>
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
import { getServiceRequests, hasServiceRequestsData, setServiceRequests } from "@/utils/dataStore";


const requests     = ref([])
const loading      = ref(false)
const currentPage  = ref(1)
const itemsPerPage = 6

const showViewModal    = ref(false)
const showRejectInput  = ref(false)
const rejectionRemarks = ref('')
const selectedRequest  = ref(null)

const filters = reactive({ search: '', status: '', type: '' })
const baseUrl = import.meta.env.VITE_API_URL || ''

function calculateAge(birthDate) {
  if (!birthDate) return null
  const today = new Date(), birth = new Date(birthDate)
  let age = today.getFullYear() - birth.getFullYear()
  if (today.getMonth() - birth.getMonth() < 0 || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--
  return age
}

const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']
function getAvatarColor(name = '') { let h = 0; for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h); return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length] }
function getInitials(name = '') { const p = name.trim().split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
function formatType(type = '') { return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }
function formatStatus(status = '') { return status.charAt(0).toUpperCase() + status.slice(1) }
function formatDate(date) { if (!date) return '—'; return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }
function statusBadge(s) { return { pending: 'bg-amber-50 text-amber-700 border border-amber-200', processing: 'bg-slate-100 text-slate-600 border border-slate-200', approved: 'bg-emerald-50 text-emerald-700 border border-emerald-200', completed: 'bg-emerald-50 text-emerald-700 border border-emerald-200', disapproved: 'bg-red-50 text-red-700 border border-red-200', rejected: 'bg-red-50 text-red-700 border border-red-200', cancelled: 'bg-slate-100 text-slate-600 border border-slate-200' }[s] || 'bg-slate-100 text-slate-600' }
function statusDot(s) { return { pending: 'bg-amber-400', processing: 'bg-slate-400', approved: 'bg-emerald-400', completed: 'bg-emerald-400', disapproved: 'bg-red-400', rejected: 'bg-red-400', cancelled: 'bg-slate-400' }[s] || 'bg-slate-400' }
function statusDotSolid(s) { return { pending: 'bg-amber-500', processing: 'bg-slate-500', approved: 'bg-emerald-500', completed: 'bg-emerald-500', disapproved: 'bg-red-500', rejected: 'bg-red-500', cancelled: 'bg-slate-500' }[s] || 'bg-slate-500' }

const hasActiveFilters  = computed(() => filters.search || filters.status || filters.type)
const filteredRequests  = computed(() => { let r = requests.value; if (filters.search) { const s = filters.search.toLowerCase(); r = r.filter(x => x.resident_name?.toLowerCase().includes(s) || x.type?.toLowerCase().includes(s)) } if (filters.status) r = r.filter(x => x.status === filters.status); if (filters.type) r = r.filter(x => x.type === filters.type); return r })
const totalPages        = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredRequests.value.slice(s, s + itemsPerPage) })

async function fetchRequests(showLoading = true) {
  if (showLoading) loading.value = true;
  try {
    const data = await ServiceRequestService.getAll();
    setServiceRequests(data);           
    requests.value = filterByStatus(data);
  } catch {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load requests.', timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
  } finally {
    if (showLoading) loading.value = false;
  }
}

function filterByStatus(data) {
  const arr = Array.isArray(data) ? data : (data.data ?? []);
  return arr.filter(r => r.status === 'pending');
}

function openViewModal(req) { selectedRequest.value = req; showRejectInput.value = false; rejectionRemarks.value = ''; showViewModal.value = true }

async function confirmApprove(req) {
  const result = await Swal.fire({ title: 'Approve Request?', text: `Approve "${formatType(req.type)}" for ${req.resident_name}?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#059669', cancelButtonColor: '#3d4f7c', confirmButtonText: 'Yes, approve' })
  if (!result.isConfirmed) return
  try {
    await ServiceRequestService.approve(req.id);
    const stored = getServiceRequests() ?? [];
    const updated = stored.map(r => r.id === req.id ? { ...r, status: 'approved' } : r);
    setServiceRequests(updated);
    requests.value = requests.value.filter(r => r.id !== req.id);
    showViewModal.value = false;
    showSuccess('Request approved successfully');
  } catch { showError('Failed to approve request') }
}

async function confirmReject(req) {
  const result = await Swal.fire({ title: 'Disapprove Request?', text: `Disapprove "${formatType(req.type)}" for ${req.resident_name}?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#3d4f7c', confirmButtonText: 'Yes, disapprove' })
  if (!result.isConfirmed) return
  try {
    await ServiceRequestService.reject(req.id, { remarks: rejectionRemarks.value });

    const stored = getServiceRequests() ?? [];
    const updated = stored.map(r =>
      r.id === req.id ? { ...r, status: 'disapproved', remarks: rejectionRemarks.value } : r
    );
    setServiceRequests(updated);

    requests.value = requests.value.filter(r => r.id !== req.id);

    showViewModal.value = false;
    showRejectInput.value = false;
    rejectionRemarks.value = '';
    showSuccess('Request disapproved');
  } catch { showError('Failed to disapprove request') }
}

function resetFilters() { filters.search = ''; filters.status = ''; filters.type = ''; currentPage.value = 1 }
const showSuccess = msg => Swal.fire({ icon: 'success', title: 'Success', text: msg, timer: 2000, showConfirmButton: false, position: 'top-end', toast: true })
const showError   = msg => Swal.fire({ icon: 'error', title: 'Error', text: msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })

watch(filters, () => { currentPage.value = 1 }, { deep: true })
onMounted(() => {
  if (hasServiceRequestsData()) {
    requests.value = filterByStatus(getServiceRequests());
  } else {
    fetchRequests(true);
  }
});</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>