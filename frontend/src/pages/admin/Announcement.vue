<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c]"></div>
          <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c] bg-opacity-10 text-[#3d4f7c]">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Announcements</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and publish barangay announcements.</p>
          </div>
        </div>
        <button
          @click="openCreateModal"
          class="inline-flex items-center gap-2.5 text-white text-base font-medium px-5 py-3 rounded-lg shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all duration-150 border-0 cursor-pointer"
          style="background-color: #3d4f7c;"
          @mouseenter="e => e.currentTarget.style.backgroundColor = '#252b3b'"
          @mouseleave="e => e.currentTarget.style.backgroundColor = '#3d4f7c'"
        >
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          New Announcement
        </button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-slate-100 mb-6 shadow-sm overflow-hidden">
      <div class="border-b border-slate-100">
        <nav class="flex px-4">
          <button
            @click="showTrash = false"
            class="py-4 px-4 text-sm font-medium border-b-2 transition-colors"
            :class="!showTrash ? 'border-[#3d4f7c] text-[#3d4f7c]' : 'border-transparent text-slate-500 hover:text-slate-700'"
          >
            Active Announcements
            <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs">{{ stats.total || 0 }}</span>
          </button>
          <button
            @click="showTrash = true"
            class="py-4 px-4 text-sm font-medium border-b-2 transition-colors"
            :class="showTrash ? 'border-[#3d4f7c] text-[#3d4f7c]' : 'border-transparent text-slate-500 hover:text-slate-700'"
          >
            Trash
            <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs">{{ stats.trashed || 0 }}</span>
          </button>
        </nav>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search announcements by title, content or category…"
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c] focus:ring-opacity-20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
          <span v-if="filters.search" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredCount }} found
          </span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.status"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="all">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="archived">Archived</option>
          </select>
          <select v-model="filters.category"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="all">All Categories</option>
            <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
          </select>
          <select v-model="filters.urgent"
            class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl px-3 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all">
            <option value="all">All Priority</option>
            <option value="urgent">Urgent Only</option>
            <option value="normal">Normal</option>
          </select>
          <button
            v-if="hasActiveFilters"
            @click="resetFilters"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-2.5 rounded-xl transition-all cursor-pointer"
          >
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
          </button>
        </div>
      </div>
    </div>

    <!-- Trash warning banner -->
    <div v-if="showTrash && stats.trashed > 0" class="bg-amber-50 rounded-xl border border-amber-200 p-4 mb-6 flex items-center justify-between">
      <span class="text-sm text-amber-700 flex items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        {{ stats.trashed }} items in trash
      </span>
      <button @click="emptyTrash" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all flex items-center gap-1.5">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Empty Trash
      </button>
    </div>

    <!-- Bulk Actions -->
    <div v-if="selectedItems.length > 0" class="bg-[#3d4f7c]/10 rounded-xl border border-[#3d4f7c]/20 p-4 mb-6 flex items-center justify-between">
      <span class="text-sm text-[#3d4f7c] font-semibold">{{ selectedItems.length }} items selected</span>
      <div class="flex gap-2">
        <template v-if="!showTrash">
          <select v-model="bulkStatus" class="px-3 py-1.5 text-sm border border-[#3d4f7c]/20 rounded-lg bg-white focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]">
            <option value="published">Publish</option>
            <option value="draft">Move to Draft</option>
            <option value="archived">Archive</option>
          </select>
          <button @click="bulkUpdateStatus" class="px-3 py-1.5 text-white text-sm font-medium rounded-lg transition-all" style="background:#3d4f7c">Apply</button>
          <button @click="confirmBulkDelete" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all">Move to Trash</button>
        </template>
        <template v-else>
          <button @click="confirmBulkRestore" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all">Restore Selected</button>
          <button @click="confirmBulkForceDelete" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all">Delete Permanently</button>
        </template>
        <button @click="selectedItems = []" class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm hover:bg-slate-50 transition-all">Clear</button>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

      <!-- Gradient Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full bg-white bg-opacity-20"></div>
          <h2 class="text-lg font-semibold text-white tracking-tight">
            {{ showTrash ? 'Trashed Announcements' : 'Announcements List' }}
          </h2>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
        <div class="relative w-11 h-11">
          <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-sm text-slate-400 font-medium">Loading announcements...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="px-6 py-3.5 w-10">
                <input type="checkbox" @change="selectAll" v-model="allSelected" class="rounded border-slate-300 cursor-pointer"/>
              </th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Title</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Category</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Author</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Created</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-28">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="announcement in paginatedAnnouncements"
              :key="announcement.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group"
              :class="{ 'bg-[#3d4f7c]/5': selectedItems.includes(announcement.id) }"
            >
              <td class="px-6 py-4">
                <input type="checkbox" :value="announcement.id" v-model="selectedItems" class="rounded border-slate-300 cursor-pointer"/>
              </td>

              <!-- Title -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div v-if="announcement.featured_image" class="flex-shrink-0 w-10 h-10">
                    <img
                      :src="announcement.featured_image_url || '/storage/' + announcement.featured_image"
                      class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform"
                      @error="handleImageError"
                    />
                  </div>
                  <div v-else class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform" style="background:#3d4f7c15">
                    <svg width="18" height="18" fill="none" stroke="#3d4f7c" stroke-width="1.8" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <p class="text-sm font-semibold text-slate-800">{{ announcement.title }}</p>
                      <span v-if="announcement.is_urgent" class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-red-50 text-red-600 border border-red-200">URGENT</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ announcement.excerpt }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="px-6 py-4">
                <span :class="categoryBadgeClass(announcement.category)" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-lg whitespace-nowrap">
                  {{ categories[announcement.category] }}
                </span>
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span :class="statusBadgeClass(announcement.status)" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg whitespace-nowrap">
                  <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                      :class="{ 'bg-emerald-400': announcement.status === 'published', 'bg-amber-400': announcement.status === 'draft', 'bg-slate-400': announcement.status === 'archived' }">
                    </span>
                    <span class="relative inline-flex rounded-full h-2 w-2"
                      :class="{ 'bg-emerald-500': announcement.status === 'published', 'bg-amber-500': announcement.status === 'draft', 'bg-slate-500': announcement.status === 'archived' }">
                    </span>
                  </span>
                  {{ announcement.status }}
                </span>
              </td>

              <!-- Author -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 shadow-sm"
                    :style="{ background: getAuthorColor(announcement) }">
                    {{ getAuthorInitials(announcement) }}
                  </div>
                  <span class="text-sm text-slate-700 font-medium">{{ getAuthorName(announcement) }}</span>
                </div>
              </td>

              <!-- Created -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-600">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ formatDate(announcement.created_at) }}
                </div>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <template v-if="!showTrash">
                    <button @click="toggleUrgent(announcement)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-150 cursor-pointer group/btn"
                      :class="announcement.is_urgent ? 'bg-red-50 text-red-500 border border-red-200' : 'bg-slate-50 text-slate-400 border border-slate-200 hover:bg-red-50 hover:text-red-500 hover:border-red-200'"
                      :title="announcement.is_urgent ? 'Remove urgent' : 'Mark urgent'">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                      </svg>
                    </button>
                    <button @click="editAnnouncement(announcement)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                      title="Edit">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <button @click="confirmDelete(announcement)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md hover:shadow-red-200 active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                      title="Move to Trash">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </template>
                  <template v-else>
                    <button @click="restoreAnnouncement(announcement.id)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                      title="Restore">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                      </svg>
                    </button>
                    <button @click="forceDeleteAnnouncement(announcement.id)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group/btn"
                      title="Permanently Delete">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </template>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredAnnouncements.length === 0">
              <td colspan="7" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No announcements found</p>
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
      <div v-if="!loading && filteredAnnouncements.length > itemsPerPage" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredAnnouncements.length) }}
          <span class="text-slate-300 mx-1">/</span>
          {{ filteredAnnouncements.length }} results
        </p>
        <div class="flex items-center gap-1.5">
          <button :disabled="currentPage === 1" @click="currentPage--"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#3d4f7c] hover:text-[#3d4f7c] hover:bg-[#3d4f7c]/5 transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <template v-for="page in totalPages" :key="page">
            <button
              v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="page === currentPage ? 'text-white shadow-md' : 'text-slate-500 hover:bg-[#3d4f7c]/5 hover:text-[#3d4f7c] border border-transparent'"
              :style="page === currentPage ? 'background:#3d4f7c' : ''"
            >{{ page }}</button>
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

      <div v-else-if="!loading && filteredAnnouncements.length > 0" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono">
          Showing <span class="font-medium text-slate-700">{{ filteredAnnouncements.length }}</span> {{ filteredAnnouncements.length === 1 ? 'result' : 'results' }}
        </p>
      </div>
    </div>

    <!-- Modals -->
    <AnnouncementModal
      v-if="showModal"
      :modal-mode="modalMode"
      :announcement="selectedAnnouncement"
      :loading="saving"
      @close="closeModal"
      @save="handleSave"
    />

    <Transition name="modal">
      <div v-if="showPreviewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="closePreviewModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
            <div class="px-6 py-4 rounded-t-2xl bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
              <h3 class="text-lg font-semibold text-white">{{ previewData?.title }}</h3>
              <button @click="closePreviewModal" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-1.5 transition-all cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
              <div class="flex items-center gap-3 text-sm text-slate-500 mb-4">
                <span :class="categoryBadgeClass(previewData?.category)" class="px-2 py-1 rounded-lg text-xs font-semibold">{{ categories[previewData?.category] }}</span>
                <span>{{ formatDate(previewData?.created_at) }}</span>
                <span v-if="previewData?.is_urgent" class="text-red-600 font-bold text-xs">• URGENT</span>
              </div>
              <div class="text-slate-700 prose max-w-none" v-html="previewData?.content"></div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { announcementService } from '@/services/announcementService'
