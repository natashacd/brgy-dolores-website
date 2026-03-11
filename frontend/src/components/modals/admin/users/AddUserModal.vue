<template>
  <!-- Backdrop -->
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')"></div>

    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl" @click.stop>

        <!-- Header -->
        <div class="px-6 py-4 rounded-t-2xl bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
              <svg width="18" height="18" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
              </svg>
            </div>
            <div>
              <h3 class="text-base font-semibold text-white">Appoint New Official</h3>
              <p class="text-xs text-white/50">Assign a resident as a barangay official</p>
            </div>
          </div>
          <button @click="$emit('close')"
            class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-1.5 transition-all cursor-pointer border-0">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto scrollbar-thin">

          <!-- Section 1: Role -->
          <div>
            <div class="flex items-center gap-2 mb-3">
              <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
                <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Role Assignment</p>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 mb-1.5">Role <span class="text-red-400">*</span></label>
              <select v-model="form.role_id"
                class="w-full appearance-none px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white cursor-pointer"
                :class="errors.role_id ? 'border-red-300' : 'border-slate-200'">
                <option value="">Select role…</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.role_name }}
                </option>
              </select>
              <p v-if="errors.role_id" class="text-[10px] text-red-500 mt-1">{{ errors.role_id }}</p>
            </div>
          </div>

          <!-- Divider -->
          <div class="border-t border-slate-100"></div>

          <!-- Section 2: Search Resident -->
          <div>
            <div class="flex items-center gap-2 mb-3">
              <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
                <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Search Resident</p>
            </div>

            <div class="relative" ref="searchContainer">
              <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                v-model="residentSearch"
                type="text"
                placeholder="Type resident name or email to search..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] hover:border-slate-300 transition-all bg-slate-50 focus:bg-white"
                @focus="showDropdown = true"
              />

              <!-- Loading dropdown -->
              <div v-if="loadingResidents"
                class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-20 px-4 py-3 text-sm text-slate-400 flex items-center gap-2">
                <svg class="animate-spin w-4 h-4 text-[#3d4f7c]" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Loading residents...
              </div>

              <!-- Results dropdown -->
              <div v-else-if="showDropdown && residentSearch && filteredResidents.length > 0"
                class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-20 overflow-hidden max-h-48 overflow-y-auto">
                <button
                  v-for="r in filteredResidents" :key="r.id"
                  @click="selectResident(r)"
                  class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-[#3d4f7c]/5 transition-colors text-left cursor-pointer border-0 bg-transparent border-b border-slate-50 last:border-0">
                  <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 bg-slate-100 flex items-center justify-center">
                    <img
                      v-if="getImageUrl(r)"
                      :src="getImageUrl(r)"
                      class="w-full h-full object-cover"
                      @error="e => e.target.style.display = 'none'"
                    />
                    <svg v-else class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-800">{{ getFullName(r) }}</p>
                    <p class="text-xs text-slate-400">{{ r.email }}</p>
                  </div>
                </button>
              </div>

              <!-- No results -->
              <div v-else-if="showDropdown && residentSearch && filteredResidents.length === 0"
                class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-20 px-4 py-3 text-sm text-slate-400">
                No residents found
              </div>
            </div>

            <!-- Selected Resident Card -->
            <div v-if="selectedResident"
              class="mt-3 flex items-center gap-4 px-4 py-3 rounded-xl border border-[#3d4f7c]/20"
              style="background:#3d4f7c08">
              <div class="w-14 h-14 rounded-2xl border-2 border-white shadow-md flex-shrink-0 overflow-hidden bg-slate-100 flex items-center justify-center">
                <img
                  v-if="getImageUrl(selectedResident)"
                  :src="getImageUrl(selectedResident)"
                  class="w-full h-full object-cover"
                  @error="e => e.target.style.display = 'none'"
                />
                <svg v-else class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#3d4f7c]">{{ getFullName(selectedResident) }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ selectedResident.email }}</p>
                <span class="inline-flex items-center gap-1 mt-1.5 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Resident
                </span>
              </div>
              <button @click="clearResident"
                class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all cursor-pointer border-0 bg-transparent flex-shrink-0">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- No selection hint -->
            <p v-else class="mt-2 text-[11px] text-slate-400 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Search and select a resident to auto-fill their information below.
            </p>
          </div>

          <!-- Divider -->
          <div class="border-t border-slate-100"></div>

          <!-- Section 3: Personal Information (read-only, auto-filled) -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
                  <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Personal Information</p>
              </div>
              <span v-if="selectedResident"
                class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#3d4f7c]/10 text-[#3d4f7c] border border-[#3d4f7c]/20">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Auto-filled
              </span>
              <span v-else class="text-[10px] text-slate-400">Select a resident first</span>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">First Name</label>
                <input v-model="form.first_name" type="text" placeholder="Auto-filled" disabled
                  class="w-full px-3 py-2.5 text-sm border rounded-xl transition-all cursor-not-allowed"
                  :class="selectedResident ? 'border-slate-200 bg-slate-50 text-slate-700' : 'border-slate-100 bg-slate-50/50 text-slate-300'"/>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Middle Name</label>
                <input v-model="form.middle_name" type="text" placeholder="Auto-filled" disabled
                  class="w-full px-3 py-2.5 text-sm border rounded-xl transition-all cursor-not-allowed"
                  :class="selectedResident ? 'border-slate-200 bg-slate-50 text-slate-700' : 'border-slate-100 bg-slate-50/50 text-slate-300'"/>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Last Name</label>
                <input v-model="form.last_name" type="text" placeholder="Auto-filled" disabled
                  class="w-full px-3 py-2.5 text-sm border rounded-xl transition-all cursor-not-allowed"
                  :class="selectedResident ? 'border-slate-200 bg-slate-50 text-slate-700' : 'border-slate-100 bg-slate-50/50 text-slate-300'"/>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Suffix</label>
                <input v-model="form.suffix" type="text" placeholder="Auto-filled" disabled
                  class="w-full px-3 py-2.5 text-sm border rounded-xl transition-all cursor-not-allowed"
                  :class="selectedResident ? 'border-slate-200 bg-slate-50 text-slate-700' : 'border-slate-100 bg-slate-50/50 text-slate-300'"/>
              </div>
            </div>
          </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/60 rounded-b-2xl">
          <p class="text-[10px] text-slate-400"><span class="text-red-400">*</span> Required fields</p>
          <div class="flex gap-2">
            <button @click="$emit('close')"
              class="px-4 py-2 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all cursor-pointer bg-white">
              Cancel
            </button>
            <button @click="handleSubmit" :disabled="saving || !selectedResident || !form.role_id"
              class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all cursor-pointer border-0"
              style="background:#3d4f7c"
              @mouseenter="e => !saving && selectedResident && form.role_id && (e.currentTarget.style.backgroundColor='#252b3b')"
              @mouseleave="e => (e.currentTarget.style.backgroundColor='#3d4f7c')">
              <svg v-if="saving" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
              </svg>
              <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              {{ saving ? 'Saving…' : 'Appoint Official' }}
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted, onUnmounted } from 'vue'
import UserService from '@/services/Admin/UserService'
import Swal from 'sweetalert2'

