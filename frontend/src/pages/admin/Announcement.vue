<template>
  <div class="space-y-6">
    <!-- Header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
        <p class="text-sm text-gray-500 mt-1">Manage and publish announcements for residents</p>
      </div>
      <div class="flex gap-2">
        <button @click="exportAnnouncements" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Export
        </button>
        <button @click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New Announcement
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Total</p>
        <p class="text-2xl font-bold text-gray-900">{{ stats.total || 0 }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Published</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.published || 0 }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Draft</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.draft || 0 }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Archived</p>
        <p class="text-2xl font-bold text-gray-600">{{ stats.archived || 0 }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Urgent</p>
        <p class="text-2xl font-bold text-red-600">{{ stats.urgent || 0 }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500">Total Views</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.total_views || 0 }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="relative">
          <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input v-model="filters.search" type="text" placeholder="Search announcements..." 
                 class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <select v-model="filters.status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option value="all">All Status</option>
          <option value="published">Published</option>
          <option value="draft">Draft</option>
          <option value="archived">Archived</option>
        </select>
        <select v-model="filters.category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option value="all">All Categories</option>
          <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
        </select>
        <select v-model="filters.urgent" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option value="all">All Priority</option>
          <option value="urgent">Urgent Only</option>
          <option value="normal">Normal</option>
        </select>
        <button @click="resetFilters" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Reset Filters
        </button>
      </div>
    </div>

    <!-- Bulk Actions -->
    <div v-if="selectedItems.length > 0" class="bg-blue-50 rounded-lg p-4 flex items-center justify-between">
      <span class="text-sm text-blue-700">{{ selectedItems.length }} items selected</span>
      <div class="flex gap-2">
        <select v-model="bulkStatus" class="px-3 py-1 border border-blue-300 rounded text-sm">
          <option value="published">Publish</option>
          <option value="draft">Move to Draft</option>
          <option value="archived">Archive</option>
        </select>
        <button @click="bulkUpdateStatus" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
          Apply
        </button>
        <button @click="confirmBulkDelete" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
          Delete Selected
        </button>
        <button @click="selectedItems = []" class="px-3 py-1 border border-blue-300 rounded text-sm hover:bg-blue-100">
          Clear
        </button>
      </div>
    </div>

    <!-- Announcements Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left">
              <input type="checkbox" @change="selectAll" v-model="allSelected" class="rounded border-gray-300">
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="announcement in announcements" :key="announcement.id" 
              :class="{ 'bg-blue-50': selectedItems.includes(announcement.id) }">
            <td class="px-6 py-4">
              <input type="checkbox" :value="announcement.id" v-model="selectedItems" class="rounded border-gray-300">
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div v-if="announcement.featured_image" class="flex-shrink-0 h-10 w-10 mr-3">
                  <img :src="'/storage/' + announcement.featured_image" class="h-10 w-10 rounded object-cover">
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ announcement.title }}</div>
                  <div class="text-sm text-gray-500">{{ announcement.excerpt }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span :class="categoryBadgeClass(announcement.category)" class="px-2 py-1 text-xs font-medium rounded-full">
                {{ categories[announcement.category] }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span :class="statusBadgeClass(announcement.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                {{ announcement.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span v-if="announcement.is_urgent" class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                URGENT
              </span>
              <span v-else class="text-gray-400 text-sm">Normal</span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ announcement.views || 0 }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">
              <div>{{ formatDate(announcement.created_at) }}</div>
              <div class="text-xs text-gray-400">by {{ announcement.creator?.name }}</div>
            </td>
            <td class="px-6 py-4 text-right text-sm font-medium">
              <div class="flex items-center justify-end gap-2">
                <button @click="previewAnnouncement(announcement)" class="text-gray-400 hover:text-blue-600" title="Preview">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
                <button @click="toggleUrgent(announcement)" class="text-gray-400 hover:text-red-600" :title="announcement.is_urgent ? 'Remove urgent' : 'Mark urgent'">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </button>
                <button @click="duplicateAnnouncement(announcement)" class="text-gray-400 hover:text-green-600" title="Duplicate">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </button>
                <button @click="editAnnouncement(announcement)" class="text-gray-400 hover:text-green-600" title="Edit">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="confirmDelete(announcement)" class="text-gray-400 hover:text-red-600" title="Delete">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </div>
        <div class="flex gap-2">
          <button :disabled="pagination.current_page === 1" @click="changePage(pagination.current_page - 1)"
                  class="px-3 py-1 border rounded text-sm disabled:opacity-50 hover:bg-gray-50">
            Previous
          </button>
          <button :disabled="pagination.current_page === pagination.last_page" @click="changePage(pagination.current_page + 1)"
                  class="px-3 py-1 border rounded text-sm disabled:opacity-50 hover:bg-gray-50">
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal (keep as is) -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- ... modal content remains the same ... -->
      </div>
    </Transition>

    <!-- Preview Modal (keep as is) -->
    <Transition name="modal">
      <div v-if="showPreviewModal" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- ... preview modal content remains the same ... -->
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { announcementService } from '@/services/announcementService'
import Swal from 'sweetalert2'

// State
const announcements = ref([])
const stats = ref({})
const loading = ref(false)
const showModal = ref(false)
const showPreviewModal = ref(false)
const modalMode = ref('create')
const selectedAnnouncement = ref(null)
const previewData = ref(null)
const selectedItems = ref([])
const allSelected = ref(false)
const featuredImageInput = ref(null)
const attachmentInput = ref(null)

// Filters
const filters = reactive({
  search: '',
  status: 'all',
  category: 'all',
  urgent: 'all'
})

const bulkStatus = ref('published')

// Pagination
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0
})