import Swal from 'sweetalert2'
import AnnouncementModal from '@/components/modals/admin/announcement/AnnouncementModal.vue'

const announcements = ref([])
const stats = ref({})
const loading = ref(false)
const saving = ref(false)
const showModal = ref(false)
const showPreviewModal = ref(false)
const modalMode = ref('create')
const selectedAnnouncement = ref(null)
const previewData = ref(null)
const selectedItems = ref([])
const allSelected = ref(false)
const showTrash = ref(false)
const currentPage = ref(1)
const itemsPerPage = 10

const filters = reactive({ search: '', status: 'all', category: 'all', urgent: 'all' })
const bulkStatus = ref('published')

const categories = {
  event: 'Events & Activities',
  advisory: 'Public Advisories',
  emergency: 'Emergency Alerts',
  meeting: 'Community Meetings',
  program: 'Government Programs'
}

const hasActiveFilters = computed(() => filters.search || filters.status !== 'all' || filters.category !== 'all' || filters.urgent !== 'all')
const filteredCount = computed(() => filteredAnnouncements.value.length)

const filteredAnnouncements = computed(() => {
  let filtered = announcements.value
  if (filters.search) {
    const search = filters.search.toLowerCase()
    filtered = filtered.filter(a => a.title.toLowerCase().includes(search) || a.content.toLowerCase().includes(search) || categories[a.category]?.toLowerCase().includes(search))
  }
  if (filters.status !== 'all') filtered = filtered.filter(a => a.status === filters.status)
  if (filters.category !== 'all') filtered = filtered.filter(a => a.category === filters.category)
  if (filters.urgent !== 'all') filtered = filtered.filter(a => a.is_urgent === (filters.urgent === 'urgent'))
  return filtered
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredAnnouncements.value.length / itemsPerPage)))
const paginatedAnnouncements = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredAnnouncements.value.slice(start, start + itemsPerPage)
})

