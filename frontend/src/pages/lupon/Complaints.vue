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
          <input v-model="filters.search" type="text" placeholder="Search by complainant, respondent, or case number…"
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
            <option value="person">Person vs Person</option>
            <option value="entity">Person vs Entity</option>
            <option value="property">Person vs Property/Animal</option>
            <option value="incident">Incident Report</option>
          </select>
          <select v-model="filters.lupon"
            class="flex-1 sm:flex-none appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Lupon</option>
            <option v-for="member in luponMembers" :key="member.id" :value="member.id">{{ member.name }}</option>
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
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Complainant</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Respondent/Subject</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Type</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Assigned Lupon</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Filed Date</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-36">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="case_ in paginatedComplaints" :key="case_.id"
                class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100">

                <td class="px-6 py-4">
                  <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">#C-{{ String(case_.id).padStart(5, '0') }}</span>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                      :style="{ background: getAvatarColor(case_.complainant_name) }">{{ getInitials(case_.complainant_name) }}</div>
                    <div>
                      <p class="text-sm font-semibold text-slate-800">{{ case_.complainant_name }}</p>
                      <p class="text-xs text-slate-400">{{ case_.complainant_address }}</p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <div v-if="case_.respondent_type === 'person'"
                      class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                      :style="{ background: getAvatarColor(case_.respondent_name) }">{{ getInitials(case_.respondent_name) }}</div>
                    <div v-else class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                      <svg v-if="case_.respondent_type === 'entity'" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                      <svg v-else-if="case_.respondent_type === 'property'" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      <svg v-else class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-semibold text-slate-800">{{ case_.respondent_name }}</p>
                      <p class="text-xs text-slate-400">
                        <span v-if="case_.respondent_type === 'person'">{{ case_.respondent_address }}</span>
                        <span v-else-if="case_.respondent_type === 'entity'">Entity/Business</span>
                        <span v-else-if="case_.respondent_type === 'property'">Property/Animal</span>
                        <span v-else>Incident Report</span>
                      </p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ case_.type }}</span></td>

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
                  <div v-if="case_.lupon_name" class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white"
                      :style="{ background: getAvatarColor(case_.lupon_name) }">{{ getInitials(case_.lupon_name) }}</div>
                    <span class="text-sm text-slate-600">{{ case_.lupon_name }}</span>
                  </div>
                  <span v-else class="text-sm text-slate-400 italic">Unassigned</span>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(case_.filed_date) }}
                  </div>
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
                      style="color:#3d4f7c; background:#3d4f7c15; border: 1px solid #3d4f7c30">Clear Filters</button>
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
              style="color:#3d4f7c; background:#3d4f7c15; border: 1px solid #3d4f7c30">Clear Filters</button>
          </div>

          <div class="p-3 space-y-3">
            <div v-for="case_ in paginatedComplaints" :key="case_.id"
              class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
              style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);"
              @click="openViewModal(case_)">

              <!-- Card Top -->
              <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-[10px] font-semibold text-[#3d4f7c] font-mono mb-0.5">#C-{{ String(case_.id).padStart(5, '0') }}</p>
                  <p class="text-base font-bold text-slate-800 leading-tight">{{ case_.type }}</p>
                </div>
                <span :class="statusBadge(case_.status)"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0 mt-0.5">
                  <span class="w-1.5 h-1.5 rounded-full" :class="statusDotSolid(case_.status)"></span>
                  {{ formatStatus(case_.status) }}
                </span>
              </div>

              <div class="mx-4 h-px bg-slate-100"></div>

              <!-- Info Grid -->
              <div class="grid grid-cols-2 gap-0 p-3">
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c12">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Complainant</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ case_.complainant_name }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#dc262612">
                    <svg width="11" height="11" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Respondent</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ case_.respondent_name }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#05966912">
                    <svg width="11" height="11" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Filed Date</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(case_.filed_date) }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#7c3aed12">
                    <svg width="11" height="11" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Lupon</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">{{ case_.lupon_name || 'Unassigned' }}</p>
                  </div>
                </div>
              </div>

              <!-- Complaint Preview -->
              <div v-if="case_.details" class="mx-3 mb-3 px-3 py-2.5 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-[9px] font-bold text-amber-600 uppercase tracking-wider mb-0.5">Complaint</p>
                <p class="text-xs text-amber-800 line-clamp-2">{{ case_.details }}</p>
              </div>

              <!-- Mobile Action Buttons -->
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
      <div v-else-if="!loading && filteredComplaints.length > 0" class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ filteredComplaints.length }}</span> case{{ filteredComplaints.length !== 1 ? 's' : '' }}</p>
      </div>
    </div>

    <!-- ── View Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showViewModal = false"></div>
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-3xl sm:max-w-2xl sm:max-h-[90vh] flex flex-col overflow-hidden"
          style="box-shadow: 0 40px 80px -12px rgba(0,0,0,0.22), 0 0 0 1px rgba(0,0,0,0.04);">
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
          <div v-if="selectedCase" class="flex-1 overflow-y-auto scrollbar-thin">
            <!-- Hero -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-4">
              <div class="relative flex-shrink-0">
                <div class="w-16 h-16 rounded-2xl border-2 border-slate-100 shadow-md flex items-center justify-center text-lg font-bold text-white"
                  :style="{ background: getAvatarColor(selectedCase.complainant_name) }">
                  {{ getInitials(selectedCase.complainant_name) }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-white flex items-center justify-center">
                  <div class="w-2.5 h-2.5 rounded-full" :class="statusDotSolid(selectedCase.status)"></div>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-slate-900 truncate">{{ selectedCase.complainant_name }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ selectedCase.complainant_address }}</p>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                  <span :class="statusBadge(selectedCase.status)"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(selectedCase.status)"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2" :class="statusDotSolid(selectedCase.status)"></span>
                    </span>
                    {{ formatStatus(selectedCase.status) }}
                  </span>
                  <span class="text-[10px] text-slate-400">Filed: {{ formatDate(selectedCase.filed_date) }}</span>
                </div>
              </div>
            </div>
            <!-- Content -->
            <div class="px-6 py-5 space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                  <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-[#3d4f7c]/10 flex items-center justify-center">
                      <svg class="w-3 h-3 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Complainant</p>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Name</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.complainant_name }}</span></div>
                    <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Address</span><span class="text-[11px] font-semibold text-slate-700 text-right max-w-[160px]">{{ selectedCase.complainant_address }}</span></div>
                    <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Contact</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.complainant_contact || '—' }}</span></div>
                  </div>
                </div>
                <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                  <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-red-50 flex items-center justify-center">
                      <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Respondent/Subject</p>
                  </div>
                  <div class="divide-y divide-slate-100/80">
                    <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Name</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.respondent_name }}</span></div>
                    <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Type</span><span class="text-[11px] font-semibold text-slate-700 text-right capitalize">{{ selectedCase.respondent_type || 'Person' }}</span></div>
                    <div v-if="selectedCase.respondent_type === 'person'" class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Contact</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.respondent_contact || '—' }}</span></div>
                  </div>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                  <div class="w-5 h-5 rounded-md bg-[#3d4f7c]/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </div>
                  <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case Information</p>
                </div>
                <div class="divide-y divide-slate-100/80">
                  <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Type</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.type }}</span></div>
                  <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Incident Date</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ formatDate(selectedCase.incident_date) }}</span></div>
                  <div class="px-4 py-2.5 flex justify-between items-center gap-2"><span class="text-[11px] text-slate-400 flex-shrink-0">Assigned Lupon</span><span class="text-[11px] font-semibold text-slate-700 text-right">{{ selectedCase.lupon_name || 'Unassigned' }}</span></div>
                </div>
              </div>
              <div class="rounded-2xl border border-amber-200/60 overflow-hidden">
                <div class="px-4 py-2.5 bg-amber-50/60 border-b border-amber-100/80 flex items-center gap-2">
                  <div class="w-5 h-5 rounded-md bg-amber-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                  </div>
                  <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Complaint Details</p>
                </div>
                <div class="px-4 py-3.5 bg-amber-50/20">
                  <p class="text-xs text-slate-600 leading-relaxed">{{ selectedCase.details || 'No details provided.' }}</p>
                </div>
              </div>
              <div v-if="selectedCase.mediation_notes" class="rounded-2xl border border-blue-200/60 overflow-hidden">
                <div class="px-4 py-2.5 bg-blue-50/60 border-b border-blue-100/80 flex items-center gap-2">
                  <div class="w-5 h-5 rounded-md bg-blue-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                  </div>
                  <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Mediation Notes</p>
                </div>
                <div class="px-4 py-3.5 bg-blue-50/20">
                  <p class="text-xs text-slate-600 leading-relaxed">{{ selectedCase.mediation_notes }}</p>
                </div>
              </div>
              <div v-if="selectedCase.resolution" class="rounded-2xl border border-emerald-200/60 overflow-hidden">
                <div class="px-4 py-2.5 bg-emerald-50/60 border-b border-emerald-100/80 flex items-center gap-2">
                  <div class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Resolution</p>
                </div>
                <div class="px-4 py-3.5 bg-emerald-50/20">
                  <p class="text-xs text-slate-600 leading-relaxed">{{ selectedCase.resolution }}</p>
                  <p class="text-[10px] text-slate-400 mt-2">Resolved on: {{ formatDate(selectedCase.resolved_date) }}</p>
                </div>
              </div>
              <div class="flex justify-end pt-1 pb-2">
                <button @click="showViewModal = false"
                  class="flex items-center gap-1.5 px-4 py-2 text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Reassign Modal ── -->
    <Transition name="modal">
      <div v-if="showReassignModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showReassignModal = false"></div>
        <div class="relative bg-white w-full sm:rounded-3xl sm:max-w-md overflow-hidden"
          style="box-shadow: 0 40px 80px -12px rgba(0,0,0,0.22);">
          <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
            <h3 class="text-base font-semibold text-white">Reassign Lupon Member</h3>
            <p class="text-xs text-white/60 mt-0.5">Case <span class="font-mono font-semibold text-white/80">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</span></p>
          </div>
          <div class="px-6 py-5">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1.5">Select Lupon Member</label>
            <select v-model="selectedLuponId"
              class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white">
              <option value="">— Select Lupon member —</option>
              <option v-for="member in luponMembers" :key="member.id" :value="member.id">{{ member.name }}</option>
            </select>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
            <button @click="showReassignModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer">Cancel</button>
            <button @click="submitReassign" :disabled="!selectedLuponId"
              class="px-5 py-2 text-sm font-semibold text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all cursor-pointer"
              style="background:#3d4f7c"
              @mouseenter="e => e.currentTarget.style.backgroundColor='#252b3b'"
              @mouseleave="e => e.currentTarget.style.backgroundColor='#3d4f7c'">
              Confirm Reassign
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Resolution Modal ── -->
    <Transition name="modal">
      <div v-if="showResolutionModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md" @click="showResolutionModal = false"></div>
        <div class="relative bg-white w-full sm:rounded-3xl sm:max-w-lg overflow-hidden"
          style="box-shadow: 0 40px 80px -12px rgba(0,0,0,0.22);">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-800">Approve Resolution</h3>
            <button @click="showResolutionModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="px-6 py-5">
            <div class="bg-[#3d4f7c]/5 border border-[#3d4f7c]/20 rounded-xl p-3 mb-4">
              <p class="text-xs font-semibold text-[#3d4f7c] font-mono">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</p>
              <p class="text-sm text-slate-600 mt-0.5">{{ selectedCase?.complainant_name }} vs {{ selectedCase?.respondent_name }}</p>
            </div>
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Resolution Details</label>
                <textarea v-model="resolutionText" rows="4" placeholder="Enter the resolution details..."
                  class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white resize-none"></textarea>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Action</label>
                <div class="flex gap-3">
                  <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm"
                    :class="resolutionAction === 'close' ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                    <input type="radio" v-model="resolutionAction" value="close" class="hidden" />
                    <span class="w-2 h-2 rounded-full flex-shrink-0" :class="resolutionAction === 'close' ? 'bg-amber-500' : 'bg-slate-300'"></span>
                    Close Case
                  </label>
                  <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm"
                    :class="resolutionAction === 'approve' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                    <input type="radio" v-model="resolutionAction" value="approve" class="hidden" />
                    <span class="w-2 h-2 rounded-full flex-shrink-0" :class="resolutionAction === 'approve' ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                    Approve
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
            <button @click="showResolutionModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer">Cancel</button>
            <button @click="submitResolution"
              class="px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all cursor-pointer"
              :class="resolutionAction === 'close' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700'">
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

