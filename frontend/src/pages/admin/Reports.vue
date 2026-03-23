<template>
  <div class="min-h-full bg-[#f5f6fa] p-3 sm:p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-4 sm:mb-6">
      <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-9 sm:h-10 rounded-full bg-[#3d4f7c] flex-shrink-0"></div>
          <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c] flex-shrink-0">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
          </div>
          <div>
            <h1 class="text-lg sm:text-3xl font-semibold text-slate-800 tracking-tight">Audit Logs</h1>
            <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 hidden sm:block">Track all system activities, user actions, and data changes</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-4 sm:mb-5 shadow-sm">
      <div class="flex flex-col gap-2.5">
        <!-- Search -->
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="filters.search" type="text" placeholder="Search by name, action, description…"
            class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all bg-slate-50 focus:bg-white"/>
        </div>
        <!-- Filter row -->
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.action"
            class="flex-1 min-w-[100px] appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Actions</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
            <option value="login">Login</option>
            <option value="logout">Logout</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
          <select v-model="filters.module"
            class="flex-1 min-w-[100px] appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="">All Modules</option>
            <option value="residents">Residents</option>
            <option value="announcements">Announcements</option>
            <option value="service_requests">Service Requests</option>
            <option value="complaints">Complaints</option>
            <option value="users">Users</option>
            <option value="auth">Auth</option>
          </select>
          <!-- Date inputs hidden on mobile unless needed -->
          <input v-model="filters.date_from" type="date"
            class="hidden sm:block flex-1 min-w-[120px] bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
          <input v-model="filters.date_to" type="date"
            class="hidden sm:block flex-1 min-w-[120px] bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
          <button v-if="hasActiveFilters" @click="resetFilters"
            class="w-9 h-9 flex items-center justify-center text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl transition-all cursor-pointer flex-shrink-0">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <!-- Mobile date row -->
        <div class="flex gap-2 sm:hidden">
          <input v-model="filters.date_from" type="date"
            class="flex-1 bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
          <input v-model="filters.date_to" type="date"
            class="flex-1 bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
        </div>
      </div>
    </div>

    <!-- Log List Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Header -->
      <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-1.5 h-7 rounded-full bg-white/20"></div>
          <h2 class="text-sm sm:text-lg font-semibold text-white tracking-tight">Activity Log</h2>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading audit logs…</p>
      </div>

      <!-- Empty -->
      <div v-else-if="filteredLogs.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
          <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <p class="text-sm font-bold text-slate-600">No log entries found</p>
        <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
        <button v-if="hasActiveFilters" @click="resetFilters"
          class="text-xs font-semibold px-4 py-2 rounded-xl cursor-pointer mt-1"
          style="color:#3d4f7c; background:#3d4f7c15; border:1px solid #3d4f7c30">Clear Filters</button>
      </div>

      <!-- ── Log Items ── -->
      <div v-else class="divide-y divide-slate-100">
        <div v-for="log in paginatedLogs" :key="log.id"
             class="group flex items-start gap-3 sm:gap-4 px-3 sm:px-6 py-3.5 sm:py-4 hover:bg-slate-50/60 active:bg-slate-100 transition-colors duration-100 cursor-pointer"
             @click="openDetail(log)">

          <!-- Action Icon -->
          <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center mt-0.5"
               :class="actionIconBg(log.action)">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" :class="actionIconColor(log.action)"
                 fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                 v-html="actionSvgPath(log.action)"></svg>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">

            <!-- Title + badge (mobile: stacked, desktop: inline) -->
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-800 leading-tight">{{ actionTitle(log.action) }}</p>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 leading-snug line-clamp-2 sm:line-clamp-1">{{ log.description }}</p>
              </div>
              <!-- Module badge — always visible, right side -->
              <span :class="moduleBadge(log.module)"
                class="flex-shrink-0 inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap border mt-0.5">
                {{ moduleLabel(log.module) }}
              </span>
            </div>

            <!-- Meta row — wraps gracefully on mobile -->
            <div class="flex items-center gap-x-3 gap-y-1 mt-1.5 flex-wrap">

              <!-- Time -->
              <span class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ formatRelative(log.created_at) }}
              </span>

              <!-- IP -->
              <span class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                {{ log.ip_address }}
              </span>

              <!-- Agent -->
              <span class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ shortAgent(log.user_agent) }}
              </span>

              <!-- Date -->
              <span class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ formatShortDate(log.created_at) }}
              </span>

            </div>
          </div>

        </div>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredLogs.length > itemsPerPage"
        class="px-3 sm:px-6 py-3 sm:py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-2">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredLogs.length) }}
          <span class="text-slate-300 mx-1">/</span>{{ filteredLogs.length }}
        </p>
        <p class="text-xs text-slate-400 sm:hidden">Page {{ currentPage }} / {{ totalPages }}</p>
        <div class="flex items-center gap-1">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage ? 'text-white' : 'text-slate-500 hover:bg-[#3d4f7c]/5 border border-transparent'"
              :style="page === currentPage ? 'background:#3d4f7c' : ''">{{ page }}</button>
            <span v-else-if="Math.abs(page - currentPage) === 2" class="w-6 flex items-end justify-center text-slate-300 text-xs pb-1">…</span>
          </template>
          <button :disabled="currentPage === totalPages" @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] transition-all cursor-pointer">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
      <div v-else-if="!loading && filteredLogs.length > 0"
        class="px-3 sm:px-6 py-3 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ filteredLogs.length }}</span> entr{{ filteredLogs.length !== 1 ? 'ies' : 'y' }}</p>
      </div>
    </div>

    <!-- ── Detail Modal ── -->
    <Transition name="modal">
      <div v-if="showDetailModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-md" @click="showDetailModal = false"></div>

        <!-- Full-screen on mobile, floating on desktop -->
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-3xl sm:max-w-lg sm:max-h-[90vh] flex flex-col overflow-hidden"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22)">

          <!-- Modal Header -->
          <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1 h-7 sm:h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm font-bold text-slate-800">Log Entry Details</h3>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">#LOG-{{ selectedLog ? String(selectedLog.id).padStart(6,'0') : '' }}</p>
              </div>
            </div>
            <button @click="showDetailModal = false"
              class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div v-if="selectedLog" class="flex-1 overflow-y-auto scrollbar-thin px-4 sm:px-6 py-4 sm:py-5 space-y-3.5">

            <!-- Banner -->
            <div class="flex items-center gap-3 px-3.5 py-3 rounded-xl border border-slate-200/60 bg-slate-50">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" :class="actionIconBg(selectedLog.action)">
                <svg class="w-5 h-5" :class="actionIconColor(selectedLog.action)" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                     v-html="actionSvgPath(selectedLog.action)"></svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800">{{ actionTitle(selectedLog.action) }}</p>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ formatDate(selectedLog.created_at) }} · {{ formatTime(selectedLog.created_at) }}</p>
              </div>
              <span :class="moduleBadge(selectedLog.module)" class="text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap flex-shrink-0">
                {{ moduleLabel(selectedLog.module) }}
              </span>
            </div>

            <!-- Fields -->
            <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
              <div class="px-4 sm:px-5 py-2.5 bg-slate-50 border-b border-slate-100">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Entry Information</p>
              </div>
              <div class="divide-y divide-slate-100">

                <div class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Name</span>
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold text-white flex-shrink-0"
                      :style="{ background: getAvatarColor(selectedLog.user_name) }">
                      {{ getInitials(selectedLog.user_name) }}
                    </div>
                    <div class="text-right">
                      <p class="text-xs sm:text-sm font-bold text-slate-800 leading-tight">{{ selectedLog.user_name }}</p>
                      <p class="text-[10px] text-slate-400 capitalize">{{ selectedLog.user_role }}</p>
                    </div>
                  </div>
                </div>

                <div class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Action</span>
                  <span :class="actionBadge(selectedLog.action)" class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg border">
                    <span class="w-1.5 h-1.5 rounded-full" :class="actionDot(selectedLog.action)"></span>
                    {{ capitalise(selectedLog.action) }}
                  </span>
                </div>

                <div class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Module</span>
                  <span :class="moduleBadge(selectedLog.module)" class="text-[11px] font-semibold px-2.5 py-1 rounded-full border">{{ moduleLabel(selectedLog.module) }}</span>
                </div>

                <div class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">IP Address</span>
                  <span class="text-xs sm:text-sm font-mono font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">{{ selectedLog.ip_address }}</span>
                </div>

                <div class="px-4 sm:px-5 py-3 flex justify-between items-start gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Agent</span>
                  <span class="text-xs text-slate-600 text-right max-w-[180px] sm:max-w-[220px] leading-relaxed break-words">{{ selectedLog.user_agent || 'Unknown' }}</span>
                </div>

                <div class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Date & Time</span>
                  <div class="text-right">
                    <p class="text-xs sm:text-sm font-semibold text-slate-700">{{ formatDate(selectedLog.created_at) }}</p>
                    <p class="text-[10px] sm:text-xs font-mono text-slate-400">{{ formatTime(selectedLog.created_at) }}</p>
                  </div>
                </div>

                <div v-if="selectedLog.record_id" class="px-4 sm:px-5 py-3 flex justify-between items-center gap-3">
                  <span class="text-xs sm:text-sm text-slate-500 flex-shrink-0">Record ID</span>
                  <span class="text-xs sm:text-sm font-mono font-semibold text-slate-700">#{{ selectedLog.record_id }}</span>
                </div>

              </div>
            </div>

            <!-- Description -->
            <div class="rounded-2xl border border-blue-200/60 overflow-hidden">
              <div class="px-4 sm:px-5 py-2.5 bg-blue-50/60 border-b border-blue-100/80 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-blue-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Description</p>
              </div>
              <div class="px-4 sm:px-5 py-3.5 bg-blue-50/20">
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ selectedLog.description }}</p>
              </div>
            </div>

            <!-- Changes -->
            <div v-if="selectedLog.changes && Object.keys(selectedLog.changes).length > 0"
                 class="rounded-2xl border border-amber-200/60 overflow-hidden">
              <div class="px-4 sm:px-5 py-2.5 bg-amber-50/60 border-b border-amber-100/80 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-amber-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Changes</p>
              </div>
              <div class="px-4 sm:px-5 py-3.5 space-y-3">
                <div v-for="(change, field) in selectedLog.changes" :key="field" class="space-y-1.5">
                  <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ field }}</p>
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded-lg line-through">{{ change.from ?? '—' }}</span>
                    <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-lg font-semibold">{{ change.to ?? '—' }}</span>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="flex-shrink-0 flex justify-end px-4 sm:px-6 py-3.5 sm:py-4 border-t border-slate-100 bg-slate-50/60">
            <button @click="showDetailModal = false"
              class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'

