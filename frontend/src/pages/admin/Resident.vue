<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 sm:p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">
    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-4 sm:mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-9 rounded-full bg-[#3d4f7c] flex-shrink-0"></div>
          <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c] flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-800 tracking-tight">Resident Management</h1>
            <p class="text-xs text-slate-500 mt-0.5 hidden sm:block">Manage all residents and their information.</p>
          </div>
        </div>
        <button
          class="inline-flex items-center justify-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all duration-150 border-0 cursor-pointer"
          style="background-color: #3d4f7c"
          @mouseenter="e => e.currentTarget.style.backgroundColor = '#252b3b'"
          @mouseleave="e => e.currentTarget.style.backgroundColor = '#3d4f7c'"
          @click="showAddModal = true"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          Add Resident
        </button>
      </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl border border-slate-100 p-3 sm:p-4 mb-4 sm:mb-5 shadow-sm">
      <div class="flex items-center gap-2">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search residents..."
            class="w-full pl-10 pr-16 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
          <span v-if="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredResidents.length }} found
          </span>
        </div>
        <button v-if="hasActiveFilters" @click="resetFilters"
          class="w-9 h-9 flex items-center justify-center text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl transition-all cursor-pointer flex-shrink-0">
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Gradient Header -->
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-base sm:text-lg font-semibold text-white tracking-tight">Residents List</h2>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-20">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading residents...</p>
      </div>

      <div v-else>

        <!-- ── DESKTOP TABLE (FIXED) ── -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full" style="table-layout: fixed;">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/60">
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-[20%]">Resident</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-[20%]">Email</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-[15%]">Contact</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-[30%]">Address</th>
                <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-[15%]">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="resident in paginatedResidents" :key="resident.id"
                class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">
                
                <!-- Resident -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white shadow-sm flex-shrink-0"
                      :style="{ background: avatarBg(fullName(resident)) }">
                      {{ initials(fullName(resident)) }}
                    </div>
                    <div class="min-w-0">
                      <p class="text-sm font-semibold text-slate-800 truncate" :title="fullName(resident)">{{ fullName(resident) }}</p>
                      <p v-if="resident.information?.suffix" class="text-xs text-slate-400 truncate" :title="resident.information.suffix">{{ resident.information.suffix }}</p>
                    </div>
                  </div>
                </td>

                <!-- Email -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span v-if="resident.email" class="text-sm text-slate-600 truncate block max-w-[140px]" :title="resident.email">{{ resident.email }}</span>
                    <span v-else class="text-slate-300 text-sm">—</span>
                    <button v-if="resident.email" @click="copyToClipboard(resident.email)"
                      class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-[#3d4f7c] transition-all cursor-pointer flex-shrink-0">
                      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                      </svg>
                    </button>
                  </div>
                </td>

                <!-- Contact -->
                <td class="px-6 py-4">
                  <span v-if="resident.information?.contact_number" class="text-sm text-slate-600 flex items-center gap-1.5" :title="resident.information.contact_number">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="truncate block max-w-[120px]">{{ formatPhoneNumber(resident.information.contact_number) }}</span>
                  </span>
                  <span v-else class="text-slate-300 text-sm">—</span>
                </td>

                <!-- Address (FIXED: Now with proper wrapping) -->
                <td class="px-6 py-4">
                  <div v-if="resident.address" class="text-sm text-slate-600 flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="break-words line-clamp-2" :title="fullAddress(resident)">{{ fullAddress(resident) }}</span>
                  </div>
                  <span v-else class="text-slate-300 text-sm">—</span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1">
                    <button @click="openViewModal(resident)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-500 hover:text-white active:scale-95 transition-all duration-150 cursor-pointer"
                      title="View">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>
                    <button @click="openEditModal(resident)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white active:scale-95 transition-all duration-150 cursor-pointer">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <button @click="handleDelete(resident)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white active:scale-95 transition-all duration-150 cursor-pointer">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredResidents.length === 0">
                <td colspan="5" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No residents found</p>
                    <p class="text-xs text-slate-400">Try adjusting your search</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── MOBILE CARD LIST ── -->
        <div class="md:hidden">

          <!-- Empty state -->
          <div v-if="filteredResidents.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
              <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">No residents found</p>
            <p class="text-xs text-slate-400">Try adjusting your search</p>
          </div>

          <!-- Cards -->
          <div class="p-3 space-y-3">
            <div v-for="resident in paginatedResidents" :key="resident.id"
                 class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                 style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);">

              <!-- Card Header: colored strip + avatar + name + actions -->
              <div class="flex items-center gap-3 px-4 pt-4 pb-3">
                <!-- Avatar -->
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                     :style="{ background: avatarBg(fullName(resident)) }">
                  {{ initials(fullName(resident)) }}
                </div>

                <!-- Name -->
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-bold text-slate-800 truncate">{{ fullName(resident) }}</p>
                  <div class="flex items-center gap-1.5 mt-0.5">
                    <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-[11px] text-slate-400 truncate">{{ resident.email || '—' }}</p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1.5 flex-shrink-0">
                  <button @click="openViewModal(resident)"
                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-sky-50 text-sky-600 border border-sky-100 hover:bg-sky-500 hover:text-white active:scale-95 transition-all cursor-pointer"
                    title="View">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                  <button @click="openEditModal(resident)"
                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#3d4f7c]/8 text-[#3d4f7c] border border-[#3d4f7c]/15 hover:bg-[#3d4f7c] hover:text-white active:scale-95 transition-all cursor-pointer"
                    style="background: rgba(61,79,124,0.08);">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button @click="handleDelete(resident)"
                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-500 border border-red-100 hover:bg-red-500 hover:text-white active:scale-95 transition-all cursor-pointer">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Divider -->
              <div class="mx-4 h-px bg-slate-100"></div>

              <!-- Info grid -->
              <div class="grid grid-cols-2 gap-0 p-3">

                <!-- Contact -->
                <div class="flex items-start gap-2 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#3d4f7c12">
                    <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Contact</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">
                      {{ resident.information?.contact_number ? formatPhoneNumber(resident.information.contact_number) : '—' }}
                    </p>
                  </div>
                </div>

                <!-- House No. -->
                <div class="flex items-start gap-2 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#05966912">
                    <svg width="11" height="11" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">House No.</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ resident.address?.house_no || '—' }}</p>
                  </div>
                </div>

                <!-- Purok -->
                <div class="flex items-start gap-2 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#d9770612">
                    <svg width="11" height="11" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Purok</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ resident.address?.purok || '—' }}</p>
                  </div>
                </div>

                <!-- Sitio -->
                <div class="flex items-start gap-2 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#7c3aed12">
                    <svg width="11" height="11" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Sitio</p>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ resident.address?.sitio || '—' }}</p>
                  </div>
                </div>

              </div>

              <!-- Full address strip -->
              <div v-if="fullAddress(resident) !== '—'" class="mx-3 mb-3 px-3 py-2 bg-slate-50 rounded-xl flex items-center gap-2 border border-slate-100">
                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-[11px] text-slate-500 truncate">{{ fullAddress(resident) }}</p>
              </div>

            </div>
          </div>

        </div>

      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredResidents.length > itemsPerPage"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredResidents.length) }}
          / {{ filteredResidents.length }}
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
              :class="page === currentPage ? 'text-white' : 'text-slate-500 hover:bg-[#3d4f7c]/5 hover:text-[#3d4f7c] border border-transparent'"
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
      <div v-else-if="!loading && filteredResidents.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400">
          Showing <span class="font-medium text-slate-700">{{ filteredResidents.length }}</span> result{{ filteredResidents.length !== 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- Modals -->
    <AddResidentModal v-if="showAddModal" :roles="roles" @close="showAddModal = false" @saved="handleResidentAdded"/>
    <EditResidentModal v-if="showEditModal && selectedResident" :resident="selectedResident" :roles="roles" @close="showEditModal = false" @saved="handleResidentUpdated"/>
    <ViewResidentModal
      v-if="showViewModal && selectedResident"
      :resident="selectedResident"
      @close="showViewModal = false; selectedResident = null"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import ResidentService from "@/services/Admin/ResidentService";
import AddResidentModal from "@/components/modals/admin/residents/AddResidentModal.vue";
import EditResidentModal from "@/components/modals/admin/residents/EditResidentModal.vue";
import ViewResidentModal from "@/components/modals/admin/residents/ViewResidentModal.vue";
import Swal from "sweetalert2";
import { getResidents, getRoles, hasResidentsData, hasData, setResidents, setRoles } from "@/utils/dataStore";

const residents        = ref([]);
const roles            = ref([]);
const loading          = ref(false);
const showAddModal     = ref(false);
const showEditModal    = ref(false);
const showViewModal = ref(false);
const selectedResident = ref(null);
const searchQuery      = ref("");
const purokFilter      = ref("all");
const currentPage      = ref(1);
const itemsPerPage     = 6;

function fullName(resident) {
  const info = resident.information;
  if (!info) return "—";
  return [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(" ");
}

function fullAddress(resident) {
  const addr = resident.address;
  if (!addr) return "—";
  const parts = [];
  if (addr.house_no) parts.push(`#${addr.house_no}`);
  if (addr.purok)    parts.push(`Purok ${addr.purok}`);
  if (addr.sitio)    parts.push(`Sitio ${addr.sitio}`);
  if (addr.barangay) parts.push(addr.barangay);
  if (addr.city)     parts.push(addr.city);
  if (addr.province) parts.push(addr.province);
  return parts.join(", ") || "—";
}

function formatPhoneNumber(phone) {
  if (!phone) return "—";
  const cleaned = phone.replace(/\D/g, "");
  if (cleaned.length === 11 && cleaned.startsWith("0"))
    return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, "$1 $2 $3");
  return phone;
}

function initials(name) {
  if (!name || name === "—") return "?";
  const parts = name.trim().split(" ");
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || "?";
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

const PALETTE = ["#2563eb","#7c3aed","#059669","#d97706","#dc2626","#0891b2","#9333ea","#ea580c"];
function avatarBg(name) {
  if (!name || name === "—") return "#94a3b8";
  let h = 0;
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
  return PALETTE[Math.abs(h) % PALETTE.length];
}

async function copyToClipboard(text) {
  try {
    await navigator.clipboard.writeText(text);
    Swal.fire({ icon: "success", title: "Copied!", text: "Email copied to clipboard", timer: 1500, showConfirmButton: false, position: "top-end", toast: true });
  } catch {}
}

const hasActiveFilters = computed(() => searchQuery.value || purokFilter.value !== "all");

const filteredResidents = computed(() => {
  let filtered = residents.value;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    filtered = filtered.filter(r =>
      fullName(r).toLowerCase().includes(q) ||
      r.email?.toLowerCase().includes(q) ||
      r.information?.contact_number?.includes(q)
    );
  }
  if (purokFilter.value !== "all")
    filtered = filtered.filter(r => r.address?.purok?.toString() === purokFilter.value.toString());
  return filtered;
});

const totalPages         = computed(() => Math.max(1, Math.ceil(filteredResidents.value.length / itemsPerPage)));
const paginatedResidents = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredResidents.value.slice(start, start + itemsPerPage);
});