const loading = ref(false)
const currentPage = ref(1)
const itemsPerPage = 8
const showViewModal = ref(false)
const showReassignModal = ref(false)
const showResolutionModal = ref(false)
const selectedCase = ref(null)
const selectedLuponId = ref('')
const resolutionText = ref('')
const resolutionAction = ref('approve')
const filters = reactive({ search: '', status: '', type: '', lupon: '' })

const complaints = ref([
  { id: 101, complainant_name: 'Maria Santos', complainant_address: 'Purok 3, Maligaya', complainant_contact: '0912 345 6789', respondent_name: 'Juan Dela Cruz', respondent_address: 'Purok 5, Maligaya', respondent_contact: '0923 456 7890', respondent_type: 'person', type: 'Boundary Dispute', status: 'pending', lupon_name: null, filed_date: '2024-03-10', incident_date: '2024-03-08', details: 'Dispute over property boundary between neighboring lots. Complainant claims respondent constructed fence encroaching on their property.', mediation_notes: null, resolution: null, resolved_date: null },
  { id: 102, complainant_name: 'Pedro Reyes', complainant_address: 'Purok 7, Masagana', complainant_contact: '0934 567 8901', respondent_name: 'Ana Gonzales', respondent_address: 'Purok 2, Masagana', respondent_contact: '0945 678 9012', respondent_type: 'person', type: 'Noise Complaint', status: 'mediation', lupon_name: 'Lupon Member Santos', filed_date: '2024-03-08', incident_date: '2024-03-05', details: "Complainant reports excessive noise from respondent's property during late hours.", mediation_notes: 'Initial mediation scheduled. Both parties agreed to meet.', resolution: null, resolved_date: null },
  { id: 103, complainant_name: 'Josefa Fernandez', complainant_address: 'Sitio Mabuhay', complainant_contact: '0956 789 0123', respondent_name: 'Ramon Fernandez', respondent_address: 'Sitio Mabuhay', respondent_contact: '0967 890 1234', respondent_type: 'person', type: 'Family Dispute', status: 'escalated', lupon_name: 'Lupon Member Cruz', filed_date: '2024-03-01', incident_date: '2024-02-28', details: 'Family disagreement over inheritance and property distribution.', mediation_notes: 'Multiple mediation sessions held. Parties unable to reach agreement.', resolution: null, resolved_date: null },
  { id: 104, complainant_name: 'Roberto Lim', complainant_address: 'Purok 1, Masunurin', complainant_contact: '0978 901 2345', respondent_name: 'Elena Lim', respondent_address: 'Purok 1, Masunurin', respondent_contact: '0989 012 3456', respondent_type: 'person', type: 'Domestic Issue', status: 'resolved', lupon_name: 'Lupon Member Reyes', filed_date: '2024-02-20', incident_date: '2024-02-15', details: 'Minor domestic disagreement resolved through mediation.', mediation_notes: 'Both parties reached amicable agreement.', resolution: 'Parties agreed to family counseling and signed peace covenant.', resolved_date: '2024-03-05' }
])

