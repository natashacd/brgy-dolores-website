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
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Approved Services</h1>
          <p class="text-sm text-slate-500 mt-1">View all approved requests</p>
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
          <select v-model="filters.status"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Status</option>
            <option value="approved">Approved</option>
            <option value="completed">Completed</option>
            <option value="processing">Processing</option>
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
            <h2 class="text-lg font-semibold text-white tracking-tight">Approved Requests</h2>
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
        <p class="text-sm text-slate-400 font-medium">Loading approved requests...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Request ID</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Resident</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Service Type</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Approved Date</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Completed Date</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Assigned To</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-36">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="req in paginatedRequests" :key="req.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">
              <td class="px-6 py-4">
                <span class="text-xs font-mono text-slate-400">#SR-{{ String(req.id).padStart(4, '0') }}</span>
              </td>
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
              <td class="px-6 py-4">
                <span class="text-sm text-slate-600">{{ formatType(req.type) }}</span>
              </td>
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
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ formatDate(req.approved_at || req.updated_at) }}
                </div>
              </td>
              <td class="px-6 py-4">
                <div v-if="req.completed_at" class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ formatDate(req.completed_at) }}
                </div>
                <span v-else class="text-sm text-slate-400 italic">—</span>
              </td>
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
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
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
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No approved requests found</p>
                    <p class="text-xs text-slate-400 mt-0.5">Service requests that are approved will appear here</p>
                  </div>
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

    <!-- View Details Modal -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
                <h3 class="text-base font-semibold text-slate-800">Approved Request Details</h3>
                <span class="px-2 py-0.5 bg-[#3d4f7c]/10 text-[#3d4f7c] text-xs rounded-full font-mono">#SR-{{ selectedRequest ? String(selectedRequest.id).padStart(4, '0') : '' }}</span>
              </div>
              <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            
            <!-- Body -->
            <div v-if="selectedRequest" class="px-6 py-5 max-h-[70vh] overflow-y-auto scrollbar-thin">
              <!-- Status Banner -->
              <div class="mb-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span :class="statusBadge(selectedRequest.status)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                        :class="statusDot(selectedRequest.status)"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2"
                        :class="statusDotSolid(selectedRequest.status)"></span>
                    </span>
                    {{ formatStatus(selectedRequest.status) }}
                  </span>

                  <!-- Print & Release buttons -->
                  <div v-if="selectedRequest.status === 'approved' && !selectedRequest.released_at" class="flex items-center gap-2">
                    <button @click="markAsReleased(selectedRequest)"
                      class="flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg px-3 py-1.5 transition-all"
                      title="Mark as Released">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                      </svg>
                      Release
                    </button>
                  </div>
                </div>
                <span class="text-xs text-slate-400">
                  Approved {{ formatDate(selectedRequest.approved_at || selectedRequest.updated_at) }}
                </span>
              </div>

              <!-- Three Column Layout -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column - Resident Image -->
                <div class="space-y-4">
                  <div class="bg-gradient-to-br from-[#3d4f7c] to-[#252b3b] rounded-xl p-5 text-white text-center shadow-lg">
                    <div class="flex justify-center mb-3">
                      <div class="relative">
                        <div class="w-28 h-28 rounded-xl overflow-hidden border-4 border-white/30 shadow-xl mx-auto">
                          <img 
                            v-if="selectedRequest.resident_image"
                            :src="`${baseUrl}/storage/${selectedRequest.resident_image}`" 
                            :alt="selectedRequest.resident_name"
                            class="w-full h-full object-cover"
                            @error="e => e.target.style.display = 'none'"
                          />
                          <div v-else
                            class="w-full h-full flex items-center justify-center text-3xl font-bold text-white"
                            :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                            {{ getInitials(selectedRequest.resident_name) }}
                          </div>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white"
                          :class="{
                            'bg-emerald-500': selectedRequest.status === 'approved' || selectedRequest.status === 'completed',
                            'bg-blue-500': selectedRequest.status === 'processing'
                          }">
                        </div>
                      </div>
                    </div>
                    <h3 class="font-bold text-lg truncate">{{ selectedRequest.resident_name }}</h3>
                    <p class="text-xs text-white/80 flex items-center justify-center gap-1 mt-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      {{ selectedRequest.resident_email }}
                    </p>
                  </div>

                  <!-- Quick Info -->
                  <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Quick Info
                    </h4>
                    <div class="space-y-2 text-sm">
                      <div class="flex justify-between items-center">
                        <span class="text-slate-400">Contact</span>
                        <span class="font-medium text-slate-700">{{ selectedRequest.resident_contact || '—' }}</span>
                      </div>
                      <div class="flex justify-between items-center">
                        <span class="text-slate-400">Assigned To</span>
                        <span class="font-medium text-slate-700">{{ selectedRequest.staff_name || 'Unassigned' }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Middle Column - Personal Information -->
                <div class="space-y-4">
                  <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      Personal Information
                    </h4>
                    <div class="space-y-3 text-sm">
                      <div class="grid grid-cols-2 gap-2">
                        <div>
                          <p class="text-[10px] text-slate-400">Birth Date</p>
                          <p class="font-medium text-slate-700">{{ formatDate(selectedRequest.resident_birth_date) || '—' }}</p>
                        </div>
                        <div>
                          <p class="text-[10px] text-slate-400">Birth Place</p>
                          <p class="font-medium text-slate-700">{{ selectedRequest.resident_birth_place || '—' }}</p>
                        </div>
                      </div>
                      <div class="grid grid-cols-2 gap-2">
                        <div>
                          <p class="text-[10px] text-slate-400">Sex</p>
                          <p class="font-medium text-slate-700">{{ selectedRequest.resident_sex || '—' }}</p>
                        </div>
                        <div>
                          <p class="text-[10px] text-slate-400">Civil Status</p>
                          <p class="font-medium text-slate-700">{{ selectedRequest.resident_civil_status || '—' }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      Address Information
                    </h4>
                    <div class="space-y-2 text-sm">
                      <div class="flex justify-between">
                        <span class="text-slate-400">House No.</span>
                        <span class="font-medium text-slate-700">{{ selectedRequest.resident_house_no || '—' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-slate-400">Purok</span>
                        <span class="font-medium text-slate-700">{{ selectedRequest.resident_purok || '—' }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-slate-400">Sitio</span>
                        <span class="font-medium text-slate-700">{{ selectedRequest.resident_sitio || '—' }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Right Column - Request Details -->
                <div class="space-y-4">
                  <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                      Request Information
                    </h4>
                    <div class="space-y-2">
                      <div>
                        <p class="text-[10px] text-slate-400">Service Type</p>
                        <p class="font-medium text-slate-700">{{ formatType(selectedRequest.type) }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] text-slate-400">Preferred Date</p>
                        <p class="font-medium text-slate-700">{{ formatDate(selectedRequest.preferred_date) }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] text-slate-400">Completed Date</p>
                        <p class="font-medium text-slate-700">{{ formatDate(selectedRequest.completed_at) || '—' }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Official Remarks -->
                  <div v-if="selectedRequest.remarks" class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-700 mb-1 flex items-center gap-1.5">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                      </svg>
                      Official Remarks
                    </p>
                    <p class="text-sm text-blue-800 bg-white p-3 rounded-lg">{{ selectedRequest.remarks }}</p>
                  </div>
                </div>
              </div>

              <!-- Print Button -->
              <div class="border-t border-slate-100 pt-4 mt-4 flex justify-end">
                <button @click="printCertificate(selectedRequest)"
                  class="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg px-3 py-1.5 transition-all"
                  title="Print Certificate">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  Print
                </button>
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
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()

const requests = ref([])
const loading = ref(false)
const currentPage = ref(1)
const itemsPerPage = 6
const showViewModal = ref(false)
const selectedRequest = ref(null)

const filters = reactive({ search: '', timeframe: '', status: '', type: '' })

const sampleRequests = [
  {
    id: 101,
    resident_name: 'Maria Santos',
    resident_email: 'maria.santos@email.com',
    resident_contact: '0912 345 6789',
    type: 'certificate_of_indigency',
    purpose: 'For financial assistance application',
    preferred_date: '2024-03-20',
    created_at: '2024-03-10',
    updated_at: '2024-03-15',
    approved_at: '2024-03-15',
    completed_at: '2024-03-18',
    status: 'approved',
    remarks: 'Approved by Punong Barangay',
    staff_name: 'John Doe',
    document_url: '#',
    address: { house_no: '123', purok: '3', sitio: 'Maligaya' }
  },
  {
    id: 102,
    resident_name: 'Juan Dela Cruz',
    resident_email: 'juan.dc@email.com',
    resident_contact: '0923 456 7890',
    type: 'barangay_clearance',
    purpose: 'For employment requirement',
    preferred_date: '2024-03-18',
    created_at: '2024-03-08',
    updated_at: '2024-03-14',
    approved_at: '2024-03-14',
    completed_at: null,
    status: 'approved',
    remarks: 'Approved for processing',
    staff_name: 'Jane Smith',
    document_url: '#',
    address: { house_no: '456', purok: '7', sitio: 'Masagana' }
  }
]

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
    approved: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
  }[status] || 'bg-slate-100 text-slate-600'
}

function statusDot(status) {
  return { 
    approved: 'bg-emerald-400',
  }[status] || 'bg-slate-400'
}

function statusDotSolid(status) {
  return { 
    approved: 'bg-emerald-500',
  }[status] || 'bg-slate-500'
}

const hasActiveFilters = computed(() => filters.search || filters.timeframe || filters.status || filters.type)

const filteredRequests = computed(() => {
  let r = [...sampleRequests]
  
  if (filters.search) {
    const s = filters.search.toLowerCase()
    r = r.filter(x => x.resident_name?.toLowerCase().includes(s) || x.type?.toLowerCase().includes(s))
  }
  
  if (filters.status) {
    r = r.filter(x => x.status === filters.status)
  }
  
  if (filters.type) {
    r = r.filter(x => x.type === filters.type)
  }
  
  if (filters.timeframe) {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    
    r = r.filter(x => {
      const date = new Date(x.approved_at || x.updated_at)
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
  
  return r
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredRequests.value.slice(start, start + itemsPerPage)
})

function openViewModal(req) {
  selectedRequest.value = req
  showViewModal.value = true
}

function resetFilters() {
  filters.search = ''
  filters.timeframe = ''
  filters.status = ''
  filters.type = ''
  currentPage.value = 1
}

function printCertificate(req) {
  const printWindow = window.open('', '_blank')
  
  const today = new Date().toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })

  const certificateHtml = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Barangay Certificate - ${req.resident_name}</title>
      <style>
        body {
          font-family: 'Times New Roman', serif;
          margin: 0;
          padding: 40px;
          background: white;
        }
        .certificate-container {
          max-width: 800px;
          margin: 0 auto;
          border: 2px solid #3d4f7c;
          padding: 40px;
          position: relative;
        }
        .header {
          text-align: center;
          border-bottom: 2px solid #3d4f7c;
          padding-bottom: 20px;
          margin-bottom: 30px;
        }
        .logo {
          width: 100px;
          height: 100px;
          margin: 0 auto 20px;
          background: #3d4f7c;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-size: 40px;
          font-weight: bold;
        }
        .title {
          font-size: 28px;
          font-weight: bold;
          color: #3d4f7c;
          margin-bottom: 10px;
        }
        .subtitle {
          font-size: 18px;
          color: #666;
          margin-bottom: 30px;
        }
        .content {
          margin-bottom: 40px;
        }
        .field {
          margin-bottom: 15px;
        }
        .field-label {
          font-weight: bold;
          color: #3d4f7c;
          width: 150px;
          display: inline-block;
        }
        .field-value {
          font-size: 16px;
        }
        .signature {
          margin-top: 50px;
          display: flex;
          justify-content: space-between;
        }
        .signature-line {
          width: 200px;
          border-top: 1px solid #000;
          margin-top: 40px;
          padding-top: 10px;
          text-align: center;
        }
        .footer {
          text-align: center;
          margin-top: 40px;
          font-size: 12px;
          color: #666;
        }
        @media print {
          body { margin: 0; }
          .certificate-container { border: none; }
        }
      </style>
    </head>
    <body>
      <div class="certificate-container">
        <div class="header">
          <div class="logo">BD</div>
          <div class="title">BARANGAY DOLORES</div>
          <div class="subtitle">Capas, Tarlac</div>
        </div>
        
        <div style="text-align: center; margin-bottom: 30px;">
          <h2 style="color: #3d4f7c;">CERTIFICATE OF ${formatType(req.type).toUpperCase().replace(/ /g, '_')}</h2>
        </div>
        
        <div class="content">
          <div class="field">
            <span class="field-label">Resident Name:</span>
            <span class="field-value">${req.resident_name}</span>
          </div>
          <div class="field">
            <span class="field-label">Service Type:</span>
            <span class="field-value">${formatType(req.type)}</span>
          </div>
          <div class="field">
            <span class="field-label">Date Approved:</span>
            <span class="field-value">${formatDate(req.approved_at)}</span>
          </div>
          <div class="field">
            <span class="field-label">Date Completed:</span>
            <span class="field-value">${formatDate(req.completed_at)}</span>
          </div>
          <div class="field">
            <span class="field-label">Purpose:</span>
            <span class="field-value">${req.purpose || 'N/A'}</span>
          </div>
          <div class="field">
            <span class="field-label">Address:</span>
            <span class="field-value">${formatAddress(req)}</span>
          </div>
          <div class="field">
            <span class="field-label">Contact:</span>
            <span class="field-value">${req.resident_contact || 'N/A'}</span>
          </div>
        </div>
        
        <div class="signature">
          <div class="signature-line">
            ${req.staff_name || 'Authorized Personnel'}<br>
            <span style="font-size: 12px;">Issued By</span>
          </div>
          <div class="signature-line">
            ${req.resident_name}<br>
            <span style="font-size: 12px;">Recipient</span>
          </div>
        </div>
        
        <div class="footer">
          <p>This certificate is issued on ${today} by Barangay Dolores.</p>
          <p>Document ID: SR-${String(req.id).padStart(4, '0')}</p>
        </div>
      </div>
      <script>
        window.onload = function() { window.print(); window.close(); }
      <\/script>
    </body>
    </html>
  `

  printWindow.document.write(certificateHtml)
  printWindow.document.close()
}

async function markAsReleased(req) {
  const result = await Swal.fire({
    title: 'Mark as Released?',
    text: `Mark this request as released to ${req.resident_name}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, mark as released',
    cancelButtonText: 'Cancel',
    input: 'textarea',
    inputPlaceholder: 'Release remarks (optional)...',
  })

  if (result.isConfirmed) {
    try {
      const request = sampleRequests.find(r => r.id === req.id)
      if (request) {
        request.status = 'released'
        request.released_at = new Date().toISOString()
        request.release_remarks = result.value || 'Released to resident'
      }

      requests.value = requests.value.filter(r => r.id !== req.id)
      showViewModal.value = false
      
      await Swal.fire({
        icon: 'success',
        title: 'Released!',
        text: 'Request has been marked as released.',
        timer: 1500,
        showConfirmButton: false
      })

      router.push('/admin/service-requests/released')
      
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to mark as released.'
      })
    }
  }
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

async function fetchRequests() {
  loading.value = true
  try {
    setTimeout(() => {
      requests.value = sampleRequests
      loading.value = false
    }, 800)
  } catch (e) {
    console.error('Failed to load requests', e)
    loading.value = false
  }
}

watch(filters, () => { currentPage.value = 1 }, { deep: true })

onMounted(() => {
  fetchRequests()
})
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