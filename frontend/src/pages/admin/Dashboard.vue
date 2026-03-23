<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

      @keyframes fade-up {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      @keyframes count-up {
        from { opacity: 0; }
        to   { opacity: 1; }
      }
      @keyframes pulse-ring {
        0%   { transform: scale(1);   opacity: .5; }
        100% { transform: scale(1.9); opacity: 0; }
      }
      .anim-0  { animation: fade-up .45s ease both; }
      .anim-1  { animation: fade-up .45s .06s ease both; }
      .anim-2  { animation: fade-up .45s .12s ease both; }
      .anim-3  { animation: fade-up .45s .18s ease both; }
      .anim-4  { animation: fade-up .45s .24s ease both; }
      .anim-5  { animation: fade-up .45s .30s ease both; }
      .anim-6  { animation: fade-up .45s .36s ease both; }
      .anim-7  { animation: fade-up .45s .42s ease both; }
      .anim-8  { animation: fade-up .45s .48s ease both; }
      .pulse-ring::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 9999px;
        background: currentColor;
        animation: pulse-ring 1.4s ease-out infinite;
      }
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
    <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-28">
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
        <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
      </div>
      <p class="text-sm text-slate-400 font-medium">Loading dashboard data…</p>
    </div>

    <div v-else>

      <!-- ── KPI Cards ── -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">

        <!-- Residents -->
        <div class="anim-1 bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
               style="background: linear-gradient(135deg, #3d4f7c08 0%, transparent 60%)"></div>
          <div class="flex items-start justify-between gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#3d4f7c12">
              <svg class="w-5 h-5" style="color:#3d4f7c" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full hidden sm:inline">Active</span>
          </div>
          <div class="mt-3">
            <p class="text-2xl sm:text-3xl font-bold text-slate-800 tabular-nums">{{ stats.totalResidents }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Total Residents</p>
          </div>
        </div>

        <!-- Officials -->
        <div class="anim-2 bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
               style="background: linear-gradient(135deg, #7c3aed08 0%, transparent 60%)"></div>
          <div class="flex items-start justify-between gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-violet-50">
              <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <span class="text-[10px] font-bold text-violet-600 bg-violet-50 border border-violet-100 px-2 py-0.5 rounded-full hidden sm:inline">S.Y. 2026</span>
          </div>
          <div class="mt-3">
            <p class="text-2xl sm:text-3xl font-bold text-slate-800 tabular-nums">{{ stats.totalOfficials }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Barangay Officials</p>
          </div>
        </div>

        <!-- Pending Cases -->
        <div class="anim-3 bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
               style="background: linear-gradient(135deg, #d9770608 0%, transparent 60%)"></div>
          <div class="flex items-start justify-between gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50">
              <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div v-if="stats.pendingCases > 0" class="relative flex-shrink-0">
              <span class="w-2 h-2 rounded-full bg-amber-500 pulse-ring relative inline-block"></span>
            </div>
          </div>
          <div class="mt-3">
            <p class="text-2xl sm:text-3xl font-bold text-slate-800 tabular-nums">{{ stats.pendingCases }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Pending Cases</p>
          </div>
        </div>

        <!-- Pending Service Requests -->
        <div class="anim-4 bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
               style="background: linear-gradient(135deg, #05966908 0%, transparent 60%)"></div>
          <div class="flex items-start justify-between gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-50">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div v-if="stats.pendingRequests > 0" class="relative flex-shrink-0">
              <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-ring relative inline-block"></span>
            </div>
          </div>
          <div class="mt-3">
            <p class="text-2xl sm:text-3xl font-bold text-slate-800 tabular-nums">{{ stats.pendingRequests }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Pending Requests</p>
          </div>
        </div>

      </div>

      <!-- ── Middle Row: Lupon Overview + Service Requests ── -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        <!-- Lupon Cases Breakdown -->
        <div class="anim-5 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-[#3d4f7c]/10">
                <svg class="w-4 h-4" style="color:#3d4f7c" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <p class="text-sm font-bold text-slate-700">Lupon Cases Overview</p>
            </div>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-lg tabular-nums">
              {{ stats.totalCases }} total
            </span>
          </div>
          <div class="p-5 space-y-3">

            <template v-for="item in caseBreakdown" :key="item.label">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full flex-shrink-0" :style="{ background: item.color }"></div>
                <p class="text-xs font-semibold text-slate-600 w-28 flex-shrink-0">{{ item.label }}</p>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full transition-all duration-700"
                       :style="{ width: barWidth(item.value, stats.totalCases) + '%', background: item.color }"></div>
                </div>
                <span class="text-xs font-bold tabular-nums text-slate-700 w-6 text-right">{{ item.value }}</span>
              </div>
            </template>

            <!-- Donut-style total indicator -->
            <div class="pt-2 mt-2 border-t border-slate-100 flex items-center gap-2">
              <div class="flex -space-x-1">
                <span v-for="item in caseBreakdown.filter(i => i.value > 0)" :key="item.label"
                  class="w-3 h-3 rounded-full border-2 border-white"
                  :style="{ background: item.color }"></span>
              </div>
              <p class="text-[11px] text-slate-400">
                <span class="font-semibold text-slate-600">{{ stats.pendingCases }}</span> need attention
              </p>
            </div>
          </div>
        </div>

        <!-- Service Requests Breakdown -->
        <div class="anim-6 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-50">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <p class="text-sm font-bold text-slate-700">Service Requests</p>
            </div>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-lg tabular-nums">
              {{ stats.totalRequests }} total
            </span>
          </div>
          <div class="p-5 space-y-3">

            <template v-for="item in requestBreakdown" :key="item.label">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full flex-shrink-0" :style="{ background: item.color }"></div>
                <p class="text-xs font-semibold text-slate-600 w-28 flex-shrink-0">{{ item.label }}</p>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full transition-all duration-700"
                       :style="{ width: barWidth(item.value, stats.totalRequests) + '%', background: item.color }"></div>
                </div>
                <span class="text-xs font-bold tabular-nums text-slate-700 w-6 text-right">{{ item.value }}</span>
              </div>
            </template>

            <div class="pt-2 mt-2 border-t border-slate-100 flex items-center gap-2">
              <div class="flex -space-x-1">
                <span v-for="item in requestBreakdown.filter(i => i.value > 0)" :key="item.label"
                  class="w-3 h-3 rounded-full border-2 border-white"
                  :style="{ background: item.color }"></span>
              </div>
              <p class="text-[11px] text-slate-400">
                <span class="font-semibold text-slate-600">{{ stats.pendingRequests }}</span> awaiting action
              </p>
            </div>
          </div>
        </div>

      </div>

      <!-- ── Bottom Row: Recent Activity + Quick Links ── -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- Recent Lupon Cases -->
        <div class="anim-7 lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-amber-50">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <p class="text-sm font-bold text-slate-700">Recent Pending Cases</p>
            </div>
          </div>

          <div v-if="recentCases.length === 0" class="flex flex-col items-center justify-center py-10 gap-2">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-slate-400 font-medium">No pending cases — all clear!</p>
          </div>

          <div v-else class="divide-y divide-slate-50">
            <div v-for="c in recentCases" :key="c.id"
                 class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/60 transition-colors">
              <!-- Type icon -->
              <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                   :class="typeIconBg(c.type)">
                <svg class="w-3.5 h-3.5" :class="typeIconColor(c.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="typeIcon(c.type)"></svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ c.title }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ c.location }} · {{ formatDate(c.created_at) }}</p>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0">
                <span class="text-[10px] font-mono text-slate-400">#C-{{ String(c.id).padStart(5,'0') }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links / Summary -->
        <div class="anim-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-[#3d4f7c]/10">
              <svg class="w-4 h-4" style="color:#3d4f7c" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-700">Quick Summary</p>
          </div>

          <div class="p-4 flex-1 flex flex-col gap-2.5">

            <!-- Cases needing action -->
            <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-4 py-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Cases for Review</p>
                <p class="text-lg font-bold text-amber-700 tabular-nums">{{ stats.pendingCases }}</p>
              </div>
            </div>

            <!-- Approved / Scheduled -->
            <div class="rounded-xl border border-violet-100 bg-violet-50/60 px-4 py-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Approved + Scheduled</p>
                <p class="text-lg font-bold text-violet-700 tabular-nums">{{ stats.approvedCases + stats.scheduledCases }}</p>
              </div>
            </div>

            <!-- Closed cases -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Closed Cases</p>
                <p class="text-lg font-bold text-slate-700 tabular-nums">{{ stats.closedCases }}</p>
              </div>
            </div>

            <!-- Released requests -->
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Docs Released</p>
                <p class="text-lg font-bold text-emerald-700 tabular-nums">{{ stats.releasedRequests }}</p>
              </div>
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

import {
  getResidents, hasResidentsData, setResidents,
  getUsers,     hasData,          setUsers,
  getServiceRequests, hasServiceRequestsData, setServiceRequests,
  getLuponCases, hasLuponCasesData, setLuponCases,   
} from '@/utils/dataStore'

// ── State ────────────────────────────────────────────────────
const loading = ref(false)

const stats = ref({
  totalResidents:   0,
  totalOfficials:   0,
  totalCases:       0,
  pendingCases:     0,
  approvedCases:    0,
  scheduledCases:   0,
  disapprovedCases: 0,
  closedCases:      0,
  totalRequests:    0,
  pendingRequests:  0,
  approvedRequests: 0,
  disapprovedRequests: 0,
  releasedRequests: 0,
})

const recentCases = ref([])

// ── Computed ─────────────────────────────────────────────────
const todayDate = computed(() =>
  new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
)

const caseBreakdown = computed(() => [
  { label: 'Pending',      value: stats.value.pendingCases,     color: '#f59e0b' },
  { label: 'Approved',     value: stats.value.approvedCases,    color: '#10b981' },
  { label: 'Scheduled',    value: stats.value.scheduledCases,   color: '#8b5cf6' },
  { label: 'Disapproved',  value: stats.value.disapprovedCases, color: '#ef4444' },
  { label: 'Closed',       value: stats.value.closedCases,      color: '#94a3b8' },
])

const requestBreakdown = computed(() => [
  { label: 'Pending',      value: stats.value.pendingRequests,     color: '#f59e0b' },
  { label: 'Approved',     value: stats.value.approvedRequests,    color: '#10b981' },
  { label: 'Disapproved',  value: stats.value.disapprovedRequests, color: '#ef4444' },
  { label: 'Released',     value: stats.value.releasedRequests,    color: '#3d4f7c' },
])

// ── Helpers ──────────────────────────────────────────────────
function barWidth(val, total) {
  if (!total) return 0
  return Math.round((val / total) * 100)
}

function typeLabel(t) {
  return { incident: 'Incident', dispute: 'Dispute', report: 'Report', other: 'Other' }[t] || t
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
  try { return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return d }
}

async function fetchAll() {
  loading.value = true
  try {
    // ── Residents ──────────────────────────────────────────
    const residentsArr = Array.isArray(getResidents()) ? getResidents() : (getResidents()?.data ?? [])
    stats.value.totalResidents = residentsArr.length

    // ── Officials (Users) ──────────────────────────────────
    const usersArr = Array.isArray(getUsers()) ? getUsers() : (getUsers()?.data ?? [])
    stats.value.totalOfficials = usersArr.length

    // ── Lupon Cases ────────────────────────────────────────
    const casesArr = Array.isArray(getLuponCases()) ? getLuponCases() : (getLuponCases()?.data ?? [])
    stats.value.totalCases       = casesArr.length
    stats.value.pendingCases     = casesArr.filter(c => c.status === 'pending').length
    stats.value.approvedCases    = casesArr.filter(c => c.status === 'approved').length
    stats.value.scheduledCases   = casesArr.filter(c => c.status === 'scheduled').length
    stats.value.disapprovedCases = casesArr.filter(c => c.status === 'disapproved').length
    stats.value.closedCases      = casesArr.filter(c => c.status === 'closed').length
    recentCases.value = casesArr
      .filter(c => c.status === 'pending')
      .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      .slice(0, 5)

    // ── Service Requests ───────────────────────────────────
    const requestsArr = Array.isArray(getServiceRequests()) ? getServiceRequests() : (getServiceRequests()?.data ?? [])
    stats.value.totalRequests       = requestsArr.length
    stats.value.pendingRequests     = requestsArr.filter(r => r.status === 'pending').length
    stats.value.approvedRequests    = requestsArr.filter(r => r.status === 'approved').length
    stats.value.disapprovedRequests = requestsArr.filter(r => ['disapproved','rejected'].includes(r.status)).length
    stats.value.releasedRequests    = requestsArr.filter(r => ['released','completed'].includes(r.status)).length

  } catch (err) {
    console.error('Dashboard fetch error:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchAll())
</script>