<template>
  <div class="min-h-full bg-[#f5f6fa] p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <!-- Google Font Import for Inter -->
    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- ── Page Header ── -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <!-- Left border accent -->
          <div class="w-1.5 h-10 bg-blue-600 rounded-full"></div>
          
          <!-- Icon with background -->
          <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
          
          <!-- Title -->
          <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Announcements</h1>
        </div>

        <div class="flex gap-2">
          <button
            @click="openCreateModal"
            class="inline-flex items-center gap-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-base font-medium px-5 py-3 rounded-lg shadow-sm hover:shadow transition-all duration-150 border-0 cursor-pointer"
          >
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Announcement
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards - Enhanced -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
      <!-- Total -->
      <div class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ stats.total || 0 }}</p>
          </div>
          <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Published -->
      <div class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Published</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ stats.published || 0 }}</p>
          </div>
          <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Draft -->
      <div class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Draft</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">{{ stats.draft || 0 }}</p>
          </div>
          <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Archived -->
      <div class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Archived</p>
            <p class="text-2xl font-bold text-slate-500 mt-1">{{ stats.archived || 0 }}</p>
          </div>
          <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 group-hover:scale-110 transition-transform">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Urgent -->
      <div class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Urgent</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ stats.urgent || 0 }}</p>
          </div>
          <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Trash/Active Tabs -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden mb-6">
      <div class="border-b border-slate-100">
        <nav class="flex px-4">
          <button 
            @click="showTrash = false" 
            class="py-4 px-4 text-sm font-medium border-b-2 transition-colors"
            :class="showTrash ? 'border-transparent text-slate-500 hover:text-slate-700' : 'border-blue-600 text-blue-600'"
          >
            <span>Active Announcements</span>
            <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs">{{ stats.total || 0 }}</span>
          </button>
          <button 
            @click="showTrash = true" 
            class="py-4 px-4 text-sm font-medium border-b-2 transition-colors"
            :class="!showTrash ? 'border-transparent text-slate-500 hover:text-slate-700' : 'border-blue-600 text-blue-600'"
          >
            <span>Trash</span>
            <span class="ml-2 bg-slate-100 text-slate-600 py-0.5 px-2 rounded-full text-xs">{{ stats.trashed || 0 }}</span>
          </button>
        </nav>
      </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-xl border border-slate-100 p-4 mb-6 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center gap-3">
        <!-- Search -->
        <div class="relative flex-1">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input 
            v-model="filters.search" 
            type="text" 
            placeholder="Search announcements by title, content or category…" 
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
          />
          <span v-if="filters.search" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
            {{ filteredCount }} found
          </span>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-2 flex-wrap">
          <div class="relative">
            <select 
              v-model="filters.status" 
              class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl pl-8 pr-8 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
            >
              <option value="all">All Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
              <option value="archived">Archived</option>
            </select>
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

          <div class="relative">
            <select 
              v-model="filters.category" 
              class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl pl-8 pr-8 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
            >
              <option value="all">All Categories</option>
              <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
            </select>
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

          <div class="relative">
            <select 
              v-model="filters.urgent" 
              class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl pl-8 pr-8 py-2.5 cursor-pointer hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
            >
              <option value="all">All Priority</option>
              <option value="urgent">Urgent Only</option>
              <option value="normal">Normal</option>
            </select>
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

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

    <!-- Trash Actions -->
    <div v-if="showTrash && stats.trashed > 0" class="bg-amber-50 rounded-xl border border-amber-200 p-4 mb-6 flex items-center justify-between">
      <span class="text-sm text-amber-700 flex items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        {{ stats.trashed }} items in trash
      </span>
      <button 
        @click="emptyTrash" 
        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all flex items-center gap-1.5"
      >
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Empty Trash
      </button>
    </div>

    <!-- Bulk Actions -->
    <div v-if="selectedItems.length > 0" class="bg-blue-50 rounded-xl border border-blue-200 p-4 mb-6 flex items-center justify-between">
      <span class="text-sm text-blue-700">{{ selectedItems.length }} items selected</span>
      <div class="flex gap-2">
        <template v-if="!showTrash">
          <select 
            v-model="bulkStatus" 
            class="px-3 py-1.5 text-sm border border-blue-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
          >
            <option value="published">Publish</option>
            <option value="draft">Move to Draft</option>
            <option value="archived">Archive</option>
          </select>
          <button 
            @click="bulkUpdateStatus" 
            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all"
          >
            Apply
          </button>
          <button 
            @click="confirmBulkDelete" 
            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all"
          >
            Move to Trash
          </button>
        </template>
        <template v-else>
          <button 
            @click="confirmBulkRestore" 
            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all"
          >
            Restore Selected
          </button>
          <button 
            @click="confirmBulkForceDelete" 
            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all"
          >
            Delete Permanently
          </button>
        </template>
        <button 
          @click="selectedItems = []" 
          class="px-3 py-1.5 border border-blue-200 rounded-lg text-sm hover:bg-blue-100 transition-all"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- Announcements Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/60">
              <th class="px-6 py-4 w-10">
                <input 
                  type="checkbox" 
                  @change="selectAll" 
                  v-model="allSelected" 
                  class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                >
              </th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Title</th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Category</th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status</th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Author</th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Created</th>
              <th class="text-left px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider w-28">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="announcement in paginatedAnnouncements" 
              :key="announcement.id" 
              class="border-b border-slate-50 hover:bg-blue-50/30 transition-colors duration-150 group"
              :class="{ 'bg-blue-50/30': selectedItems.includes(announcement.id) }"
            >
              <td class="px-6 py-4">
                <input 
                  type="checkbox" 
                  :value="announcement.id" 
                  v-model="selectedItems" 
                  class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                >
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div v-if="announcement.featured_image" class="flex-shrink-0 w-10 h-10">
                    <img 
                      :src="announcement.featured_image_url || '/storage/' + announcement.featured_image" 
                      class="w-10 h-10 rounded-lg object-cover shadow-sm group-hover:scale-105 transition-transform"
                      @error="handleImageError"
                    >
                  </div>
                  <div v-else class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg flex items-center justify-center text-slate-400 group-hover:scale-105 transition-transform">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-800">{{ announcement.title }}</p>
                    <p class="text-xs text-slate-500">{{ announcement.excerpt }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span 
                  :class="categoryBadgeClass(announcement.category)" 
                  class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-lg whitespace-nowrap"
                >
                  {{ categories[announcement.category] }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span 
                  :class="statusBadgeClass(announcement.status)" 
                  class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap"
                >
                  <span class="relative flex h-2 w-2 mr-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                      :class="{
                        'bg-emerald-400': announcement.status === 'published',
                        'bg-yellow-400': announcement.status === 'draft',
                        'bg-slate-400': announcement.status === 'archived'
                      }"
                    ></span>
                    <span class="relative inline-flex rounded-full h-2 w-2"
                      :class="{
                        'bg-emerald-500': announcement.status === 'published',
                        'bg-yellow-500': announcement.status === 'draft',
                        'bg-slate-500': announcement.status === 'archived'
                      }"
                    ></span>
                  </span>
                  {{ announcement.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <!-- Author avatar with initials -->
                  <div 
                    class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 shadow-sm"
                    :style="{ background: getAuthorColor(announcement) }"
                  >
                    {{ getAuthorInitials(announcement) }}
                  </div>
                  <span class="text-sm font-medium text-slate-700">{{ getAuthorName(announcement) }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-slate-600">
                  <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ formatDate(announcement.created_at) }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <!-- Active view buttons -->
                  <template v-if="!showTrash">
                    <button 
                      @click="toggleUrgent(announcement)" 
                      class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all group/btn"
                      :title="announcement.is_urgent ? 'Remove urgent' : 'Mark urgent'"
                    >
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </button>
                    <button 
                      @click="editAnnouncement(announcement)" 
                      class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all group/btn"
                      title="Edit"
                    >
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      @click="confirmDelete(announcement)" 
                      class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all group/btn"
                      title="Move to Trash"
                    >
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </template>
                  
                  <!-- Trash view buttons -->
                  <template v-else>
                    <button 
                      @click="restoreAnnouncement(announcement.id)" 
                      class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition-all group/btn"
                      title="Restore"
                    >
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                    </button>
                    <button 
                      @click="forceDeleteAnnouncement(announcement.id)" 
                      class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all group/btn"
                      title="Permanently Delete"
                    >
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover/btn:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </template>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && filteredAnnouncements.length > 0" class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-xs text-slate-500">
          Showing <span class="font-medium text-slate-700">{{ pagination.from || 0 }}</span>
          to <span class="font-medium text-slate-700">{{ pagination.to || 0 }}</span>
          of <span class="font-medium text-slate-700">{{ pagination.total || 0 }}</span> results
        </p>
        <div class="flex items-center gap-2">
          <button 
            :disabled="pagination.current_page === 1" 
            @click="changePage(pagination.current_page - 1)"
            class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm disabled:opacity-40 disabled:cursor-not-allowed hover:bg-white hover:border-blue-300 hover:text-blue-600 transition-all"
          >
            Previous
          </button>
          <div class="flex items-center gap-1">
            <span class="text-sm text-slate-600 px-2">{{ pagination.current_page }} / {{ pagination.last_page }}</span>
          </div>
          <button 
            :disabled="pagination.current_page === pagination.last_page" 
            @click="changePage(pagination.current_page + 1)"
            class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm disabled:opacity-40 disabled:cursor-not-allowed hover:bg-white hover:border-blue-300 hover:text-blue-600 transition-all"
          >
            Next
          </button>
        </div>
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

    <!-- Preview Modal (if needed) -->
    <Transition name="modal">
      <div v-if="showPreviewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="closePreviewModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all animate-fadeIn">
            <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-6 py-4 rounded-t-xl">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Preview: {{ previewData?.title }}</h3>
                <button @click="closePreviewModal" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-1.5 transition-all">
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
              <div class="prose max-w-none">
                <h1 class="text-2xl font-bold text-slate-800">{{ previewData?.title }}</h1>
                <div class="flex items-center gap-3 text-sm text-slate-500 mt-2 mb-4">
                  <span :class="categoryBadgeClass(previewData?.category)" class="px-2 py-1 rounded-lg">
                    {{ categories[previewData?.category] }}
                  </span>
                  <span>{{ formatDate(previewData?.created_at) }}</span>
                  <span v-if="previewData?.is_urgent" class="text-red-600 font-semibold">• URGENT</span>
                </div>
                <div class="text-slate-700" v-html="previewData?.content"></div>
              </div>
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

// State
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

// Filters
const filters = reactive({
  search: '',
  status: 'all',
  category: 'all',
  urgent: 'all'
})

const bulkStatus = ref('published')

// Pagination
const currentPage = ref(1)
const itemsPerPage = 10
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0
})

// Constants
const categories = {
  event: 'Events & Activities',
  advisory: 'Public Advisories',
  emergency: 'Emergency Alerts',
  meeting: 'Community Meetings',
  program: 'Government Programs'
}

// Computed
const hasActiveFilters = computed(() => 
  filters.search || filters.status !== 'all' || filters.category !== 'all' || filters.urgent !== 'all'
)

const filteredCount = computed(() => filteredAnnouncements.value.length)

const filteredAnnouncements = computed(() => {
  let filtered = announcements.value
  
  if (filters.search) {
    const search = filters.search.toLowerCase()
    filtered = filtered.filter(a => 
      a.title.toLowerCase().includes(search) || 
      a.content.toLowerCase().includes(search) ||
      categories[a.category]?.toLowerCase().includes(search)
    )
  }
  
  if (filters.status !== 'all') {
    filtered = filtered.filter(a => a.status === filters.status)
  }
  
  if (filters.category !== 'all') {
    filtered = filtered.filter(a => a.category === filters.category)
  }
  
  if (filters.urgent !== 'all') {
    const isUrgent = filters.urgent === 'urgent'
    filtered = filtered.filter(a => a.is_urgent === isUrgent)
  }
  
  return filtered
})

const paginatedAnnouncements = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredAnnouncements.value.slice(start, end)
})

