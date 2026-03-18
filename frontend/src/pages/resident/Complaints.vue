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
          <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[#3d4f7c]/10 text-[#3d4f7c]">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Complaints & Reports</h1>
            <p class="text-sm text-slate-500 mt-1">File and track incident reports or complaints</p>
          </div>
        </div>
      </div>
    </div>

    <!-- New Complaint Button -->
    <div class="mb-6 flex justify-end">
      <button
        @click="openNewComplaintModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#3d4f7c] text-white text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all shadow-sm hover:shadow-md"
      >
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        File New Complaint
      </button>
    </div>

    <!-- Complaints List -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
      <!-- Table Header -->
      <div class="px-6 py-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 rounded-full bg-white/20"></div>
            <h2 class="text-lg font-semibold text-white tracking-tight">My Complaints & Reports</h2>
          </div>
          <span class="text-xs text-white/50">
            {{ myComplaints.length }} record{{ myComplaints.length !== 1 ? 's' : '' }}
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
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Type</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Subject/Respondent</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Date Filed</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Assigned To</th>
              <th class="text-left px-6 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-24">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="complaint in paginatedComplaints" :key="complaint.id"
              class="border-b border-slate-50 hover:bg-[#3d4f7c]/5 transition-colors duration-100 group">

              <!-- Case Number -->
              <td class="px-6 py-4">
                <span class="text-xs font-mono font-medium text-[#3d4f7c] bg-[#3d4f7c]/10 px-2 py-1 rounded-lg">
                  #C-{{ String(complaint.id).padStart(5, '0') }}
                </span>
              </td>

              <!-- Type -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full" :class="{
                    'bg-red-100': complaint.complaint_type === 'incident',
                    'bg-amber-100': complaint.complaint_type === 'dispute',
                    'bg-blue-100': complaint.complaint_type === 'report',
                    'bg-purple-100': complaint.complaint_type === 'other'
                  }">
                    <svg v-if="complaint.complaint_type === 'incident'" class="w-3 h-3 m-1.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <svg v-else-if="complaint.complaint_type === 'dispute'" class="w-3 h-3 m-1.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <svg v-else class="w-3 h-3 m-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <span class="text-sm text-slate-600">{{ complaint.complaint_type_display }}</span>
                </div>
              </td>

              <!-- Subject/Respondent -->
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-slate-800">{{ complaint.subject }}</p>
                <p v-if="complaint.respondent_name" class="text-xs text-slate-400">
                  vs {{ complaint.respondent_name }}
                </p>
              </td>

              <!-- Date Filed -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ formatDate(complaint.created_at) }}
                </div>
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span :class="statusBadge(complaint.status)"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold">
                  <span class="w-1.5 h-1.5 rounded-full" :class="statusDot(complaint.status)"></span>
                  {{ formatStatus(complaint.status) }}
                </span>
              </td>

              <!-- Assigned To -->
              <td class="px-6 py-4">
                <span v-if="complaint.assigned_lupon" class="text-sm text-slate-600">
                  {{ complaint.assigned_lupon }}
                </span>
                <span v-else class="text-sm text-slate-400 italic">Pending assignment</span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <!-- View Details -->
                  <button @click="viewComplaint(complaint)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>

                  <!-- Cancel (only for pending) -->
                  <button v-if="complaint.status === 'pending'"
                    @click="cancelComplaint(complaint.id)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer"
                    title="Cancel Complaint">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!loading && myComplaints.length === 0">
              <td colspan="7" class="py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-600">No complaints filed yet</p>
                    <p class="text-xs text-slate-400 mt-0.5">Click the button above to file your first complaint or report</p>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && myComplaints.length > itemsPerPage"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-slate-400 font-mono">
          {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, myComplaints.length) }}
          <span class="text-slate-300 mx-1">/</span>
          {{ myComplaints.length }} results
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
      <div v-else-if="!loading && myComplaints.length > 0"
        class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60">
        <p class="text-xs text-slate-400 font-mono">
          Showing <span class="font-medium text-slate-700">{{ myComplaints.length }}</span> record{{ myComplaints.length !== 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- New Complaint Modal -->
    <Transition name="modal">
      <div v-if="showNewComplaintModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showNewComplaintModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 rounded-full bg-[#3d4f7c]"></div>
                <h3 class="text-base font-semibold text-slate-800">File New Complaint/Report</h3>
              </div>
              <button @click="showNewComplaintModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 max-h-[70vh] overflow-y-auto scrollbar-thin">
              <form @submit.prevent="submitComplaint" class="space-y-5">
                <!-- Complaint Type -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-semibold text-slate-600">Complaint Type <span class="text-red-400">*</span></label>
                  <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 px-4 py-3 rounded-xl border cursor-pointer"
                      :class="form.complaint_type === 'incident' ? 'border-[#3d4f7c] bg-[#3d4f7c]/5' : 'border-slate-200 bg-slate-50'">
                      <input type="radio" v-model="form.complaint_type" value="incident" class="hidden" />
                      <div class="w-5 h-5 rounded-full border flex items-center justify-center" 
                        :class="form.complaint_type === 'incident' ? 'border-[#3d4f7c] bg-[#3d4f7c]' : 'border-slate-300'">
                        <span v-if="form.complaint_type === 'incident'" class="w-2 h-2 rounded-full bg-white"></span>
                      </div>
                      <span class="text-sm">Incident Report</span>
                    </label>
                    <label class="flex items-center gap-2 px-4 py-3 rounded-xl border cursor-pointer"
                      :class="form.complaint_type === 'dispute' ? 'border-[#3d4f7c] bg-[#3d4f7c]/5' : 'border-slate-200 bg-slate-50'">
                      <input type="radio" v-model="form.complaint_type" value="dispute" class="hidden" />
                      <div class="w-5 h-5 rounded-full border flex items-center justify-center" 
                        :class="form.complaint_type === 'dispute' ? 'border-[#3d4f7c] bg-[#3d4f7c]' : 'border-slate-300'">
                        <span v-if="form.complaint_type === 'dispute'" class="w-2 h-2 rounded-full bg-white"></span>
                      </div>
                      <span class="text-sm">Dispute</span>
                    </label>
                    <label class="flex items-center gap-2 px-4 py-3 rounded-xl border cursor-pointer"
                      :class="form.complaint_type === 'report' ? 'border-[#3d4f7c] bg-[#3d4f7c]/5' : 'border-slate-200 bg-slate-50'">
                      <input type="radio" v-model="form.complaint_type" value="report" class="hidden" />
                      <div class="w-5 h-5 rounded-full border flex items-center justify-center" 
                        :class="form.complaint_type === 'report' ? 'border-[#3d4f7c] bg-[#3d4f7c]' : 'border-slate-300'">
                        <span v-if="form.complaint_type === 'report'" class="w-2 h-2 rounded-full bg-white"></span>
                      </div>
                      <span class="text-sm">General Report</span>
                    </label>
                    <label class="flex items-center gap-2 px-4 py-3 rounded-xl border cursor-pointer"
                      :class="form.complaint_type === 'other' ? 'border-[#3d4f7c] bg-[#3d4f7c]/5' : 'border-slate-200 bg-slate-50'">
                      <input type="radio" v-model="form.complaint_type" value="other" class="hidden" />
                      <div class="w-5 h-5 rounded-full border flex items-center justify-center" 
                        :class="form.complaint_type === 'other' ? 'border-[#3d4f7c] bg-[#3d4f7c]' : 'border-slate-300'">
                        <span v-if="form.complaint_type === 'other'" class="w-2 h-2 rounded-full bg-white"></span>
                      </div>
                      <span class="text-sm">Other</span>
                    </label>
                  </div>
                  <span v-if="errors.complaint_type" class="text-[10px] text-red-400">{{ errors.complaint_type }}</span>
                </div>

                <!-- Subject -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-semibold text-slate-600">Subject/Title <span class="text-red-400">*</span></label>
                  <input v-model="form.subject" type="text" placeholder="Brief title of your complaint"
                    class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
                    :class="errors.subject ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'" />
                  <span v-if="errors.subject" class="text-[10px] text-red-400">{{ errors.subject }}</span>
                </div>

                <!-- Incident Date -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-semibold text-slate-600">Date of Incident <span class="text-red-400">*</span></label>
                  <input v-model="form.incident_date" type="date" :max="today"
                    class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
                    :class="errors.incident_date ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'" />
                  <span v-if="errors.incident_date" class="text-[10px] text-red-400">{{ errors.incident_date }}</span>
                </div>

                <!-- Location -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-semibold text-slate-600">Location of Incident <span class="text-red-400">*</span></label>
                  <input v-model="form.location" type="text" placeholder="Where did this happen?"
                    class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
                    :class="errors.location ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'" />
                  <span v-if="errors.location" class="text-[10px] text-red-400">{{ errors.location }}</span>
                </div>

                <!-- Description -->
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-semibold text-slate-600">Description <span class="text-red-400">*</span></label>
                  <textarea v-model="form.description" rows="4"
                    placeholder="Provide detailed information about the incident or complaint..."
                    class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                    :class="errors.description ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"></textarea>
                  <span v-if="errors.description" class="text-[10px] text-red-400">{{ errors.description }}</span>
                </div>             
              </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2">
              <button @click="showNewComplaintModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                Cancel
              </button>
              <button @click="submitComplaint" :disabled="submitting"
                class="px-5 py-2 text-sm font-semibold text-white bg-[#3d4f7c] rounded-xl hover:bg-[#252b3b] transition-all flex items-center gap-2 disabled:opacity-60">
                <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span>{{ submitting ? 'Submitting...' : 'Submit Complaint' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- View Complaint Modal -->
    <Transition name="modal">
      <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showViewModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 rounded-full" :class="{
                  'bg-amber-500': selectedComplaint?.status === 'pending',
                  'bg-blue-500': selectedComplaint?.status === 'processing',
                  'bg-red-500': selectedComplaint?.status === 'escalated',
                  'bg-emerald-500': selectedComplaint?.status === 'resolved'
                }"></div>
                <h3 class="text-base font-semibold text-slate-800">Complaint Details</h3>
                <span class="px-2 py-0.5 bg-[#3d4f7c]/10 text-[#3d4f7c] text-xs rounded-full font-mono">
                  #C-{{ selectedComplaint ? String(selectedComplaint.id).padStart(5, '0') : '' }}
                </span>
              </div>
              <button @click="showViewModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <div v-if="selectedComplaint" class="px-6 py-5 space-y-4">
              <!-- Status Banner -->
              <div class="bg-slate-50 rounded-lg p-3">
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="statusBadge(selectedComplaint.status)">
                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDot(selectedComplaint.status)"></span>
                    {{ formatStatus(selectedComplaint.status) }}
                  </span>
                  <span class="text-xs text-slate-400">Filed: {{ formatDate(selectedComplaint.created_at) }}</span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-xs text-slate-400">Complaint Type</p>
                  <p class="text-sm font-medium text-slate-700">{{ selectedComplaint.complaint_type_display }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Incident Date</p>
                  <p class="text-sm text-slate-700">{{ formatDate(selectedComplaint.incident_date) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Location</p>
                  <p class="text-sm text-slate-700">{{ selectedComplaint.location }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-400">Assigned To</p>
                  <p class="text-sm text-slate-700">{{ selectedComplaint.assigned_lupon || 'Pending assignment' }}</p>
                </div>
              </div>

              <div>
                <p class="text-xs text-slate-400">Subject</p>
                <p class="text-sm font-medium text-slate-700">{{ selectedComplaint.subject }}</p>
              </div>

              <div v-if="selectedComplaint.respondent_name">
                <p class="text-xs text-slate-400">Respondent</p>
                <p class="text-sm text-slate-700">{{ selectedComplaint.respondent_name }}</p>
              </div>

              <div>
                <p class="text-xs text-slate-400">Description</p>
                <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg mt-1">{{ selectedComplaint.description }}</p>
              </div>

              <div v-if="selectedComplaint.remarks">
                <p class="text-xs text-slate-400">Official Remarks</p>
                <p class="text-sm text-slate-700 bg-blue-50 p-3 rounded-lg mt-1">{{ selectedComplaint.remarks }}</p>
              </div>

              <div v-if="selectedComplaint.documents && selectedComplaint.documents.length > 0">
                <p class="text-xs text-slate-400 mb-2">Supporting Documents</p>
                <div class="space-y-2">
                  <a v-for="(doc, idx) in selectedComplaint.documents" :key="idx"
                    :href="doc.url" target="_blank"
                    class="flex items-center gap-2 text-sm text-blue-600 hover:underline bg-blue-50 p-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    {{ doc.name }}
                  </a>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end">
              <button @click="showViewModal = false"
                class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import Swal from 'sweetalert2';
// State
const loading = ref(false);
const submitting = ref(false);
const showNewComplaintModal = ref(false);
const showViewModal = ref(false);
const selectedComplaint = ref(null);
const myComplaints = ref([]);
const currentPage = ref(1);
const itemsPerPage = 8;
const fileInput = ref(null);
const uploadedFiles = ref([]);

// Form
const form = ref({
  complaint_type: 'incident',
  subject: '',
  respondent_name: '',
  incident_date: '',
  location: '',
  description: '',
  confirmed: false
});

const errors = ref({});

// Today's date for max attribute
const today = computed(() => {
  const date = new Date();
  return date.toISOString().split('T')[0];
});

// Computed counts
const pendingCount = computed(() => {
  return myComplaints.value.filter(c => c.status === 'pending').length;
});

const processingCount = computed(() => {
  return myComplaints.value.filter(c => c.status === 'processing').length;
});

const resolvedCount = computed(() => {
  return myComplaints.value.filter(c => ['resolved', 'closed'].includes(c.status)).length;
});

// Pagination
const totalPages = computed(() => Math.max(1, Math.ceil(myComplaints.value.length / itemsPerPage)));
const paginatedComplaints = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return myComplaints.value.slice(start, start + itemsPerPage);
});

// Helper functions
const formatDate = (dateString) => {
  if (!dateString) return '—';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};

const formatStatus = (status) => {
  const statusMap = {
    'pending': 'Pending',
    'processing': 'Processing',
    'escalated': 'Escalated',
    'resolved': 'Resolved',
    'closed': 'Closed'
  };
  return statusMap[status] || status;
};

const statusBadge = (status) => {
  return {
    'pending': 'bg-amber-50 text-amber-600 border border-amber-200',
    'processing': 'bg-blue-50 text-blue-600 border border-blue-200',
    'escalated': 'bg-red-50 text-red-600 border border-red-200',
    'resolved': 'bg-emerald-50 text-emerald-600 border border-emerald-200',
    'closed': 'bg-slate-100 text-slate-600 border border-slate-200'
  }[status] || 'bg-slate-100 text-slate-600';
};

const statusDot = (status) => {
  return {
    'pending': 'bg-amber-500',
    'processing': 'bg-blue-500',
    'escalated': 'bg-red-500',
    'resolved': 'bg-emerald-500',
    'closed': 'bg-slate-500'
  }[status] || 'bg-slate-500';
};

// File upload
const triggerFileInput = () => {
  fileInput.value?.click();
};

const handleFileUpload = (event) => {
  const files = Array.from(event.target.files);
  uploadedFiles.value = [...uploadedFiles.value, ...files];
};

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
};

// Modal actions
const openNewComplaintModal = () => {
  form.value = {
    complaint_type: 'incident',
    subject: '',
    respondent_name: '',
    incident_date: '',
    location: '',
    description: '',
    confirmed: false
  };
  errors.value = {};
  uploadedFiles.value = [];
  showNewComplaintModal.value = true;
};

const viewComplaint = (complaint) => {
  selectedComplaint.value = complaint;
  showViewModal.value = true;
};

// Validate form
const validateForm = () => {
  const newErrors = {};
  
  if (!form.value.complaint_type) newErrors.complaint_type = 'Please select a complaint type';
  if (!form.value.subject) newErrors.subject = 'Subject is required';
  if (!form.value.incident_date) newErrors.incident_date = 'Incident date is required';
  if (!form.value.location) newErrors.location = 'Location is required';
  if (!form.value.description) newErrors.description = 'Description is required';
  if (!form.value.confirmed) newErrors.confirmed = 'You must confirm the information is true';
  
  errors.value = newErrors;
  return Object.keys(newErrors).length === 0;
};

// Submit complaint
const submitComplaint = async () => {
  if (!validateForm()) return;
  
  submitting.value = true;
  
  try {
    const formData = new FormData();
    formData.append('complaint_type', form.value.complaint_type);
    formData.append('subject', form.value.subject);
    formData.append('incident_date', form.value.incident_date);
    formData.append('location', form.value.location);
    formData.append('description', form.value.description);
    
    if (form.value.respondent_name) {
      formData.append('respondent_name', form.value.respondent_name);
    }
    
    // Append files
    uploadedFiles.value.forEach(file => {
      formData.append('documents[]', file);
    });
    
    await ComplaintService.createComplaint(formData);
    
    Swal.fire({
      icon: 'success',
      title: 'Complaint Filed',
      text: 'Your complaint has been submitted successfully.',
      timer: 2000,
      showConfirmButton: false
    });
    
    showNewComplaintModal.value = false;
    await fetchMyComplaints();
    
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: error.response?.data?.message || 'Failed to submit complaint. Please try again.'
    });
  } finally {
    submitting.value = false;
  }
};

// Cancel complaint
const cancelComplaint = async (id) => {
  const result = await Swal.fire({
    title: 'Cancel Complaint?',
    text: 'Are you sure you want to cancel this complaint?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#f59e0b',
    confirmButtonText: 'Yes, cancel',
    cancelButtonText: 'No, keep it'
  });
  
  if (result.isConfirmed) {
    try {
      await ComplaintService.cancelComplaint(id);
      await fetchMyComplaints();
      Swal.fire({
        icon: 'success',
        title: 'Cancelled',
        text: 'Your complaint has been cancelled.',
        timer: 1500,
        showConfirmButton: false
      });
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to cancel complaint.'
      });
    }
  }
};

// Fetch complaints
const fetchMyComplaints = async () => {
  loading.value = true;
  try {
    myComplaints.value = await ComplaintService.getMyComplaints();
  } catch (error) {
    console.error('Failed to fetch complaints:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchMyComplaints();
});
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