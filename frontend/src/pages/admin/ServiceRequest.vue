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

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
      <div v-for="stat in statsCards" :key="stat.label"
        class="group bg-white rounded-xl border border-slate-100 p-3 sm:p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div class="min-w-0">
            <p class="text-[9px] sm:text-[10px] font-semibold text-slate-400 uppercase tracking-wider leading-tight">{{ stat.label }}</p>
            <p class="text-xl sm:text-2xl font-bold mt-0.5" :class="stat.color">{{ stat.value }}</p>
            <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5 hidden sm:block">{{ stat.sub }}</p>
          </div>
          <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0"
            :class="stat.bg">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" :class="stat.iconColor">
              <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon"/>
            </svg>
          </div>
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
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search requests…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
        </div>
        <div class="flex items-center gap-2">
          <select v-model="filters.status"
            class="flex-1 appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="approved">Approved</option>
            <option value="completed">Completed</option>
            <option value="rejected">Rejected</option>
            <option value="cancelled">Cancelled</option>
          </select>
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

    <!-- Table/Card Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Header -->
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">All Service Requests</h2>
          </div>
          <span class="text-xs text-white/50">
            {{ filteredRequests.length }} result{{ filteredRequests.length !== 1 ? 's' : '' }}
          </span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading requests...</p>
      </div>

      <div v-else>

        <!-- ── DESKTOP: Table (hidden on mobile) ── -->
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
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white active:scale-95 transition-all duration-150 cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </td>
              </tr>

              <!-- Empty state -->
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

        <!-- ── MOBILE: Card List (hidden on desktop) ── -->
        <div class="md:hidden">
          <!-- Empty state -->
          <div v-if="filteredRequests.length === 0" class="flex flex-col items-center gap-3 py-16 px-4 text-center">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
              <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No service requests found</p>
            <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
          </div>

          <div v-for="req in paginatedRequests" :key="req.id"
            class="border-b border-slate-100 last:border-0 px-4 py-3.5 hover:bg-slate-50 transition-colors active:bg-slate-100"
            @click="openViewModal(req)">
            <div class="flex items-start justify-between gap-3">
              <!-- Avatar + name -->
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                  :style="{ background: getAvatarColor(req.resident_name) }">
                  {{ getInitials(req.resident_name) }}
                </div>
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-slate-800 truncate">{{ req.resident_name }}</p>
                  <p class="text-xs text-slate-400 truncate">{{ formatType(req.type) }}</p>
                </div>
              </div>
              <!-- Status + date -->
              <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                <span :class="statusBadge(req.status)"
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-semibold whitespace-nowrap">
                  <span class="w-1 h-1 rounded-full" :class="statusDotSolid(req.status)"></span>
                  {{ formatStatus(req.status) }}
                </span>
                <span class="text-[10px] text-slate-400 font-mono">#SR-{{ String(req.id).padStart(4, '0') }}</span>
                <span class="text-[10px] text-slate-400">{{ formatDate(req.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredRequests.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }}
          / {{ filteredRequests.length }}
        </p>
        <p class="text-xs text-slate-400 sm:hidden">
          Page {{ currentPage }} of {{ totalPages }}
        </p>
        <div class="flex items-center gap-1.5">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage ? 'text-white shadow-sm' : 'text-slate-500 hover:bg-[#3d4f7c]/5 hover:text-[#3d4f7c] border border-transparent'"
              :style="page === currentPage ? 'background:#3d4f7c' : ''">{{ page }}</button>
            <span v-else-if="Math.abs(page - currentPage) === 2" class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5">…</span>
          </template>
          <button :disabled="currentPage === totalPages" @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>
      <div v-else-if="!loading && filteredRequests.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">
          Showing <span class="font-medium text-slate-700">{{ filteredRequests.length }}</span> result{{ filteredRequests.length !== 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- ── View Details Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>

        <!-- Modal: full screen on mobile, max-w-4xl on desktop -->
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-2xl sm:shadow-2xl sm:max-w-4xl sm:max-h-[90vh] flex flex-col">

          <!-- Modal Header -->
          <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Request Details</h3>
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

            <!-- Status Banner -->
            <div class="flex items-center justify-between bg-slate-50 px-3 py-2.5 rounded-xl">
              <span :class="statusBadge(selectedRequest.status)"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(selectedRequest.status)"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(selectedRequest.status)"></span>
                </span>
                {{ formatStatus(selectedRequest.status) }}
              </span>
              <span class="text-xs text-slate-500">{{ formatDate(selectedRequest.created_at) }}</span>
            </div>

            <!-- Resident Card -->
            <div class="bg-[#3d4f7c] rounded-xl p-4 text-white flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl overflow-hidden border-2 border-white/30 flex-shrink-0">
                <img v-if="selectedRequest.resident_image"
                  :src="`${baseUrl}/storage/${selectedRequest.resident_image}`"
                  class="w-full h-full object-cover"
                  @error="e => e.target.style.display = 'none'"/>
                <div v-else class="w-full h-full flex items-center justify-center text-xl font-bold"
                  :style="{ background: getAvatarColor(selectedRequest.resident_name) }">
                  {{ getInitials(selectedRequest.resident_name) }}
                </div>
              </div>
              <div class="min-w-0">
                <p class="font-bold text-base truncate">{{ selectedRequest.resident_name }}</p>
                <p class="text-xs text-white/70 truncate">{{ selectedRequest.resident_email }}</p>
                <div class="flex gap-3 mt-1.5">
                  <span v-if="selectedRequest.resident_sex" class="text-[10px] text-white/60">{{ selectedRequest.resident_sex }}</span>
                  <span v-if="calculateAge(selectedRequest.resident_birth_date)" class="text-[10px] text-white/60">{{ calculateAge(selectedRequest.resident_birth_date) }} yrs</span>
                  <span v-if="selectedRequest.resident_civil_status" class="text-[10px] text-white/60">{{ selectedRequest.resident_civil_status }}</span>
                </div>
              </div>
            </div>

            <!-- Info Grid - 1 col mobile, 3 col desktop -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

              <!-- Personal Info -->
              <div class="bg-slate-50 rounded-xl p-3.5 space-y-2.5">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Personal Info</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between"><span class="text-slate-400">Contact</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_contact || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Birth Date</span><span class="font-medium text-slate-700">{{ formatDate(selectedRequest.resident_birth_date) || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Birth Place</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_birth_place || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Nationality</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_nationality || 'Filipino' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Occupation</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_occupation || '—' }}</span></div>
                </div>
              </div>

              <!-- Address -->
              <div class="bg-slate-50 rounded-xl p-3.5 space-y-2.5">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Address</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between"><span class="text-slate-400">House No.</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_house_no || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Purok</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_purok || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Sitio</span><span class="font-medium text-slate-700">{{ selectedRequest.resident_sitio || '—' }}</span></div>
                </div>
              </div>

              <!-- Request Info -->
              <div class="bg-slate-50 rounded-xl p-3.5 space-y-2.5">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Request</p>
                <div class="space-y-1.5 text-xs">
                  <div class="flex justify-between"><span class="text-slate-400">Type</span><span class="font-medium text-slate-700 text-right max-w-[120px]">{{ formatType(selectedRequest.type) }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-400">Preferred Date</span><span class="font-medium text-slate-700">{{ formatDate(selectedRequest.preferred_date) }}</span></div>
                </div>
                <div v-if="selectedRequest.purpose" class="pt-1">
                  <p class="text-[10px] text-slate-400 mb-1">Purpose</p>
                  <p class="text-xs text-slate-700 bg-white p-2 rounded-lg border border-slate-100">{{ selectedRequest.purpose }}</p>
                </div>
              </div>
            </div>

            <!-- Remarks / Notes -->
            <div v-if="selectedRequest.remarks" class="bg-purple-50 border border-purple-100 rounded-xl p-3.5">
              <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-1.5">Official Remarks</p>
              <p class="text-sm text-purple-800">{{ selectedRequest.remarks }}</p>
            </div>

            <!-- Action Buttons -->
            <div v-if="selectedRequest.status !== 'completed' && selectedRequest.status !== 'cancelled' && selectedRequest.status !== 'rejected'"
                 class="border-t border-slate-100 pt-4">
              <div class="flex gap-3">
                <button @click="confirmApprove(selectedRequest)"
                  class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-all active:scale-95 cursor-pointer">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  Approve
                </button>
                <button v-if="!showRejectInput" @click="showRejectInput = true"
                  class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-all active:scale-95 cursor-pointer">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Disapprove
                </button>
              </div>
              <div v-if="showRejectInput" class="mt-3 space-y-2">
                <textarea v-model="rejectionRemarks" rows="2" placeholder="Reason for disapproval (optional)..."
                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#3d4f7c]/50 bg-slate-50 focus:bg-white resize-none"></textarea>
                <div class="flex justify-end gap-2">
                  <button @click="showRejectInput = false; rejectionRemarks = ''"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer">Cancel</button>
                  <button @click="confirmReject(selectedRequest)"
                    class="px-3 py-1.5 text-xs font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600 cursor-pointer">Confirm Disapprove</button>
                </div>
              </div>
            </div>

            <div v-else-if="selectedRequest.status === 'completed'" class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-center">
              <p class="text-sm font-semibold text-emerald-700">This request has been completed</p>
            </div>
            <div v-else-if="selectedRequest.status === 'cancelled'" class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-center">
              <p class="text-sm font-semibold text-slate-600">This request has been cancelled</p>
            </div>
            <div v-else-if="selectedRequest.status === 'rejected'" class="bg-red-50 border border-red-200 rounded-xl p-3.5 text-center">
              <p class="text-sm font-semibold text-red-700">This request has been disapproved</p>
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
import ServiceRequestService from '@/services/Admin/ServiceRequestService'
import Swal from 'sweetalert2'

const router = useRouter()

const requests     = ref([])
const staff        = ref([])
const loading      = ref(false)
const saving       = ref(false)
const currentPage  = ref(1)
const itemsPerPage = 6

const showViewModal     = ref(false)
const showRejectInput   = ref(false)
const rejectionRemarks  = ref('')
const selectedRequest   = ref(null)
const selectedStaffId   = ref('')
const showReassignModal = ref(false)

const filters = reactive({ search: '', status: '', type: '' })
const baseUrl = import.meta.env.VITE_API_URL || ''

function calculateAge(birthDate) {
  if (!birthDate) return null
  const today = new Date()
  const birth = new Date(birthDate)
  let age = today.getFullYear() - birth.getFullYear()
  if (today.getMonth() - birth.getMonth() < 0 || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--
  return age
}

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
  return { pending: 'bg-amber-50 text-amber-700 border border-amber-200', processing: 'bg-blue-50 text-blue-700 border border-blue-200', approved: 'bg-emerald-50 text-emerald-700 border border-emerald-200', completed: 'bg-green-50 text-green-700 border border-green-200', rejected: 'bg-red-50 text-red-700 border border-red-200', cancelled: 'bg-slate-100 text-slate-600 border border-slate-200' }[status] || 'bg-slate-100 text-slate-600'
}
function statusDot(status) {
  return { pending: 'bg-amber-400', processing: 'bg-blue-400', approved: 'bg-emerald-400', completed: 'bg-green-400', rejected: 'bg-red-400', cancelled: 'bg-slate-400' }[status] || 'bg-slate-400'
}
function statusDotSolid(status) {
  return { pending: 'bg-amber-500', processing: 'bg-blue-500', approved: 'bg-emerald-500', completed: 'bg-green-500', rejected: 'bg-red-500', cancelled: 'bg-slate-500' }[status] || 'bg-slate-500'
}

const hasActiveFilters = computed(() => filters.search || filters.status || filters.type)
const filteredRequests = computed(() => {
  let r = requests.value
  if (filters.search) { const s = filters.search.toLowerCase(); r = r.filter(x => x.resident_name?.toLowerCase().includes(s) || x.type?.toLowerCase().includes(s)) }
  if (filters.status) r = r.filter(x => x.status === filters.status)
  if (filters.type)   r = r.filter(x => x.type === filters.type)
  return r
})
const totalPages        = computed(() => Math.max(1, Math.ceil(filteredRequests.value.length / itemsPerPage)))
const paginatedRequests = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredRequests.value.slice(s, s + itemsPerPage) })
const statsCards        = computed(() => {
  const total = requests.value.length, pending = requests.value.filter(r => r.status === 'pending').length, processing = requests.value.filter(r => r.status === 'processing').length, completed = requests.value.filter(r => r.status === 'completed').length
  return [
    { label: 'Total',      value: total,      sub: 'All time',         color: 'text-slate-800',   bg: 'bg-blue-50',    iconColor: 'text-blue-600',    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
    { label: 'Pending',    value: pending,    sub: 'Awaiting action',  color: 'text-amber-500',   bg: 'bg-amber-50',   iconColor: 'text-amber-500',   icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Processing', value: processing, sub: 'In progress',      color: 'text-blue-500',    bg: 'bg-blue-50',    iconColor: 'text-blue-500',    icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
    { label: 'Completed',  value: completed,  sub: 'Done',             color: 'text-emerald-600', bg: 'bg-emerald-50', iconColor: 'text-emerald-600', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
  ]
})

async function fetchRequests() {
  loading.value = true
  try { const data = await ServiceRequestService.getRequests({ per_page: 1000 }); requests.value = Array.isArray(data) ? data : (data.data ?? []) }
  catch { showError('Failed to load service requests') }
  finally { loading.value = false }
}

async function fetchStaff() {
  try { const data = await ServiceRequestService.getStaff(); staff.value = Array.isArray(data) ? data : (data.data ?? []) }
  catch (e) { console.error('Failed to load staff', e) }
}

function openViewModal(req) {
  selectedRequest.value = req
  showRejectInput.value = false
  rejectionRemarks.value = ''
  showViewModal.value = true
}

async function confirmApprove(req) {
  const result = await Swal.fire({ title: 'Approve Request?', text: `Approve "${formatType(req.type)}" for ${req.resident_name}?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#059669', cancelButtonColor: '#3d4f7c', confirmButtonText: 'Yes, approve' })
  if (!result.isConfirmed) return
  try {
    await ServiceRequestService.approve(req.id)
    showSuccess('Request approved successfully')
    showViewModal.value = false
    setTimeout(() => router.push('/admin/service-requests/approved'), 1500)
    await fetchRequests()
  } catch { showError('Failed to approve request') }
}

async function confirmReject(req) {
  const result = await Swal.fire({ title: 'Disapprove Request?', text: `Reject "${formatType(req.type)}" for ${req.resident_name}?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#3d4f7c', confirmButtonText: 'Yes, disapprove' })
  if (!result.isConfirmed) return
  try {
    await ServiceRequestService.reject(req.id, { remarks: rejectionRemarks.value })
    showSuccess('Request disapproved')
    showViewModal.value = false
    showRejectInput.value = false
    rejectionRemarks.value = ''
    setTimeout(() => router.push('/admin/service-requests/disapproved'), 1500)
    await fetchRequests()
  } catch { showError('Failed to disapprove request') }
}

async function submitReassign() {
  if (!selectedStaffId.value) return
  saving.value = true
  try { await ServiceRequestService.reassign(selectedRequest.value.id, selectedStaffId.value); showSuccess('Reassigned'); showReassignModal.value = false; await fetchRequests() }
  catch { showError('Failed to reassign') }
  finally { saving.value = false }
}

function resetFilters() { filters.search = ''; filters.status = ''; filters.type = ''; currentPage.value = 1 }
const showSuccess = (msg) => Swal.fire({ icon: 'success', title: 'Success', text: msg, timer: 2000, showConfirmButton: false, position: 'top-end', toast: true })
const showError   = (msg) => Swal.fire({ icon: 'error',   title: 'Error',   text: msg, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })

watch(filters, () => { currentPage.value = 1 }, { deep: true })
onMounted(() => { fetchRequests(); fetchStaff() })
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>