// SweetAlert2 helpers
const showSuccess = (message) => {
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showError = (message) => {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showConfirmDialog = async (title, text) => {
  const result = await Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  })
  return result.isConfirmed
}

// Methods
const fetchAnnouncements = async () => {
  loading.value = true
  try {
    const params = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      ...filters
    }
    const response = await announcementService.getAnnouncements(params)
    announcements.value = response.data.data
    pagination.value = response.data
    stats.value = response.stats
    currentPage.value = 1
  } catch (error) {
    showError('Failed to fetch announcements')
    console.error(error)
  } finally {
    loading.value = false
  }
}

const fetchTrashedAnnouncements = async () => {
  loading.value = true
  try {
    const params = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      search: filters.search,
      category: filters.category !== 'all' ? filters.category : undefined
    }
    const response = await announcementService.getTrashedAnnouncements(params)
    announcements.value = response.data.data
    pagination.value = response.data
    stats.value = response.stats
    currentPage.value = 1
  } catch (error) {
    showError('Failed to fetch trashed announcements')
    console.error(error)
  } finally {
    loading.value = false
  }
}

const handleSave = async (formData) => {
  saving.value = true
  try {
    if (modalMode.value === 'create') {
      await announcementService.createAnnouncement(formData)
      showSuccess('Announcement created successfully')
    } else {
      await announcementService.updateAnnouncement(formData.id, formData)
      showSuccess('Announcement updated successfully')
    }
    closeModal()
    if (showTrash.value) {
      await fetchTrashedAnnouncements()
    } else {
      await fetchAnnouncements()
    }
  } catch (error) {
    showError('Failed to save announcement')
    console.error(error)
  } finally {
    saving.value = false
  }
}

