<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl shadow-slate-300/50 overflow-hidden">

      <!-- Header -->
      <div class="flex items-center gap-3 px-6 py-5 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b]">
        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
          <svg width="17" height="17" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h2 class="text-base font-bold text-white leading-tight">
            {{ modalMode === 'create' ? 'Add New Announcement' : 'Edit Announcement' }}
          </h2>
          <p class="text-xs text-white/60 mt-0.5">
            {{ modalMode === 'create' ? 'Fill in the details to create a new announcement.' : 'Update the announcement information.' }}
          </p>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all cursor-pointer border-0 bg-transparent"
          @click="$emit('close')"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-5 flex flex-col gap-6 max-h-[70vh] overflow-y-auto">

        <!-- Section: Media -->
        <div>
          <div class="flex items-center gap-2 mb-3">
            <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:#3d4f7c15">
              <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest">Media</p>
          </div>

          <div class="flex items-center justify-between mb-2">
            <label class="text-xs font-semibold text-slate-600">Featured Image</label>
            <span class="text-[10px] text-slate-400">Max 4 MB</span>
          </div>

          <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 bg-slate-50/50 hover:border-[#3d4f7c]/40 hover:bg-[#3d4f7c]/5 transition-all">
            <input type="file" @change="handleFeaturedImage" accept="image/*" ref="featuredImageInput" class="hidden"/>
            <div v-if="form.featured_image_preview" class="mb-4">
              <img :src="form.featured_image_preview" class="max-h-40 rounded-xl mx-auto object-cover shadow-sm"/>
            </div>
            <div class="flex flex-col items-center gap-3">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#3d4f7c15">
                <svg width="22" height="22" fill="none" stroke="#3d4f7c" stroke-width="1.8" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="text-center">
                <p class="text-sm text-slate-600">
                  <span @click="triggerFeaturedImage" class="font-semibold cursor-pointer hover:underline" style="color:#3d4f7c">Click to upload</span>
                  <span class="text-slate-400"> or drag and drop</span>
                </p>
                <p class="text-xs text-slate-400 mt-1">JPG, PNG or GIF (Max 4MB)</p>
              </div>
              <div class="flex gap-2">
                <button
                  type="button"
                  @click="triggerFeaturedImage"
                  class="px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition-all cursor-pointer"
                  style="background:#3d4f7c"
                  @mouseenter="e => e.currentTarget.style.background='#252b3b'"
                  @mouseleave="e => e.currentTarget.style.background='#3d4f7c'"
                >
                  Upload Image
                </button>
                <button
                  v-if="form.featured_image_preview"
                  type="button"
                  @click="removeFeaturedImage"
                  class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-all cursor-pointer"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t border-slate-100"></div>

        <!-- Section: Basic Information -->
        <div>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:#3d4f7c15">
              <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <p class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest">Basic Information</p>
          </div>

          <!-- Title -->
          <div class="mb-4">
            <label class="text-xs font-semibold text-slate-600 block mb-1.5">Title <span class="text-red-400">*</span></label>
            <input
              v-model="form.title"
              type="text"
              required
              placeholder="Enter announcement title"
              class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 transition-all"
              :class="errors.title
                ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300'"
            />
            <span v-if="errors.title" class="text-[11px] text-red-500 flex items-center gap-1 mt-1">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
              {{ errors.title }}
            </span>
          </div>

          <!-- Category + Status + Priority -->
          <div class="grid grid-cols-3 gap-3">

            <!-- Category -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Category</label>
              <div class="relative">
                <select
                  v-model="form.category"
                  class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 transition-all pr-9"
                  :class="errors.category ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300'"
                >
                  <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Status</label>
              <div class="relative">
                <select
                  v-model="form.status"
                  class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 transition-all pr-9"
                  :class="errors.status ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300'"
                >
                  <option value="published">Published</option>
                  <option value="draft">Draft</option>
                  <option value="archived">Archived</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Priority -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Priority</label>
              <label
                class="flex items-center gap-2.5 cursor-pointer border rounded-xl px-3.5 py-2.5 transition-all h-[42px]"
                :class="form.is_urgent ? 'border-red-200 bg-red-50' : 'border-slate-200 hover:border-slate-300 bg-white'"
              >
                <input type="checkbox" v-model="form.is_urgent" class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500 cursor-pointer"/>
                <span class="text-sm font-medium" :class="form.is_urgent ? 'text-red-600' : 'text-slate-600'">
                  {{ form.is_urgent ? '🔴 Urgent' : 'Mark as Urgent' }}
                </span>
              </label>
            </div>
          </div>
        </div>

        <div class="border-t border-slate-100"></div>

        <!-- Section: Content -->
        <div>
          <div class="flex items-center gap-2 mb-3">
            <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:#3d4f7c15">
              <svg width="11" height="11" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h12"/>
              </svg>
            </div>
            <p class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest">Content</p>
          </div>

          <label class="text-xs font-semibold text-slate-600 block mb-1.5">Body <span class="text-red-400">*</span></label>

          <textarea
            v-model="form.content"
            rows="7"
            required
            placeholder="Write your announcement content here..."
            class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 transition-all resize-none"
            :class="errors.content
              ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
              : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300'"
          ></textarea>

          <div class="flex items-center justify-between mt-1.5">
            <span v-if="errors.content" class="text-[11px] text-red-500 flex items-center gap-1">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
              {{ errors.content }}
            </span>
            <span v-else></span>
            <span class="text-[11px] font-mono" :class="wordCount > 500 ? 'text-red-500' : 'text-slate-400'">
              {{ wordCount }} / 500 words
            </span>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-slate-100 bg-slate-50/60">
        <button
          class="text-sm font-semibold text-slate-500 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-5 py-2.5 rounded-xl transition-all cursor-pointer"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          @click="handleSubmit"
          :disabled="loading"
          class="inline-flex items-center gap-2 text-sm font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed px-5 py-2.5 rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer border-0"
          style="background:#3d4f7c"
          @mouseenter="e => { if (!loading) e.currentTarget.style.background='#252b3b' }"
          @mouseleave="e => e.currentTarget.style.background='#3d4f7c'"
        >
          <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
          <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          {{ loading ? 'Saving…' : (modalMode === 'create' ? 'Add Announcement' : 'Save Changes') }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  modalMode: { type: String, default: 'create' },
  announcement: { type: Object, default: null },
  loading: { type: Boolean, default: false }
})

const emit = defineEmits(['close', 'save'])

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

const errors = ref({})
const featuredImageInput = ref(null)

const categories = {
  event: 'Events & Activities',
  advisory: 'Public Advisories',
  emergency: 'Emergency Alerts',
  meeting: 'Community Meetings',
  program: 'Government Programs'
}

const wordCount = computed(() => {
  if (!form.value.content) return 0
  return form.value.content.trim().split(/\s+/).filter(Boolean).length
})

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

const validate = () => {
  errors.value = {}
  if (!form.value.title) errors.value.title = 'Title is required.'
  if (!form.value.content) errors.value.content = 'Content is required.'
  return Object.keys(errors.value).length === 0
}

const triggerFeaturedImage = () => featuredImageInput.value.click()

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
  if (featuredImageInput.value) featuredImageInput.value.value = ''
}

const handleSubmit = () => {
  if (!validate()) return
  emit('save', { ...form.value })
}
</script>