<template>
  <Transition name="fade">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
      @click.self="$emit('close')"
      style="font-family: 'Inter', sans-serif;"
    >
      <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
           style="box-shadow: 0 24px 60px rgba(0,0,0,0.15);">

        <!-- Header - clean white -->
        <div class="flex-shrink-0 bg-white px-6 pt-6 pb-5 border-b border-slate-100">

          <!-- Top row: close button -->
          <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-4">
              <!-- Photo -->
              <div class="relative flex-shrink-0">
                <div class="w-[72px] h-[72px] rounded-2xl overflow-hidden border border-slate-200 bg-slate-100"
                     style="box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                  <img
                    v-if="user.information?.image_path"
                    :src="`${baseUrl}/storage/${user.information.image_path}`"
                    class="w-full h-full object-cover object-top"
                    @error="e => e.target.style.display = 'none'"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-slate-100">
                    <svg width="28" height="28" fill="none" stroke="#94a3b8" stroke-width="1.4" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                </div>
                <!-- Status dot -->
                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white"
                     :class="localStatus ? 'bg-emerald-500' : 'bg-amber-400'">
                </div>
              </div>

              <!-- Name + badges -->
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md border"
                        style="background:#3d4f7c10; color:#3d4f7c; border-color:#3d4f7c20;">
                    {{ user.role?.role_name ?? '—' }}
                  </span>
                  <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full border"
                        :class="localStatus
                          ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                          : 'bg-amber-50 text-amber-500 border-amber-200'">
                    <span class="w-1.5 h-1.5 rounded-full"
                          :class="localStatus ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'"></span>
                    {{ localStatus ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 leading-tight">{{ fullName }}</h3>
                <!-- Contact row -->
                <div class="flex flex-wrap gap-x-4 mt-1">
                  <span v-if="user.email" class="text-[11px] text-slate-400 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ user.email }}
                  </span>
                  <span v-if="user.information?.contact_number" class="text-[11px] text-slate-400 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ user.information.contact_number }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Close -->
            <button
              class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer flex-shrink-0"
              @click="$emit('close')"
            >
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Tab bar -->
          <div class="flex gap-1">
            <button
              v-for="tab in tabs" :key="tab.key"
              @click="activeTab = tab.key"
              class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer border-0"
              :class="activeTab === tab.key
                ? 'bg-[#3d4f7c] text-white'
                : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100 bg-transparent'"
            >
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" :d="tab.icon"/>
              </svg>
              {{ tab.label }}
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 p-5 bg-slate-50 scrollbar-thin">

          <!-- Personal Info Tab -->
          <div v-if="activeTab === 'personal'" class="space-y-3">
            <div class="grid grid-cols-3 gap-2.5">
              <div v-for="field in personalFields" :key="field.label"
                   class="bg-white rounded-xl px-3.5 py-3 border border-slate-100"
                   :class="field.span ? 'col-span-2' : ''"
                   style="box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">{{ field.label }}</p>
                <p class="text-sm text-slate-700 font-semibold">{{ field.value ?? '—' }}</p>
              </div>
            </div>
          </div>

          <!-- Address Tab -->
          <div v-if="activeTab === 'address'" class="space-y-3">
            <div class="grid grid-cols-3 gap-2.5">
              <div v-for="field in addressFields" :key="field.label"
                   class="bg-white rounded-xl px-3.5 py-3 border border-slate-100"
                   style="box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">{{ field.label }}</p>
                <p class="text-sm text-slate-700 font-semibold">{{ field.value ?? '—' }}</p>
              </div>
            </div>
          </div>

          <!-- Emergency Tab -->
          <div v-if="activeTab === 'emergency'" class="space-y-3">
            <div class="grid grid-cols-3 gap-2.5">
              <div v-for="field in emergencyFields" :key="field.label"
                   class="bg-white rounded-xl px-3.5 py-3 border border-slate-100"
                   style="box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">{{ field.label }}</p>
                <p class="text-sm text-slate-700 font-semibold">{{ field.value ?? '—' }}</p>
              </div>
            </div>
          </div>

        </div>

        <!-- Footer -->
        <div class="flex-shrink-0 px-5 py-3.5 border-t border-slate-100 bg-white flex items-center justify-between">
          <p class="text-[11px] text-slate-400">
            Account is currently
            <span class="font-bold" :class="localStatus ? 'text-emerald-600' : 'text-amber-500'">
              {{ localStatus ? 'Active' : 'Inactive' }}
            </span>
          </p>

          <button
            @click="handleToggleStatus"
            :disabled="togglingStatus"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
            :class="localStatus
              ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100 hover:border-amber-300'
              : 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300'"
          >
            <svg v-if="togglingStatus" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            <svg v-else-if="localStatus" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            <svg v-else width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ togglingStatus ? 'Updating...' : localStatus ? 'Set Inactive' : 'Set Active' }}
          </button>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, ref } from 'vue';
