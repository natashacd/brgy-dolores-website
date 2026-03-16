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
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Approved Services</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">View all approved requests</p>
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
          <select v-model="filters.timeframe"
            class="flex-1 appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 transition-all">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
          </select>
          <select v-model="filters.status"
            class="flex-1 appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 transition-all">
            <option value="">All Status</option>
            <option value="approved">Approved</option>
            <option value="completed">Completed</option>
            <option value="processing">Processing</option>
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
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">Approved Requests</h2>
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
        <p class="text-sm text-slate-400 font-medium">Loading approved requests...</p>
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
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Approved</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Completed</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Assigned To</th>
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
                <td class="px-6 py-4"><span class="text-sm text-slate-500">{{ formatDate(req.approved_at || req.updated_at) }}</span></td>
                <td class="px-6 py-4">
                  <span v-if="req.completed_at" class="text-sm text-slate-500">{{ formatDate(req.completed_at) }}</span>
                  <span v-else class="text-sm text-slate-300 italic">—</span>
                </td>
                <td class="px-6 py-4">
                  <div v-if="req.staff_name" class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white"
                      :style="{ background: getAvatarColor(req.staff_name) }">{{ getInitials(req.staff_name) }}</div>
                    <span class="text-sm text-slate-600">{{ req.staff_name }}</span>
                  </div>
                  <span v-else class="text-sm text-slate-400 italic">Unassigned</span>
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
                <td colspan="8" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No approved requests found</p>
                    <p class="text-xs text-slate-400">Approved requests will appear here</p>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No approved requests</p>
            <p class="text-xs text-slate-400">Approved requests will appear here</p>
          </div>

          <!-- Cards -->
          <div class="p-3 space-y-3">
            <div v-for="req in paginatedRequests" :key="req.id"
                 class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
                 style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);"
                 @click="openViewModal(req)">

              <!-- Card top: service type + status -->
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

                <!-- Approved Date -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#d9770612">
                    <svg width="11" height="11" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Approved</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(req.approved_at || req.updated_at) }}</p>
                  </div>
                </div>

                <!-- Assigned To -->
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#7c3aed12">
                    <svg width="11" height="11" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Assigned To</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ req.staff_name || 'Unassigned' }}</p>
                  </div>
                </div>

              </div>

              <!-- Quick actions strip -->
              <div class="mx-3 mb-3 flex items-center gap-2">
                <button @click.stop="printCertificate(req)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-all cursor-pointer">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                  </svg>
                  Print
                </button>
                <button v-if="req.status === 'approved' && !req.released_at"
                  @click.stop="markAsReleased(req)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition-all cursor-pointer">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                  </svg>
                  Release
                </button>
                <button @click.stop="openViewModal(req)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-bold text-[#3d4f7c] bg-[#3d4f7c]/8 border border-[#3d4f7c]/15 hover:bg-[#3d4f7c]/15 transition-all cursor-pointer"
                  style="background:rgba(61,79,124,0.08);">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Details
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredRequests.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 hidden sm:block font-mono">
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
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-2xl sm:shadow-2xl sm:max-w-4xl sm:max-h-[90vh] flex flex-col">

          <!-- Modal Header -->
          <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Approved Request Details</h3>
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
          <div v-if="selectedRequest" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3">

            <!-- Status + Actions -->
            <div class="flex items-center justify-between bg-slate-50 px-3 py-2.5 rounded-xl flex-wrap gap-2">
              <span :class="statusBadge(selectedRequest.status)" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(selectedRequest.status)"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(selectedRequest.status)"></span>
                </span>
                {{ formatStatus(selectedRequest.status) }}
              </span>
              <div class="flex items-center gap-2">
                <button v-if="selectedRequest.status === 'approved' && !selectedRequest.released_at"
                  @click="markAsReleased(selectedRequest)"
                  class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg px-3 py-1.5 transition-all cursor-pointer">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                  </svg>
                  Release
                </button>
                <button @click="printCertificate(selectedRequest)"
                  class="flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg px-3 py-1.5 transition-all cursor-pointer">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                  </svg>
                  Print
                </button>
              </div>
            </div>

            <!-- Resident Card — compact horizontal on mobile -->
            <div class="bg-[#3d4f7c] rounded-xl p-3.5 text-white flex items-center gap-3">
              <div class="w-11 h-11 rounded-xl overflow-hidden border-2 border-white/30 flex-shrink-0">
                <img v-if="selectedRequest.resident_image"
                  :src="`${baseUrl}/storage/${selectedRequest.resident_image}`"
                  class="w-full h-full object-cover"
                  @error="e => e.target.style.display = 'none'"/>
                <div v-else class="w-full h-full flex items-center justify-center text-base font-bold"
                  :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                  {{ getInitials(selectedRequest.resident_name) }}
                </div>
              </div>
              <div class="min-w-0 flex-1">
                <p class="font-bold text-sm truncate">{{ selectedRequest.resident_name }}</p>
                <p class="text-[11px] text-white/65 truncate">{{ selectedRequest.resident_email }}</p>
                <p v-if="selectedRequest.staff_name" class="text-[10px] text-white/45 mt-0.5">Assigned: {{ selectedRequest.staff_name }}</p>
              </div>
            </div>

            <!-- Info sections — 1 col on mobile, 3 col on sm+ -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
              <div class="bg-slate-50 rounded-xl p-3 space-y-2">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Personal Info</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Contact</span><span class="font-medium text-slate-700 text-right truncate">{{ selectedRequest.resident_contact || '—' }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Birth Date</span><span class="font-medium text-slate-700 text-right">{{ formatDate(selectedRequest.resident_birth_date) || '—' }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Sex</span><span class="font-medium text-slate-700 text-right">{{ selectedRequest.resident_sex || '—' }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Civil Status</span><span class="font-medium text-slate-700 text-right">{{ selectedRequest.resident_civil_status || '—' }}</span></div>
                </div>
              </div>
              <div class="bg-slate-50 rounded-xl p-3 space-y-2">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Address</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">House No.</span><span class="font-medium text-slate-700 text-right">{{ selectedRequest.resident_house_no || selectedRequest.address?.house_no || '—' }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Purok</span><span class="font-medium text-slate-700 text-right">{{ selectedRequest.resident_purok || selectedRequest.address?.purok || '—' }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Sitio</span><span class="font-medium text-slate-700 text-right">{{ selectedRequest.resident_sitio || selectedRequest.address?.sitio || '—' }}</span></div>
                </div>
              </div>
              <div class="bg-slate-50 rounded-xl p-3 space-y-2">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Request</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Type</span><span class="font-medium text-slate-700 text-right">{{ formatType(selectedRequest.type) }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Preferred</span><span class="font-medium text-slate-700 text-right">{{ formatDate(selectedRequest.preferred_date) }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Approved</span><span class="font-medium text-slate-700 text-right">{{ formatDate(selectedRequest.approved_at || selectedRequest.updated_at) }}</span></div>
                  <div class="flex justify-between items-baseline gap-2"><span class="text-slate-400 flex-shrink-0">Completed</span><span class="font-medium text-slate-700 text-right">{{ formatDate(selectedRequest.completed_at) || '—' }}</span></div>
                </div>
                <div v-if="selectedRequest.purpose" class="pt-1 border-t border-slate-200">
                  <p class="text-[9px] text-slate-400 uppercase tracking-wider mb-1">Purpose</p>
                  <p class="text-xs text-slate-700">{{ selectedRequest.purpose }}</p>
                </div>
              </div>
            </div>

            <div v-if="selectedRequest.remarks" class="bg-blue-50 border border-blue-100 rounded-xl p-3">
              <p class="text-[9px] font-bold text-blue-600 uppercase tracking-wider mb-1">Official Remarks</p>
              <p class="text-xs text-blue-800">{{ selectedRequest.remarks }}</p>
            </div>

          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()
const requests    = ref([])
const loading     = ref(false)
const currentPage = ref(1)
const itemsPerPage = 6
const showViewModal  = ref(false)
const selectedRequest = ref(null)
const baseUrl = import.meta.env.VITE_API_URL || ''

const filters = reactive({ search: '', timeframe: '', status: '', type: '' })

const sampleRequests = [
  { id: 101, resident_name: 'Maria Santos', resident_email: 'maria.santos@email.com', resident_contact: '0912 345 6789', type: 'certificate_of_indigency', purpose: 'For financial assistance application', preferred_date: '2024-03-20', created_at: '2024-03-10', updated_at: '2024-03-15', approved_at: '2024-03-15', completed_at: '2024-03-18', status: 'approved', remarks: 'Approved by Punong Barangay', staff_name: 'John Doe', address: { house_no: '123', purok: '3', sitio: 'Maligaya' } },
  { id: 102, resident_name: 'Juan Dela Cruz', resident_email: 'juan.dc@email.com', resident_contact: '0923 456 7890', type: 'barangay_clearance', purpose: 'For employment requirement', preferred_date: '2024-03-18', created_at: '2024-03-08', updated_at: '2024-03-14', approved_at: '2024-03-14', completed_at: null, status: 'approved', remarks: 'Approved for processing', staff_name: 'Jane Smith', address: { house_no: '456', purok: '7', sitio: 'Masagana' } }
]

const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']
function getAvatarColor(name = '') { let h = 0; for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h); return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length] }
function getInitials(name = '') { const p = name.trim().split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
function formatType(type = '') { return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }
function formatStatus(status = '') { return status.charAt(0).toUpperCase() + status.slice(1) }
function formatDate(date) { if (!date) return '—'; return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }
function statusBadge(status) { return { approved: 'bg-emerald-50 text-emerald-700 border border-emerald-200', completed: 'bg-green-50 text-green-700 border border-green-200', processing: 'bg-blue-50 text-blue-700 border border-blue-200' }[status] || 'bg-slate-100 text-slate-600' }
function statusDot(status) { return { approved: 'bg-emerald-400', completed: 'bg-green-400', processing: 'bg-blue-400' }[status] || 'bg-slate-400' }
function statusDotSolid(status) { return { approved: 'bg-emerald-500', completed: 'bg-green-500', processing: 'bg-blue-500' }[status] || 'bg-slate-500' }

const hasActiveFilters = computed(() => filters.search || filters.timeframe || filters.status || filters.type)
const filteredRequests = computed(() => {
  let r = [...sampleRequests]
  if (filters.search) { const s = filters.search.toLowerCase(); r = r.filter(x => x.resident_name?.toLowerCase().includes(s) || x.type?.toLowerCase().includes(s)) }
  if (filters.status) r = r.filter(x => x.status === filters.status)
  if (filters.type)   r = r.filter(x => x.type === filters.type)
  if (filters.timeframe) {
    const now = new Date(), today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    r = r.filter(x => {
      const date = new Date(x.approved_at || x.updated_at)
      if (filters.timeframe === 'today') return date >= today
      if (filters.timeframe === 'week') { const w = new Date(today); w.setDate(w.getDate() - 7); return date >= w }
      if (filters.timeframe === 'month') return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
      if (filters.timeframe === 'year') return date.getFullYear() === now.getFullYear()
      return true
    })
  }
  return r
})
const totalPages        = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredRequests.value.slice(s, s + itemsPerPage) })

function openViewModal(req) { selectedRequest.value = req; showViewModal.value = true }
function resetFilters() { filters.search = ''; filters.timeframe = ''; filters.status = ''; filters.type = ''; currentPage.value = 1 }
function formatAddress(req) { const addr = req.address; if (!addr) return '—'; return [addr.house_no ? `#${addr.house_no}` : '', addr.purok ? `Purok ${addr.purok}` : '', addr.sitio].filter(Boolean).join(', ') || '—' }

function printCertificate(req) {
  const printWindow = window.open('', '_blank')
  const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
  printWindow.document.write(`<!DOCTYPE html><html><head><title>Barangay Certificate - ${req.resident_name}</title><style>body{font-family:'Times New Roman',serif;margin:0;padding:40px;background:white}.cert{max-width:800px;margin:0 auto;border:2px solid #3d4f7c;padding:40px}.header{text-align:center;border-bottom:2px solid #3d4f7c;padding-bottom:20px;margin-bottom:30px}.title{font-size:28px;font-weight:bold;color:#3d4f7c}.field{margin-bottom:15px}.label{font-weight:bold;color:#3d4f7c;width:150px;display:inline-block}.sig{margin-top:50px;display:flex;justify-content:space-between}.sig-line{width:200px;border-top:1px solid #000;margin-top:40px;padding-top:10px;text-align:center}.footer{text-align:center;margin-top:40px;font-size:12px;color:#666}@media print{body{margin:0}.cert{border:none}}</style></head><body><div class="cert"><div class="header"><div class="title">BARANGAY DOLORES</div><div style="font-size:18px;color:#666">Capas, Tarlac</div></div><div style="text-align:center;margin-bottom:30px"><h2 style="color:#3d4f7c">CERTIFICATE OF ${formatType(req.type).toUpperCase()}</h2></div><div class="field"><span class="label">Resident Name:</span>${req.resident_name}</div><div class="field"><span class="label">Service Type:</span>${formatType(req.type)}</div><div class="field"><span class="label">Date Approved:</span>${formatDate(req.approved_at)}</div><div class="field"><span class="label">Purpose:</span>${req.purpose || 'N/A'}</div><div class="field"><span class="label">Address:</span>${formatAddress(req)}</div><div class="field"><span class="label">Contact:</span>${req.resident_contact || 'N/A'}</div><div class="sig"><div class="sig-line">${req.staff_name || 'Authorized Personnel'}<br><span style="font-size:12px">Issued By</span></div><div class="sig-line">${req.resident_name}<br><span style="font-size:12px">Recipient</span></div></div><div class="footer"><p>Issued on ${today} by Barangay Dolores.</p><p>Document ID: SR-${String(req.id).padStart(4, '0')}</p></div></div><script>window.onload=function(){window.print();window.close()}<\/script></body></html>`)
  printWindow.document.close()
}

async function markAsReleased(req) {
  const result = await Swal.fire({ title: 'Mark as Released?', text: `Mark this request as released to ${req.resident_name}?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, mark as released', input: 'textarea', inputPlaceholder: 'Release remarks (optional)...' })
  if (!result.isConfirmed) return
  try {
    showViewModal.value = false
    await Swal.fire({ icon: 'success', title: 'Released!', text: 'Request marked as released.', timer: 1500, showConfirmButton: false })
    router.push('/admin/service-requests/released')
  } catch { Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to mark as released.' }) }
}

async function fetchRequests() {
  loading.value = true
  setTimeout(() => { requests.value = sampleRequests; loading.value = false }, 800)
}

watch(filters, () => { currentPage.value = 1 }, { deep: true })
onMounted(() => { fetchRequests() })
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>