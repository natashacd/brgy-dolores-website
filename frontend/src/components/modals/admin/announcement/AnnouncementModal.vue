<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl shadow-gray-300/50 overflow-hidden">

      <!-- ── Header ── -->
      <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-indigo-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </div>
        <div class="flex-1">
          <h2 class="text-base font-bold text-gray-900 leading-tight">
            {{ modalMode === 'create' ? 'Add New Announcement' : 'Edit Announcement' }}
          </h2>
          <p class="text-xs text-gray-400 mt-0.5">
            {{ modalMode === 'create' ? 'Fill in the details to create a new announcement.' : 'Update the announcement information.' }}
          </p>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all cursor-pointer border-0 bg-transparent"
          @click="$emit('close')"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- ── Body ── -->
      <div class="px-6 py-5 flex flex-col gap-5 max-h-[70vh] overflow-y-auto">
        <form @submit.prevent="handleSubmit">
          
          <!-- Section: Media -->
          <div>
            <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Media</p>
            
            <!-- Featured Image Upload -->
            <div class="mb-4">
              <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-gray-600">Featured Image</label>
                <span class="text-[10px] text-gray-400">Image should be below 4 MB</span>
              </div>
              
              <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <input 
                  type="file" 
                  @change="handleFeaturedImage" 
                  accept="image/*" 
                  ref="featuredImageInput" 
                  class="hidden"
                />
                <div v-if="form.featured_image_preview" class="mb-4">
                  <img :src="form.featured_image_preview" class="max-h-40 rounded-lg mx-auto object-cover">
                </div>
                <div class="flex flex-col items-center gap-3">
                  <div class="w-14 h-14 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-sm text-gray-600">
                      <span 
                        @click="triggerFeaturedImage" 
                        class="text-indigo-600 font-medium cursor-pointer hover:text-indigo-700"
                      >Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG or GIF (Max 4MB)</p>
                  </div>
                  <div class="flex gap-3 mt-2">
                    <button type="button" @click="triggerFeaturedImage" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                      Upload
                    </button>
                    <button 
                      v-if="form.featured_image_preview" 
                      type="button" 
                      @click="removeFeaturedImage" 
                      class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      Remove
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Section: Basic Information -->
          <div>
            <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Basic Information</p>
            
            <!-- Title - Full width -->
            <div class="mb-4">
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-600">Title <span class="text-red-400">*</span></label>
                <input
                  v-model="form.title"
                  type="text"
                  required
                  placeholder="Enter announcement title"
                  class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all w-full"
                  :class="errors.title
                    ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                    : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300'"
                />
                <span v-if="errors.title" class="text-[11px] text-red-500 flex items-center gap-1">
                  <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  {{ errors.title }}
                </span>
              </div>
            </div>

            <!-- Two column grid -->
            <div class="grid grid-cols-2 gap-3">
              <!-- Category -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-600">Category</label>
                <div class="relative">
                  <select
                    v-model="form.category"
                    class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all pr-9"
                    :class="errors.category
                      ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400 text-gray-800'
                      : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300 text-gray-800'"
                  >
                    <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
                  </select>
                  <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
                <span v-if="errors.category" class="text-[11px] text-red-500 flex items-center gap-1">
                  <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  {{ errors.category }}
                </span>
              </div>

              <!-- Status -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-600">Status</label>
                <div class="relative">
                  <select
                    v-model="form.status"
                    class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all pr-9"
                    :class="errors.status
                      ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400 text-gray-800'
                      : 'border-gray-200 focus:ring-indigo-500/20 focus:border-indigo-400 hover:border-gray-300 text-gray-800'"
                  >
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                  </select>
                  <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
                <span v-if="errors.status" class="text-[11px] text-red-500 flex items-center gap-1">
                  <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  {{ errors.status }}
                </span>
              </div>

              <!-- Priority -->
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-600">Priority</label>
                <div class="flex items-center h-[42px]">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input 
                      type="checkbox" 
                      v-model="form.is_urgent" 
                      class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                    />
                    <span class="text-sm text-gray-700">Mark as Urgent</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Section: Content -->
          <div>
            <p class="text-[10.5px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Content</p>
            
            <!-- Rich Text Toolbar -->
            <div class="flex items-center gap-1 p-2 bg-gray-50 border border-gray-200 rounded-t-xl">
              <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded font-bold">B</button>
              <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded italic">I</button>
              <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded underline">U</button>
              <span class="w-px h-4 bg-gray-200 mx-1"></span>
              <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded text-lg">🔗</button>
              <button type="button" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded text-lg">📋</button>
            </div>
            
            <!-- Textarea -->
            <div class="flex flex-col gap-1.5">
              <textarea
                v-model="form.content"
                rows="6"
                required
                class="border border-t-0 border-gray-200 rounded-b-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all w-full"
                :class="errors.content ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400' : ''"
                placeholder="Write your announcement content here..."
              ></textarea>
              <span v-if="errors.content" class="text-[11px] text-red-500 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.content }}
              </span>
            </div>
            
            <!-- Word count -->
            <div class="flex justify-end mt-1">
              <span class="text-[11px] text-gray-400">Maximum 500 words</span>
            </div>
          </div>
        </form>
      </div>

      <!-- ── Footer ── -->
      <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-gray-100 bg-gray-50/60">
        <button
          class="text-sm font-semibold text-gray-500 hover:text-gray-800 bg-white hover:bg-gray-100 border border-gray-200 px-5 py-2.5 rounded-xl transition-all cursor-pointer"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-200 transition-all cursor-pointer border-0"
          @click="handleSubmit"
          :disabled="loading"
        >
          <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
          <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
          {{ loading ? 'Saving…' : (modalMode === 'create' ? 'Add Announcement' : 'Save Changes') }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  modalMode: {
    type: String,
    default: 'create'
  },
  announcement: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'save'])