const showSuccess = (message) => Swal.fire({ icon: 'success', title: 'Success', text: message, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })
const showError = (message) => Swal.fire({ icon: 'error', title: 'Error', text: message, timer: 3000, showConfirmButton: false, position: 'top-end', toast: true })
const showConfirmDialog = async (title, text) => {
  const result = await Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3d4f7c', confirmButtonText: 'Yes', cancelButtonText: 'Cancel' })
  return result.isConfirmed
}

const fetchAnnouncements = async () => {
  loading.value = true
  try {
    const response = await announcementService.getAnnouncements({ page: 1, per_page: 10, ...filters })
    announcements.value = response.data.data
    stats.value = response.stats
    currentPage.value = 1
  } catch { showError('Failed to fetch announcements') } finally { loading.value = false }
}

const fetchTrashedAnnouncements = async () => {
  loading.value = true
  try {
    const response = await announcementService.getTrashedAnnouncements({ search: filters.search, category: filters.category !== 'all' ? filters.category : undefined })
    announcements.value = response.data.data
    stats.value = response.stats
    currentPage.value = 1
  } catch { showError('Failed to fetch trashed announcements') } finally { loading.value = false }
}

const handleSave = async (formData) => {
  saving.value = true
  try {
    if (modalMode.value === 'create') { await announcementService.createAnnouncement(formData); showSuccess('Announcement created successfully') }
    else { await announcementService.updateAnnouncement(formData.id, formData); showSuccess('Announcement updated successfully') }
    closeModal()
    showTrash.value ? await fetchTrashedAnnouncements() : await fetchAnnouncements()
  } catch { showError('Failed to save announcement') } finally { saving.value = false }
}