const loading         = ref(false)
const currentPage     = ref(1)
const itemsPerPage    = 15
const showDetailModal = ref(false)
const selectedLog     = ref(null)
const logs            = ref([])
const filters         = reactive({ search: '', action: '', module: '', date_from: '', date_to: '' })

const sampleLogs = [
  { id: 1,  user_name: 'Admin User',    user_role: 'admin',     action: 'login',    module: 'auth',             description: 'Admin User logged into the system.',                               ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T08:00:00', record_id: null, changes: {} },
  { id: 2,  user_name: 'Maria Santos',  user_role: 'resident',  action: 'created',  module: 'complaints',       description: 'Maria Santos filed a new complaint: Noise disturbance in Purok 3.', ip_address: '192.168.1.45', user_agent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Safari/604.1',     created_at: '2025-03-20T08:15:32', record_id: 5,   changes: {} },
  { id: 3,  user_name: 'Admin User',    user_role: 'admin',     action: 'approved', module: 'service_requests', description: 'Approved service request #SR-00023 for Barangay Clearance.',         ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T09:02:11', record_id: 23,  changes: { status: { from: 'pending', to: 'approved' } } },
  { id: 4,  user_name: 'Juan Reyes',    user_role: 'lupon',     action: 'updated',  module: 'complaints',       description: 'Updated complaint #C-00005 status to In Mediation.',                ip_address: '10.0.0.12',    user_agent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/17', created_at: '2025-03-20T09:30:00', record_id: 5,   changes: { status: { from: 'pending', to: 'mediation' } } },
  { id: 5,  user_name: 'Admin User',    user_role: 'admin',     action: 'created',  module: 'announcements',    description: 'Published new announcement: Barangay Fiesta 2025.',                  ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T10:00:00', record_id: 12,  changes: {} },
  { id: 6,  user_name: 'Pedro Cruz',    user_role: 'resident',  action: 'created',  module: 'service_requests', description: 'Pedro Cruz submitted a service request for Certificate of Indigency.', ip_address: '192.168.2.10', user_agent: 'Mozilla/5.0 (Android 14; Mobile) Firefox/123.0',          created_at: '2025-03-20T10:45:00', record_id: 24,  changes: {} },
  { id: 7,  user_name: 'Secretary Ana', user_role: 'secretary', action: 'updated',  module: 'residents',        description: 'Updated resident profile for Juan Dela Cruz.',                       ip_address: '10.0.0.5',     user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/122.0',      created_at: '2025-03-20T11:00:00', record_id: 87,  changes: { address: { from: 'Purok 1', to: 'Purok 3' } } },
  { id: 8,  user_name: 'Admin User',    user_role: 'admin',     action: 'deleted',  module: 'users',            description: 'Removed user account for inactive official.',                       ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T11:30:00', record_id: 15,  changes: {} },
  { id: 9,  user_name: 'Josefa Lim',    user_role: 'resident',  action: 'login',    module: 'auth',             description: 'Josefa Lim logged into the resident portal.',                        ip_address: '192.168.3.22', user_agent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2) Safari/604.1',     created_at: '2025-03-20T12:00:00', record_id: null, changes: {} },
  { id: 10, user_name: 'Admin User',    user_role: 'admin',     action: 'rejected', module: 'service_requests', description: 'Rejected service request #SR-00025 — incomplete documents.',         ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T13:15:00', record_id: 25,  changes: { status: { from: 'pending', to: 'rejected' } } },
  { id: 11, user_name: 'Roberto Gomez', user_role: 'kagawad',   action: 'updated',  module: 'announcements',    description: 'Edited announcement: Road repair advisory.',                         ip_address: '10.0.0.8',     user_agent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X) Chrome/122.0',      created_at: '2025-03-20T14:00:00', record_id: 10,  changes: { title: { from: 'Road repair', to: 'Road repair advisory' } } },
  { id: 12, user_name: 'Maria Santos',  user_role: 'resident',  action: 'logout',   module: 'auth',             description: 'Maria Santos logged out of the system.',                            ip_address: '192.168.1.45', user_agent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Safari/604.1',     created_at: '2025-03-20T14:30:00', record_id: null, changes: {} },
  { id: 13, user_name: 'Secretary Ana', user_role: 'secretary', action: 'created',  module: 'residents',        description: 'Registered new resident: Elena Pascual.',                           ip_address: '10.0.0.5',     user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/122.0',      created_at: '2025-03-20T15:00:00', record_id: 201, changes: {} },
  { id: 14, user_name: 'Juan Reyes',    user_role: 'lupon',     action: 'updated',  module: 'complaints',       description: 'Marked complaint #C-00003 as Resolved.',                            ip_address: '10.0.0.12',    user_agent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/17', created_at: '2025-03-20T15:45:00', record_id: 3,   changes: { status: { from: 'mediation', to: 'resolved' } } },
  { id: 15, user_name: 'Admin User',    user_role: 'admin',     action: 'updated',  module: 'users',            description: "Reset password for Secretary Ana's account.",                       ip_address: '192.168.1.1',  user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0',    created_at: '2025-03-20T16:00:00', record_id: 7,   changes: {} },
]

// ── Icon helpers ──────────────────────────────────────────────
function actionIconBg(a) {
  return { created: 'bg-emerald-50', updated: 'bg-blue-50', deleted: 'bg-red-50', login: 'bg-emerald-50', logout: 'bg-slate-100', approved: 'bg-emerald-50', rejected: 'bg-red-50' }[a] || 'bg-slate-100'
}
function actionIconColor(a) {
  return { created: 'text-emerald-600', updated: 'text-blue-600', deleted: 'text-red-500', login: 'text-emerald-600', logout: 'text-slate-500', approved: 'text-emerald-600', rejected: 'text-red-500' }[a] || 'text-slate-500'
}
function actionSvgPath(a) {
  if (a === 'login')    return '<path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>'
  if (a === 'logout')   return '<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>'
  if (a === 'created')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'
  if (a === 'updated')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
  if (a === 'deleted')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>'
  if (a === 'approved') return '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'
  if (a === 'rejected') return '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
  return '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
}
function actionTitle(action) {
  return { login: 'Login Activity', logout: 'Logout Activity', created: 'Record Created', updated: 'Record Updated', deleted: 'Record Deleted', approved: 'Request Approved', rejected: 'Request Rejected' }[action] || capitalise(action) + ' Activity'
}

// ── Other helpers ─────────────────────────────────────────────
const AVATAR_COLORS = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']
function getAvatarColor(name = '') { let h = 0; for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h); return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length] }
function getInitials(name = '') { const p = name.trim().split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
function capitalise(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }
function formatDate(d) { if (!d) return '—'; return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }
function formatShortDate(d) {
  if (!d) return '—'
  const dt = new Date(d)
  return `${String(dt.getMonth()+1).padStart(2,'0')}-${String(dt.getDate()).padStart(2,'0')}-${dt.getFullYear()}`
}
function formatTime(d) { if (!d) return ''; return new Date(d).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }
function formatRelative(d) {
  if (!d) return ''
  const diff = Date.now() - new Date(d).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1)  return 'Just now'
  if (mins < 60) return `${mins}m ago`
  const hrs = Math.floor(mins / 60)
  if (hrs < 24)  return `${hrs}h ago`
  const days = Math.floor(hrs / 24)
  if (days < 7)  return `${days}d ago`
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}
function shortAgent(ua = '') {
  if (!ua) return 'Unknown'
  if (ua.includes('Edg'))     return 'Edge'
  if (ua.includes('Chrome'))  return 'Chrome'
  if (ua.includes('Firefox')) return 'Firefox'
  if (ua.includes('Safari') && !ua.includes('Chrome')) return 'Safari'
  if (ua.includes('iPhone') || ua.includes('iPad')) return 'iOS'
  if (ua.includes('Android')) return 'Android'
  return ua.slice(0, 16)
}
function actionBadge(a) {
  return { created: 'bg-emerald-50 text-emerald-700 border-emerald-200', updated: 'bg-blue-50 text-blue-700 border-blue-200', deleted: 'bg-red-50 text-red-700 border-red-200', login: 'bg-emerald-50 text-emerald-700 border-emerald-200', logout: 'bg-slate-100 text-slate-600 border-slate-200', approved: 'bg-emerald-50 text-emerald-700 border-emerald-200', rejected: 'bg-red-50 text-red-700 border-red-200' }[a] || 'bg-slate-100 text-slate-600 border-slate-200'
}
function actionDot(a) {
  return { created: 'bg-emerald-500', updated: 'bg-blue-500', deleted: 'bg-red-500', login: 'bg-emerald-500', logout: 'bg-slate-400', approved: 'bg-emerald-500', rejected: 'bg-red-500' }[a] || 'bg-slate-400'
}
function moduleBadge(m) {
  return { residents: 'bg-violet-50 text-violet-700 border-violet-200', announcements: 'bg-sky-50 text-sky-700 border-sky-200', service_requests: 'bg-amber-50 text-amber-700 border-amber-200', complaints: 'bg-red-50 text-red-700 border-red-200', users: 'bg-[#3d4f7c]/10 text-[#3d4f7c] border-[#3d4f7c]/20', auth: 'bg-emerald-50 text-emerald-700 border-emerald-200' }[m] || 'bg-slate-100 text-slate-600 border-slate-200'
}
function moduleLabel(m) {
  return { residents: 'Residents', announcements: 'Announcements', service_requests: 'Service Requests', complaints: 'Complaints', users: 'Users', auth: 'Login/Logout' }[m] || capitalise(m)
}

const hasActiveFilters = computed(() => filters.search || filters.action || filters.module || filters.date_from || filters.date_to)
const filteredLogs = computed(() => {
  let f = [...logs.value]
  if (filters.search) { const s = filters.search.toLowerCase(); f = f.filter(l => l.user_name?.toLowerCase().includes(s) || l.description?.toLowerCase().includes(s) || l.ip_address?.includes(s) || String(l.id).includes(s)) }
  if (filters.action)    f = f.filter(l => l.action === filters.action)
  if (filters.module)    f = f.filter(l => l.module === filters.module)
  if (filters.date_from) f = f.filter(l => new Date(l.created_at) >= new Date(filters.date_from))
  if (filters.date_to)   f = f.filter(l => new Date(l.created_at) <= new Date(filters.date_to + 'T23:59:59'))
  return f
})
const totalPages    = computed(() => Math.max(1, Math.ceil(filteredLogs.value.length / itemsPerPage)))
const paginatedLogs = computed(() => { const s = (currentPage.value - 1) * itemsPerPage; return filteredLogs.value.slice(s, s + itemsPerPage) })

function resetFilters() { filters.search = ''; filters.action = ''; filters.module = ''; filters.date_from = ''; filters.date_to = ''; currentPage.value = 1 }
watch(filters, () => { currentPage.value = 1 }, { deep: true })
function openDetail(log) { selectedLog.value = log; showDetailModal.value = true }

async function fetchLogs() {
  loading.value = true
  try {
    await new Promise(r => setTimeout(r, 500))
    logs.value = sampleLogs
  } catch (err) {
    console.error('Failed to load audit logs:', err)
  } finally {
    loading.value = false
  }
}
onMounted(() => fetchLogs())
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>