// Form
const form = reactive({
  id: null,
  title: '',
  content: '',
  category: 'general',
  type: 'announcement',
  status: 'draft',
  is_urgent: false,
  expires_at: '',
  featured_image: null,
  featured_image_preview: null,
  attachments: [],
  meta_title: '',
  meta_description: ''
})

// Constants
const categories = {
  event: 'Events & Activities',
  advisory: 'Public Advisories',
  emergency: 'Emergency Alerts',
  meeting: 'Community Meetings',
  program: 'Government Programs'
}

// SweetAlert2 helpers
const showSuccess = (message) => {
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showError = (message) => {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showWarning = (message) => {
  Swal.fire({
    icon: 'warning',
    title: 'Warning',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showInfo = (message) => {
  Swal.fire({
    icon: 'info',
    title: 'Info',
    text: message,
    timer: 3000,
    showConfirmButton: false,
    position: 'top-end',
    toast: true
  })
}

const showConfirmDialog = async (title, text, icon = 'warning') => {
  const result = await Swal.fire({
    title: title,
    text: text,
    icon: icon,
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  })
  return result.isConfirmed
}

// Methods
const fetchAnnouncements = async () => {
  loading.value = true
  try {
    const params = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      ...filters
    }
    const response = await announcementService.getAnnouncements(params)
    announcements.value = response.data.data
    pagination.value = response.data
    stats.value = response.stats
  } catch (error) {
    showError('Failed to fetch announcements')
  } finally {
    loading.value = false
  }
}

const statusBadgeClass = (status) => {
  const classes = {
    published: 'bg-green-100 text-green-800',
    draft: 'bg-yellow-100 text-yellow-800',
    archived: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const categoryBadgeClass = (category) => {
  const classes = {
    event: 'bg-purple-100 text-purple-800',
    advisory: 'bg-blue-100 text-blue-800',
    emergency: 'bg-red-100 text-red-800',
    meeting: 'bg-green-100 text-green-800',
    program: 'bg-yellow-100 text-yellow-800'
  }
  return classes[category] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const openCreateModal = () => {
  modalMode.value = 'create'
  resetForm()
  showModal.value = true
}

const editAnnouncement = (announcement) => {
  modalMode.value = 'edit'
  form.id = announcement.id
  form.title = announcement.title
  form.content = announcement.content
  form.category = announcement.category
  form.type = announcement.type
  form.status = announcement.status
  form.is_urgent = announcement.is_urgent
  form.expires_at = announcement.expires_at
  form.meta_title = announcement.meta_title
  form.meta_description = announcement.meta_description
  if (announcement.featured_image) {
    form.featured_image_preview = '/storage/' + announcement.featured_image
  }
  form.attachments = announcement.attachments || []
  showModal.value = true
}

const previewAnnouncement = (announcement) => {
  previewData.value = announcement
  showPreviewModal.value = true
}

const closePreviewModal = () => {
  showPreviewModal.value = false
  previewData.value = null
}

const confirmDelete = async (announcement) => {
  const confirmed = await showConfirmDialog(
    'Delete Announcement',
    `Are you sure you want to delete "${announcement.title}"? This action cannot be undone.`
  )
  
  if (confirmed) {
    try {
      await announcementService.deleteAnnouncement(announcement.id)
      showSuccess('Announcement deleted successfully')
      fetchAnnouncements()
    } catch (error) {
      showError('Failed to delete announcement')
    }
  }
}

const saveAnnouncement = async () => {
  try {
    const formData = new FormData()
    
    Object.keys(form).forEach(key => {
      if (key === 'attachments' && form[key].length > 0) {
        form[key].forEach(file => {
          if (file instanceof File) {
            formData.append('attachments[]', file)
          }
        })
      } else if (key === 'featured_image' && form[key] instanceof File) {
        formData.append('featured_image', form[key])
      } else if (key !== 'featured_image_preview' && form[key] !== null) {
        formData.append(key, form[key])
      }
    })

    if (modalMode.value === 'create') {
      await announcementService.createAnnouncement(formData)
      showSuccess('Announcement created successfully')
    } else {
      await announcementService.updateAnnouncement(form.id, formData)
      showSuccess('Announcement updated successfully')
    }
    
    closeModal()
    fetchAnnouncements()
  } catch (error) {
    showError('Failed to save announcement')
  }
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const resetForm = () => {
  form.id = null
  form.title = ''
  form.content = ''
  form.category = 'general'
  form.type = 'announcement'
  form.status = 'draft'
  form.is_urgent = false
  form.expires_at = ''
  form.featured_image = null
  form.featured_image_preview = null
  form.attachments = []
  form.meta_title = ''
  form.meta_description = ''
}

const toggleUrgent = async (announcement) => {
  try {
    await announcementService.toggleUrgent(announcement.id)
    showSuccess(announcement.is_urgent ? 'Removed urgent status' : 'Marked as urgent')
    fetchAnnouncements()
  } catch (error) {
    showError('Failed to update urgent status')
  }
}

const duplicateAnnouncement = async (announcement) => {
  try {
    await announcementService.duplicateAnnouncement(announcement.id)
    showSuccess('Announcement duplicated successfully')
    fetchAnnouncements()
  } catch (error) {
    showError('Failed to duplicate announcement')
  }
}

const handleFeaturedImage = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.featured_image = file
    form.featured_image_preview = URL.createObjectURL(file)
  }
}

const triggerFeaturedImage = () => {
  featuredImageInput.value.click()
}

const handleAttachments = (event) => {
  const files = Array.from(event.target.files)
  form.attachments.push(...files)
}

const triggerAttachments = () => {
  attachmentInput.value.click()
}

const removeAttachment = (index) => {
  form.attachments.splice(index, 1)
}

const selectAll = () => {
  if (allSelected.value) {
    selectedItems.value = announcements.value.map(a => a.id)
  } else {
    selectedItems.value = []
  }
}

const bulkUpdateStatus = async () => {
  try {
    await announcementService.bulkUpdateStatus({
      ids: selectedItems.value,
      status: bulkStatus.value
    })
    showSuccess('Status updated successfully')
    selectedItems.value = []
    fetchAnnouncements()
  } catch (error) {
    showError('Failed to update status')
  }
}

const confirmBulkDelete = async () => {
  const confirmed = await showConfirmDialog(
    'Delete Multiple Announcements',
    `Are you sure you want to delete ${selectedItems.value.length} announcements? This action cannot be undone.`
  )
  
  if (confirmed) {
    try {
      await announcementService.bulkDelete({ ids: selectedItems.value })
      showSuccess('Announcements deleted successfully')
      selectedItems.value = []
      fetchAnnouncements()
    } catch (error) {
      showError('Failed to delete announcements')
    }
  }
}

const exportAnnouncements = () => {
  // Implement export functionality
  window.location.href = '/api/admin/announcements/export?' + new URLSearchParams(filters)
}

const resetFilters = () => {
  filters.search = ''
  filters.status = 'all'
  filters.category = 'all'
  filters.urgent = 'all'
  pagination.value.current_page = 1
  fetchAnnouncements()
}

const changePage = (page) => {
  pagination.value.current_page = page
  fetchAnnouncements()
}

// Watch for filter changes
watch(filters, () => {
  pagination.value.current_page = 1
  fetchAnnouncements()
}, { deep: true })

// Initial load
onMounted(() => {
  fetchAnnouncements()
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>