const restoreAnnouncement = async (id) => {
  if (await showConfirmDialog('Restore Announcement', 'Are you sure you want to restore this announcement?')) {
    try { await announcementService.restoreAnnouncement(id); showSuccess('Restored successfully'); await fetchTrashedAnnouncements() }
    catch { showError('Failed to restore') }
  }
}

const forceDeleteAnnouncement = async (id) => {
  if (await showConfirmDialog('Permanently Delete', 'This action cannot be undone.')) {
    try { await announcementService.forceDeleteAnnouncement(id); showSuccess('Permanently deleted'); await fetchTrashedAnnouncements() }
    catch { showError('Failed to delete') }
  }
}

const emptyTrash = async () => {
  if (await showConfirmDialog('Empty Trash', 'Permanently delete all items in trash?')) {
    try { await announcementService.emptyTrash(); showSuccess('Trash emptied'); await fetchTrashedAnnouncements() }
    catch { showError('Failed to empty trash') }
  }
}

const confirmBulkRestore = async () => {
  if (await showConfirmDialog('Restore Multiple', `Restore ${selectedItems.value.length} announcements?`)) {
    try { await announcementService.bulkRestore({ ids: selectedItems.value }); showSuccess('Restored'); selectedItems.value = []; await fetchTrashedAnnouncements() }
    catch { showError('Failed to restore') }
  }
}

const confirmBulkForceDelete = async () => {
  if (await showConfirmDialog('Permanently Delete Multiple', `Permanently delete ${selectedItems.value.length} announcements?`)) {
    try { await announcementService.bulkForceDelete({ ids: selectedItems.value }); showSuccess('Permanently deleted'); selectedItems.value = []; await fetchTrashedAnnouncements() }
    catch { showError('Failed to delete') }
  }
}

const confirmDelete = async (announcement) => {
  if (await showConfirmDialog('Move to Trash', `Move "${announcement.title}" to trash?`)) {
    try { await announcementService.deleteAnnouncement(announcement.id); showSuccess('Moved to trash'); await fetchAnnouncements() }
    catch { showError('Failed to delete') }
  }
}

const confirmBulkDelete = async () => {
  if (await showConfirmDialog('Move Multiple to Trash', `Move ${selectedItems.value.length} announcements to trash?`)) {
    try { await announcementService.bulkDelete({ ids: selectedItems.value }); showSuccess('Moved to trash'); selectedItems.value = []; await fetchAnnouncements() }
    catch { showError('Failed') }
  }
}

