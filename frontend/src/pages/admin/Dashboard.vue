<template>
  <div class="min-h-full bg-[#f0f2f8] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- ── Page Header ── -->
    <div class="anim-0 bg-white rounded-2xl shadow-sm border border-slate-100 px-5 sm:px-8 py-5 mb-6 overflow-hidden relative">
      <!-- Decorative gradient blob -->
      <div class="absolute -top-8 -right-8 w-48 h-48 rounded-full opacity-[0.07] pointer-events-none"
           style="background: radial-gradient(circle, #3d4f7c 0%, transparent 70%)"></div>
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative">
        <div class="flex items-center gap-4">
          <div class="w-1.5 h-12 rounded-full bg-[#3d4f7c] flex-shrink-0"></div>
          <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Welcome back — here's what's happening in your barangay.</p>
          </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400 font-medium bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 self-start sm:self-auto">
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          {{ todayDate }}
        </div>
      </div>
    </div>

    <!-- ── Loading ── -->
    <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-32">
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 border-[3px] border-slate-200 rounded-full"></div>
        <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
      </div>
      <p class="text-sm text-slate-400 font-medium">Loading dashboard…</p>
    </div>

    <div v-else>

      <!-- ── KPI Cards ── -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">

        <div v-for="(kpi, i) in kpiCards" :key="kpi.label"
             :class="`a${i + 2} bg-white rounded-2xl border border-slate-200/60 p-5 sm:p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden cursor-default`">
          <!-- bottom accent -->
          <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl" :style="{ background: kpi.accent }"></div>

          <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm"
                 :style="{ background: kpi.accent }">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" v-html="kpi.icon"></svg>
            </div>
            <!-- pulse dot for items needing attention -->
            <div v-if="kpi.alert && kpi.value > 0" class="relative w-3 h-3 mt-1">
              <span class="w-3 h-3 rounded-full inline-block pulse-dot relative" :style="{ color: kpi.accent, background: kpi.accent }"></span>
            </div>
          </div>

          <p class="text-3xl sm:text-4xl font-extrabold text-slate-800 tabular-nums leading-none">{{ kpi.value }}</p>
          <p class="text-sm font-semibold text-slate-500 mt-2">{{ kpi.label }}</p>
          <span class="mt-3 inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full border"
                :style="{ color: kpi.accent, background: kpi.accent + '18', borderColor: kpi.accent + '35' }">
            {{ kpi.badge }}
          </span>
        </div>

      </div>

      <!-- ── Breakdown Charts ── -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        <!-- Lupon Cases -->
        <div class="a6 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#3d4f7c">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-bold text-slate-800">Lupon Cases</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Status breakdown</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-2xl font-extrabold text-slate-800 tabular-nums leading-none">{{ stats.totalCases }}</p>
              <p class="text-[11px] text-slate-400 mt-0.5 font-medium">total</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-3.5">
            <div v-for="item in caseBreakdown" :key="item.label">
              <div class="flex items-center justify-between mb-1.5">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full" :style="{ background: item.color }"></span>
                  <span class="text-xs font-semibold text-slate-600">{{ item.label }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[11px] text-slate-400 font-medium">{{ barPct(item.value, stats.totalCases) }}%</span>
                  <span class="text-xs font-bold text-slate-700 tabular-nums w-5 text-right">{{ item.value }}</span>
                </div>
              </div>
              <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bar" :style="{ width: barPct(item.value, stats.totalCases) + '%', background: item.color }"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Service Requests -->
        <div class="a7 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-500">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-bold text-slate-800">Service Requests</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Status breakdown</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-2xl font-extrabold text-slate-800 tabular-nums leading-none">{{ stats.totalRequests }}</p>
              <p class="text-[11px] text-slate-400 mt-0.5 font-medium">total</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-3.5">
            <div v-for="item in requestBreakdown" :key="item.label">
              <div class="flex items-center justify-between mb-1.5">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full" :style="{ background: item.color }"></span>
                  <span class="text-xs font-semibold text-slate-600">{{ item.label }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[11px] text-slate-400 font-medium">{{ barPct(item.value, stats.totalRequests) }}%</span>
                  <span class="text-xs font-bold text-slate-700 tabular-nums w-5 text-right">{{ item.value }}</span>
                </div>
              </div>
              <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bar" :style="{ width: barPct(item.value, stats.totalRequests) + '%', background: item.color }"></div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- ── Recent Pending Cases ── -->
      <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-400 flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-800">Recent Pending Cases</p>
              <p class="text-[11px] text-slate-400 mt-0.5">Latest cases awaiting review</p>
            </div>
          </div>
          <span v-if="recentCases.length > 0"
                class="text-sm font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl tabular-nums">
            {{ stats.pendingCases }} pending
          </span>
        </div>

        <!-- Empty -->
        <div v-if="recentCases.length === 0" class="flex flex-col items-center justify-center py-14 gap-3">
          <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <p class="text-sm font-bold text-slate-600">No pending cases</p>
          <p class="text-xs text-slate-400">All cases have been reviewed.</p>
        </div>

        <!-- List -->
        <div v-else class="divide-y divide-slate-100">
          <div v-for="c in recentCases" :key="c.id"
               class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" :class="typeIconBg(c.type)">
              <svg class="w-4 h-4" :class="typeIconColor(c.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="typeIcon(c.type)"></svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-bold text-slate-800 truncate">{{ c.title }}</p>
              <p class="text-xs text-slate-400 mt-0.5 truncate">{{ c.location }} · Filed {{ formatDate(c.created_at) }}</p>
            </div>
            <div class="flex items-center gap-2.5 flex-shrink-0">
              <span class="text-xs font-mono text-slate-400 hidden sm:inline">#C-{{ String(c.id).padStart(5,'0') }}</span>
              <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import LuponCasesService     from '@/services/Lupon/LuponCasesService.js'
import ServiceRequestService from '@/services/Admin/ServiceRequestService'
import ResidentService       from '@/services/Admin/ResidentService'
import UserService           from '@/services/Admin/UserService'
import {
  getResidents,    hasResidentsData,    setResidents,
  getUsers,        hasData,             setUsers,
  getServiceRequests, hasServiceRequestsData, setServiceRequests,
  getLuponCases,   hasLuponCasesData,   setLuponCases, 
} from '@/utils/dataStore'

const loading     = ref(false)
const recentCases = ref([])

const stats = ref({
  totalResidents: 0, totalOfficials: 0,
  totalCases: 0, pendingCases: 0, approvedCases: 0,
  scheduledCases: 0, disapprovedCases: 0, closedCases: 0,
  totalRequests: 0, pendingRequests: 0, approvedRequests: 0,
  disapprovedRequests: 0, releasedRequests: 0,
})

const todayDate = computed(() =>
  new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
)

// ── KPI cards config — driven by stats ───────────────────────
const kpiCards = computed(() => [
  {
    label:  'Total Residents',
    value:  stats.value.totalResidents,
    accent: '#3d4f7c',
    badge:  'Active',
    alert:  false,
    icon:   '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
  },
  {
    label:  'Barangay Officials',
    value:  stats.value.totalOfficials,
    accent: '#7c3aed',
    badge:  'S.Y. 2026',
    alert:  false,
    icon:   '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
  },
  {
    label:  'Pending Cases',
    value:  stats.value.pendingCases,
    accent: '#f59e0b',
    badge:  'Needs Review',
    alert:  true,
    icon:   '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
  },
  {
    label:  'Pending Requests',
    value:  stats.value.pendingRequests,
    accent: '#10b981',
    badge:  'Awaiting Action',
    alert:  true,
    icon:   '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
  },
])

const caseBreakdown = computed(() => [
  { label: 'Pending',     value: stats.value.pendingCases,     color: '#f59e0b' },
  { label: 'Approved',    value: stats.value.approvedCases,    color: '#10b981' },
  { label: 'Scheduled',   value: stats.value.scheduledCases,   color: '#8b5cf6' },
  { label: 'Disapproved', value: stats.value.disapprovedCases, color: '#ef4444' },
  { label: 'Closed',      value: stats.value.closedCases,      color: '#94a3b8' },
])

const requestBreakdown = computed(() => [
  { label: 'Pending',     value: stats.value.pendingRequests,     color: '#f59e0b' },
  { label: 'Approved',    value: stats.value.approvedRequests,    color: '#10b981' },
  { label: 'Disapproved', value: stats.value.disapprovedRequests, color: '#ef4444' },
  { label: 'Released',    value: stats.value.releasedRequests,    color: '#3d4f7c' },
])

function barPct(val, total) { if (!total) return 0; return Math.round((val / total) * 100) }

function typeIconBg(t) { return { incident: 'bg-red-100', dispute: 'bg-amber-100', report: 'bg-blue-100', other: 'bg-purple-100' }[t] || 'bg-slate-100' }
function typeIconColor(t) { return { incident: 'text-red-500', dispute: 'text-amber-500', report: 'text-blue-500', other: 'text-purple-500' }[t] || 'text-slate-400' }
function typeIcon(t) {
  if (t === 'incident') return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
  if (t === 'dispute')  return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>'
  return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
}
function formatDate(d) {
  if (!d) return '—'
  try { return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return d }
}

async function fetchAll() {
  loading.value = true
  try {
    const [res, off, cas, req] = await Promise.allSettled([
      hasResidentsData()
        ? Promise.resolve(getResidents())
        : ResidentService.getResidents().then(d => { setResidents(d); return d }),

      hasData()
        ? Promise.resolve(getUsers())
        : UserService.getUsers().then(d => { setUsers(d); return d }),

      hasLuponCasesData()
        ? Promise.resolve(getLuponCases())
        : LuponCasesService.allCases().then(d => { setLuponCases(d); return d }),

      hasServiceRequestsData()
        ? Promise.resolve(getServiceRequests())
        : ServiceRequestService.getAll().then(d => { setServiceRequests(d); return d }),
    ])

    if (res.status === 'fulfilled') {
      const a = Array.isArray(res.value) ? res.value : (res.value?.data ?? [])
      stats.value.totalResidents = a.length
    }
    if (off.status === 'fulfilled') {
      const a = Array.isArray(off.value) ? off.value : (off.value?.data ?? [])
      stats.value.totalOfficials = a.length
    }
    if (cas.status === 'fulfilled') {
      const a = Array.isArray(cas.value) ? cas.value : (cas.value?.data ?? [])
      stats.value.totalCases       = a.length
      stats.value.pendingCases     = a.filter(c => c.status === 'pending').length
      stats.value.approvedCases    = a.filter(c => c.status === 'approved').length
      stats.value.scheduledCases   = a.filter(c => c.status === 'scheduled').length
      stats.value.disapprovedCases = a.filter(c => c.status === 'disapproved').length
      stats.value.closedCases      = a.filter(c => c.status === 'closed').length
      recentCases.value = a
        .filter(c => c.status === 'pending')
        .sort((x, y) => new Date(y.created_at) - new Date(x.created_at))
        .slice(0, 5)
    }
    if (req.status === 'fulfilled') {
      const a = Array.isArray(req.value) ? req.value : (req.value?.data ?? [])
      stats.value.totalRequests       = a.length
      stats.value.pendingRequests     = a.filter(r => r.status === 'pending').length
      stats.value.approvedRequests    = a.filter(r => r.status === 'approved').length
      stats.value.disapprovedRequests = a.filter(r => ['disapproved', 'rejected'].includes(r.status)).length
      stats.value.releasedRequests    = a.filter(r => ['released', 'completed'].includes(r.status)).length
    }
  } catch (err) {
    console.error('Dashboard fetch error:', err)
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load dashboard data.', confirmButtonColor: '#3d4f7c' })
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchAll())
</script>