const restoreAnnouncement = async (id) => {
  const confirmed = await showConfirmDialog(
    'Restore Announcement',
    'Are you sure you want to restore this announcement?'
  )
  
  if (confirmed) {
    try {
      await announcementService.restoreAnnouncement(id)
      showSuccess('Announcement restored successfully')
      if (showTrash.value) {
        await fetchTrashedAnnouncements()
      }
    } catch (error) {
      showError('Failed to restore announcement')
      console.error(error)
    }
  }
}

const forceDeleteAnnouncement = async (id) => {
  const confirmed = await showConfirmDialog(
    'Permanently Delete Announcement',
    'This action cannot be undone. The announcement will be permanently deleted.'
  )
  
  if (confirmed) {
    try {
      await announcementService.forceDeleteAnnouncement(id)
      showSuccess('Announcement permanently deleted')
      if (showTrash.value) {
        await fetchTrashedAnnouncements()
      }
    } catch (error) {
      showError('Failed to delete announcement')
      console.error(error)
    }
  }
}

const emptyTrash = async () => {
  const confirmed = await showConfirmDialog(
    'Empty Trash',
    'Are you sure you want to permanently delete all items in trash?'
  )
  
  if (confirmed) {
    try {
      await announcementService.emptyTrash()
      showSuccess('Trash emptied successfully')
      await fetchTrashedAnnouncements()
    } catch (error) {
      showError('Failed to empty trash')
      console.error(error)
    }
  }
}

