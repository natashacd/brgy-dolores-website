<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
  >
    <div
      class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden"
    >
      <!-- Header -->
      <div
        class="px-6 py-4 border-b border-slate-100 flex items-center justify-between"
      >
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full" :class="statusColor"></div>
          <h2 class="text-base font-bold text-slate-800">Request Details</h2>
        </div>
        <button
          @click="close"
          class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer"
        >
          <svg
            width="16"
            height="16"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-5 space-y-4" v-if="request">
        <!-- Disapproved Banner -->
        <div
          v-if="request.status === 'disapproved'"
          class="bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-3"
        >
          <div
            class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0"
          >
            <svg
              class="w-4 h-4 text-red-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
              />
            </svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-red-700">
              Request Disapproved
            </p>
            <p class="text-xs text-red-600 mt-1">
              {{ request.remarks || "No remarks provided" }}
            </p>
          </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-xs text-slate-400">Service Type</p>
            <p class="text-sm font-medium text-slate-700">{{ request.type }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-400">Status</p>
            <span
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold mt-1"
              :class="statusClass"
            >
              <span
                class="w-1.5 h-1.5 rounded-full"
                :class="statusDotClass"
              ></span>
              {{ formatStatus(request.status) }}
            </span>
          </div>
          <div>
            <p class="text-xs text-slate-400">Request Date</p>
            <p class="text-sm text-slate-700">
              {{ formatDate(request.created_at) }}
            </p>
          </div>
          <div>
            <p class="text-xs text-slate-400">Preferred Date</p>
            <p class="text-sm text-slate-700">{{ request.preferred_date }}</p>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="request.notes && request.notes !== 'N/A'">
          <p class="text-xs text-slate-400">Additional Notes</p>
          <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg mt-1">
            {{ request.notes }}
          </p>
        </div>

        <!-- Remarks (non-disapproved, e.g. approved/completed) -->
        <div
          v-if="request.remarks && request.status !== 'disapproved'"
          class="bg-blue-50 border border-blue-200 rounded-lg p-3"
        >
          <p class="text-xs font-semibold text-blue-700 mb-1">
            Official Remarks
          </p>
          <p class="text-sm text-blue-800">{{ request.remarks }}</p>
        </div>
      </div>

      <!-- Footer -->
      <div
        class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex justify-end gap-2"
      >
        <button
          @click="close"
          class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  request: { type: Object, required: true },
});

const emit = defineEmits(["close", "resubmit"]);

const formatDate = (dateString) => {
  if (!dateString) return "—";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const formatStatus = (status) =>
  status.charAt(0).toUpperCase() + status.slice(1);

const statusColor = computed(
  () =>
    ({
      pending: "bg-amber-500",
      processing: "bg-blue-500",
      approved: "bg-emerald-500",
      disapproved: "bg-red-500",
      completed: "bg-green-500",
      cancelled: "bg-slate-400",
    })[props.request?.status] || "bg-slate-400",
);

const statusClass = computed(
  () =>
    ({
      pending: "bg-amber-50 text-amber-600 border border-amber-200",
      processing: "bg-blue-50 text-blue-600 border border-blue-200",
      approved: "bg-emerald-50 text-emerald-600 border border-emerald-200",
      disapproved: "bg-red-50 text-red-600 border border-red-200",
      completed: "bg-green-50 text-green-600 border border-green-200",
      cancelled: "bg-slate-50 text-slate-500 border border-slate-200",
    })[props.request?.status] || "bg-slate-100 text-slate-600",
);

const statusDotClass = computed(
  () =>
    ({
      pending: "bg-amber-500",
      processing: "bg-blue-500",
      approved: "bg-emerald-500",
      disapproved: "bg-red-500",
      completed: "bg-green-500",
      cancelled: "bg-slate-400",
    })[props.request?.status] || "bg-slate-500",
);

const close = () => emit("close");
</script>
