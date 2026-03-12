<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="close">
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
        <button @click="close" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 max-h-[60vh] overflow-y-auto scrollbar-thin">
        <form @submit.prevent="submit" class="space-y-5">
          
          <!-- Service Type (Read-only) -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Service Type</label>
            <input type="text" :value="serviceType" readonly
              class="border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-sm" />
          </div>

          <!-- Image Upload Section -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Supporting Document / Photo <span class="text-red-400">*</span></label>
            
            <!-- Image preview area -->
            <div class="w-full border-2 border-dashed border-[#3d4f7c]/30 rounded-xl p-4 bg-[#3d4f7c]/5 flex flex-col items-center justify-center text-center transition-all hover:border-[#3d4f7c]/50 hover:bg-[#3d4f7c]/10">
              
              <template v-if="imagePreview">
                <div class="relative w-full max-w-xs mx-auto">
                  <img :src="imagePreview" class="w-full h-auto rounded-lg shadow-md" alt="Preview" />
                  <button type="button" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all shadow-md" @click.stop="removeImage">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </template>
              
              <template v-else>
                <div class="w-16 h-16 rounded-full bg-[#3d4f7c]/10 flex items-center justify-center mb-3">
                  <svg width="24" height="24" fill="none" stroke="#3d4f7c" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <p class="text-sm font-medium text-slate-700 mb-2">Upload a photo or document</p>
                
                <!-- Upload and Camera buttons -->
                <div class="flex items-center gap-2">
                  <button 
                    type="button"
                    @click="triggerFileInput"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3d4f7c] text-white text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all"
                  >
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Browse
                  </button>
                  
                  <button 
                    type="button"
                    @click="openCamera"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-all"
                  >
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Take Photo
                  </button>
                </div>
                
                <p class="text-xs text-slate-400 mt-3">JPG, PNG or PDF (Max 4MB)</p>
              </template>
            </div>
            
            <!-- Hidden file input -->
            <input 
              ref="fileInput" 
              @change="handleImageUpload" 
              type="file" 
              accept="image/*,application/pdf" 
              class="hidden" 
            />
            
            <!-- Hidden camera input -->
            <input 
              ref="cameraInput" 
              @change="handleImageUpload" 
              type="file" 
              accept="image/*" 
              capture="environment"
              class="hidden" 
            />
            
            <span v-if="errors.image" class="text-[10px] text-red-400">{{ errors.image }}</span>
          </div>

          <!-- Preferred Date -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Preferred Date <span class="text-red-400">*</span></label>
            <input 
              type="date" 
              v-model="formData.preferred_date"
              :min="minDate"
              class="border rounded-xl px-4 py-2.5 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
              :class="errors.preferred_date ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"
            />
            <span v-if="errors.preferred_date" class="text-[10px] text-red-400">{{ errors.preferred_date }}</span>
            <span class="text-[10px] text-slate-400">Select your preferred date for processing</span>
          </div>

          <!-- Additional Notes (Optional) -->
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">Additional Notes (Optional)</label>
            <textarea 
              v-model="formData.notes"
              rows="2"
              placeholder="Any additional information or special requests..."
              class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300 resize-none"
            ></textarea>
          </div>

        </form>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2">
        <button
          @click="close"
          class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all"
        >
          Cancel
        </button>
        <button
          @click="submit"
          :disabled="submitting"
          class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-all flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
          :style="{ background: modalColor }"
          @mouseenter="e => { if(!e.currentTarget.disabled) e.currentTarget.style.background = modalHoverColor }"
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
  serviceType: {
    type: String,
    required: true
  },
  submitting: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'submit']);

// Form data
const formData = ref({
  preferred_date: '',
  notes: '',
  confirmed: false
});

// Image upload
const imagePreview = ref(null);
const imageFile = ref(null);
const fileInput = ref(null);
const cameraInput = ref(null);

const errors = ref({});

// Modal colors based on service type
const getModalColor = (service) => {
  if (service.includes('ID')) return '#3d4f7c';
  if (service.includes('Business')) return '#059669';
  if (service.includes('Indigency')) return '#d97706';
  if (service.includes('Residency')) return '#2563eb';
  return '#7c3aed';
};

const modalColor = computed(() => getModalColor(props.serviceType));
const modalHoverColor = computed(() => {
  if (props.serviceType.includes('ID')) return '#252b3b';
  if (props.serviceType.includes('Business')) return '#047857';
  if (props.serviceType.includes('Indigency')) return '#b45309';
  if (props.serviceType.includes('Residency')) return '#1d4ed8';
  return '#6d28d9';
});

// Min date for date picker (today)
const minDate = computed(() => {
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
});

// Image upload functions
const triggerFileInput = () => {
  fileInput.value?.click();
};

const openCamera = () => {
  cameraInput.value?.click();
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    // Check file size (max 4MB)
    if (file.size > 4 * 1024 * 1024) {
      errors.value.image = 'File size must be less than 4MB';
      return;
    }
    
    // Check file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
    if (!validTypes.includes(file.type)) {
      errors.value.image = 'Only JPG, PNG, GIF or PDF files are allowed';
      return;
    }
    
    // Store file
    imageFile.value = file;
    
    // Create preview for images
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.value = e.target.result;
        errors.value.image = '';
      };
      reader.readAsDataURL(file);
    } else {
      // For PDFs, show a PDF icon
      imagePreview.value = null;
      errors.value.image = '';
    }
  }
};

const removeImage = () => {
  imageFile.value = null;
  imagePreview.value = null;
  if (fileInput.value) fileInput.value.value = '';
  if (cameraInput.value) cameraInput.value.value = '';
};

// Validate form
const validateForm = () => {
  const newErrors = {};
  
  if (!formData.value.preferred_date) newErrors.preferred_date = 'Preferred date is required';
  if (!imageFile.value) newErrors.image = 'Supporting document/photo is required';
  if (!formData.value.confirmed) newErrors.confirmed = 'You must confirm the information';
  
  errors.value = newErrors;
  return Object.keys(newErrors).length === 0;
};

// Submit
const submit = () => {
  if (!validateForm()) return;
  
  // Create FormData for file upload
  const formPayload = new FormData();
  formPayload.append('service_type', props.serviceType);
  formPayload.append('preferred_date', formData.value.preferred_date);
  formPayload.append('notes', formData.value.notes || '');
  formPayload.append('document', imageFile.value);
  
  emit('submit', formPayload);
};

// Close
const close = () => {
  // Reset form
  formData.value = {
    preferred_date: '',
    notes: '',
    confirmed: false
  };
  imageFile.value = null;
  imagePreview.value = null;
  errors.value = {};
  if (fileInput.value) fileInput.value.value = '';
  if (cameraInput.value) cameraInput.value.value = '';
  
  emit('close');
};
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>