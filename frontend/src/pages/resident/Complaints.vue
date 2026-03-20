<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-6 py-4 mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-1.5 h-10 rounded-full bg-[#3d4f7c]"></div>
          <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c]">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Complaints & Reports</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">File and track your barangay complaints and incident reports</p>
          </div>
        </div>
        <button @click="openNewComplaintModal"
          class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-[#3d4f7c] text-white text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          File New Complaint
        </button>
      </div>
    </div>

    <!-- Mobile File Button -->
    <div class="sm:hidden mb-4 flex justify-end">
      <button @click="openNewComplaintModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#3d4f7c] text-white text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all shadow-sm active:scale-[0.98] cursor-pointer">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        File New Complaint
      </button>
    </div>

    <!-- Section Title + Table -->
    <div>
      <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-[#3d4f7c] rounded-full"></span>
        My Complaints & Reports
      </h2>

      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

        <!-- Loading -->
        <div v-if="loading" class="flex flex-col items-center justify-center gap-4 py-24">
          <div class="relative w-11 h-11">
            <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
            <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
          </div>
          <p class="text-sm text-slate-400 font-medium">Loading complaints...</p>
        </div>

        <div v-else>
          <!-- Desktop Table -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case #</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Type</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Title</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Incident Date</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Date Filed</th>
                  <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-24">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="complaint in paginatedComplaints" :key="complaint.id"
                  class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100">

                  <td class="px-6 py-4">
                    <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">
                      #C-{{ String(complaint.id).padStart(5, '0') }}
                    </span>
                  </td>

                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" :class="typeIconBg(complaint.type)">
                        <svg class="w-3 h-3" :class="typeIconColor(complaint.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="typeIcon(complaint.type)"></svg>
                      </div>
                      <span class="text-sm text-slate-600">{{ typeLabel(complaint.type) }}</span>
                    </div>
                  </td>

                  <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-slate-800">{{ complaint.title }}</p>
                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[180px]">{{ complaint.location }}</p>
                  </td>

                  <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5 text-sm text-slate-500">
                      <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      {{ formatDate(complaint.incident_date) }}
                    </div>
                  </td>

                  <td class="px-6 py-4">
                    <span :class="statusBadge(complaint.status)"
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                      <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(complaint.status)"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(complaint.status)"></span>
                      </span>
                      {{ formatStatus(complaint.status) }}
                    </span>
                  </td>

                  <td class="px-6 py-4">
                    <span class="text-sm text-slate-500">{{ formatDate(complaint.created_at) }}</span>
                  </td>

                  <td class="px-6 py-4">
                    <div class="flex items-center gap-1">
                      <button @click="viewComplaint(complaint)"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:shadow-md active:scale-95 transition-all cursor-pointer"
                        title="View Details">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                      <button v-if="complaint.status === 'pending'" @click="cancelComplaint(complaint.id)"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white hover:shadow-md active:scale-95 transition-all cursor-pointer"
                        title="Cancel Complaint">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="myComplaints.length === 0">
                  <td colspan="7" class="py-20 text-center">
                    <div class="flex flex-col items-center gap-3">
                      <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <p class="text-sm font-bold text-slate-600">No complaints filed yet</p>
                      <p class="text-xs text-slate-400">Use the button above to file your first complaint</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile Cards -->
          <div class="md:hidden">
            <div v-if="myComplaints.length === 0" class="flex flex-col items-center gap-3 py-16 text-center px-4">
              <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <p class="text-sm font-bold text-slate-600">No complaints filed yet</p>
              <p class="text-xs text-slate-400">Tap the button above to get started</p>
            </div>

            <div class="p-3 space-y-3">
              <div v-for="complaint in paginatedComplaints" :key="complaint.id"
                class="bg-white rounded-2xl border border-slate-100 overflow-hidden cursor-pointer active:scale-[0.99] transition-all"
                style="box-shadow:0 2px 8px rgba(0,0,0,0.05);"
                @click="viewComplaint(complaint)">

                <div class="px-4 pt-4 pb-3 flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-[#3d4f7c] font-mono mb-0.5">#C-{{ String(complaint.id).padStart(5, '0') }}</p>
                    <p class="text-sm font-bold text-slate-800 leading-tight truncate">{{ complaint.title }}</p>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ complaint.location }}</p>
                  </div>
                  <span :class="statusBadge(complaint.status)"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDotSolid(complaint.status)"></span>
                    {{ formatStatus(complaint.status) }}
                  </span>
                </div>

                <div class="mx-4 h-px bg-slate-100"></div>

                <div class="grid grid-cols-2 gap-0 px-3 py-3">
                  <div class="flex items-center gap-2 px-1 py-1">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c12">
                      <svg width="10" height="10" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Type</p>
                      <p class="text-[11px] font-semibold text-slate-700">{{ typeLabel(complaint.type) }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 px-1 py-1">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#05966912">
                      <svg width="10" height="10" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Incident</p>
                      <p class="text-[11px] font-semibold text-slate-700">{{ formatDate(complaint.incident_date) }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="complaint.status === 'pending'" class="mx-3 mb-3">
                  <button @click.stop="cancelComplaint(complaint.id)"
                    class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-semibold bg-red-50 text-red-600 border border-red-200 active:scale-95 transition-all cursor-pointer">
                    Cancel Complaint
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && myComplaints.length > itemsPerPage"
          class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
          <p class="text-xs text-slate-400 font-mono hidden sm:block">
            {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, myComplaints.length) }}
            <span class="text-slate-300 mx-1">/</span>{{ myComplaints.length }}
          </p>
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
        <div v-else-if="!loading && myComplaints.length > 0"
          class="px-4 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
          <p class="text-xs text-slate-400">Showing <span class="font-medium text-slate-700">{{ myComplaints.length }}</span> record{{ myComplaints.length !== 1 ? 's' : '' }}</p>
        </div>
      </div>
    </div>

    <!-- ── New Complaint Modal ── -->
    <Transition name="modal">
      <div v-if="showNewComplaintModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showNewComplaintModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[92vh]"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22);">

          <!-- Modal Header -->
          <div class="flex-shrink-0 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full bg-[#3d4f7c]"></div>
              <div>
                <h3 class="text-sm font-bold text-slate-800">File New Complaint / Report</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">All submissions are confidential and handled by the Barangay Lupon</p>
              </div>
            </div>
            <button @click="showNewComplaintModal = false"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="flex-1 overflow-y-auto scrollbar-thin px-6 py-5 space-y-5">

            <!-- Type -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-2">Complaint Type <span class="text-red-400">*</span></label>
              <div class="grid grid-cols-2 gap-3">
                <label v-for="t in complaintTypes" :key="t.value"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl border cursor-pointer transition-all"
                  :class="form.type === t.value ? 'border-[#3d4f7c] bg-[#3d4f7c]/5' : 'border-slate-200 bg-slate-50 hover:border-slate-300'">
                  <input type="radio" v-model="form.type" :value="t.value" class="hidden" />
                  <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                    :class="form.type === t.value ? 'border-[#3d4f7c] bg-[#3d4f7c]' : 'border-slate-300 bg-white'">
                    <span v-if="form.type === t.value" class="w-2 h-2 rounded-full bg-white"></span>
                  </div>
                  <span class="text-sm font-medium" :class="form.type === t.value ? 'text-[#3d4f7c]' : 'text-slate-600'">{{ t.label }}</span>
                </label>
              </div>
              <p v-if="errors.type" class="text-[10px] text-red-400 mt-1">{{ errors.type }}</p>
            </div>

            <!-- ── "Other" type description — shows only when Other is selected ── -->
            <Transition name="slide-down">
              <div v-if="form.type === 'other'">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                  Specify Complaint Type <span class="text-red-400">*</span>
                </label>
                <input v-model="form.other_type" type="text"
                  placeholder="e.g. Environmental concern, Public disturbance…"
                  class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                  :class="errors.other_type ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:border-[#3d4f7c] focus:ring-[#3d4f7c]/20'" />
                <p v-if="errors.other_type" class="text-[10px] text-red-400 mt-1">{{ errors.other_type }}</p>
              </div>
            </Transition>

            <!-- Title -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">Title / Subject <span class="text-red-400">*</span></label>
              <input v-model="form.title" type="text" placeholder="Brief title of your complaint or report"
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                :class="errors.title ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:border-[#3d4f7c] focus:ring-[#3d4f7c]/20'" />
              <p v-if="errors.title" class="text-[10px] text-red-400 mt-1">{{ errors.title }}</p>
            </div>

            <!-- Incident Date + Location -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Date of Incident <span class="text-red-400">*</span></label>
                <input v-model="form.incident_date" type="date" :max="today"
                  class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                  :class="errors.incident_date ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:border-[#3d4f7c] focus:ring-[#3d4f7c]/20'" />
                <p v-if="errors.incident_date" class="text-[10px] text-red-400 mt-1">{{ errors.incident_date }}</p>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Location of Incident <span class="text-red-400">*</span></label>
                <input v-model="form.location" type="text" placeholder="Purok / Sitio / Street"
                  class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                  :class="errors.location ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:border-[#3d4f7c] focus:ring-[#3d4f7c]/20'" />
                <p v-if="errors.location" class="text-[10px] text-red-400 mt-1">{{ errors.location }}</p>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description <span class="text-red-400">*</span></label>
              <textarea v-model="form.description" rows="4"
                placeholder="Provide a detailed description of the incident or complaint…"
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white resize-none"
                :class="errors.description ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:border-[#3d4f7c] focus:ring-[#3d4f7c]/20'"></textarea>
              <div class="flex items-center justify-between mt-1">
                <p v-if="errors.description" class="text-[10px] text-red-400">{{ errors.description }}</p>
                <p class="text-[10px] text-slate-400 ml-auto">{{ form.description.length }}/1000</p>
              </div>
            </div>

            <!-- ── Involved Person — optional ── -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                Person(s) Involved
                <span class="ml-1 text-[10px] font-normal text-slate-400">(Optional)</span>
              </label>
              <textarea v-model="form.involved_user" rows="2"
                placeholder="Name or description of the person(s) involved in the incident (if applicable)…"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all bg-slate-50 focus:bg-white resize-none"></textarea>
              <p class="text-[10px] text-slate-400 mt-1">Leave blank if not applicable or if identity is unknown.</p>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="flex-shrink-0 px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2">
            <button @click="showNewComplaintModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all cursor-pointer">
              Cancel
            </button>
            <button @click="submitComplaint" :disabled="submitting"
              class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all disabled:opacity-60 disabled:cursor-not-allowed active:scale-[0.98] cursor-pointer"
              style="background:#3d4f7c"
              @mouseenter="e => !submitting && (e.currentTarget.style.backgroundColor='#252b3b')"
              @mouseleave="e => (e.currentTarget.style.backgroundColor='#3d4f7c')">
              <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
              {{ submitting ? 'Submitting...' : 'Submit Complaint' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── View Complaint Modal ── -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[92vh]"
          style="box-shadow:0 40px 80px -12px rgba(0,0,0,0.22);">

          <div class="flex-shrink-0 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-1 h-8 rounded-full" style="background:#3d4f7c"></div>
              <div>
                <h3 class="text-sm font-bold text-slate-800">Complaint Details</h3>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">#C-{{ selectedComplaint ? String(selectedComplaint.id).padStart(5, '0') : '' }}</p>
              </div>
            </div>
            <button @click="showViewModal = false"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div v-if="selectedComplaint" class="flex-1 overflow-y-auto scrollbar-thin px-6 py-5 space-y-4">

            <!-- Status + Filed Date -->
            <div class="bg-slate-50 rounded-xl px-4 py-3 flex items-center justify-between">
              <span :class="statusBadge(selectedComplaint.status)"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="statusDot(selectedComplaint.status)"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="statusDotSolid(selectedComplaint.status)"></span>
                </span>
                {{ formatStatus(selectedComplaint.status) }}
              </span>
              <span class="text-xs text-slate-400">Filed: {{ formatDate(selectedComplaint.created_at) }}</span>
            </div>

            <!-- Case Info -->
            <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
              <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-[#3d4f7c]/10 flex items-center justify-center">
                  <svg class="w-3 h-3 text-[#3d4f7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Case Information</p>
              </div>
              <div class="divide-y divide-slate-100/80">
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Type</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">{{ typeLabel(selectedComplaint.type) }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Title</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right max-w-[220px]">{{ selectedComplaint.title }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Incident Date</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right">{{ formatDate(selectedComplaint.incident_date) }}</span>
                </div>
                <div class="px-4 py-2.5 flex justify-between items-center gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Location</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right max-w-[220px]">{{ selectedComplaint.location }}</span>
                </div>
                <!-- Show involved person if filled -->
                <div v-if="selectedComplaint.involved_user" class="px-4 py-2.5 flex justify-between items-start gap-2">
                  <span class="text-[11px] text-slate-400 flex-shrink-0">Person(s) Involved</span>
                  <span class="text-[11px] font-semibold text-slate-700 text-right max-w-[220px]">{{ selectedComplaint.involved_user }}</span>
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
                <p class="text-xs text-slate-600 leading-relaxed">{{ selectedComplaint.description }}</p>
              </div>
            </div>

            <!-- Status Notice -->
            <div v-if="selectedComplaint.status === 'pending'"
              class="rounded-2xl border border-blue-200/60 bg-blue-50/40 px-4 py-3.5 flex items-start gap-3">
              <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-xs text-blue-700 leading-relaxed">Your complaint is under review by the Barangay. You will be notified of any updates.</p>
            </div>
            <div v-else-if="selectedComplaint.status === 'approved'"
              class="rounded-2xl border border-emerald-200/60 bg-emerald-50/40 px-4 py-3.5 flex items-start gap-3">
              <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-xs text-emerald-700 leading-relaxed">Your complaint has been approved and is being processed by the Barangay Lupon.</p>
            </div>
            <div v-else-if="selectedComplaint.status === 'scheduled'"
              class="rounded-2xl border border-violet-200/60 bg-violet-50/40 px-4 py-3.5 flex items-start gap-3">
              <svg class="w-4 h-4 text-violet-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <p class="text-xs text-violet-700 leading-relaxed">A mediation session has been scheduled. Please check with the Barangay for the date and time.</p>
            </div>
            <div v-else-if="selectedComplaint.status === 'disapproved'"
              class="rounded-2xl border border-red-200/60 bg-red-50/40 px-4 py-3.5 flex items-start gap-3">
              <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-xs text-red-700 leading-relaxed">Your complaint was not approved. Please visit the Barangay Hall for more information.</p>
            </div>
            <div v-else-if="selectedComplaint.status === 'closed'"
              class="rounded-2xl border border-slate-200/60 bg-slate-50/40 px-4 py-3.5 flex items-start gap-3">
              <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <p class="text-xs text-slate-500 leading-relaxed">This case has been closed. Thank you for bringing this matter to the Barangay.</p>
            </div>

          </div>

          <div class="flex-shrink-0 px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end">
            <button @click="showViewModal = false"
              class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import Swal from 'sweetalert2'
import LuponCasesService from '@/services/Resident/LuponCasesService'

const loading    = ref(false)
const submitting = ref(false)
const showNewComplaintModal = ref(false)
const showViewModal         = ref(false)
const selectedComplaint     = ref(null)
const myComplaints          = ref([])
const currentPage           = ref(1)
const itemsPerPage          = 8
const today = new Date().toISOString().split('T')[0]

const complaintTypes = [
  { value: 'incident', label: 'Incident Report' },
  { value: 'dispute',  label: 'Dispute' },
  { value: 'report',   label: 'General Report' },
  { value: 'other',    label: 'Other' },
]

const form = ref({
  type:          'incident',
  other_type:    '',       // shown when type === 'other'
  title:         '',
  incident_date: '',
  location:      '',
  description:   '',
  involved_user: '',       // optional — person(s) involved
  confirmed:     false,
})

const errors = ref({})

// Clear other_type when switching away from 'other'
watch(() => form.value.type, (val) => {
  if (val !== 'other') form.value.other_type = ''
})

const totalPages = computed(() => Math.max(1, Math.ceil(myComplaints.value.length / itemsPerPage)))
const paginatedComplaints = computed(() => {
  const s = (currentPage.value - 1) * itemsPerPage
  return myComplaints.value.slice(s, s + itemsPerPage)
})

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
function formatStatus(s) {
  return { pending: 'Pending', approved: 'Approved', disapproved: 'Disapproved', scheduled: 'Scheduled', closed: 'Closed' }[s] || s
}
function statusBadge(s) {
  return {
    pending:     'bg-blue-50 text-blue-700 border border-blue-200',
    approved:    'bg-emerald-50 text-emerald-700 border border-emerald-200',
    disapproved: 'bg-red-50 text-red-700 border border-red-200',
    scheduled:   'bg-violet-50 text-violet-700 border border-violet-200',
    closed:      'bg-slate-100 text-slate-500 border border-slate-200',
  }[s] || 'bg-slate-100 text-slate-500 border border-slate-200'
}
function statusDot(s) {
  return { pending: 'bg-blue-400', approved: 'bg-emerald-400', disapproved: 'bg-red-400', scheduled: 'bg-violet-400', closed: 'bg-slate-400' }[s] || 'bg-slate-400'
}
function statusDotSolid(s) {
  return { pending: 'bg-blue-500', approved: 'bg-emerald-500', disapproved: 'bg-red-500', scheduled: 'bg-violet-500', closed: 'bg-slate-500' }[s] || 'bg-slate-500'
}

async function fetchMyComplaints(showLoading = true) {
  if (showLoading) loading.value = true
  try {
    const data = await LuponCasesService.getMyCases()
    myComplaints.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load your complaints.', confirmButtonColor: '#3d4f7c' })
  } finally {
    if (showLoading) loading.value = false
  }
}

function openNewComplaintModal() {
  form.value = { type: 'incident', other_type: '', title: '', incident_date: '', location: '', description: '', involved_user: '', confirmed: false }
  errors.value = {}
  showNewComplaintModal.value = true
}
function viewComplaint(c) { selectedComplaint.value = c; showViewModal.value = true }

function validateForm() {
  const e = {}
  if (!form.value.type)                              e.type          = 'Please select a complaint type'
  if (form.value.type === 'other' && !form.value.other_type?.trim())
                                                     e.other_type    = 'Please specify the complaint type'
  if (!form.value.title?.trim())                      e.title         = 'Title is required'
  if (!form.value.incident_date)                      e.incident_date = 'Incident date is required'
  if (!form.value.location?.trim())                   e.location      = 'Location is required'
  if (!form.value.description?.trim())                e.description   = 'Description is required'
  else if (form.value.description.trim().length < 20) e.description   = 'Please provide more detail (at least 20 characters)'
  errors.value = e
  return Object.keys(e).length === 0
}

async function submitComplaint() {
  if (!validateForm()) return
  submitting.value = true
  try {
    await LuponCasesService.createCase({
      type:          form.value.type,
      title:         form.value.type === 'other' && form.value.other_type
                       ? `[${form.value.other_type}] ${form.value.title}`
                       : form.value.title,
      incident_date: form.value.incident_date,
      location:      form.value.location,
      description:   form.value.description,
      // only send involved_user if filled
      ...(form.value.involved_user?.trim() && { involved_user: form.value.involved_user.trim() }),
    })
    showNewComplaintModal.value = false
    Swal.fire({ icon: 'success', title: 'Complaint Filed', text: 'Your complaint has been submitted successfully.', timer: 2000, showConfirmButton: false })
    await fetchMyComplaints(false)
  } catch (error) {
    const msg = error.response?.data?.message || 'Failed to submit complaint. Please try again.'
    Swal.fire({ icon: 'error', title: 'Submission Failed', text: msg, confirmButtonColor: '#3d4f7c' })
  } finally {
    submitting.value = false
  }
}

async function cancelComplaint(id) {
  const result = await Swal.fire({
    title: 'Cancel Complaint?', text: 'Are you sure you want to withdraw this complaint?',
    icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#d33', cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, cancel it', cancelButtonText: 'No, keep it',
  })
  if (!result.isConfirmed) return
  try {
    await LuponCasesService.deleteCase(id)
    await fetchMyComplaints(false)
    Swal.fire({ icon: 'success', title: 'Withdrawn', text: 'Your complaint has been cancelled.', timer: 1500, showConfirmButton: false })
  } catch {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to cancel complaint.', confirmButtonColor: '#3d4f7c' })
  }
}

onMounted(() => fetchMyComplaints())
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.slide-down-enter-active { transition: all 0.2s ease; }
.slide-down-leave-active { transition: all 0.15s ease; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; transform: translateY(-6px); }
</style>