const props = defineProps({
  roles: { type: Array, default: () => [] }
})
const emit = defineEmits(['close', 'saved'])

const allResidents     = ref([])
const loadingResidents = ref(false)
const residentSearch   = ref('')
const showDropdown     = ref(false)
const selectedResident = ref(null)
const saving           = ref(false)
const searchContainer  = ref(null)

const form = reactive({
  first_name:  '',
  middle_name: '',
  last_name:   '',
  suffix:      '',
  role_id:     '',
})

const errors = reactive({
  role_id: '',
})

// ── Fetch residents on mount ──────────────────────────────────────
onMounted(async () => {
  loadingResidents.value = true
  try {
    allResidents.value = await UserService.getResidents()
  } catch {
    console.error('Failed to fetch residents')
  } finally {
    loadingResidents.value = false
  }
  document.addEventListener('mousedown', handleClickOutside)
})
onUnmounted(() => document.removeEventListener('mousedown', handleClickOutside))

// ── Helpers ───────────────────────────────────────────────────────
function getFullName(r) {
  const info = r.information
  if (!info) return '—'
  return [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(' ')
}

function getImageUrl(r) {
  return r.information?.image_path
    ? `/storage/${r.information.image_path}`
    : null
}

// ── Resident search ───────────────────────────────────────────────
const filteredResidents = computed(() => {
  if (!residentSearch.value) return []
  const q = residentSearch.value.toLowerCase()
  return allResidents.value.filter(r => {
    const name = getFullName(r).toLowerCase()
    return name.includes(q) || r.email?.toLowerCase().includes(q)
  })
})

function selectResident(r) {
  selectedResident.value = r
  residentSearch.value   = ''
  showDropdown.value     = false
  const info             = r.information
  form.first_name        = info?.first_name  ?? ''
  form.middle_name       = info?.middle_name ?? ''
  form.last_name         = info?.last_name   ?? ''
  form.suffix            = info?.suffix      ?? ''
}

function clearResident() {
  selectedResident.value = null
  form.first_name  = ''
  form.middle_name = ''
  form.last_name   = ''
  form.suffix      = ''
}

function handleClickOutside(e) {
  if (searchContainer.value && !searchContainer.value.contains(e.target)) {
    showDropdown.value = false
  }
}

// ── Validation ────────────────────────────────────────────────────
function validate() {
  errors.role_id = ''
  let valid = true
  if (!form.role_id)         { errors.role_id = 'Please select a role'; valid = false }
  if (!selectedResident.value) { valid = false }
  return valid
}

// ── Submit ────────────────────────────────────────────────────────
async function handleSubmit() {
  if (!validate()) return

  saving.value = true
  Swal.fire({
    title: 'Please wait...',
    text: 'Appointing official...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => Swal.showLoading()
  })

  try {
    await UserService.appointOfficial({
      user_id: selectedResident.value.id,
      role_id: form.role_id,
    })
    Swal.close()
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Official has been appointed successfully.',
      timer: 2000,
      showConfirmButton: false
    })
    emit('saved')
    emit('close')
  } catch (err) {
    Swal.close()
    if (err.response?.data?.errors) {
      Object.entries(err.response.data.errors).forEach(([k, v]) => {
        if (errors[k] !== undefined) errors[k] = v[0]
      })
    }
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to appoint official. Please try again.', confirmButtonColor: '#3d4f7c' })
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>