async function fetchResidents(showLoading = true) {
  if (showLoading) loading.value = true;
  try { const data = await ResidentService.getResidents(); residents.value = data; setResidents(data); }
  catch { Swal.fire({ icon: "error", title: "Error", text: "Failed to load residents", confirmButtonColor: "#3d4f7c" }); }
  finally { if (showLoading) loading.value = false; }
}

async function fetchRoles() {
  try { const data = await ResidentService.getRoles(); roles.value = data; setRoles(data); }
  catch (e) { console.error("Failed to fetch roles:", e); }
}

function openEditModal(resident) { selectedResident.value = resident; showEditModal.value = true; }
function resetFilters() { searchQuery.value = ""; purokFilter.value = "all"; currentPage.value = 1; }
function openViewModal(resident) { selectedResident.value = resident; showViewModal.value = true; }

async function handleDelete(resident) {
  const result = await Swal.fire({ title: "Delete Resident?", html: `Are you sure you want to delete <strong>${fullName(resident)}</strong>?<br><br><span class="text-xs text-slate-500">This action cannot be undone.</span>`, icon: "warning", showCancelButton: true, confirmButtonColor: "#d33", cancelButtonColor: "#3d4f7c", confirmButtonText: "Yes, delete", cancelButtonText: "Cancel" });
  if (result.isConfirmed) {
    try { await ResidentService.deleteResident(resident.id); await fetchResidents(false); Swal.fire({ icon: "success", title: "Deleted!", text: "Resident has been deleted.", timer: 2000, showConfirmButton: false }); }
    catch { Swal.fire({ icon: "error", title: "Error", text: "Failed to delete resident." }); }
  }
}

async function handleResidentAdded() {
  showAddModal.value = false;
  await Swal.fire({ icon: "success", title: "Success!", text: "Resident has been added successfully.", timer: 1500, showConfirmButton: false });
  await fetchResidents(false);
}

async function handleResidentUpdated() {
  showEditModal.value = false; selectedResident.value = null;
  await Swal.fire({ icon: "success", title: "Success!", text: "Resident has been updated successfully.", timer: 1500, showConfirmButton: false });
  await fetchResidents(false);
}

watch([searchQuery, purokFilter], () => { currentPage.value = 1; });

onMounted(async () => {
  if (hasResidentsData()) residents.value = getResidents(); else await fetchResidents(true);
  if (hasData()) roles.value = getRoles(); else await fetchRoles();
});
</script>

<style scoped>
/* Add line-clamp utility for multi-line truncation */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-clamp: 2;
}

/* Ensure table cells don't overflow */
table {
  table-layout: fixed;
  width: 100%;
}

td, th {
  word-wrap: break-word;
  overflow-wrap: break-word;
}

/* Mobile card styles */
@media (max-width: 768px) {
  .line-clamp-2 {
    -webkit-line-clamp: 3;
    line-clamp: 2;
  }
}
</style>