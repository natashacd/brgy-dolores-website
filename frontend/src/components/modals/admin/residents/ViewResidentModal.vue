<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-2 sm:p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl overflow-hidden max-h-[98vh] sm:max-h-[92vh] flex flex-col">

      <!-- Header -->
      <div class="flex-shrink-0 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] px-5 sm:px-7 py-4 sm:py-5">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <div
              class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-base sm:text-lg font-bold text-white flex-shrink-0 shadow-lg ring-2 ring-white/20 overflow-hidden"
              :style="currentImage ? {} : { background: avatarBg(fullName) }"
            >
              <img v-if="currentImage" :src="currentImage" class="w-full h-full object-cover" alt="Profile" />
              <span v-else>{{ initials(fullName) }}</span>
            </div>
            <div class="min-w-0">
              <h2 class="text-base sm:text-xl font-bold text-white truncate">{{ fullName }}</h2>
            </div>
          </div>
          <button
            class="w-8 h-8 flex items-center justify-center rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer border-0 bg-transparent flex-shrink-0"
            @click="$emit('close')"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="flex-1 overflow-y-auto scrollbar-thin bg-[#f5f6fa]">
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">

          <!-- Personal Information -->
          <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="flex items-center gap-2.5 px-4 sm:px-5 py-3 border-b border-slate-100 bg-slate-50/60">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
                <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <h3 class="text-xs sm:text-sm font-bold text-slate-700">Personal Information</h3>
            </div>
            <div class="p-4 sm:p-5">
              <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-4">

                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">First Name</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.first_name ? 'text-slate-700' : 'text-slate-300'">{{ info.first_name || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Middle Name</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.middle_name ? 'text-slate-700' : 'text-slate-300'">{{ info.middle_name || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Last Name</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.last_name ? 'text-slate-700' : 'text-slate-300'">{{ info.last_name || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Suffix</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.suffix ? 'text-slate-700' : 'text-slate-300'">{{ info.suffix || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sex</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.sex ? 'text-slate-700' : 'text-slate-300'">{{ info.sex || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Birth Date</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.birth_date ? 'text-slate-700' : 'text-slate-300'">{{ formatDate(info.birth_date) || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Birth Place</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.birth_place ? 'text-slate-700' : 'text-slate-300'">{{ info.birth_place || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Civil Status</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.civil_status ? 'text-slate-700' : 'text-slate-300'">{{ info.civil_status || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Religion</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.religion ? 'text-slate-700' : 'text-slate-300'">{{ info.religion || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nationality</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.nationality ? 'text-slate-700' : 'text-slate-300'">{{ info.nationality || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Occupation</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.occupation ? 'text-slate-700' : 'text-slate-300'">{{ info.occupation || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Educational Attainment</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="info.educational_attainment ? 'text-slate-700' : 'text-slate-300'">{{ info.educational_attainment || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">PWD</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.pwd ? 'text-slate-700' : 'text-slate-300'">{{ info.pwd || '—' }}</span>
                </div>

              </div>
            </div>
          </div>

          <!-- Contact & Account + Emergency Contact -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

            <!-- Contact & Account -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
              <div class="flex items-center gap-2.5 px-4 sm:px-5 py-3 border-b border-slate-100 bg-slate-50/60">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
                  <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-700">Contact & Account</h3>
              </div>
              <div class="p-4 sm:p-5 space-y-4">
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</span>
                  <span class="text-xs sm:text-sm font-semibold break-all" :class="resident.email ? 'text-slate-700' : 'text-slate-300'">{{ resident.email || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Contact Number</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.contact_number ? 'text-slate-700' : 'text-slate-300'">{{ formatPhone(info.contact_number) }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Role</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="(resident.role?.role_name || resident.role?.name) ? 'text-slate-700' : 'text-slate-300'">{{ resident.role?.role_name || resident.role?.name || '—' }}</span>
                </div>
              </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
              <div class="flex items-center gap-2.5 px-4 sm:px-5 py-3 border-b border-slate-100 bg-slate-50/60">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#ef444415">
                  <svg width="13" height="13" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                </div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-700">Emergency Contact</h3>
              </div>
              <div class="p-4 sm:p-5 space-y-4">
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Person</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.emergency_contact_person ? 'text-slate-700' : 'text-slate-300'">{{ info.emergency_contact_person || '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Number</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.emergency_contact_number ? 'text-slate-700' : 'text-slate-300'">{{ formatPhone(info.emergency_contact_number) }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Relationship</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="info.emergency_contact_relationship ? 'text-slate-700' : 'text-slate-300'">{{ info.emergency_contact_relationship || '—' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Address & Map -->
          <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="flex items-center gap-2.5 px-4 sm:px-5 py-3 border-b border-slate-100 bg-slate-50/60">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#05966915">
                <svg width="13" height="13" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <h3 class="text-xs sm:text-sm font-bold text-slate-700">Address & Location</h3>
            </div>
            <div class="p-4 sm:p-5 space-y-4">
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-4">
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">House No.</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="addr.house_no ? 'text-slate-700' : 'text-slate-300'">{{ addr.house_no ? '#' + addr.house_no : '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Purok</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="addr.purok ? 'text-slate-700' : 'text-slate-300'">{{ addr.purok ? 'Purok ' + addr.purok : '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sitio</span>
                  <span class="text-xs sm:text-sm font-semibold" :class="addr.sitio ? 'text-slate-700' : 'text-slate-300'">{{ addr.sitio ? 'Sitio ' + addr.sitio : '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Coordinates</span>
                  <span class="text-xs sm:text-sm font-semibold break-words" :class="(addr.latitude && addr.longitude) ? 'text-slate-700' : 'text-slate-300'">
                    {{ addr.latitude && addr.longitude ? addr.latitude + ', ' + addr.longitude : '—' }}
                  </span>
                </div>
              </div>

              <!-- Google Map iframe -->
              <div v-if="hasCoordinates" class="rounded-xl overflow-hidden border border-slate-200 shadow-sm" style="height: 280px;">
                <iframe
                  :src="mapSrc"
                  width="100%"
                  height="100%"
                  style="border:0; display:block;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  title="Resident Location"
                ></iframe>
              </div>
              <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center gap-2 py-10">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-xs text-slate-400">No coordinates available</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Footer -->
      <div class="flex-shrink-0 flex items-center justify-end gap-2 px-5 sm:px-7 py-3 sm:py-4 border-t border-slate-100 bg-white">
        <button
          class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 text-xs sm:text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all cursor-pointer active:scale-95 border-0"
          @click="$emit('close')"
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
  resident: { type: Object, required: true },
});

defineEmits(["close"]);

const info = computed(() => props.resident.information || {});
const addr = computed(() => props.resident.address || {});

const fullName = computed(() => {
  const i = info.value;
  return [i.first_name, i.middle_name, i.last_name].filter(Boolean).join(" ") || "—";
});

const isActive = computed(() => props.resident.status?.status == 1);

const currentImage = computed(() => {
  if (!info.value.image_path) return null;
  const base = import.meta.env.VITE_API_URL || "";
  return `${base}/storage/${info.value.image_path}`;
});

const hasCoordinates = computed(() => !!addr.value.latitude && !!addr.value.longitude);

const mapSrc = computed(() => {
  if (!hasCoordinates.value) return "";
  return `https://maps.google.com/maps?q=${addr.value.latitude},${addr.value.longitude}&z=17&output=embed`;
});

const PALETTE = ["#2563eb", "#7c3aed", "#059669", "#d97706", "#dc2626", "#0891b2", "#9333ea", "#ea580c"];

function avatarBg(name) {
  if (!name || name === "—") return "#94a3b8";
  let h = 0;
  for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
  return PALETTE[Math.abs(h) % PALETTE.length];
}

function initials(name) {
  if (!name || name === "—") return "?";
  const parts = name.trim().split(" ");
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || "?";
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

function formatPhone(phone) {
  if (!phone) return "—";
  const cleaned = phone.replace(/\D/g, "");
  if (cleaned.length === 11 && cleaned.startsWith("0"))
    return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, "$1 $2 $3");
  return phone;
}

function formatDate(dateStr) {
  if (!dateStr) return null;
  try {
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-PH", { year: "numeric", month: "long", day: "numeric" });
  } catch {
    return dateStr;
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