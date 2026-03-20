<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-6">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="w-1.5 h-10 rounded-full flex-shrink-0" style="background:#3d4f7c"></div>
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15; color:#3d4f7c">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Approved Cases</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">Manage approved Lupon cases — schedule summons, close cases, or issue certificates</p>
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
          <input v-model="filters.search" type="text" placeholder="Search by title, location, or case number…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
            @focus="e => e.target.style.borderColor='#3d4f7c'"
            @blur="e => e.target.style.borderColor=''"
          />
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.type"
            class="flex-1 sm:flex-none appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none transition-all">
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
      <div class="px-4 sm:px-6 py-4" style="background: linear-gradient(to right, #3d4f7c, #252b3b)">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">Approved Cases List</h2>
          </div>
          <span class="text-xs text-white/50">{{ filteredCases.length }} case{{ filteredCases.length !== 1 ? 's' : '' }}</span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading approved cases...</p>
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
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Filed By</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Incident Date</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Filed Date</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-28">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="case_ in paginatedCases" :key="case_.id"
                class="border-b border-slate-50 transition-colors duration-100"
                @mouseover="e => e.currentTarget.style.background='#3d4f7c08'"
                @mouseleave="e => e.currentTarget.style.background=''">

                <td class="px-6 py-4">
                  <span class="text-xs font-mono font-medium px-2 py-1 rounded-lg" style="color:#3d4f7c; background:#3d4f7c10; border:1px solid #3d4f7c20">
                    #C-{{ String(case_.id).padStart(5, '0') }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" :class="typeIconBg(case_.type)">
                      <svg class="w-3 h-3" :class="typeIconColor(case_.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="typeIcon(case_.type)"></svg>
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
                  <p class="text-sm text-slate-600 font-medium">User #{{ case_.user_id }}</p>
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm text-slate-500">{{ formatDate(case_.incident_date) }}</span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm text-slate-500">{{ formatDate(case_.created_at) }}</span>
                </td>

                <td class="px-6 py-4">
                  <button @click="openViewModal(case_)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    style="background:#3d4f7c10; color:#3d4f7c; border-color:#3d4f7c20"
                    @mouseover="e => { e.currentTarget.style.background='#3d4f7c'; e.currentTarget.style.color='white' }"
                    @mouseleave="e => { e.currentTarget.style.background='#3d4f7c10'; e.currentTarget.style.color='#3d4f7c' }"
                    title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </td>
              </tr>

              <tr v-if="filteredCases.length === 0">
                <td colspan="8" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:#3d4f7c10">
                      <svg class="w-7 h-7" style="color:#3d4f7c60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No approved cases found</p>
                    <p class="text-xs text-slate-400">Try adjusting your search or filter criteria</p>
                    <button v-if="hasActiveFilters" @click="resetFilters"
                      class="text-xs font-semibold px-4 py-2 rounded-xl transition-all cursor-pointer"
                      style="color:#3d4f7c; background:#3d4f7c10; border:1px solid #3d4f7c25">Clear Filters</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── Mobile Cards ── -->
        <div class="md:hidden">
          <div v-if="filteredCases.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:#3d4f7c10">
              <svg class="w-7 h-7" style="color:#3d4f7c60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No approved cases found</p>
            <p class="text-xs text-slate-400">Try adjusting your search or filters</p>
            <button v-if="hasActiveFilters" @click="resetFilters"
              class="text-xs font-semibold px-4 py-2 rounded-xl transition-all cursor-pointer"
              style="color:#3d4f7c; background:#3d4f7c10; border:1px solid #3d4f7c25">Clear Filters</button>
          </div>

          <div class="p-3 space-y-3">
            <div v-for="case_ in paginatedCases" :key="case_.id"
              class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
              style="box-shadow:0 2px 8px rgba(0,0,0,0.06);"
              @click="openViewModal(case_)">

              <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-[10px] font-semibold font-mono mb-0.5" style="color:#3d4f7c">#C-{{ String(case_.id).padStart(5, '0') }}</p>
                  <p class="text-sm font-bold text-slate-800 leading-tight truncate">{{ case_.title }}</p>
                  <p class="text-xs text-slate-400 mt-0.5 truncate">{{ case_.location }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0 mt-0.5" style="background:#3d4f7c10; color:#3d4f7c; border:1px solid #3d4f7c25">
                  <span class="w-1.5 h-1.5 rounded-full" style="background:#3d4f7c"></span>
                  Approved
                </span>
              </div>

              <div class="mx-4 h-px bg-slate-100"></div>

              <div class="grid grid-cols-2 gap-0 p-3">
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c10">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Filed By</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5 truncate">User #{{ case_.user_id }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-2 p-2 rounded-xl">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 bg-slate-50">
                    <svg width="11" height="11" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Incident Date</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ formatDate(case_.incident_date) }}</p>
                  </div>
                </div>
              </div>

              <!-- Mobile quick action chips -->
              <div class="px-4 pb-3 flex gap-2 flex-wrap">
                <button @click.stop="handleClosedCase(case_)"
                  :disabled="actionLoading"
                  class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all active:scale-95 disabled:opacity-50">
                  <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Case Closed
                </button>
                <button @click.stop="handleScheduleSummon(case_)"
                  :disabled="actionLoading"
                  class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all active:scale-95 disabled:opacity-50">
                  <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  Summon
                </button>
                <button @click.stop="handleCertificate(case_)"
                  class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all active:scale-95">
                  <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Certificate
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredCases.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono hidden sm:block">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredCases.length) }} / {{ filteredCases.length }}
        </p>
        <p class="text-xs text-slate-400 sm:hidden">Page {{ currentPage }} of {{ totalPages }}</p>
        <div class="flex items-center gap-1.5">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed transition-all cursor-pointer"
            @mouseover="e => { if(!e.currentTarget.disabled){ e.currentTarget.style.borderColor='#3d4f7c'; e.currentTarget.style.color='#3d4f7c' }}"
            @mouseleave="e => { e.currentTarget.style.borderColor=''; e.currentTarget.style.color='' }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer border border-transparent"
              :style="page === currentPage ? 'background:#3d4f7c; color:white' : 'color:#64748b'"
              @mouseover="e => { if(page !== currentPage) e.currentTarget.style.background='#3d4f7c10' }"
              @mouseleave="e => { if(page !== currentPage) e.currentTarget.style.background='' }">
              {{ page }}
            </button>
            <span v-else-if="Math.abs(page - currentPage) === 2" class="w-8 h-8 flex items-end justify-center text-slate-300 text-xs pb-1.5">…</span>
          </template>
          <button :disabled="currentPage === totalPages" @click="currentPage++"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed transition-all cursor-pointer"
            @mouseover="e => { if(!e.currentTarget.disabled){ e.currentTarget.style.borderColor='#3d4f7c'; e.currentTarget.style.color='#3d4f7c' }}"
            @mouseleave="e => { e.currentTarget.style.borderColor=''; e.currentTarget.style.color='' }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
      <div v-else-if="!loading && filteredCases.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ filteredCases.length }}</span> case{{ filteredCases.length !== 1 ? 's' : '' }}</p>
      </div>
    </div>

    <!-- ── View Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex flex-col sm:items-center sm:justify-center sm:p-4">
        <div class="fixed inset-0 bg-black/55 backdrop-blur-md"></div>
        <div class="relative bg-white w-full h-full sm:h-auto sm:rounded-3xl sm:max-w-2xl sm:max-h-[90vh] flex flex-col overflow-hidden"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22), 0 0 0 1px rgba(0,0,0,0.04);">

          <!-- Modal Header -->
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full" style="background:#3d4f7c"></div>
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
            <div class="rounded-xl px-4 py-3 flex items-center justify-between border" style="background:#3d4f7c08; border-color:#3d4f7c20">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold" style="background:#3d4f7c15; color:#3d4f7c; border:1px solid #3d4f7c25">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#3d4f7c"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background:#3d4f7c"></span>
                </span>
                Approved
              </span>
              <span class="text-xs text-slate-400">Filed: {{ formatDate(selectedCase.created_at) }}</span>
            </div>

            <!-- Case Info -->
            <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
              <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:#3d4f7c10">
                  <svg class="w-3 h-3" style="color:#3d4f7c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Filed By</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">User #{{ selectedCase.user_id }}</span>
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

            <!-- ── Case Actions ── -->
            <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
              <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-slate-200 flex items-center justify-center">
                  <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case Actions</p>
              </div>
              <div class="p-4 grid grid-cols-1 sm:grid-cols-3 gap-3">

                <!-- Case Closed -->
                <button @click="handleClosedCase(selectedCase)"
                  :disabled="actionLoading"
                  class="group flex flex-col items-center gap-2.5 px-4 py-4 rounded-xl border-2 bg-white transition-all active:scale-[0.97] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                  style="border-color:#e2e8f0"
                  @mouseover="e => { if(!e.currentTarget.disabled){ e.currentTarget.style.borderColor='#94a3b8'; e.currentTarget.style.background='#f8fafc' }}"
                  @mouseleave="e => { e.currentTarget.style.borderColor='#e2e8f0'; e.currentTarget.style.background='white' }">
                  <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-slate-200">
                    <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-xs font-bold text-slate-700">Case Closed</p>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Mark this case as closed</p>
                  </div>
                </button>

                <!-- Schedule Summon -->
                <button @click="handleScheduleSummon(selectedCase)"
                  :disabled="actionLoading"
                  class="group flex flex-col items-center gap-2.5 px-4 py-4 rounded-xl border-2 bg-white transition-all active:scale-[0.97] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                  style="border-color:#bfdbfe"
                  @mouseover="e => { if(!e.currentTarget.disabled){ e.currentTarget.style.borderColor='#60a5fa'; e.currentTarget.style.background='#eff6ff' }}"
                  @mouseleave="e => { e.currentTarget.style.borderColor='#bfdbfe'; e.currentTarget.style.background='white' }">
                  <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center transition-colors group-hover:bg-blue-100">
                    <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-xs font-bold text-blue-700">Schedule Summon</p>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Set a summon schedule</p>
                  </div>
                </button>

                <!-- Certificate to File Action -->
                <button @click="handleCertificate(selectedCase)"
                  class="group flex flex-col items-center gap-2.5 px-4 py-4 rounded-xl border-2 bg-white transition-all active:scale-[0.97] cursor-pointer"
                  style="border-color:#fde68a"
                  @mouseover="e => { e.currentTarget.style.borderColor='#fbbf24'; e.currentTarget.style.background='#fffbeb' }"
                  @mouseleave="e => { e.currentTarget.style.borderColor='#fde68a'; e.currentTarget.style.background='white' }">
                  <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center transition-colors group-hover:bg-amber-100">
                    <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-xs font-bold text-amber-700">Certificate to File</p>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Issue certificate to file action</p>
                  </div>
                </button>

              </div>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="flex-shrink-0 flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50/60">
            <button @click="showViewModal = false"
              class="flex items-center gap-1.5 px-4 py-2 text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import Swal from 'sweetalert2'
