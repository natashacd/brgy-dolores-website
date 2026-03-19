<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4">
          <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c] flex-shrink-0"></div>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c] flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Complaints & Reports</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">Manage and monitor all complaints, reports, and Lupon cases</p>
          </div>
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
          <input v-model="filters.search" type="text" placeholder="Search by complainant, title, or case number…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"/>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.status"
            class="flex-1 sm:flex-none appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="mediation">In Mediation</option>
            <option value="escalated">Escalated</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
          <select v-model="filters.type"
            class="flex-1 sm:flex-none appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Types</option>
            <option value="incident">Incident Report</option>
            <option value="dispute">Dispute</option>
            <option value="report">General Report</option>
            <option value="other">Other</option>
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

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Table Header -->
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">Complaints & Reports List</h2>
          </div>
          <span class="text-xs text-white/50">{{ filteredComplaints.length }} case{{ filteredComplaints.length !== 1 ? 's' : '' }}</span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading complaints...</p>
      </div>

      <div v-else>

        <!-- ── Desktop Table ── -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/60">
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case #</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Type</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Title</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Location</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Incident Date</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Filed Date</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-36">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="case_ in paginatedComplaints" :key="case_.id"
                class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100">

                <td class="px-6 py-4">
                  <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">
                    #C-{{ String(case_.id).padStart(5, '0') }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
                      :class="typeIconBg(case_.type)">
                      <svg class="w-3 h-3" :class="typeIconColor(case_.type)"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        v-html="typeIcon(case_.type)"></svg>
                    </div>
                    <span class="text-sm text-slate-600">{{ typeLabel(case_.type) }}</span>
                  </div>
                </td>

                <td class="px-6 py-4">
                  <p class="text-sm font-semibold text-slate-800">{{ case_.title }}</p>
                </td>

                <td class="px-6 py-4">
                  <p class="text-sm text-slate-500 truncate max-w-[140px]">{{ case_.location }}</p>
                </td>

                <td class="px-6 py-4">
                  <span :class="statusBadge(case_.status)"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                    <span class="relative flex h-1.5 w-1.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(case_.status)"></span>
                      <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(case_.status)"></span>
                    </span>
                    {{ formatStatus(case_.status) }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm text-slate-500">{{ formatDate(case_.incident_date) }}</span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm text-slate-500">{{ formatDate(case_.created_at) }}</span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1">
                    <!-- View -->
                    <button @click="openViewModal(case_)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                      title="View Details">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>
                    <!-- Reassign -->
                    <button @click="openReassignModal(case_)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                      title="Reassign Lupon Member">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                      </svg>
                    </button>
                    <!-- Escalate -->
                    <button v-if="case_.status !== 'escalated' && case_.status !== 'resolved' && case_.status !== 'closed'"
                      @click="confirmEscalate(case_)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                      title="Escalate Case">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                      </svg>
                    </button>
                    <!-- Resolve -->
                    <button v-if="case_.status === 'mediation'"
                      @click="openResolutionModal(case_)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                      title="Approve Resolution">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="filteredComplaints.length === 0">
                <td colspan="8" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No complaints found</p>
                    <p class="text-xs text-slate-400">Try adjusting your search or filter criteria</p>
                    <button v-if="hasActiveFilters" @click="resetFilters"
                      class="text-xs font-semibold px-4 py-2 rounded-xl transition-all cursor-pointer"
                      style="color:#3d4f7c; background:#3d4f7c15; border:1px solid #3d4f7c30">Clear Filters</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── Mobile Cards ── -->
        <div class="md:hidden">
          <div v-if="filteredComplaints.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
              <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No complaints found</p>
            <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
            <button v-if="hasActiveFilters" @click="resetFilters"
              class="text-xs font-semibold px-4 py-2 rounded-xl transition-all cursor-pointer"
              style="color:#3d4f7c; background:#3d4f7c15; border:1px solid #3d4f7c30">Clear Filters</button>
          </div>

          <div class="p-3 space-y-3">
            <div v-for="case_ in paginatedComplaints" :key="case_.id"
              class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
              style="box-shadow:0 2px 8px rgba(0,0,0,0.06);"
              @click="openViewModal(case_)">

              <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-[10px] font-semibold text-[#3d4f7c] font-mono mb-0.5">#C-{{ String(case_.id).padStart(5, '0') }}</p>
                  <p class="text-sm font-bold text-slate-800 leading-tight truncate">{{ case_.title }}</p>
                  <p class="text-xs text-slate-400 mt-0.5 truncate">{{ case_.location }}</p>
                </div>
                <span :class="statusBadge(case_.status)"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0 mt-0.5">
                  <span class="w-1.5 h-1.5 rounded-full" :class="statusDotSolid(case_.status)"></span>
                  {{ formatStatus(case_.status) }}
                </span>
              </div>

              <div class="mx-4 h-px bg-slate-100"></div>

              <div class="grid grid-cols-2 gap-0 p-3">
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c12">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Type</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ typeLabel(case_.type) }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#05966912">
                    <svg width="11" height="11" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Incident Date</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(case_.incident_date) }}</p>
                  </div>
                </div>
              </div>

              <div class="mx-3 mb-3 flex items-center gap-2">
                <button @click.stop="openReassignModal(case_)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-semibold bg-indigo-50 text-indigo-600 border border-indigo-200 active:scale-95 transition-all cursor-pointer">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                  </svg>
                  Reassign
                </button>
                <button v-if="case_.status !== 'escalated' && case_.status !== 'resolved' && case_.status !== 'closed'"
                  @click.stop="confirmEscalate(case_)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-semibold bg-red-50 text-red-600 border border-red-200 active:scale-95 transition-all cursor-pointer">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  Escalate
                </button>
                <button v-if="case_.status === 'mediation'"
                  @click.stop="openResolutionModal(case_)"
                  class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 active:scale-95 transition-all cursor-pointer">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Resolve
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredComplaints.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredComplaints.length) }} / {{ filteredComplaints.length }}
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
      <div v-else-if="!loading && filteredComplaints.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ filteredComplaints.length }}</span> case{{ filteredComplaints.length !== 1 ? 's' : '' }}</p>
      </div>
    </div>

    <!-- ── View Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showViewModal = false"></div>
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-3xl sm:max-w-2xl sm:max-h-[90vh] flex flex-col overflow-hidden"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22), 0 0 0 1px rgba(0,0,0,0.04);">

          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm font-bold text-slate-800 tracking-tight">Case Details</h3>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</p>
              </div>
            </div>
            <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <div v-if="selectedCase" class="flex-1 overflow-y-auto scrollbar-thin px-6 py-5 space-y-4">

            <!-- Status Banner -->
            <div class="bg-slate-50 rounded-xl px-4 py-3 flex items-center justify-between">
              <span :class="statusBadge(selectedCase.status)"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(selectedCase.status)"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(selectedCase.status)"></span>
                </span>
                {{ formatStatus(selectedCase.status) }}
              </span>
              <span class="text-xs text-slate-400">Filed: {{ formatDate(selectedCase.created_at) }}</span>
            </div>

            <!-- Case Info -->
            <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
              <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-[#3d4f7c]/10 flex items-center justify-center">
                  <svg class="w-3 h-3 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case Information</p>
              </div>
              <div class="divide-y divide-slate-100/80">
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Type</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">{{ typeLabel(selectedCase.type) }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Title</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right max-w-[260px]">{{ selectedCase.title }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Incident Date</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">{{ formatDate(selectedCase.incident_date) }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Location</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right max-w-[260px]">{{ selectedCase.location }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Filed By (User ID)</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">#{{ selectedCase.user_id }}</span>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="rounded-2xl border border-blue-200/60 overflow-hidden">
              <div class="px-4 py-2.5 bg-blue-50/60 border-b border-blue-100/80 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-blue-100 flex items-center justify-center">
                  <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Description</p>
              </div>
              <div class="px-4 py-3.5 bg-blue-50/20">
                <p class="text-xs text-slate-600 leading-relaxed">{{ selectedCase.description || 'No description provided.' }}</p>
              </div>
            </div>

          </div>

          <div class="flex-shrink-0 flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50/60">
            <button @click="showViewModal = false"
              class="flex items-center gap-1.5 px-4 py-2 text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Reassign Modal (UI only — no API yet) ── -->
    <Transition name="modal">
      <div v-if="showReassignModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showReassignModal = false"></div>
        <div class="relative bg-white w-full sm:rounded-3xl sm:max-w-md overflow-hidden"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22);">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm font-bold text-slate-800">Reassign Lupon Member</h3>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</p>
              </div>
            </div>
            <button @click="showReassignModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="px-6 py-5">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1.5">Select Lupon Member</label>
            <select v-model="selectedLuponId"
              class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white">
              <option value="">— Select Lupon member —</option>
              <option v-for="member in luponMembers" :key="member.id" :value="member.id">{{ member.name }}</option>
            </select>
            <p class="text-[10px] text-slate-400 mt-2">API integration coming soon.</p>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
            <button @click="showReassignModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer">Cancel</button>
            <button :disabled="!selectedLuponId"
              class="px-5 py-2 text-sm font-semibold text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all cursor-pointer"
              style="background:#3d4f7c">
              Confirm Reassign
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Resolution Modal (UI only — no API yet) ── -->
    <Transition name="modal">
      <div v-if="showResolutionModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showResolutionModal = false"></div>
        <div class="relative bg-white w-full sm:rounded-3xl sm:max-w-lg overflow-hidden"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22);">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full bg-[#3d4f7c]"></div>
              <h3 class="text-sm font-bold text-slate-800">Approve Resolution</h3>
            </div>
            <button @click="showResolutionModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="px-6 py-5 space-y-4">
            <div class="bg-[#3d4f7c]/5 border border-[#3d4f7c]/20 rounded-xl p-3">
              <p class="text-xs font-semibold text-[#3d4f7c] font-mono">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</p>
              <p class="text-sm text-slate-600 mt-0.5">{{ selectedCase?.title }}</p>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 mb-1.5">Resolution Details</label>
              <textarea v-model="resolutionText" rows="4" placeholder="Enter the resolution details..."
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white resize-none"></textarea>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 mb-1.5">Action</label>
              <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm transition-all"
                  :class="resolutionAction === 'close' ? 'border-slate-400 bg-slate-50 text-slate-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                  <input type="radio" v-model="resolutionAction" value="close" class="hidden" />
                  <span class="w-2 h-2 rounded-full flex-shrink-0" :class="resolutionAction === 'close' ? 'bg-slate-500' : 'bg-slate-300'"></span>
                  Close Case
                </label>
                <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm transition-all"
                  :class="resolutionAction === 'approve' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                  <input type="radio" v-model="resolutionAction" value="approve" class="hidden" />
                  <span class="w-2 h-2 rounded-full flex-shrink-0" :class="resolutionAction === 'approve' ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                  Approve
                </label>
              </div>
            </div>
            <p class="text-[10px] text-slate-400">API integration coming soon.</p>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
            <button @click="showResolutionModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer">Cancel</button>
            <button
              class="px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all cursor-pointer"
              :class="resolutionAction === 'close' ? 'bg-[#3d4f7c] hover:bg-[#252b3b]' : 'bg-emerald-600 hover:bg-emerald-700'">
              {{ resolutionAction === 'close' ? 'Close Case' : 'Approve Resolution' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import Swal from 'sweetalert2'
import LuponCasesService from '@/services/Resident/LuponCasesService'
import { getLuponCases, hasLuponCasesData, setLuponCases } from "@/utils/dataStore";


// ── State ─────────────────────────────────────────────────────
const loading             = ref(false)
const currentPage         = ref(1)
const itemsPerPage        = 8
const showViewModal       = ref(false)
const showReassignModal   = ref(false)
const showResolutionModal = ref(false)
const selectedCase        = ref(null)
const selectedLuponId     = ref('')
const resolutionText      = ref('')
const resolutionAction    = ref('approve')
const complaints          = ref([])
const filters             = reactive({ search: '', status: '', type: '' })

// Static lupon list until API is wired
const luponMembers = ref([
  { id: 1, name: 'Lupon Member Santos' },
  { id: 2, name: 'Lupon Member Cruz' },
  { id: 3, name: 'Lupon Member Reyes' },
  { id: 4, name: 'Lupon Member Gomez' },
])

// ── Helpers ───────────────────────────────────────────────────
function typeLabel(t) {
  return { incident: 'Incident Report', dispute: 'Dispute', report: 'General Report', other: 'Other' }[t] || t
}
function typeIconBg(t) {
  return { incident: 'bg-red-100', dispute: 'bg-amber-100', report: 'bg-blue-100', other: 'bg-purple-100' }[t] || 'bg-slate-100'
}
function typeIconColor(t) {
  return { incident: 'text-red-600', dispute: 'text-amber-600', report: 'text-blue-600', other: 'text-purple-600' }[t] || 'text-slate-500'
}
function typeIcon(t) {
  if (t === 'incident') return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
  if (t === 'dispute')  return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>'
  return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
}
function formatDate(d) {
  if (!d) return '—'
  try { return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }
  catch { return d }
}
function formatStatus(s) {
  return { pending: 'Pending', mediation: 'In Mediation', escalated: 'Escalated', resolved: 'Resolved', closed: 'Closed' }[s] || s
}
function statusBadge(s) {
  return {
    pending:   'bg-amber-50 text-amber-700 border border-amber-200',
    mediation: 'bg-blue-50 text-blue-700 border border-blue-200',
    escalated: 'bg-red-50 text-red-700 border border-red-200',
    resolved:  'bg-emerald-50 text-emerald-700 border border-emerald-200',
    closed:    'bg-slate-100 text-slate-600 border border-slate-200',
  }[s] || 'bg-slate-100 text-slate-600'
}
function statusDot(s) {
  return { pending: 'bg-amber-400', mediation: 'bg-blue-400', escalated: 'bg-red-400', resolved: 'bg-emerald-400', closed: 'bg-slate-400' }[s] || 'bg-slate-400'
}
function statusDotSolid(s) {
  return { pending: 'bg-amber-500', mediation: 'bg-blue-500', escalated: 'bg-red-500', resolved: 'bg-emerald-500', closed: 'bg-slate-500' }[s] || 'bg-slate-500'
}

async function fetchComplaints() {
  // Serve from cache if already loaded
  if (hasLuponCasesData()) {
    complaints.value = getLuponCases()
    return
  }

  loading.value = true
  try {
    const data = await LuponCasesService.adminCases()
    const result = Array.isArray(data) ? data : (data.data ?? [])
    setLuponCases(result)       // cache it
    complaints.value = result
  } catch (err) {
    console.error('Fetch error:', err)
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load complaints.', confirmButtonColor: '#3d4f7c' })
  } finally {
    loading.value = false
  }
}

// ── Filters & Pagination ──────────────────────────────────────
const hasActiveFilters = computed(() => filters.search || filters.status || filters.type)

const filteredComplaints = computed(() => {
  let f = [...complaints.value]
  if (filters.search) {
    const s = filters.search.toLowerCase()
    f = f.filter(c =>
      c.title?.toLowerCase().includes(s) ||
      c.location?.toLowerCase().includes(s) ||
      c.type?.toLowerCase().includes(s) ||
      String(c.id).includes(s)
    )
  }
  if (filters.status) f = f.filter(c => c.status === filters.status)
  if (filters.type)   f = f.filter(c => c.type === filters.type)
  return f
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredComplaints.value.length / itemsPerPage)))
const paginatedComplaints = computed(() => {
  const s = (currentPage.value - 1) * itemsPerPage
  return filteredComplaints.value.slice(s, s + itemsPerPage)
})

function resetFilters() { filters.search = ''; filters.status = ''; filters.type = ''; currentPage.value = 1 }
watch(filters, () => { currentPage.value = 1 }, { deep: true })

// ── Modal openers (actions wired to UI only for now) ──────────
function openViewModal(c)       { selectedCase.value = c; showViewModal.value = true }
function openReassignModal(c)   { selectedCase.value = c; selectedLuponId.value = ''; showReassignModal.value = true }
function openResolutionModal(c) { selectedCase.value = c; resolutionText.value = ''; resolutionAction.value = 'approve'; showResolutionModal.value = true }

function confirmEscalate(case_) {
  Swal.fire({
    title: 'Escalate Case?',
    text: `Escalate case #C-${String(case_.id).padStart(5, '0')}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, escalate',
  }).then(r => {
    if (r.isConfirmed) {
      Swal.fire({ icon: 'info', title: 'Coming Soon', text: 'Escalate API not yet connected.', confirmButtonColor: '#3d4f7c' })
    }
  })
}

onMounted(() => fetchComplaints())
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2; }
</style>