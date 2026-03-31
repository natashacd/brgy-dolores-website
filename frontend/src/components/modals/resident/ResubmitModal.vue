<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden">

      <!-- Header -->
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full bg-amber-600"></div>
          <h2 class="text-base font-bold text-slate-800">Resubmit Request</h2>
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

      <!-- Body -->
      <div class="px-6 py-5 max-h-[70vh] overflow-y-auto scrollbar-thin space-y-5">

        <!-- Original Request Info -->
        <div class="bg-slate-50 rounded-xl p-4">
          <p class="text-xs font-semibold text-slate-500 mb-2">Original Request</p>
          <p class="text-sm text-slate-700"><span class="font-medium">Service:</span> {{ request?.type }}</p>
          <p class="text-sm text-slate-700"><span class="font-medium">Submitted:</span> {{ formatDate(request?.created_at) }}</p>
          <p class="text-sm text-slate-700"><span class="font-medium">Disapproved:</span> {{ formatDate(request?.updated_at) }}</p>
          <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-xs font-semibold text-red-700">Disapproval Remarks:</p>
            <p class="text-xs text-red-600 mt-1">{{ request?.remarks || 'No remarks provided' }}</p>
          </div>
        </div>

        <!-- Service Type (Read-only) -->
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-600">Service Type</label>
          <input
            type="text"
            :value="request?.type"
            readonly
            class="border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-sm"
          />
        </div>

        <!-- Document Upload -->
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-600">
            Supporting Document / Photo <span class="text-red-400">*</span>
          </label>

          <div class="w-full border-2 border-dashed border-[#3d4f7c]/30 rounded-xl p-4 bg-[#3d4f7c]/5 flex flex-col items-center justify-center text-center transition-all hover:border-[#3d4f7c]/50 hover:bg-[#3d4f7c]/10">

            <!-- Image Preview -->
            <template v-if="imagePreview">
              <div class="relative w-full max-w-xs mx-auto">
                <img :src="imagePreview" class="w-full h-auto rounded-lg shadow-md" alt="Preview"/>
                <button
                  type="button"
                  class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all shadow-md cursor-pointer"
                  @click="removeImage"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </template>

            <!-- PDF Uploaded -->
            <template v-else-if="imageFile && imageFile.type === 'application/pdf'">
              <div class="w-16 h-16 rounded-lg bg-red-50 border border-red-200 flex items-center justify-center mb-2">
                <svg width="24" height="24" fill="none" stroke="#ef4444" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
              </div>
              <p class="text-xs font-medium text-slate-700 mb-1">{{ imageFile.name }}</p>
              <button type="button" @click="removeImage" class="text-xs text-red-500 hover:text-red-600 cursor-pointer">Remove</button>
            </template>

            <!-- Empty State -->
            <template v-else>
              <div class="w-16 h-16 rounded-full bg-[#3d4f7c]/10 flex items-center justify-center mb-3">
                <svg width="24" height="24" fill="none" stroke="#3d4f7c" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <p class="text-sm font-medium text-slate-700 mb-2">Upload updated document or photo</p>
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  @click="triggerFileInput"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-[#3d4f7c] text-white text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all cursor-pointer"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                  </svg>
                  Browse
                </button>
                <button
                  type="button"
                  @click="openCamera"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-all cursor-pointer"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Take Photo
                </button>
              </div>
              <p class="text-xs text-slate-400 mt-3">JPG, PNG or PDF (Max 4MB)</p>
            </template>
          </div>

          <input ref="fileInput"   @change="handleFileUpload" type="file" accept="image/*,application/pdf" class="hidden"/>
          <input ref="cameraInput" @change="handleFileUpload" type="file" accept="image/*" capture="environment" class="hidden"/>
          <span v-if="errors.image" class="text-[10px] text-red-400">{{ errors.image }}</span>
        </div>

        <!-- Preferred Date -->
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-600">
            Preferred Date <span class="text-red-400">*</span>
          </label>
          <input
            type="date"
            v-model="form.preferred_date"
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
            v-model="form.notes"
            rows="2"
            placeholder="Any corrections or additional information..."
            class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300 resize-none"
          ></textarea>
        </div>

        <!-- Confirmation -->
        <div class="flex items-start gap-3 pt-1">
          <input
            type="checkbox"
            v-model="form.confirmed"
            id="confirm-resubmit"
            class="mt-1 w-4 h-4 rounded border-slate-300 text-amber-600 cursor-pointer"
          />
          <label for="confirm-resubmit" class="text-xs text-slate-600 cursor-pointer">
            I confirm that the information provided is accurate and complete.
          </label>
        </div>
        <span v-if="errors.confirmed" class="text-[10px] text-red-400 block">{{ errors.confirmed }}</span>

      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2">
        <button
          @click="close"
          class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer"
        >
          Cancel
        </button>
        <button
          @click="submit"
          :disabled="submitting"
          class="px-5 py-2 text-sm font-semibold text-white bg-amber-600 rounded-xl hover:bg-amber-700 transition-all flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
        >
          <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
          </svg>
          <span>{{ submitting ? 'Submitting...' : 'Resubmit Request' }}</span>
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  request:    { type: Object,  required: true },
  submitting: { type: Boolean, default: false },
});

