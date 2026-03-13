<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full" :class="statusColor"></div>
          <h2 class="text-base font-bold text-slate-800">Request Details</h2>
        </div>
        <button @click="close" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="px-6 py-5 space-y-4" v-if="request">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-xs text-slate-400">Service Type</p>
            <p class="text-sm font-medium text-slate-700">{{ request.service_type }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-400">Status</p>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold mt-1" :class="statusClass">
              <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass"></span>
              {{ formatStatus(request.status) }}
            </span>
          </div>
          <div>
            <p class="text-xs text-slate-400">Request Date</p>
            <p class="text-sm text-slate-700">{{ formatDate(request.created_at) }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-400">Preferred Date</p>
            <p class="text-sm text-slate-700">{{ formatDate(request.preferred_date) }}</p>
          </div>
        </div>
        
        <div v-if="request.purpose">
          <p class="text-xs text-slate-400">Purpose</p>
          <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg mt-1">{{ request.purpose }}</p>
        </div>
        
        <div v-if="request.notes">
          <p class="text-xs text-slate-400">Additional Notes</p>
          <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg mt-1">{{ request.notes }}</p>
        </div>

        <div v-if="request.remarks" class="bg-blue-50 border border-blue-200 rounded-lg p-3">
          <p class="text-xs font-semibold text-blue-700 mb-1">Official Remarks</p>
          <p class="text-sm text-blue-800">{{ request.remarks }}</p>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end">
        <button @click="close" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  request: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close'])

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
  return status.charAt(0).toUpperCase() + status.slice(1);
};

const statusColor = computed(() => {
  const colors = {
    'pending': 'bg-amber-500',
    'processing': 'bg-blue-500',
    'approved': 'bg-emerald-500',
    'rejected': 'bg-red-500',
    'completed': 'bg-green-500'
  };
  return colors[props.request?.status] || 'bg-gray-500';
});

const statusClass = computed(() => {
  const classes = {
    'pending': 'bg-amber-50 text-amber-600 border border-amber-200',
    'processing': 'bg-blue-50 text-blue-600 border border-blue-200',
    'approved': 'bg-emerald-50 text-emerald-600 border border-emerald-200',
    'rejected': 'bg-red-50 text-red-600 border border-red-200',
    'completed': 'bg-green-50 text-green-600 border border-green-200'
  };
  return classes[props.request?.status] || 'bg-slate-100 text-slate-600';
});

const statusDotClass = computed(() => {
  const dots = {
    'pending': 'bg-amber-500',
    'processing': 'bg-blue-500',
    'approved': 'bg-emerald-500',
    'rejected': 'bg-red-500',
    'completed': 'bg-green-500'
  };
  return dots[props.request?.status] || 'bg-slate-500';
});

const close = () => {
  emit('close');
};
</script>