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
          <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Released Requests</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">View all completed requests that have been released to residents</p>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-5 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="filters.search" type="text" placeholder="Search requests…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"/>
        </div>
        <div class="flex items-center gap-2">
          <select v-model="filters.type"
            class="w-full sm:w-auto appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
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

      <!-- Header -->
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">Released Requests List</h2>
          </div>
          <span class="text-xs text-white/50">{{ filteredRequests.length }} result{{ filteredRequests.length !== 1 ? 's' : '' }}</span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading released requests...</p>
      </div>

      <div v-else>

        <!-- ── DESKTOP TABLE ── -->
        <div class="hidden md:block overflow-x-auto">
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
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-24">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="req in paginatedRequests" :key="req.id"
                class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100">
                <td class="px-6 py-4">
                  <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">#SR-{{ String(req.id).padStart(4, '0') }}</span>
                </td>
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
                  <div class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(req.completed_at) }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ formatDate(req.released_at) }}
                  </div>
                </td>
                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ req.released_by || 'Admin' }}</span></td>
                <td class="px-6 py-4 max-w-xs">
                  <span v-if="req.release_remarks" class="text-sm text-slate-600 line-clamp-2">{{ req.release_remarks }}</span>
                  <span v-else class="text-sm text-slate-400 italic">—</span>
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

              <!-- Empty -->
              <tr v-if="filteredRequests.length === 0">
                <td colspan="8" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No released requests found</p>
                    <p class="text-xs text-slate-400">Completed requests marked as released will appear here</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── MOBILE CARD LIST ── -->
        <div class="md:hidden">

          <!-- Empty -->
          <div v-if="filteredRequests.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
              <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No released requests found</p>
            <p class="text-xs text-slate-400">Completed requests marked as released will appear here</p>
          </div>

          <!-- Cards -->
          <div class="p-3 space-y-3">
            <div v-for="req in paginatedRequests" :key="req.id"
                 class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
                 style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);"
                 @click="openViewModal(req)">

              <!-- Card top: service type + released badge -->
              <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Service Type</p>
                  <p class="text-base font-bold text-slate-800 leading-tight">{{ formatType(req.type) }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0 mt-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Released
                </span>
              </div>

              <!-- Divider -->
              <div class="mx-4 h-px bg-slate-100"></div>

              <!-- Detail grid 2×2 -->
              <div class="grid grid-cols-2 gap-0 p-3">

                <!-- Resident -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c12">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Resident</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ req.resident_name }}</p>
                  </div>
                </div>

                <!-- Request ID -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#05966912">
                    <svg width="11" height="11" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Request ID</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 font-mono">#SR-{{ String(req.id).padStart(4, '0') }}</p>
                  </div>
                </div>

                <!-- Released Date -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#d9770612">
                    <svg width="11" height="11" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Released</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(req.released_at) }}</p>
                  </div>
                </div>

                <!-- Released By -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#7c3aed12">
                    <svg width="11" height="11" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Released By</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ req.released_by || 'Admin' }}</p>
                  </div>
                </div>

              </div>

              <!-- Release notes strip -->
              <div v-if="req.release_remarks" class="mx-3 mb-3 px-3 py-2.5 bg-[#3d4f7c]/5 rounded-xl border border-[#3d4f7c]/10">
                <p class="text-[9px] font-bold text-[#3d4f7c] uppercase tracking-wider mb-0.5">Release Notes</p>
                <p class="text-xs text-slate-600 line-clamp-2">{{ req.release_remarks }}</p>
              </div>
              <div v-else class="mx-3 mb-3 px-3 py-2 bg-[#3d4f7c]/5 rounded-xl flex items-center justify-between border border-[#3d4f7c]/10">
                <span class="text-[11px] font-semibold text-[#3d4f7c]">View Full Details</span>
                <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>

            </div>
          </div>
        </div>

      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredRequests.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }} / {{ filteredRequests.length }}
        </p>
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

    <!-- View Details Modal -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-2xl sm:shadow-2xl sm:max-w-2xl sm:max-h-[90vh] flex flex-col">

          <!-- Modal Header -->
          <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Released Request Details</h3>
                <span class="text-xs text-slate-400 font-mono">#SR-{{ selectedRequest ? String(selectedRequest.id).padStart(4, '0') : '' }}</span>
              </div>
            </div>
            <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div v-if="selectedRequest" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">

            <!-- Released Banner -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-emerald-700">Request Released</p>
                <p class="text-xs text-emerald-500">Released on {{ formatDate(selectedRequest.released_at) }} by {{ selectedRequest.released_by || 'Admin' }}</p>
              </div>
            </div>

            <!-- Resident Card -->
            <div class="bg-[#3d4f7c] rounded-xl p-4 text-white flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center text-base font-bold border-2 border-white/30 flex-shrink-0"
                :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                {{ getInitials(selectedRequest.resident_name) }}
              </div>
              <div class="min-w-0">
                <p class="font-bold text-base truncate">{{ selectedRequest.resident_name }}</p>
                <p class="text-xs text-white/70 truncate">{{ selectedRequest.resident_email }}</p>
                <p class="text-[10px] text-white/50 mt-0.5">{{ selectedRequest.resident_contact || '' }}</p>
              </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-slate-50 rounded-xl p-3 space-y-1.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Service Type</p>
                <p class="text-sm font-semibold text-slate-700">{{ formatType(selectedRequest.type) }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-3 space-y-1.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Completed Date</p>
                <p class="text-sm font-semibold text-slate-700">{{ formatDate(selectedRequest.completed_at) }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-3 space-y-1.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Released Date</p>
                <p class="text-sm font-semibold text-slate-700">{{ formatDate(selectedRequest.released_at) }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl p-3 space-y-1.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Released By</p>
                <p class="text-sm font-semibold text-slate-700">{{ selectedRequest.released_by || 'Admin' }}</p>
              </div>
            </div>

            <div v-if="selectedRequest.purpose" class="bg-slate-50 rounded-xl p-3.5">
              <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Purpose</p>
              <p class="text-sm text-slate-700">{{ selectedRequest.purpose }}</p>
            </div>

            <div v-if="selectedRequest.release_remarks" class="bg-[#3d4f7c]/5 border border-[#3d4f7c]/15 rounded-xl p-3.5">
              <p class="text-[10px] font-bold text-[#3d4f7c] uppercase tracking-wider mb-1">Release Notes</p>
              <p class="text-sm text-slate-700">{{ selectedRequest.release_remarks }}</p>
            </div>

            <div class="flex justify-end pt-1">
              <button @click="showViewModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all cursor-pointer">
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

const loading     = ref(false)
const currentPage = ref(1)
const itemsPerPage = 8
const showViewModal   = ref(false)
const selectedRequest = ref(null)

const filters = reactive({ search: '', timeframe: '', type: '' })

const releasedRequests = ref([
  { id: 101, resident_name: 'Maria Santos', resident_email: 'maria.santos@email.com', resident_contact: '0912 345 6789', type: 'certificate_of_indigency', purpose: 'For financial assistance application', completed_at: '2024-03-18', released_at: '2024-03-20', released_by: 'Admin', release_remarks: 'Released to resident personally', address: { house_no: '123', purok: '3', sitio: 'Maligaya' } },
  { id: 102, resident_name: 'Pedro Reyes', resident_email: 'pedro.r@email.com', resident_contact: '0945 678 9012', type: 'certificate_of_residency', purpose: 'For bank requirement', completed_at: '2024-03-15', released_at: '2024-03-16', released_by: 'Admin', release_remarks: 'Certificate picked up by representative', address: { house_no: '101', purok: '5', sitio: 'Masunurin' } }
])

const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']
function getAvatarColor(name = '') { let h = 0; for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h); return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length] }
function getInitials(name = '') { const p = name.trim().split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
function formatType(type = '') { return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }
function formatDate(date) { if (!date) return '—'; return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }

const hasActiveFilters = computed(() => filters.search || filters.timeframe || filters.type)
const filteredRequests = computed(() => {
  let filtered = [...releasedRequests.value]
  if (filters.search) { const s = filters.search.toLowerCase(); filtered = filtered.filter(r => r.resident_name?.toLowerCase().includes(s) || r.type?.toLowerCase().includes(s)) }
  if (filters.type) filtered = filtered.filter(r => r.type === filters.type)
  if (filters.timeframe) {
    const now = new Date(), today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    filtered = filtered.filter(r => {
      const date = new Date(r.released_at)
      if (filters.timeframe === 'today') return date >= today
      if (filters.timeframe === 'week') { const w = new Date(today); w.setDate(w.getDate() - 7); return date >= w }
      if (filters.timeframe === 'month') return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
      if (filters.timeframe === 'year') return date.getFullYear() === now.getFullYear()
      return true
    })
  }
  return filtered
})
const totalPages        = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredRequests.value.slice(s, s + itemsPerPage) })

function openViewModal(req) { selectedRequest.value = req; showViewModal.value = true }
function resetFilters() { filters.search = ''; filters.timeframe = ''; filters.type = ''; currentPage.value = 1 }

watch(filters, () => { currentPage.value = 1 }, { deep: true })
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2; }
</style>