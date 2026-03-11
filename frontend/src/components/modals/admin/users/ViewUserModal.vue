<template>
  <Transition name="fade">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
      @click.self="$emit('close')"
    >
      <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

            <!-- Resume-style Header -->
            <div class="flex-shrink-0 relative overflow-hidden bg-white border-b border-slate-200" style="box-shadow: 0 2px 12px rgba(0,0,0,0.06);">

            <!-- Close button -->
            <button
            class="absolute top-4 right-4 z-20 w-8 h-8 rounded-lg flex items-center justify-center transition-all cursor-pointer border border-slate-200 hover:border-slate-300 hover:bg-slate-50 bg-white"
            @click="$emit('close')"
            >
            <svg width="14" height="14" fill="none" stroke="#1a1f2e" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            </button>

            <div class="flex items-stretch">

            <!-- Left: Square Photo -->
            <div class="flex-shrink-0 p-6 flex items-center justify-center order-last">
              <div class="w-[160px] h-[160px] rounded-2xl overflow-hidden border-2 flex-shrink-0">
                <img
                  v-if="user.information?.image_path"
                  :src="`${baseUrl}/storage/${user.information.image_path}`"
                  class="w-full h-full object-cover object-top"
                  @error="e => { e.target.style.display='none'; e.target.nextElementSibling.style.display='flex' }"
                />
                <div
                  class="w-full h-full items-center justify-center"
                  :style="{ display: user.information?.image_path ? 'none' : 'flex', background: 'rgba(255,255,255,0.07)' }"
                >
                  <img src="@/assets/images/icons/profile.png" class="w-150 h-150 object-contain opacity-40" />
                </div>
              </div>
            </div>

            <div class="flex-1 px-6 py-8 flex flex-col justify-center">

            <!-- Status -->
            <div class="mb-4">
                <span
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold"
                :class="user.status?.status
                    ? 'bg-emerald-50 text-emerald-600 border border-emerald-200'
                    : 'bg-amber-50 text-amber-500 border border-amber-200'"
                >
                <span class="w-1.5 h-1.5 rounded-full" :class="user.status?.status ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'"></span>
                {{ user.status?.status ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <!-- Name -->
            <h3 class="text-[26px] font-bold text-slate-800 leading-tight tracking-tight">{{ fullName }}</h3>

            <!-- Role -->
            <div class="mt-2.5">
                <span class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border" style="background:#3d4f7c12; color:#3d4f7c; border-color:#3d4f7c25;">
                {{ user.role?.role_name ?? '—' }}
                </span>
            </div>

            <!-- Divider -->
            <div class="mt-5 mb-4 w-12 h-px bg-slate-200"></div>

            <!-- Contact info -->
            <div class="flex flex-col gap-2">
                <span v-if="user.email" class="inline-flex items-center gap-2 text-[12px] text-slate-500">
                <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0" style="background:#3d4f7c10">
                    <svg width="10" height="10" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                {{ user.email }}
                </span>
                <span v-if="user.information?.contact_number" class="inline-flex items-center gap-2 text-[12px] text-slate-500">
                <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0" style="background:#3d4f7c10">
                    <svg width="10" height="10" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                {{ user.information.contact_number }}
                </span>
            </div>
            </div>
          </div>
        </div>

        <!-- Scrollable Body -->
        <div class="overflow-y-auto flex-1 px-6 py-5 scrollbar-thin">

          <!-- Section: Personal Information -->
          <div class="mb-5">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-1 h-4 rounded-full" style="background:#3d4f7c"></div>
              <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Personal Information</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Sex</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.sex ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Birth Date</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.birth_date ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Birth Place</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.birth_place ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Civil Status</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.civil_status ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Nationality</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.nationality ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Religion</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.religion ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Occupation</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.occupation ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100 col-span-2">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Educational Attainment</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.educational_attainment ?? '—' }}</p>
              </div>
            </div>
          </div>

          <!-- Section: Address -->
          <div class="mb-5">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-1 h-4 rounded-full bg-emerald-500"></div>
              <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Address</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">House No.</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.house_no ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Sitio</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.sitio ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Latitude</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.latitude ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Longitude</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.longitude ?? '—' }}</p>
              </div>
            </div>
          </div>

          <!-- Section: Emergency Contact -->
          <div class="mb-2">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-1 h-4 rounded-full bg-red-400"></div>
              <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Emergency Contact</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Contact Person</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.emergency_contact_person ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Number</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.emergency_contact_number ?? '—' }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Relationship</p>
                <p class="text-sm text-slate-700 font-medium">{{ user.information?.emergency_contact_relationship ?? '—' }}</p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  user: { type: Object, required: true },
});

defineEmits(['close']);

const baseUrl = import.meta.env.VITE_API_URL;

const fullName = computed(() => {
  const info = props.user.information;
  if (!info) return '—';
  return [info.first_name, info.middle_name, info.last_name, info.suffix].filter(Boolean).join(' ');
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
</style>