<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c]"></div>
          <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c]">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Complaints & Disputes</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and monitor all complaints, disputes, and Lupon cases</p>
          </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="flex flex-wrap items-center gap-3">
          <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2 flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            <span class="text-xs font-semibold text-amber-700">{{ pendingCount }} Pending</span>
          </div>
          <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            <span class="text-xs font-semibold text-blue-700">{{ mediationCount }} In Mediation</span>
          </div>
          <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-2 flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
            <span class="text-xs font-semibold text-red-700">{{ escalatedCount }} Escalated</span>
          </div>
          <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2 flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold text-emerald-700">{{ resolvedCount }} Resolved</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Complaints</p>
            <p class="text-2xl font-bold mt-1 text-slate-800">{{ stats.total }}</p>
            <p class="text-xs text-slate-400 mt-1">All time</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-[#3d4f7c]/10 flex items-center justify-center">
            <svg class="w-6 h-6 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Cases</p>
            <p class="text-2xl font-bold mt-1 text-slate-800">{{ stats.active }}</p>
            <p class="text-xs text-slate-400 mt-1">In progress</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 12H4M12 4v16" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Lupon Members</p>
            <p class="text-2xl font-bold mt-1 text-slate-800">{{ luponMembers.length }}</p>
            <p class="text-xs text-slate-400 mt-1">Available mediators</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Resolution Rate</p>
            <p class="text-2xl font-bold mt-1 text-slate-800">{{ stats.resolutionRate }}%</p>
            <p class="text-xs text-slate-400 mt-1">{{ stats.resolved }}/{{ stats.total }} cases resolved</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by complainant, respondent/entity, or case number…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.status"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="mediation">In Mediation</option>
            <option value="escalated">Escalated</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
          <select v-model="filters.type"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Types</option>
            <option value="person">Person vs Person</option>
            <option value="entity">Person vs Entity</option>
            <option value="property">Person vs Property/Animal</option>
            <option value="incident">Incident Report</option>
          </select>
          <select v-model="filters.lupon"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Lupon Members</option>
            <option v-for="member in luponMembers" :key="member.id" :value="member.id">
              {{ member.name }}
            </option>
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

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
      <!-- Table Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">Complaints & Disputes List</h2>
          </div>
          <span class="text-xs text-white/50">
            {{ filteredComplaints.length }} case{{ filteredComplaints.length !== 1 ? 's' : '' }}
          </span>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading complaints...</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
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
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">

              <!-- Case Number -->
              <td class="px-6 py-4">
                <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">#C-{{ String(case_.id).padStart(5, '0') }}</span>
              </td>

              <!-- Complainant -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                    :style="{ background: getAvatarColor(case_.complainant_name) }">
                    {{ getInitials(case_.complainant_name) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-800">{{ case_.complainant_name }}</p>
                    <p class="text-xs text-slate-400">{{ case_.complainant_address }}</p>
                  </div>
                </div>
              </td>

              <!-- Respondent/Subject (Flexible) -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <!-- Show avatar only for person-type respondents -->
                  <div v-if="case_.respondent_type === 'person'" 
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                    :style="{ background: getAvatarColor(case_.respondent_name) }">
                    {{ getInitials(case_.respondent_name) }}
                  </div>
                  <!-- Show icon for non-person respondents -->
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
                      <span v-else-if="case_.respondent_type === 'entity'" class="text-xs text-slate-400">Entity/Business</span>
                      <span v-else-if="case_.respondent_type === 'property'" class="text-xs text-slate-400">Property/Animal</span>
                      <span v-else class="text-xs text-slate-400">Incident Report</span>
                    </p>
                  </div>
                </div>
              </td>

              <!-- Type -->
              <td class="px-6 py-4">
                <span class="text-sm text-slate-600">{{ case_.type }}</span>
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span :class="statusBadge(case_.status)"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                  <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                      :class="statusDot(case_.status)"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5"
                      :class="statusDotSolid(case_.status)"></span>
                  </span>
                  {{ formatStatus(case_.status) }}
                </span>
              </td>

              <!-- Assigned Lupon -->
              <td class="px-6 py-4">
                <div v-if="case_.lupon_name" class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white"
                    :style="{ background: getAvatarColor(case_.lupon_name) }">
                    {{ getInitials(case_.lupon_name) }}
                  </div>
                  <span class="text-sm text-slate-600">{{ case_.lupon_name }}</span>
                </div>
                <span v-else class="text-sm text-slate-400 italic">Unassigned</span>
              </td>

              <!-- Filed Date -->
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
                  <!-- View Details -->
                  <button @click="openViewModal(case_)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>

                  <!-- Reassign Lupon -->
                  <button @click="openReassignModal(case_)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Reassign Lupon Member">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                  </button>

                  <!-- Escalate Case -->
                  <button v-if="case_.status !== 'escalated' && case_.status !== 'resolved' && case_.status !== 'closed'"
                    @click="confirmEscalate(case_)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Escalate Case">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                  </button>

                  <!-- Approve Resolution / Close Case -->
                  <button v-if="case_.status === 'mediation'"
                    @click="openResolutionModal(case_)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Approve Resolution">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!loading && filteredComplaints.length === 0">
              <td colspan="8" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No complaints found</p>
                    <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search or filter criteria</p>
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
      <div v-if="!loading && filteredComplaints.length > itemsPerPage"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredComplaints.length) }}
          <span class="text-slate-300 mx-1">/</span>
          {{ filteredComplaints.length }} results
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
      <div v-else-if="!loading && filteredComplaints.length > 0"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono">
          Showing <span class="font-medium text-slate-700">{{ filteredComplaints.length }}</span> case{{ filteredComplaints.length !== 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- View Details Modal (Updated to show flexible respondent) -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
                <h3 class="text-base font-semibold text-slate-800">Case Details</h3>
                <span class="px-2 py-0.5 bg-[#3d4f7c]/10 text-[#3d4f7c] text-xs rounded-full font-mono">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</span>
              </div>
              <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            
            <!-- Body -->
            <div v-if="selectedCase" class="px-6 py-5 max-h-[70vh] overflow-y-auto scrollbar-thin">
              <!-- Status Banner -->
              <div class="mb-5 flex items-center justify-between">
                <span :class="statusBadge(selectedCase.status)"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold">
                  <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                      :class="statusDot(selectedCase.status)"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2"
                      :class="statusDotSolid(selectedCase.status)"></span>
                  </span>
                  {{ formatStatus(selectedCase.status) }}
                </span>
                <span class="text-xs text-slate-400">
                  Filed: {{ formatDate(selectedCase.filed_date) }}
                </span>
              </div>

              <!-- Parties Involved -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Complainant -->
                <div class="bg-slate-50 rounded-xl p-4">
                  <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Complainant
                  </h4>
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white"
                      :style="{ background: getAvatarColor(selectedCase.complainant_name) }">
                      {{ getInitials(selectedCase.complainant_name) }}
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">{{ selectedCase.complainant_name }}</p>
                      <p class="text-xs text-slate-400">{{ selectedCase.complainant_address }}</p>
                      <p class="text-xs text-slate-400">{{ selectedCase.complainant_contact }}</p>
                    </div>
                  </div>
                </div>

                <!-- Respondent/Subject (Flexible) -->
                <div class="bg-slate-50 rounded-xl p-4">
                  <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path v-if="selectedCase.respondent_type === 'person'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Respondent/Subject
                  </h4>
                  <div class="flex items-center gap-3">
                    <!-- Avatar for person, icon for others -->
                    <div v-if="selectedCase.respondent_type === 'person'" 
                      class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white"
                      :style="{ background: getAvatarColor(selectedCase.respondent_name) }">
                      {{ getInitials(selectedCase.respondent_name) }}
                    </div>
                    <div v-else class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                      <svg v-if="selectedCase.respondent_type === 'entity'" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                      <svg v-else-if="selectedCase.respondent_type === 'property'" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      <svg v-else class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">{{ selectedCase.respondent_name }}</p>
                      <p class="text-xs text-slate-400">
                        <span v-if="selectedCase.respondent_type === 'person'">{{ selectedCase.respondent_address }}</span>
                        <span v-else-if="selectedCase.respondent_type === 'entity'">Entity/Business</span>
                        <span v-else-if="selectedCase.respondent_type === 'property'">Property/Animal</span>
                        <span v-else>Incident Report</span>
                      </p>
                      <p v-if="selectedCase.respondent_contact && selectedCase.respondent_type === 'person'" class="text-xs text-slate-400">{{ selectedCase.respondent_contact }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Case Details (rest remains the same) -->
              <div class="bg-slate-50 rounded-xl p-4 mb-4">
                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Case Information
                </h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <p class="text-[10px] text-slate-400">Complaint Type</p>
                    <p class="font-medium text-slate-700">{{ selectedCase.type }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] text-slate-400">Incident Date</p>
                    <p class="font-medium text-slate-700">{{ formatDate(selectedCase.incident_date) }}</p>
                  </div>
                </div>
                <div class="mt-3">
                  <p class="text-[10px] text-slate-400">Complaint Details</p>
                  <p class="text-sm text-slate-700 bg-white p-3 rounded-lg border border-slate-100 mt-1">
                    {{ selectedCase.details }}
                  </p>
                </div>
              </div>

              <!-- Mediation & Resolution -->
              <div class="bg-slate-50 rounded-xl p-4">
                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                  </svg>
                  Mediation & Resolution
                </h4>
                
                <!-- Assigned Lupon -->
                <div class="mb-3">
                  <p class="text-[10px] text-slate-400">Assigned Lupon Member</p>
                  <div v-if="selectedCase.lupon_name" class="flex items-center gap-2 mt-1">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white"
                      :style="{ background: getAvatarColor(selectedCase.lupon_name) }">
                      {{ getInitials(selectedCase.lupon_name) }}
                    </div>
                    <span class="text-sm text-slate-700">{{ selectedCase.lupon_name }}</span>
                  </div>
                  <span v-else class="text-sm text-slate-400 italic">Not assigned</span>
                </div>

                <!-- Mediation Notes -->
                <div v-if="selectedCase.mediation_notes" class="mb-3">
                  <p class="text-[10px] text-slate-400">Mediation Notes</p>
                  <p class="text-sm text-slate-700 bg-white p-3 rounded-lg border border-slate-100 mt-1">
                    {{ selectedCase.mediation_notes }}
                  </p>
                </div>

                <!-- Resolution -->
                <div v-if="selectedCase.resolution">
                  <p class="text-[10px] text-slate-400">Resolution</p>
                  <p class="text-sm text-slate-700 bg-white p-3 rounded-lg border border-slate-100 mt-1">
                    {{ selectedCase.resolution }}
                  </p>
                  <p class="text-xs text-slate-400 mt-1">
                    Resolved on: {{ formatDate(selectedCase.resolved_date) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end gap-2">
              <button @click="showViewModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Reassign Lupon Modal -->
    <Transition name="modal">
      <div v-if="showReassignModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showReassignModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 rounded-t-2xl bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
              <h3 class="text-base font-semibold text-white">Reassign Lupon Member</h3>
            </div>
            <div class="px-6 py-5">
              <p class="text-sm text-slate-500 mb-4">
                Reassigning case <span class="font-semibold text-[#3d4f7c]">#C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</span>
              </p>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1.5">Select Lupon Member</label>
              <select v-model="selectedLuponId"
                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white">
                <option value="">— Select Lupon member —</option>
                <option v-for="member in luponMembers" :key="member.id" :value="member.id">
                  {{ member.name }}
                </option>
              </select>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
              <button @click="showReassignModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">
                Cancel
              </button>
              <button @click="submitReassign" :disabled="!selectedLuponId"
                class="px-5 py-2 text-sm font-semibold text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                style="background:#3d4f7c"
                @mouseenter="e => e.currentTarget.style.backgroundColor='#252b3b'"
                @mouseleave="e => e.currentTarget.style.backgroundColor='#3d4f7c'">
                Confirm Reassign
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Resolution Modal -->
    <Transition name="modal">
      <div v-if="showResolutionModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showResolutionModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <h3 class="text-base font-semibold text-slate-800">Approve Resolution</h3>
              <button @click="showResolutionModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="px-6 py-5">
              <div class="bg-[#3d4f7c]/5 border border-[#3d4f7c]/20 rounded-lg p-3 mb-4">
                <p class="text-xs font-semibold text-[#3d4f7c]">Case #C-{{ selectedCase ? String(selectedCase.id).padStart(5, '0') : '' }}</p>
                <p class="text-sm text-slate-600 mt-1">{{ selectedCase?.complainant_name }} vs {{ selectedCase?.respondent_name }}</p>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Resolution Details</label>
                  <textarea v-model="resolutionText"
                    rows="4"
                    placeholder="Enter the resolution details..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] bg-slate-50 focus:bg-white resize-none"></textarea>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Action</label>
                  <div class="flex gap-3">
                    <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm"
                      :class="resolutionAction === 'close' ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                      <input type="radio" v-model="resolutionAction" value="close" class="hidden" />
                      <span class="w-2 h-2 rounded-full" :class="resolutionAction === 'close' ? 'bg-amber-500' : 'bg-slate-300'"></span>
                      Close Case
                    </label>
                    <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border cursor-pointer text-sm"
                      :class="resolutionAction === 'approve' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-500'">
                      <input type="radio" v-model="resolutionAction" value="approve" class="hidden" />
                      <span class="w-2 h-2 rounded-full" :class="resolutionAction === 'approve' ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                      Approve Resolution
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end">
              <button @click="showResolutionModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">
                Cancel
              </button>
              <button @click="submitResolution"
                class="px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all"
                :class="resolutionAction === 'close' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                {{ resolutionAction === 'close' ? 'Close Case' : 'Approve Resolution' }}
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
const showReassignModal = ref(false)
const showResolutionModal = ref(false)
const selectedCase = ref(null)
const selectedLuponId = ref('')
const resolutionText = ref('')
const resolutionAction = ref('approve')

const filters = reactive({
  search: '',
  status: '',
  priority: '',
  lupon: ''
})

// ── Sample Data ──────────────────────────────────────────────────
const complaints = ref([
  {
    id: 101,
    complainant_name: 'Maria Santos',
    complainant_address: 'Purok 3, Maligaya',
    complainant_contact: '0912 345 6789',
    respondent_name: 'Juan Dela Cruz',
    respondent_address: 'Purok 5, Maligaya',
    respondent_contact: '0923 456 7890',
    type: 'Boundary Dispute',
    status: 'pending',
    priority: 'high',
    lupon_name: null,
    filed_date: '2024-03-10',
    incident_date: '2024-03-08',
    details: 'Dispute over property boundary between neighboring lots. Complainant claims respondent constructed fence encroaching on their property.',
    mediation_notes: null,
    resolution: null,
    resolved_date: null
  },
  {
    id: 102,
    complainant_name: 'Pedro Reyes',
    complainant_address: 'Purok 7, Masagana',
    complainant_contact: '0934 567 8901',
    respondent_name: 'Ana Gonzales',
    respondent_address: 'Purok 2, Masagana',
    respondent_contact: '0945 678 9012',
    type: 'Noise Complaint',
    status: 'mediation',
    priority: 'medium',
    lupon_name: 'Lupon Member Santos',
    filed_date: '2024-03-08',
    incident_date: '2024-03-05',
    details: 'Complainant reports excessive noise from respondent\'s property during late hours.',
    mediation_notes: 'Initial mediation scheduled. Both parties agreed to meet.',
    resolution: null,
    resolved_date: null
  },
  {
    id: 103,
    complainant_name: 'Josefa Fernandez',
    complainant_address: 'Sitio Mabuhay',
    complainant_contact: '0956 789 0123',
    respondent_name: 'Ramon Fernandez',
    respondent_address: 'Sitio Mabuhay',
    respondent_contact: '0967 890 1234',
    type: 'Family Dispute',
    status: 'escalated',
    priority: 'high',
    lupon_name: 'Lupon Member Cruz',
    filed_date: '2024-03-01',
    incident_date: '2024-02-28',
    details: 'Family disagreement over inheritance and property distribution.',
    mediation_notes: 'Multiple mediation sessions held. Parties unable to reach agreement.',
    resolution: null,
    resolved_date: null
  },
  {
    id: 104,
    complainant_name: 'Roberto Lim',
    complainant_address: 'Purok 1, Masunurin',
    complainant_contact: '0978 901 2345',
    respondent_name: 'Elena Lim',
    respondent_address: 'Purok 1, Masunurin',
    respondent_contact: '0989 012 3456',
    type: 'Domestic Issue',
    status: 'resolved',
    priority: 'low',
    lupon_name: 'Lupon Member Reyes',
    filed_date: '2024-02-20',
    incident_date: '2024-02-15',
    details: 'Minor domestic disagreement resolved through mediation.',
    mediation_notes: 'Both parties reached amicable agreement.',
    resolution: 'Parties agreed to family counseling and signed peace covenant.',
    resolved_date: '2024-03-05'
  }
])

const luponMembers = ref([
  { id: 1, name: 'Lupon Member Santos', role: 'Lupon Member' },
  { id: 2, name: 'Lupon Member Cruz', role: 'Lupon Member' },
  { id: 3, name: 'Lupon Member Reyes', role: 'Lupon Member' },
  { id: 4, name: 'Lupon Member Gomez', role: 'Lupon Member' }
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

function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function formatStatus(status) {
  return status.charAt(0).toUpperCase() + status.slice(1)
}

function statusBadge(status) {
  return {
    'pending': 'bg-amber-50 text-amber-700 border border-amber-200',
    'mediation': 'bg-blue-50 text-blue-700 border border-blue-200',
    'escalated': 'bg-red-50 text-red-700 border border-red-200',
    'resolved': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    'closed': 'bg-slate-100 text-slate-600 border border-slate-200'
  }[status] || 'bg-slate-100 text-slate-600'
}

function statusDot(status) {
  return {
    'pending': 'bg-amber-400',
    'mediation': 'bg-blue-400',
    'escalated': 'bg-red-400',
    'resolved': 'bg-emerald-400',
    'closed': 'bg-slate-400'
  }[status] || 'bg-slate-400'
}

function statusDotSolid(status) {
  return {
    'pending': 'bg-amber-500',
    'mediation': 'bg-blue-500',
    'escalated': 'bg-red-500',
    'resolved': 'bg-emerald-500',
    'closed': 'bg-slate-500'
  }[status] || 'bg-slate-500'
}

function priorityBadge(priority) {
  return {
    'high': 'bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-lg text-xs font-semibold',
    'medium': 'bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-lg text-xs font-semibold',
    'low': 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg text-xs font-semibold'
  }[priority] || 'bg-slate-50 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-lg text-xs font-semibold'
}

// ── Computed Stats ───────────────────────────────────────────────
const pendingCount = computed(() => {
  return complaints.value.filter(c => c.status === 'pending').length
})

const mediationCount = computed(() => {
  return complaints.value.filter(c => c.status === 'mediation').length
})

const escalatedCount = computed(() => {
  return complaints.value.filter(c => c.status === 'escalated').length
})

const resolvedCount = computed(() => {
  return complaints.value.filter(c => c.status === 'resolved' || c.status === 'closed').length
})

const stats = computed(() => {
  const total = complaints.value.length
  const active = complaints.value.filter(c => 
    c.status === 'pending' || c.status === 'mediation' || c.status === 'escalated'
  ).length
  const resolved = complaints.value.filter(c => 
    c.status === 'resolved' || c.status === 'closed'
  ).length
  const resolutionRate = total > 0 ? Math.round((resolved / total) * 100) : 0
  
  return {
    total,
    active,
    luponMembers: luponMembers.value.length,
    resolutionRate
  }
})

// ── Filtering ────────────────────────────────────────────────────
const hasActiveFilters = computed(() => 
  filters.search || filters.status || filters.priority || filters.lupon
)

const filteredComplaints = computed(() => {
  let filtered = [...complaints.value]
  
  if (filters.search) {
    const s = filters.search.toLowerCase()
    filtered = filtered.filter(c => 
      c.complainant_name?.toLowerCase().includes(s) ||
      c.respondent_name?.toLowerCase().includes(s) ||
      c.type?.toLowerCase().includes(s) ||
      String(c.id).includes(s)
    )
  }
  
  if (filters.status) {
    filtered = filtered.filter(c => c.status === filters.status)
  }
  
  if (filters.priority) {
    filtered = filtered.filter(c => c.priority === filters.priority)
  }
  
  if (filters.lupon) {
    filtered = filtered.filter(c => c.lupon_name?.includes(filters.lupon))
  }
  
  return filtered
})

const totalPages = computed(() => 
  Math.max(1, Math.ceil(filteredComplaints.value.length / itemsPerPage))
)

const paginatedComplaints = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredComplaints.value.slice(start, start + itemsPerPage)
})

// ── Methods ──────────────────────────────────────────────────────
function openViewModal(case_) {
  selectedCase.value = case_
  showViewModal.value = true
}

function openReassignModal(case_) {
  selectedCase.value = case_
  selectedLuponId.value = ''
  showReassignModal.value = true
}

function openResolutionModal(case_) {
  selectedCase.value = case_
  resolutionText.value = ''
  resolutionAction.value = 'approve'
  showResolutionModal.value = true
}

function submitReassign() {
  if (!selectedLuponId.value) return
  
  // Find selected lupon member
  const member = luponMembers.value.find(m => m.id === selectedLuponId.value)
  
  // Update case
  const case_ = complaints.value.find(c => c.id === selectedCase.value.id)
  if (case_) {
    case_.lupon_name = member.name
  }
  
  Swal.fire({
    icon: 'success',
    title: 'Reassigned',
    text: `Case has been reassigned to ${member.name}`,
    timer: 2000,
    showConfirmButton: false
  })
  
  showReassignModal.value = false
}

function submitResolution() {
  if (!resolutionText.value.trim()) {
    Swal.fire({
      icon: 'warning',
      title: 'Resolution Required',
      text: 'Please enter the resolution details.'
    })
    return
  }
  
  // Update case
  const case_ = complaints.value.find(c => c.id === selectedCase.value.id)
  if (case_) {
    case_.resolution = resolutionText.value
    case_.resolved_date = new Date().toISOString().split('T')[0]
    case_.status = resolutionAction.value === 'close' ? 'closed' : 'resolved'
  }
  
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: `Case has been ${resolutionAction.value === 'close' ? 'closed' : 'resolved'} successfully.`,
    timer: 2000,
    showConfirmButton: false
  })
  
  showResolutionModal.value = false
}

function confirmEscalate(case_) {
  Swal.fire({
    title: 'Escalate Case?',
    text: `Are you sure you want to escalate case #C-${String(case_.id).padStart(5, '0')}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, escalate',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      // Update case status
      const updatedCase = complaints.value.find(c => c.id === case_.id)
      if (updatedCase) {
        updatedCase.status = 'escalated'
      }
      
      Swal.fire({
        icon: 'success',
        title: 'Escalated',
        text: 'Case has been escalated.',
        timer: 2000,
        showConfirmButton: false
      })
    }
  })
}

function resetFilters() {
  filters.search = ''
  filters.status = ''
  filters.priority = ''
  filters.lupon = ''
  currentPage.value = 1
}

watch(filters, () => { currentPage.value = 1 }, { deep: true })

onMounted(() => {
  // Load data if needed
})
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

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