const confirmBulkRestore = async () => {
  const confirmed = await showConfirmDialog(
    'Restore Multiple Announcements',
    `Are you sure you want to restore ${selectedItems.value.length} announcements?`
  )
  
  if (confirmed) {
    try {
      await announcementService.bulkRestore({ ids: selectedItems.value })
      showSuccess('Announcements restored successfully')
      selectedItems.value = []
      if (showTrash.value) {
        await fetchTrashedAnnouncements()
      }
    } catch (error) {
      showError('Failed to restore announcements')
      console.error(error)
    }
  }
}

const confirmBulkForceDelete = async () => {
  const confirmed = await showConfirmDialog(
    'Permanently Delete Multiple Announcements',
    `Are you sure you want to permanently delete ${selectedItems.value.length} announcements?`
  )
  
  if (confirmed) {
    try {
      await announcementService.bulkForceDelete({ ids: selectedItems.value })
      showSuccess('Announcements permanently deleted')
      selectedItems.value = []
      if (showTrash.value) {
        await fetchTrashedAnnouncements()
      }
    } catch (error) {
      showError('Failed to delete announcements')
      console.error(error)
    }
  }
}

const confirmDelete = async (announcement) => {
  const confirmed = await showConfirmDialog(
    'Move to Trash',
    `Are you sure you want to move "${announcement.title}" to trash?`
  )
  
  if (confirmed) {
    try {
      await announcementService.deleteAnnouncement(announcement.id)
      showSuccess('Announcement moved to trash')
      if (!showTrash.value) {
        await fetchAnnouncements()
      }
    } catch (error) {
      showError('Failed to move announcement to trash')
      console.error(error)
    }
  }
}

const confirmBulkDelete = async () => {
  const confirmed = await showConfirmDialog(
    'Move Multiple to Trash',
    `Are you sure you want to move ${selectedItems.value.length} announcements to trash?`
  )
  
  if (confirmed) {
    try {
      await announcementService.bulkDelete({ ids: selectedItems.value })
      showSuccess('Announcements moved to trash successfully')
      selectedItems.value = []
      if (!showTrash.value) {
        await fetchAnnouncements()
      }
    } catch (error) {
      showError('Failed to move announcements to trash')
      console.error(error)
    }
  }
}

const statusBadgeClass = (status) => {
  const classes = {
    published: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    draft: 'bg-amber-50 text-amber-700 border border-amber-200',
    archived: 'bg-slate-100 text-slate-600 border border-slate-200'
  }
  return classes[status] || 'bg-slate-100 text-slate-600'
}