const luponMembers = ref([
  { id: 1, name: 'Lupon Member Santos' }, { id: 2, name: 'Lupon Member Cruz' },
  { id: 3, name: 'Lupon Member Reyes' }, { id: 4, name: 'Lupon Member Gomez' }
])

const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']
function getAvatarColor(name = '') { let h = 0; for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h); return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length] }
function getInitials(name = '') { const p = name.trim().split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
function formatDate(date) { if (!date) return '—'; return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }
function formatStatus(status) { return status.charAt(0).toUpperCase() + status.slice(1) }
function statusBadge(status) { return { pending: 'bg-amber-50 text-amber-700 border border-amber-200', mediation: 'bg-blue-50 text-blue-700 border border-blue-200', escalated: 'bg-red-50 text-red-700 border border-red-200', resolved: 'bg-emerald-50 text-emerald-700 border border-emerald-200', closed: 'bg-slate-100 text-slate-600 border border-slate-200' }[status] || 'bg-slate-100 text-slate-600' }
function statusDot(status) { return { pending: 'bg-amber-400', mediation: 'bg-blue-400', escalated: 'bg-red-400', resolved: 'bg-emerald-400', closed: 'bg-slate-400' }[status] || 'bg-slate-400' }
function statusDotSolid(status) { return { pending: 'bg-amber-500', mediation: 'bg-blue-500', escalated: 'bg-red-500', resolved: 'bg-emerald-500', closed: 'bg-slate-500' }[status] || 'bg-slate-500' }

const pendingCount = computed(() => complaints.value.filter(c => c.status === 'pending').length)
const mediationCount = computed(() => complaints.value.filter(c => c.status === 'mediation').length)
const escalatedCount = computed(() => complaints.value.filter(c => c.status === 'escalated').length)
const resolvedCount = computed(() => complaints.value.filter(c => ['resolved','closed'].includes(c.status)).length)
const stats = computed(() => {
  const total = complaints.value.length
  const active = complaints.value.filter(c => ['pending','mediation','escalated'].includes(c.status)).length
  const resolved = complaints.value.filter(c => ['resolved','closed'].includes(c.status)).length
  return { total, active, resolved, resolutionRate: total > 0 ? Math.round((resolved / total) * 100) : 0 }
})

const hasActiveFilters = computed(() => filters.search || filters.status || filters.type || filters.lupon)
const filteredComplaints = computed(() => {
  let f = [...complaints.value]
  if (filters.search) { const s = filters.search.toLowerCase(); f = f.filter(c => c.complainant_name?.toLowerCase().includes(s) || c.respondent_name?.toLowerCase().includes(s) || c.type?.toLowerCase().includes(s) || String(c.id).includes(s)) }
  if (filters.status) f = f.filter(c => c.status === filters.status)
  if (filters.type) f = f.filter(c => c.type?.toLowerCase().includes(filters.type.toLowerCase()))
  if (filters.lupon) { const m = luponMembers.value.find(m => m.id === parseInt(filters.lupon)); if (m) f = f.filter(c => c.lupon_name === m.name) }
  return f
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredComplaints.value.length / itemsPerPage)))
const paginatedComplaints = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredComplaints.value.slice(s, s + itemsPerPage) })

