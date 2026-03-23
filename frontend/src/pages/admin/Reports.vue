<template>
  <div class="min-h-full bg-[#f5f6fa] p-3 sm:p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

      .log-row { transition: background .12s ease; }
      .log-row:hover { background: #f8fafc; }
      .line-clamp-1 { display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
    </component>

    <!-- ── Page Header ── -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-4 sm:mb-6">
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

    <!-- ── Filters ── -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-4 sm:mb-5 shadow-sm">
      <div class="flex flex-col gap-2.5">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="filters.search" type="text" placeholder="Search by name, action, description…"
            class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all bg-slate-50 focus:bg-white"/>
        </div>
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
        <div class="flex gap-2 sm:hidden">
          <input v-model="filters.date_from" type="date"
            class="flex-1 bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
          <input v-model="filters.date_to" type="date"
            class="flex-1 bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer focus:outline-none transition-all"/>
        </div>
      </div>
    </div>

    <!-- ── Log List ── -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Card header -->
      <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-1.5 h-7 rounded-full bg-white/20"></div>
          <h2 class="text-sm sm:text-lg font-semibold text-white tracking-tight">Activity Log</h2>
        </div>
        <span v-if="!loading" class="text-xs text-white/50 font-medium tabular-nums">
          {{ filteredLogs.length.toLocaleString() }} entr{{ filteredLogs.length !== 1 ? 'ies' : 'y' }}
        </span>
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

      <!-- ── Rows ── -->
      <div v-else class="divide-y divide-slate-100">
        <div v-for="log in paginatedLogs" :key="log.id"
             class="log-row flex items-center gap-3 sm:gap-5 px-4 sm:px-6 py-4 sm:py-5">

          <!-- Icon -->
          <div class="flex-shrink-0 w-10 h-10 sm:w-11 sm:h-11 rounded-2xl flex items-center justify-center shadow-sm flex-shrink-0"
               :style="actionIconStyle(log.action)">
            <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <!-- login -->
              <template v-if="normalizeAction(log.action) === 'login'">
                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
              </template>
              <!-- logout -->
              <template v-else-if="normalizeAction(log.action) === 'logout'">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </template>
              <!-- created -->
              <template v-else-if="normalizeAction(log.action) === 'created'">
                <circle cx="12" cy="12" r="9"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
              </template>
              <!-- updated -->
              <template v-else-if="normalizeAction(log.action) === 'updated'">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </template>
              <!-- deleted -->
              <template v-else-if="normalizeAction(log.action) === 'deleted'">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
              </template>
              <!-- approved -->
              <template v-else-if="normalizeAction(log.action) === 'approved'">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
              </template>
              <!-- rejected -->
              <template v-else-if="normalizeAction(log.action) === 'rejected'">
                <circle cx="12" cy="12" r="9"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
              </template>
              <!-- fallback -->
              <template v-else>
                <circle cx="12" cy="12" r="9"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </template>
            </svg>
          </div>

          <!-- Text -->
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-800 leading-tight">{{ actionTitle(log.action) }}</p>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 line-clamp-1">{{ log.description }}</p>
            <div class="flex items-center gap-x-4 gap-y-1 mt-1.5 flex-wrap">
              <span class="flex items-center gap-1 text-[11px] text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ formatRelative(log.created_at) }}
              </span>
              <span class="flex items-center gap-1 text-[11px] text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                {{ log.ip_address }}
              </span>
              <span class="flex items-center gap-1 text-[11px] text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ shortAgent(log.user_agent) }}
              </span>
              <span class="flex items-center gap-1 text-[11px] text-slate-400">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ formatShortDate(log.created_at) }}
              </span>
            </div>
          </div>

          <!-- Module badge — far right -->
          <span v-if="log.module || log.action"
                :class="moduleBadge(log.module)"
                class="flex-shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap border">
            {{ moduleLabel(log.module) || capitalise(log.action) || 'System' }}
          </span>
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

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import AuditLogsService from '@/services/Admin/AuditLogsService'
import { setAuditLogs, getAuditLogs, hasAuditLogsData } from '@/utils/dataStore'
import Swal from 'sweetalert2'

const loading      = ref(false)
const currentPage  = ref(1)
const itemsPerPage = 15
const logs         = ref([])

const filters = reactive({ search: '', action: '', module: '', date_from: '', date_to: '' })

// ── Helpers ──────────────────────────────────────────────
function normalizeAction(a) {
  if (!a) return ''
  const v = a.toLowerCase().trim()
  // handle common API variants
  if (['login','logged in','log in','sign in','signin'].some(x => v.includes(x))) return 'login'
  if (['logout','logged out','log out','sign out','signout'].some(x => v.includes(x))) return 'logout'
  if (['creat','add','insert','new'].some(x => v.includes(x))) return 'created'
  if (['updat','edit','modif','chang'].some(x => v.includes(x))) return 'updated'
  if (['delet','remov','destroy'].some(x => v.includes(x))) return 'deleted'
  if (['approv','accept'].some(x => v.includes(x))) return 'approved'
  if (['reject','declin','disapprov','denied'].some(x => v.includes(x))) return 'rejected'
  return v
}