import LuponCasesService from '@/services/Lupon/LuponCasesService.js'

// ── State ──────────────────────────────────────────────────
const loading       = ref(false)
const actionLoading = ref(false)
const currentPage   = ref(1)
const itemsPerPage  = 8
const showViewModal = ref(false)
const selectedCase  = ref(null)
const cases         = ref([])
const filters = reactive({ search: '', type: '' })

// ── Fetch ──────────────────────────────────────────────────
async function fetchApprovedCases() {
  loading.value = true
  try {
    const data = await LuponCasesService.approvedCases()
    cases.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch (err) {
    console.error('Fetch error:', err)
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load approved cases.', confirmButtonColor: '#3d4f7c' })
  } finally {
    loading.value = false
  }
}

// ── Helpers ────────────────────────────────────────────────
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

// ── Filters & Pagination ───────────────────────────────────
const hasActiveFilters = computed(() => filters.search || filters.type)

const filteredCases = computed(() => {
  let f = [...cases.value]
  if (filters.search) {
    const s = filters.search.toLowerCase()
    f = f.filter(c =>
      c.title?.toLowerCase().includes(s) ||
      c.location?.toLowerCase().includes(s) ||
      c.type?.toLowerCase().includes(s) ||
      c.user_id?.toString().includes(s) ||
      String(c.id).includes(s)
    )
  }
  if (filters.type) f = f.filter(c => c.type === filters.type)
  return f
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredCases.value.length / itemsPerPage)))
const paginatedCases = computed(() => {
  const s = (currentPage.value - 1) * itemsPerPage
  return filteredCases.value.slice(s, s + itemsPerPage)
})