const categoryBadgeClass = (category) => {
  const classes = {
    event: 'bg-purple-50 text-purple-700 border border-purple-200',
    advisory: 'bg-blue-50 text-blue-700 border border-blue-200',
    emergency: 'bg-red-50 text-red-700 border border-red-200',
    meeting: 'bg-green-50 text-green-700 border border-green-200',
    program: 'bg-yellow-50 text-yellow-700 border border-yellow-200'
  }
  return classes[category] || 'bg-slate-100 text-slate-600'
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const openCreateModal = () => {
  modalMode.value = 'create'
  selectedAnnouncement.value = null
  showModal.value = true
}

const editAnnouncement = (announcement) => {
  modalMode.value = 'edit'
  selectedAnnouncement.value = announcement
  showModal.value = true
}

const previewAnnouncement = (announcement) => {
  previewData.value = announcement
  showPreviewModal.value = true
}

const closePreviewModal = () => {
  showPreviewModal.value = false
  previewData.value = null
}

const closeModal = () => {
  showModal.value = false
  selectedAnnouncement.value = null
}

const toggleUrgent = async (announcement) => {
  try {
    await announcementService.toggleUrgent(announcement.id)
    showSuccess(announcement.is_urgent ? 'Removed urgent status' : 'Marked as urgent')
    if (showTrash.value) {
      await fetchTrashedAnnouncements()
    } else {
      await fetchAnnouncements()
    }
  } catch (error) {
    showError('Failed to update urgent status')
  }
}

const handleImageError = (e) => {
  e.target.style.display = 'none'
}

const selectAll = () => {
  if (allSelected.value) {
    selectedItems.value = paginatedAnnouncements.value.map(a => a.id)
  } else {
    selectedItems.value = []
  }
}

// Watch for select all changes
watch(allSelected, (newValue) => {
  if (newValue) {
    selectedItems.value = paginatedAnnouncements.value.map(a => a.id)
  } else {
    selectedItems.value = []
  }
})

// Watch for selected items changes
watch(selectedItems, (newValue) => {
  if (paginatedAnnouncements.value.length > 0) {
    allSelected.value = newValue.length === paginatedAnnouncements.value.length
  }
})

const bulkUpdateStatus = async () => {
  try {
    await announcementService.bulkUpdateStatus({
      ids: selectedItems.value,
      status: bulkStatus.value
    })
    showSuccess('Status updated successfully')
    selectedItems.value = []
    if (showTrash.value) {
      await fetchTrashedAnnouncements()
    } else {
      await fetchAnnouncements()
    }
  } catch (error) {
    showError('Failed to update status')
  }
}

const resetFilters = () => {
  filters.search = ''
  filters.status = 'all'
  filters.category = 'all'
  filters.urgent = 'all'
  currentPage.value = 1
  if (showTrash.value) {
    fetchTrashedAnnouncements()
  } else {
    fetchAnnouncements()
  }
}

const changePage = (page) => {
  currentPage.value = page
}

const exportAnnouncements = () => {
  showSuccess('Export feature coming soon')
}

// Watch for filter changes
watch(filters, () => {
  currentPage.value = 1
}, { deep: true })

// Watch for trash toggle
watch(showTrash, (newValue) => {
  currentPage.value = 1
  selectedItems.value = []
  allSelected.value = false
  if (newValue) {
    fetchTrashedAnnouncements()
  } else {
    fetchAnnouncements()
  }
})

// Author helper methods
const getAuthorName = (announcement) => {
  if (!announcement.creator) {
    return 'Unknown';
  }
  
  if (announcement.creator.information) {
    const info = announcement.creator.information;
    const firstName = info.first_name || '';
    const lastName = info.last_name || '';
    
    if (firstName || lastName) {
      return `${firstName} ${lastName}`.trim();
    }
  }
  
  if (announcement.creator.first_name) {
    const firstName = announcement.creator.first_name || '';
    const lastName = announcement.creator.last_name || '';
    return `${firstName} ${lastName}`.trim() || 'Unknown';
  }
  
  if (announcement.creator.name) {
    return announcement.creator.name;
  }
  
  if (announcement.creator.email) {
    return announcement.creator.email.split('@')[0] || 'Unknown';
  }
  
  return 'Unknown';
}

const getAuthorInitials = (announcement) => {
  const name = getAuthorName(announcement);
  
  if (name === 'Unknown' || !name) return '?';
  
  if (name.includes('@')) {
    return name[0].toUpperCase();
  }
  
  const parts = name.split(' ');
  if (parts.length === 1) {
    return parts[0][0]?.toUpperCase() || '?';
  }
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

const getAuthorColor = (announcement) => {
  const name = getAuthorName(announcement);
  
  const colors = ['#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0891b2', '#9333ea', '#ea580c'];
  
  let hash = 0;
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  return colors[Math.abs(hash) % colors.length];
}

// Initial load
onMounted(() => {
  fetchAnnouncements()
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
</style>