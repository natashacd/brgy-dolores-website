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
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Track and manage your service requests</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Disapproved Requests Alert Banner -->
    <div v-if="disapprovedCount > 0" class="mb-6">
      <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-amber-800">
              You have {{ disapprovedCount }} disapproved request{{ disapprovedCount > 1 ? 's' : '' }}
            </p>
            <p class="text-xs text-amber-600">Please review the remarks and resubmit with corrections.</p>
          </div>
        </div>
        <button
          @click="scrollToDisapproved"
          class="text-sm font-semibold text-amber-700 bg-amber-100 hover:bg-amber-200 px-4 py-2 rounded-lg transition-colors cursor-pointer"
        >
          View Disapproved
        </button>
      </div>
    </div>

    <!-- My Requests Table -->
    <div>
      <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-[#3d4f7c] rounded-full"></span>
        My Requests
      </h2>

      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Service Type</th>
                <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Request Date</th>
                <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Preferred Date</th>
                <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Status</th>
                <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

              <!-- Loading State -->
              <tr v-if="loading">
                <td colspan="5" class="px-6 py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-slate-200 border-t-[#3d4f7c] rounded-full animate-spin"></div>
                    <p class="text-sm text-slate-500">Loading your requests...</p>
                  </div>
                </td>
              </tr>

              <!-- Rows -->
              <tr
                v-else
                v-for="request in myRequests"
                :key="request.id"
                class="hover:bg-slate-50 transition-colors"
                :id="request.status === 'disapproved' ? 'disapproved-section' : undefined"
              >
                <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ request.type }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(request.created_at) }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ request.preferred_date }}</td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="{
                      'bg-amber-50 text-amber-600 border border-amber-200':   request.status === 'pending',
                      'bg-blue-50 text-blue-600 border border-blue-200':      request.status === 'processing',
                      'bg-emerald-50 text-emerald-600 border border-emerald-200': request.status === 'approved',
                      'bg-red-50 text-red-600 border border-red-200':         request.status === 'disapproved',
                      'bg-green-50 text-green-600 border border-green-200':   request.status === 'completed',
                      'bg-slate-50 text-slate-500 border border-slate-200':   request.status === 'cancelled',
                    }"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full"
                      :class="{
                        'bg-amber-500':   request.status === 'pending',
                        'bg-blue-500':    request.status === 'processing',
                        'bg-emerald-500': request.status === 'approved',
                        'bg-red-500':     request.status === 'disapproved',
                        'bg-green-500':   request.status === 'completed',
                        'bg-slate-400':   request.status === 'cancelled',
                      }"
                    ></span>
                    {{ request.status.charAt(0).toUpperCase() + request.status.slice(1) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <!-- View -->
                    <button
                      @click="viewRequest(request)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20 hover:bg-[#3d4f7c] hover:text-white hover:border-[#3d4f7c] hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group"
                      title="View Details"
                    >
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>

                    <!-- Resubmit (disapproved only) -->
                    <button
                      v-if="request.status === 'disapproved'"
                      @click="openResubmitModal(request)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-600 hover:text-white hover:border-amber-600 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group"
                      title="Resubmit Request"
                    >
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                      </svg>
                    </button>

                    <!-- Cancel (pending only) -->
                    <button
                      v-if="request.status === 'pending'"
                      @click="cancelRequest(request.id)"
                      class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-md active:scale-95 transition-all duration-150 cursor-pointer group"
                      title="Cancel Request"
                    >
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="!loading && myRequests.length === 0">
                <td colspan="5" class="px-6 py-16 text-center">
                  <div class="flex flex-col items-center gap-2">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-slate-500">No service requests yet</p>
                    <p class="text-xs text-slate-400">Go to Service Requests to submit a request</p>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <ViewRequestModal
      v-if="showViewModal"
      :request="selectedRequest"
      @close="closeViewModal"
      @resubmit="openResubmitFromModal"
    />

    <ResubmitModal
      v-if="showResubmitModal"
      :request="resubmitRequestData"
      :submitting="resubmitting"
      @close="closeResubmitModal"
      @submit="handleResubmit"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Swal from "sweetalert2";
import ServiceRequestService from "@/services/Resident/ServiceRequest";
import ViewRequestModal from "@/components/modals/resident/ViewRequestModal.vue";
import ResubmitModal from "@/components/modals/resident/ResubmitModal.vue";

const loading             = ref(false);
const myRequests          = ref([]);
const showViewModal       = ref(false);
const showResubmitModal   = ref(false);
const selectedRequest     = ref(null);
const resubmitRequestData = ref(null);
const resubmitting        = ref(false);

const disapprovedCount = computed(() =>
  myRequests.value.filter((r) => r.status === "disapproved").length
);

const formatDate = (dateString) => {
  if (!dateString) return "—";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const scrollToDisapproved = () => {
  const el = document.getElementById("disapproved-section");
  if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
};

// ── Fetch ─────────────────────────────────────────────────────────
const fetchMyRequests = async (showLoading = true) => {
  if (showLoading) loading.value = true;
  try {
    myRequests.value = await ServiceRequestService.getMyRequests();
  } catch {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Failed to load your requests.",
      confirmButtonColor: "#3d4f7c",
    });
  } finally {
    if (showLoading) loading.value = false;
  }
};

// ── View ──────────────────────────────────────────────────────────
const viewRequest = (request) => {
  selectedRequest.value = request;
  showViewModal.value   = true;
};

const closeViewModal = () => {
  showViewModal.value   = false;
  selectedRequest.value = null;
};

// ── Resubmit ──────────────────────────────────────────────────────
const openResubmitModal = (request) => {
  resubmitRequestData.value = request;
  showResubmitModal.value   = true;
};

const openResubmitFromModal = (request) => {
  closeViewModal();
  resubmitRequestData.value = request;
  showResubmitModal.value   = true;
};

const closeResubmitModal = () => {
  showResubmitModal.value   = false;
  resubmitRequestData.value = null;
};

const handleResubmit = async (formData) => {
  resubmitting.value = true;
  try {
    await ServiceRequestService.createRequest(formData);
    await Swal.fire({
      icon: "success",
      title: "Resubmitted!",
      text: "Your request has been resubmitted successfully.",
      timer: 2000,
      showConfirmButton: false,
    });
    closeResubmitModal();
    await fetchMyRequests(false);
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: error.response?.data?.message || "Failed to resubmit request.",
      confirmButtonColor: "#3d4f7c",
    });
  } finally {
    resubmitting.value = false;
  }
};

// ── Cancel ────────────────────────────────────────────────────────
const cancelRequest = async (id) => {
  const result = await Swal.fire({
    title: "Cancel Request?",
    text: "Are you sure you want to cancel this request?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3d4f7c",
    confirmButtonText: "Yes, cancel",
    cancelButtonText: "No, keep it",
  });

  if (result.isConfirmed) {
    try {
      await ServiceRequestService.cancelRequest(id);
      await fetchMyRequests(false);
      Swal.fire({
        icon: "success",
        title: "Cancelled",
        text: "Your request has been cancelled.",
        timer: 1500,
        showConfirmButton: false,
      });
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Failed to cancel request.",
        confirmButtonColor: "#3d4f7c",
      });
    }
  }
};

onMounted(() => fetchMyRequests());
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>