function resetFilters() { filters.search = ''; filters.type = ''; currentPage.value = 1 }
watch(filters, () => { currentPage.value = 1 }, { deep: true })
function openViewModal(c) { selectedCase.value = c; showViewModal.value = true }

// ── Sync case in local list after action ───────────────────
function removeCase(id) {
  cases.value = cases.value.filter(c => c.id !== id)
  if (selectedCase.value?.id === id) showViewModal.value = false
}

// ── Case Closed ────────────────────────────────────────────
async function handleClosedCase(case_) {
  const result = await Swal.fire({
    title: 'Close Case?',
    text: `Mark case #C-${String(case_.id).padStart(5, '0')} as closed?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#475569',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, close it',
    cancelButtonText: 'Cancel',
  })

  if (!result.isConfirmed) return

  actionLoading.value = true
  try {
    await LuponCasesService.closeCase(case_.id)
    removeCase(case_.id)
    Swal.fire({
      icon: 'success',
      title: 'Case Closed',
      text: 'The case has been marked as closed.',
      timer: 2000,
      showConfirmButton: false,
    })
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to close case.',
      confirmButtonColor: '#3d4f7c',
    })
  } finally {
    actionLoading.value = false
  }
}

// ── Schedule Summon ────────────────────────────────────────
async function handleScheduleSummon(case_) {
  const result = await Swal.fire({
    title: 'Schedule Summon',
    html: `
      <p class="text-sm text-slate-500 mb-4">Set a summon date and time for case <span class="font-semibold text-slate-700">#C-${String(case_.id).padStart(5, '0')}</span>.</p>
      <input id="swal-summon-date" type="datetime-local"
        class="w-full text-sm text-slate-700 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all"
        style="font-family: inherit;" />
      <textarea id="swal-summon-notes" rows="3" placeholder="Additional notes (optional)…"
        class="w-full mt-3 text-sm text-slate-700 border border-slate-200 rounded-xl px-3 py-2.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all"
        style="font-family: inherit;"></textarea>
    `,
    icon: 'info',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Schedule',
    cancelButtonText: 'Cancel',
    focusConfirm: false,
    preConfirm: () => {
      const date = document.getElementById('swal-summon-date').value
      if (!date) { Swal.showValidationMessage('Please select a date and time.'); return false }
      return { date, notes: document.getElementById('swal-summon-notes').value.trim() }
    },
  })

  if (!result.isConfirmed) return

  actionLoading.value = true
  try {
    await LuponCasesService.scheduleSummon(case_.id, result.value)
    removeCase(case_.id)
    Swal.fire({
      icon: 'success',
      title: 'Summon Scheduled',
      text: `Summon set for ${new Date(result.value.date).toLocaleString()}.`,
      timer: 2500,
      showConfirmButton: false,
    })
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to schedule summon.',
      confirmButtonColor: '#3d4f7c',
    })
  } finally {
    actionLoading.value = false
  }
}

// ── Certificate to File Action (untouched) ─────────────────
function handleCertificate(case_) {
  Swal.fire({
    title: 'Issue Certificate to File Action?',
    text: `Issue a Certificate to File Action for case #C-${String(case_.id).padStart(5, '0')}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d97706',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, issue it',
  }).then(r => {
    if (r.isConfirmed) {
      Swal.fire({ icon: 'success', title: 'Certificate Issued', text: 'Certificate to File Action has been issued.', confirmButtonColor: '#3d4f7c' })
    }
  })
}

onMounted(() => fetchApprovedCases())
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>