const emit = defineEmits(["close", "submit"]);

const fileInput    = ref(null);
const cameraInput  = ref(null);
const imageFile    = ref(null);
const imagePreview = ref(null);
const errors       = ref({});

const form = ref({
  preferred_date: "",
  notes:          "",
  confirmed:      false,
});

const minDate = computed(() => new Date().toISOString().split("T")[0]);

const formatDate = (dateString) => {
  if (!dateString) return "—";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric", month: "short", day: "numeric",
  });
};

const validateDate = () => {
  if (!form.value.preferred_date) return;
  const day = new Date(form.value.preferred_date).getDay();
  if (day === 0 || day === 6) {
    errors.value.preferred_date = "Weekends are not available. Please select a weekday.";
    form.value.preferred_date   = "";
  } else {
    errors.value.preferred_date = "";
  }
};

const triggerFileInput = () => fileInput.value?.click();
const openCamera       = () => cameraInput.value?.click();

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (file.size > 4 * 1024 * 1024) {
    errors.value.image = "File size must be less than 4MB.";
    return;
  }

  const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "application/pdf"];
  if (!validTypes.includes(file.type)) {
    errors.value.image = "Only JPG, PNG, GIF or PDF files are allowed.";
    return;
  }

  imageFile.value    = file;
  errors.value.image = "";

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
  if (fileInput.value)   fileInput.value.value   = "";
  if (cameraInput.value) cameraInput.value.value = "";
};

const validateForm = () => {
  const newErrors = {};
  if (!imageFile.value)               newErrors.image          = "Supporting document or photo is required.";
  if (!form.value.preferred_date)     newErrors.preferred_date = "Preferred date is required.";
  else {
    const day = new Date(form.value.preferred_date).getDay();
    if (day === 0 || day === 6)       newErrors.preferred_date = "Weekends are not available.";
  }
  if (!form.value.confirmed)          newErrors.confirmed      = "You must confirm the information.";
  errors.value = newErrors;
  return Object.keys(newErrors).length === 0;
};

const submit = () => {
  if (!validateForm()) return;

  const payload = new FormData();
  payload.append("service_type",   props.request.type);
  payload.append("preferred_date", form.value.preferred_date);
  payload.append("notes",          form.value.notes || "");
  payload.append("document",       imageFile.value);

  // Emit both the id and formData so dashboard can call resubmitRequest(id, formData)
  emit("submit", props.request.id, payload);
};

const close = () => {
  form.value         = { preferred_date: "", notes: "", confirmed: false };
  imageFile.value    = null;
  imagePreview.value = null;
  errors.value       = {};
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