import UserService from '@/services/Admin/UserService';
import Swal from 'sweetalert2';

const props = defineProps({
  user: { type: Object, required: true },
});

const emit = defineEmits(['close', 'statusUpdated']);

const baseUrl = import.meta.env.VITE_API_URL;
const togglingStatus = ref(false);
const activeTab = ref('personal');

const localStatus = ref(props.user.status?.status ?? false);

const tabs = [
  {
    key: 'personal',
    label: 'Personal Info',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  },
  {
    key: 'address',
    label: 'Address',
    icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z',
  },
  {
    key: 'emergency',
    label: 'Emergency Contact',
    icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
  },
];

const fullName = computed(() => {
  const info = props.user.information;
  if (!info) return '—';
  return [info.first_name, info.middle_name, info.last_name, info.suffix].filter(Boolean).join(' ');
});

const info = computed(() => props.user.information ?? {});

const personalFields = computed(() => [
  { label: 'Sex',                    value: info.value.sex },
  { label: 'Birth Date',             value: info.value.birth_date },
  { label: 'Birth Place',            value: info.value.birth_place },
  { label: 'Civil Status',           value: info.value.civil_status },
  { label: 'Nationality',            value: info.value.nationality },
  { label: 'Religion',               value: info.value.religion },
  { label: 'Occupation',             value: info.value.occupation },
  { label: 'Educational Attainment', value: info.value.educational_attainment, span: true },
]);

const addressFields = computed(() => [
  { label: 'House No.',    value: info.value.house_no ?? info.value.house_number },
  { label: 'Sitio',        value: info.value.sitio ?? info.value.purok },
  { label: 'Barangay',     value: info.value.barangay },
  { label: 'Municipality', value: info.value.municipality ?? info.value.city },
  { label: 'Province',     value: info.value.province },
  { label: 'Zip Code',     value: info.value.zip_code ?? info.value.zipcode },
]);

const emergencyFields = computed(() => [
  { label: 'Contact Person', value: info.value.emergency_contact_person },
  { label: 'Number',         value: info.value.emergency_contact_number },
  { label: 'Relationship',   value: info.value.emergency_contact_relationship },
]);

async function handleToggleStatus() {
  const newStatus = !localStatus.value;
  const label = newStatus ? 'Active' : 'Inactive';

  const result = await Swal.fire({
    title: `Set as ${label}?`,
    html: `This will mark <strong>${fullName.value}</strong> as <strong>${label}</strong>.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: newStatus ? '#059669' : '#d97706',
    cancelButtonColor: '#6b7280',
    confirmButtonText: `Yes, set ${label}`,
    cancelButtonText: 'Cancel',
  });

  if (!result.isConfirmed) return;

  togglingStatus.value = true;
  try {
    await UserService.updateStatus(props.user.id, newStatus);
    localStatus.value = newStatus;
    emit('statusUpdated', { userId: props.user.id, status: newStatus });
    await Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: `User has been set to ${label}.`,
      timer: 1500,
      showConfirmButton: false,
    });
  } catch {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to update status. Please try again.',
      confirmButtonColor: '#3d4f7c',
    });
  } finally {
    togglingStatus.value = false;
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>