function actionIconStyle(a) {
  const g = {
    login:    'linear-gradient(135deg,#10b981,#059669)',
    logout:   'linear-gradient(135deg,#64748b,#475569)',
    created:  'linear-gradient(135deg,#3b82f6,#2563eb)',
    updated:  'linear-gradient(135deg,#f59e0b,#d97706)',
    deleted:  'linear-gradient(135deg,#ef4444,#dc2626)',
    approved: 'linear-gradient(135deg,#10b981,#059669)',
    rejected: 'linear-gradient(135deg,#ef4444,#dc2626)',
  }
  return { background: g[normalizeAction(a)] || 'linear-gradient(135deg,#94a3b8,#64748b)' }
}
function actionSvgPath(a) {
  // login: person walking in door
  if (a === 'login')    return '<path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>'
  // logout: person walking out
  if (a === 'logout')   return '<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>'
  // created: plus circle
  if (a === 'created')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>'
  // updated: pencil
  if (a === 'updated')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>'
  // deleted: trash
  if (a === 'deleted')  return '<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>'
  // approved: badge check
  if (a === 'approved') return '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>'
  // rejected: x circle
  if (a === 'rejected') return '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
  return '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
}
function actionTitle(a) {
  return { login:'Login Activity', logout:'Logout Activity', created:'Record Created',
           updated:'Record Updated', deleted:'Record Deleted', approved:'Request Approved', rejected:'Request Rejected' }[normalizeAction(a)] || capitalise(a) + ' Activity'
}
function moduleBadge(m) {
  return { residents:'bg-violet-50 text-violet-700 border-violet-200',
           announcements:'bg-sky-50 text-sky-700 border-sky-200',
           service_requests:'bg-amber-50 text-amber-700 border-amber-200',
           complaints:'bg-red-50 text-red-700 border-red-200',
           users:'bg-[#3d4f7c]/10 text-[#3d4f7c] border-[#3d4f7c]/20',
           auth:'bg-emerald-50 text-emerald-700 border-emerald-200' }[m] || 'bg-slate-100 text-slate-600 border-slate-200'
}
function moduleLabel(m) {
  return { residents:'Residents', announcements:'Announcements', service_requests:'Service Requests',
           complaints:'Complaints', users:'Users', auth:'Login/Logout' }[m] || capitalise(m)
}
function capitalise(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }
function formatShortDate(d) {
  if (!d) return '—'
  const dt = new Date(d)
  return `${String(dt.getMonth()+1).padStart(2,'0')}-${String(dt.getDate()).padStart(2,'0')}-${dt.getFullYear()}`
}
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
  if (ua.includes('Edg'))    return 'Edge'
  if (ua.includes('Chrome')) return 'Chrome'
  if (ua.includes('Firefox')) return 'Firefox'
  if (ua.includes('Safari') && !ua.includes('Chrome')) return 'Safari'
  if (ua.includes('iPhone') || ua.includes('iPad'))    return 'iOS'
  if (ua.includes('Android')) return 'Android'
  return ua.slice(0, 16)
}

// ── Computed ─────────────────────────────────────────────
const hasActiveFilters = computed(() =>
  filters.search || filters.action || filters.module || filters.date_from || filters.date_to
)
const filteredLogs = computed(() => {
  let f = [...logs.value]
  if (filters.search) {
    const s = filters.search.toLowerCase()
    f = f.filter(l => l.user_name?.toLowerCase().includes(s) || l.description?.toLowerCase().includes(s) || l.ip_address?.includes(s) || String(l.id).includes(s))
  }
  if (filters.action)    f = f.filter(l => l.action === filters.action)
  if (filters.module)    f = f.filter(l => l.module === filters.module)
  if (filters.date_from) f = f.filter(l => new Date(l.created_at) >= new Date(filters.date_from))
  if (filters.date_to)   f = f.filter(l => new Date(l.created_at) <= new Date(filters.date_to + 'T23:59:59'))
  return f
})
const totalPages    = computed(() => Math.max(1, Math.ceil(filteredLogs.value.length / itemsPerPage)))
const paginatedLogs = computed(() => {
  const s = (currentPage.value - 1) * itemsPerPage
  return filteredLogs.value.slice(s, s + itemsPerPage)
})

function resetFilters() {
  filters.search = ''; filters.action = ''; filters.module = ''
  filters.date_from = ''; filters.date_to = ''
  currentPage.value = 1
}

watch(filters, () => { currentPage.value = 1 }, { deep: true })

// ── Fetch ─────────────────────────────────────────────────
async function fetchLogs() {
  if (hasAuditLogsData()) {
    logs.value = getAuditLogs()
    return
  }

  loading.value = true
  try {
    const data = await AuditLogsService.getAll()
    setAuditLogs(data)
    logs.value = data
  } catch (error) {
    console.error('Failed to load audit logs:', error)
    const status = error.response?.status
    Swal.fire({
      icon: status === 401 ? 'warning' : 'error',
      title: status === 401 ? 'Authentication Required' : status === 404 ? 'Not Found' : 'Error',
      text:  status === 401 ? 'Please log in to view audit logs.'
           : status === 404 ? 'Audit logs endpoint not found.'
           : 'Failed to load audit logs. Please try again later.',
      confirmButtonColor: '#3d4f7c',
    })
  } finally {
    loading.value = false
  }
}

onMounted(fetchLogs)
</script>

<style scoped>
.line-clamp-1 { display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
</style>