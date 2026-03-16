<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden">

      <!-- Modal Header -->
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full" :style="{ background: modalColor }"></div>
          <div>
            <h2 class="text-base font-bold text-slate-800">New Service Request</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ serviceType }}</p>
          </div>
        </div>
        <button
          @click="close"
          class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 max-h-[60vh] overflow-y-auto scrollbar-thin">
        <div class="space-y-5">

          <!-- Service Type (Read-only) -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Service Type</label>
            <input
              type="text"
              :value="serviceType"
              readonly
              class="border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-sm"
            />
          </div>

          <!-- Document Upload -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">
              Supporting Document / Photo <span class="text-red-400">*</span>
            </label>

            <div
              class="w-full border-2 border-dashed border-[#3d4f7c]/30 rounded-xl p-3 bg-[#3d4f7c]/5 flex items-center gap-3 transition-all hover:border-[#3d4f7c]/50 hover:bg-[#3d4f7c]/10"
            >
              <!-- Preview -->
              <template v-if="imagePreview">
                <div class="relative flex-shrink-0">
                  <div class="w-16 h-16 rounded-lg overflow-hidden border-2 border-white shadow-sm">
                    <img :src="imagePreview" class="w-full h-full object-cover" alt="Preview" />
                  </div>
                  <button
                    type="button"
                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all shadow-sm cursor-pointer"
                    @click="removeImage"
                  >
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-medium text-slate-700 truncate">{{ imageFile?.name || 'File uploaded' }}</p>
                  <p class="text-[10px] text-slate-400 mt-0.5">Click remove to change</p>
                </div>
              </template>

              <!-- PDF uploaded (no preview) -->
              <template v-else-if="imageFile && imageFile.type === 'application/pdf'">
                <div class="w-16 h-16 rounded-lg bg-red-50 border border-red-200 flex items-center justify-center flex-shrink-0">
                  <svg width="24" height="24" fill="none" stroke="#ef4444" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-medium text-slate-700 truncate">{{ imageFile.name }}</p>
                  <p class="text-[10px] text-slate-400 mt-0.5">PDF document</p>
                </div>
                <button
                  type="button"
                  class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all cursor-pointer flex-shrink-0"
                  @click="removeImage"
                >
                  <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </template>

              <!-- Empty state -->
              <template v-else>
                <div class="w-16 h-16 rounded-lg bg-[#3d4f7c]/10 flex items-center justify-center flex-shrink-0">
                  <svg width="24" height="24" fill="none" stroke="#3d4f7c" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-xs font-medium text-slate-700">Upload a photo or document</p>
                  <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG or PDF (Max 4MB)</p>
                </div>
                <div class="flex flex-col gap-1.5">
                  <button
                    type="button"
                    @click="triggerFileInput"
                    class="inline-flex items-center justify-center gap-1 px-2 py-1.5 bg-[#3d4f7c] text-white text-[10px] font-semibold rounded-lg hover:bg-[#252b3b] transition-all whitespace-nowrap cursor-pointer"
                  >
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Browse
                  </button>
                  <button
                    type="button"
                    @click="openCamera"
                    class="inline-flex items-center justify-center gap-1 px-2 py-1.5 bg-emerald-600 text-white text-[10px] font-semibold rounded-lg hover:bg-emerald-700 transition-all whitespace-nowrap cursor-pointer"
                  >
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Camera
                  </button>
                </div>
              </template>
            </div>

            <!-- Info note -->
            <div class="text-[10px] font-medium text-amber-600 bg-amber-50 p-1.5 rounded-lg border border-amber-200">
              <span class="flex items-start gap-1">
                <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><span class="font-bold">Proof of Identity:</span> Valid ID, selfie with ID, or clear face photo</span>
              </span>
            </div>

            <!-- Hidden inputs -->
            <input ref="fileInput" @change="handleFileUpload" type="file" accept="image/*,application/pdf" class="hidden"/>
            <input ref="cameraInput" @change="handleFileUpload" type="file" accept="image/*" capture="environment" class="hidden"/>

            <span v-if="errors.document" class="text-[10px] text-red-400">{{ errors.document }}</span>
          </div>

          <!-- Preferred Date -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">
              Preferred Date <span class="text-red-400">*</span>
            </label>
            <input
              type="date"
              v-model="formData.preferred_date"
              :min="minDate"
              @change="validateDate"
              class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
              :class="errors.preferred_date ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"
            />
            <span v-if="errors.preferred_date" class="text-[10px] text-red-400">{{ errors.preferred_date }}</span>
            <span class="text-[10px] text-slate-400">Weekdays only (Monday – Friday)</span>
          </div>

          <!-- Notes -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Additional Notes <span class="text-slate-400">(Optional)</span></label>
            <textarea
              v-model="formData.notes"
              rows="2"
              placeholder="Any additional information or special requests..."
              class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300 resize-none"
            ></textarea>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2">
        <button
          @click="close"
          class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer"
        >
          Cancel
        </button>
        <button
          @click="submit"
          :disabled="submitting"
          class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-all flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
          :style="{ background: modalColor }"
          @mouseenter="e => { if (!e.currentTarget.disabled) e.currentTarget.style.background = modalHoverColor }"
          @mouseleave="e => e.currentTarget.style.background = modalColor"
        >
          <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
          </svg>
          <span>{{ submitting ? 'Submitting...' : 'Submit Request' }}</span>
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  serviceType: { type: String, required: true },
  submitting:  { type: Boolean, default: false },
});

