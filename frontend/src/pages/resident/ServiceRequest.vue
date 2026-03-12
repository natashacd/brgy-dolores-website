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
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold text-slate-800 tracking-tight">Service Requests</h1>
            <p class="text-sm text-slate-500 mt-1">Request barangay services and documents</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Available Services Section -->
    <div class="mb-8">
      <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-[#3d4f7c] rounded-full"></span>
        Available Services
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 auto-rows-fr">
        <!-- BRGY ID Request -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-[#3d4f7c]/30 flex flex-col h-full max-w-full">
          <div class="h-2 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]"></div>
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-bold text-white bg-[#3d4f7c] px-2 py-1 rounded-full">ID</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">BRGY ID Request</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1 line-clamp-3">Apply for your Barangay ID or community identification card.</p>
            <button 
              @click="openRequestModal('BRGY ID Request')"
              class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl bg-[#3d4f7c] text-white hover:bg-[#252b3b] transition-all active:scale-95 mt-auto"
            >
              REQUEST
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- BRGY Business Clearance / Permit -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-[#3d4f7c]/30 flex flex-col h-full max-w-full">
          <div class="h-2 bg-gradient-to-r from-emerald-600 to-emerald-700"></div>
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-bold text-white bg-emerald-600 px-2 py-1 rounded-full">PERMIT</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">BRGY Business Clearance / Permit</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1 line-clamp-3">Apply or renew business clearance and permits in your barangay.</p>
            <button 
              @click="openRequestModal('BRGY Business Clearance / Permit')"
              class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition-all active:scale-95 mt-auto"
            >
              PERMIT
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- BRGY Certificate of Indigency -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-[#3d4f7c]/30 flex flex-col h-full max-w-full">
          <div class="h-2 bg-gradient-to-r from-amber-600 to-amber-700"></div>
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-bold text-white bg-amber-600 px-2 py-1 rounded-full">CERTIFICATE</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">BRGY Certificate of Indigency</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1 line-clamp-3">Request a Barangay Certificate of Indigency for official purposes.</p>
            <button 
              @click="openRequestModal('BRGY Certificate of Indigency')"
              class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl bg-amber-600 text-white hover:bg-amber-700 transition-all active:scale-95 mt-auto"
            >
              CERTIFICATE
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- BRGY Certificate of Residency -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-[#3d4f7c]/30 flex flex-col h-full max-w-full">
          <div class="h-2 bg-gradient-to-r from-blue-600 to-blue-700"></div>
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-bold text-white bg-blue-600 px-2 py-1 rounded-full">CERTIFICATE</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">BRGY Certificate of Residency</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1 line-clamp-3">Obtain your Certificate of Residency for official requirements.</p>
            <button 
              @click="openRequestModal('BRGY Certificate of Residency')"
              class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all active:scale-95 mt-auto"
            >
              CERTIFICATE
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Certifications for Specific Purposes -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-200 hover:border-[#3d4f7c]/30 flex flex-col h-full max-w-full md:col-span-2 lg:col-span-1">
          <div class="h-2 bg-gradient-to-r from-purple-600 to-purple-700"></div>
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-bold text-white bg-purple-600 px-2 py-1 rounded-full">MULTIPLE</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1">Certifications for Specific Purposes</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1 line-clamp-3">Request Barangay certifications for school, employment, or other purposes.</p>
            <button 
              @click="openRequestModal('Certifications for Specific Purposes')"
              class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl bg-purple-600 text-white hover:bg-purple-700 transition-all active:scale-95 mt-auto"
            >
              REQUEST
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Request Modal - Using Separated Component -->
    <ServiceRequestModal
      v-if="showModal"
      :service-type="selectedService"
      :submitting="submitting"
      @close="closeModal"
      @submit="handleModalSubmit"
    />

    <!-- View Request Modal - Using Separated Component -->
    <ViewServiceRequestModal
      v-if="showViewModal && selectedRequest"
      :request="selectedRequest"
      @close="closeViewModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Swal from 'sweetalert2';
import ResidentService from "@/services/Resident/ResidentService";
import ServiceRequestModal from "@/components/modals/admin/residents/ServiceRequestModal.vue";
import ViewServiceRequestModal from "@/components/modals/resident/ViewServiceRequestModal.vue";

const loading = ref(false);
const submitting = ref(false);
const showModal = ref(false);
const showViewModal = ref(false);
const selectedService = ref('');
const selectedRequest = ref(null);
const myRequests = ref([]);

const formatDate = (dateString) => {
  if (!dateString) return '—';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};

const openRequestModal = (service) => {
  selectedService.value = service;
  showModal.value = true;
};

const handleModalSubmit = async (formData) => {
  submitting.value = true;
  
  try {
    await ResidentService.createRequest(formData);
    
    await Swal.fire({
      icon: 'success',
      title: 'Request Submitted!',
      text: 'Your service request has been submitted successfully.',
      timer: 2000,
      showConfirmButton: false
    });
    
    showModal.value = false;
    await fetchMyRequests();
    
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: error.response?.data?.message || 'Failed to submit request. Please try again.'
    });
  } finally {
    submitting.value = false;
  }
};

const closeModal = () => {
  showModal.value = false;
};

const fetchMyRequests = async () => {
  try {
    myRequests.value = await ResidentService.getMyRequests();
  } catch (error) {
    console.error('Failed to fetch requests:', error);
  }
};

const viewRequest = (request) => {
  selectedRequest.value = request;
  showViewModal.value = true;
};

const closeViewModal = () => {
  showViewModal.value = false;
  selectedRequest.value = null;
};

const cancelRequest = async (id) => {
  const result = await Swal.fire({
    title: 'Cancel Request?',
    text: 'Are you sure you want to cancel this request?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3d4f7c',
    confirmButtonText: 'Yes, cancel',
    cancelButtonText: 'No, keep it'
  });
  
  if (result.isConfirmed) {
    try {
      await ResidentService.cancelRequest(id);
      await fetchMyRequests();
      Swal.fire({
        icon: 'success',
        title: 'Cancelled',
        text: 'Your request has been cancelled.',
        timer: 1500,
        showConfirmButton: false
      });
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to cancel request.'
      });
    }
  }
};

onMounted(() => {
  fetchMyRequests();
});
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-clamp: 1;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-clamp: 2;
}
</style>