function openViewModal(c) { selectedCase.value = c; showViewModal.value = true }
function openReassignModal(c) { selectedCase.value = c; selectedLuponId.value = ''; showReassignModal.value = true }
function openResolutionModal(c) { selectedCase.value = c; resolutionText.value = ''; resolutionAction.value = 'approve'; showResolutionModal.value = true }

function submitReassign() {
  if (!selectedLuponId.value) return
  const member = luponMembers.value.find(m => m.id === selectedLuponId.value)
  const case_ = complaints.value.find(c => c.id === selectedCase.value.id)
  if (case_) case_.lupon_name = member.name
  Swal.fire({ icon: 'success', title: 'Reassigned', text: `Case reassigned to ${member.name}`, timer: 2000, showConfirmButton: false })
  showReassignModal.value = false
}

function submitResolution() {
  if (!resolutionText.value.trim()) { Swal.fire({ icon: 'warning', title: 'Resolution Required', text: 'Please enter resolution details.' }); return }
  const case_ = complaints.value.find(c => c.id === selectedCase.value.id)
  if (case_) { case_.resolution = resolutionText.value; case_.resolved_date = new Date().toISOString().split('T')[0]; case_.status = resolutionAction.value === 'close' ? 'closed' : 'resolved' }
  Swal.fire({ icon: 'success', title: 'Success', text: `Case ${resolutionAction.value === 'close' ? 'closed' : 'resolved'} successfully.`, timer: 2000, showConfirmButton: false })
  showResolutionModal.value = false
}

function confirmEscalate(case_) {
  Swal.fire({ title: 'Escalate Case?', text: `Escalate case #C-${String(case_.id).padStart(5, '0')}?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, escalate' })
    .then(r => { if (r.isConfirmed) { const c = complaints.value.find(c => c.id === case_.id); if (c) c.status = 'escalated'; Swal.fire({ icon: 'success', title: 'Escalated', text: 'Case has been escalated.', timer: 2000, showConfirmButton: false }) } })
}

function resetFilters() { filters.search = ''; filters.status = ''; filters.type = ''; filters.lupon = ''; currentPage.value = 1 }
watch(filters, () => { currentPage.value = 1 }, { deep: true })
onMounted(() => {})
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