const emit = defineEmits(["close", "submit"]);

const formData = ref({
  preferred_date: "",
  notes: "",
});

const imageFile    = ref(null);
const imagePreview = ref(null);
const fileInput    = ref(null);
const cameraInput  = ref(null);
const errors       = ref({});

// ── Modal color per service type ─────────────────────────────────
const modalColor = computed(() => {
  if (props.serviceType.includes("ID"))       return "#3d4f7c";
  if (props.serviceType.includes("Business")) return "#059669";
  if (props.serviceType.includes("Indigency")) return "#d97706";
  if (props.serviceType.includes("Residency")) return "#2563eb";
  return "#7c3aed";
});

const modalHoverColor = computed(() => {
  if (props.serviceType.includes("ID"))        return "#252b3b";
  if (props.serviceType.includes("Business"))  return "#047857";
  if (props.serviceType.includes("Indigency")) return "#b45309";
  if (props.serviceType.includes("Residency")) return "#1d4ed8";
  return "#6d28d9";
});

// ── Min date (today) ─────────────────────────────────────────────
const minDate = computed(() => {
  const today = new Date();
  return today.toISOString().split("T")[0];
});

// ── Date validation (no weekends) ────────────────────────────────
const validateDate = () => {
  if (!formData.value.preferred_date) return;
  const date = new Date(formData.value.preferred_date);
  const day  = date.getDay();
  if (day === 0 || day === 6) {
    errors.value.preferred_date = "Weekends are not available. Please select a weekday.";
    formData.value.preferred_date = "";
  } else {
    errors.value.preferred_date = "";
  }
};

// ── File upload ──────────────────────────────────────────────────
const triggerFileInput = () => fileInput.value?.click();
const openCamera       = () => cameraInput.value?.click();

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (file.size > 4 * 1024 * 1024) {
    errors.value.document = "File size must be less than 4MB.";
    return;
  }

  const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "application/pdf"];
  if (!validTypes.includes(file.type)) {
    errors.value.document = "Only JPG, PNG, GIF or PDF files are allowed.";
    return;
  }

  imageFile.value      = file;
  errors.value.document = "";

  if (file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => { imagePreview.value = e.target.result; };
    reader.readAsDataURL(file);
  } else {
    imagePreview.value = null;
  }
};

const removeImage = () => {
  imageFile.value    = null;
  imagePreview.value = null;
  if (fileInput.value)  fileInput.value.value  = "";
  if (cameraInput.value) cameraInput.value.value = "";
};

// ── Validation ───────────────────────────────────────────────────
const validateForm = () => {
  const newErrors = {};

  if (!imageFile.value) {
    newErrors.document = "Supporting document or photo is required.";
  }

  if (!formData.value.preferred_date) {
    newErrors.preferred_date = "Preferred date is required.";
  } else {
    const day = new Date(formData.value.preferred_date).getDay();
    if (day === 0 || day === 6) {
      newErrors.preferred_date = "Weekends are not available. Please select a weekday.";
    }
  }

  errors.value = newErrors;
  return Object.keys(newErrors).length === 0;
};

// ── Submit ───────────────────────────────────────────────────────
const submit = () => {
  if (!validateForm()) return;

  const payload = new FormData();
  payload.append("service_type",   props.serviceType);
  payload.append("preferred_date", formData.value.preferred_date);
  payload.append("notes",          formData.value.notes || "");
  payload.append("document",       imageFile.value);

  emit("submit", payload);
};

// ── Close & reset ────────────────────────────────────────────────
const close = () => {
  formData.value     = { preferred_date: "", notes: "" };
  imageFile.value    = null;
  imagePreview.value = null;
  errors.value       = {};
  if (fileInput.value)  fileInput.value.value  = "";
  if (cameraInput.value) cameraInput.value.value = "";
  emit("close");
};
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>