const bulkUpdateStatus = async () => {
  try { await announcementService.bulkUpdateStatus({ ids: selectedItems.value, status: bulkStatus.value }); showSuccess('Status updated'); selectedItems.value = []; showTrash.value ? await fetchTrashedAnnouncements() : await fetchAnnouncements() }
  catch { showError('Failed to update status') }
}

const toggleUrgent = async (announcement) => {
  try { await announcementService.toggleUrgent(announcement.id); showSuccess(announcement.is_urgent ? 'Removed urgent status' : 'Marked as urgent'); showTrash.value ? await fetchTrashedAnnouncements() : await fetchAnnouncements() }
  catch { showError('Failed to update urgent status') }
}

const statusBadgeClass = (status) => ({ published: 'bg-emerald-50 text-emerald-700 border border-emerald-200', draft: 'bg-amber-50 text-amber-700 border border-amber-200', archived: 'bg-slate-100 text-slate-600 border border-slate-200' }[status] || 'bg-slate-100 text-slate-600')
const categoryBadgeClass = (category) => ({ event: 'bg-purple-50 text-purple-700 border border-purple-200', advisory: 'bg-blue-50 text-blue-700 border border-blue-200', emergency: 'bg-red-50 text-red-700 border border-red-200', meeting: 'bg-emerald-50 text-emerald-700 border border-emerald-200', program: 'bg-amber-50 text-amber-700 border border-amber-200' }[category] || 'bg-slate-100 text-slate-600')
const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—'
const openCreateModal = () => { modalMode.value = 'create'; selectedAnnouncement.value = null; showModal.value = true }
const editAnnouncement = (a) => { modalMode.value = 'edit'; selectedAnnouncement.value = a; showModal.value = true }
const closeModal = () => { showModal.value = false; selectedAnnouncement.value = null }
const closePreviewModal = () => { showPreviewModal.value = false; previewData.value = null }
const handleImageError = (e) => { e.target.style.display = 'none' }
const selectAll = () => { selectedItems.value = allSelected.value ? paginatedAnnouncements.value.map(a => a.id) : [] }
const resetFilters = () => { filters.search = ''; filters.status = 'all'; filters.category = 'all'; filters.urgent = 'all'; currentPage.value = 1; showTrash.value ? fetchTrashedAnnouncements() : fetchAnnouncements() }

const getAuthorName = (announcement) => {
  if (!announcement.creator) return 'Unknown'
  if (announcement.creator.information) { const i = announcement.creator.information; const n = `${i.first_name || ''} ${i.last_name || ''}`.trim(); if (n) return n }
  if (announcement.creator.name) return announcement.creator.name
  if (announcement.creator.email) return announcement.creator.email.split('@')[0]
  return 'Unknown'
}
const getAuthorInitials = (a) => { const n = getAuthorName(a); if (n === 'Unknown' || !n) return '?'; const p = n.split(' '); return p.length === 1 ? p[0][0]?.toUpperCase() || '?' : (p[0][0] + p[p.length - 1][0]).toUpperCase() }
const getAuthorColor = (a) => { const n = getAuthorName(a); const c = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#9333ea','#ea580c']; let h = 0; for (let i = 0; i < n.length; i++) h = n.charCodeAt(i) + ((h << 5) - h); return c[Math.abs(h) % c.length] }

watch(allSelected, (v) => { selectedItems.value = v ? paginatedAnnouncements.value.map(a => a.id) : [] })
watch(selectedItems, (v) => { allSelected.value = paginatedAnnouncements.value.length > 0 && v.length === paginatedAnnouncements.value.length })
watch(filters, () => { currentPage.value = 1 }, { deep: true })
watch(showTrash, (v) => { currentPage.value = 1; selectedItems.value = []; allSelected.value = false; v ? fetchTrashedAnnouncements() : fetchAnnouncements() })

onMounted(() => { fetchAnnouncements() })
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>