// Form state
const form = ref({
  id: null,
  title: '',
  content: '',
  category: 'event',
  status: 'draft',
  is_urgent: false,
  featured_image: null,
  featured_image_preview: null,
  attachments: []
})

// Error state
const errors = ref({})

// Constants
const categories = {
  event: 'Events & Activities',
  advisory: 'Public Advisories',
  emergency: 'Emergency Alerts',
  meeting: 'Community Meetings',
  program: 'Government Programs'
}

// Refs
const featuredImageInput = ref(null)

// Initialize form with announcement data if in edit mode
onMounted(() => {
  if (props.modalMode === 'edit' && props.announcement) {
    form.value = {
      id: props.announcement.id,
      title: props.announcement.title || '',
      content: props.announcement.content || '',
      category: props.announcement.category || 'event',
      status: props.announcement.status || 'draft',
      is_urgent: props.announcement.is_urgent || false,
      featured_image: props.announcement.featured_image || null,
      featured_image_preview: props.announcement.featured_image_url || 
        (props.announcement.featured_image ? '/storage/' + props.announcement.featured_image : null),
      attachments: props.announcement.attachments || []
    }
  }
})

// Validation
const validate = () => {
  errors.value = {}
  if (!form.value.title) errors.value.title = 'Title is required.'
  if (!form.value.content) errors.value.content = 'Content is required.'
  return Object.keys(errors.value).length === 0
}

// Methods
const triggerFeaturedImage = () => {
  featuredImageInput.value.click()
}

const handleFeaturedImage = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.featured_image = file
    form.value.featured_image_preview = URL.createObjectURL(file)
  }
}

const removeFeaturedImage = () => {
  form.value.featured_image = null
  form.value.featured_image_preview = null
  if (featuredImageInput.value) {
    featuredImageInput.value.value = ''
  }
}

const handleSubmit = () => {
  if (!validate()) return
  emit('save', { ...form.value })
}
</script>

<style scoped>
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