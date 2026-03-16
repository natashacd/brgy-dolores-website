<template>
  <!-- Mobile overlay with blur - only show when open on mobile -->
  <Transition name="fade">
    <div
      v-if="isOpen && !isDesktop"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
      @click="closeMobileSidebar"
    />
  </Transition>

  <aside
    class="fixed lg:static inset-y-0 left-0 z-50 flex flex-col h-screen
           bg-[#1a1f2e] text-white
           shadow-2xl
           transition-all duration-300 ease-out"
    :class="[
      isDesktop ? (isCollapsed ? 'lg:w-24' : 'lg:w-80') : (isOpen ? 'translate-x-0' : '-translate-x-full'),
      { 'w-80': !isDesktop }
    ]"
  >
    <!-- Header with Logo and Collapse Button -->
    <div class="px-4 py-7 border-b border-gray-700/50">
      <div class="flex items-center" :class="isCollapsed ? 'justify-center' : 'justify-between'">
        <!-- Logo + Title -->
        <div class="flex items-center gap-4 min-w-0">
          <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
            <img
              src="@/assets/images/dolores_logo.png"
              alt="Barangay Dolores"
              class="w-10 h-10 rounded-md object-cover"
              @error="handleImageError"
            />
          </div>
          <div v-if="!isCollapsed || !isDesktop" class="min-w-0" :class="{ 'hidden lg:block': isCollapsed && isDesktop }">
            <h2 class="font-semibold text-xl text-white truncate">Barangay Dolores</h2>
            <p class="text-base text-gray-400">{{ userRole === 'resident' ? 'Resident Portal' : 'Admin Dashboard' }}</p>
          </div>
        </div>

        <!-- Desktop Collapse Button (only on desktop) -->
        <button
          v-if="isDesktop && !isCollapsed"
          @click="toggleCollapse"
          class="w-8 h-8 rounded-lg hover:bg-[#2a2f3f] flex items-center justify-center transition-colors group flex-shrink-0"
        >
          <img
            src="@/assets/images/icons/out.png"
            alt=""
            class="w-5 h-5 object-contain brightness-0 invert opacity-80 group-hover:opacity-100 transition-opacity"
            @error="handleIconError"
          />
        </button>

        <!-- Mobile Close Button (only on mobile) -->
        <button
          v-if="!isDesktop && isOpen"
          @click="closeMobileSidebar"
          class="w-8 h-8 rounded-lg hover:bg-[#2a2f3f] flex items-center justify-center transition-colors group flex-shrink-0"
        >
          <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Desktop Expand button shown BELOW logo when collapsed (desktop only) -->
      <div v-if="isDesktop && isCollapsed" class="hidden lg:flex justify-center mt-4">
        <button
          @click="toggleCollapse"
          class="w-8 h-8 rounded-lg hover:bg-[#2a2f3f] flex items-center justify-center transition-colors group"
        >
          <img
            src="@/assets/images/icons/in.png"
            alt=""
            class="w-5 h-5 object-contain brightness-0 invert opacity-80 group-hover:opacity-100 transition-opacity"
            @error="handleIconError"
          />
        </button>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 scrollbar-thin">
      
      <!-- Main Section (only for admin/officials) -->
      <div v-if="userRole !== 'resident'">
        <p v-if="!isCollapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">MAIN</p>
        <ul class="space-y-1">
          <li>
            <router-link
              to="/admin"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Dashboard' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Dashboard</span>
            </router-link>
          </li>
          <li>
            <router-link
              to="/admin/users"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/users') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Barangay Officials' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Barangay Officials</span>
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Management Section -->
      <div>
        <p v-if="!isCollapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">
          {{ userRole === 'resident' ? 'SERVICES' : 'MANAGEMENT' }}
        </p>
        <ul class="space-y-1">
          <!-- Resident Management -->
          <li v-if="userRole !== 'resident'">
            <router-link
              to="/admin/residents"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/residents') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Resident Management' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Resident Management</span>
            </router-link>
          </li>
          
          <!-- Resident Dashboard -->
          <li v-if="userRole === 'resident'">
            <router-link
              to="/resident/dashboard"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/resident/dashboard') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Dashboard' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span v-if="!isCollapsed">Dashboard</span>
            </router-link>
          </li>
          
          <!-- Service Requests with Enhanced Dropdown -->
          <li v-if="userRole !== 'resident'">
            <!-- Main Service Requests Button (acts as dropdown trigger) -->
            <button
              @click="toggleServiceRequestsDropdown"
              class="w-full flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors text-gray-300 hover:bg-[#252a3a] hover:text-white"
              :class="{ 'bg-[#2a2f3f] text-white': isServiceRequestsActive }"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1 text-left">Service Requests</span>
              <svg 
                v-if="!isCollapsed"
                class="w-5 h-5 transition-transform duration-200 text-gray-400" 
                :class="{ 'rotate-180': serviceRequestsDropdownOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Items -->
            <Transition name="dropdown">
              <div v-if="serviceRequestsDropdownOpen && !isCollapsed" class="mt-1 ml-11 space-y-1">
                <!-- All Requests -->
                <router-link
                  to="/admin/service-requests"
                  class="flex items-center gap-4 px-3 py-2.5 rounded-lg text-base transition-colors"
                  :class="isActiveRoute('/admin/service-requests') && !isActiveRoute('/admin/service-requests/disapproved') && !isActiveRoute('/admin/service-requests/approved') && !isActiveRoute('/admin/service-requests/released') ? 'bg-[#2a2f3f] text-white' : 'text-gray-400 hover:bg-[#252a3a] hover:text-white'"
                  @click="closeMobileSidebar"
                >
                  <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                  <span class="flex-1">All Requests</span>
                </router-link>
                
                <!-- Approved Requests -->
                <router-link
                  to="/admin/service-requests/approved"
                  class="flex items-center gap-4 px-3 py-2.5 rounded-lg text-base transition-colors"
                  :class="isActiveRoute('/admin/service-requests/approved') ? 'bg-[#2a2f3f] text-white' : 'text-gray-400 hover:bg-[#252a3a] hover:text-white'"
                  @click="closeMobileSidebar"
                >
                  <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                  <span class="flex-1">Approved Requests</span>
                </router-link>
                
                <!-- Disapproved Requests -->
                <router-link
                  to="/admin/service-requests/disapproved"
                  class="flex items-center gap-4 px-3 py-2.5 rounded-lg text-base transition-colors"
                  :class="isActiveRoute('/admin/service-requests/disapproved') ? 'bg-[#2a2f3f] text-white' : 'text-gray-400 hover:bg-[#252a3a] hover:text-white'"
                  @click="closeMobileSidebar"
                >
                  <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                  <span class="flex-1">Disapproved Requests</span>
                </router-link>

                <!-- Released Requests -->
                <router-link
                  to="/admin/service-requests/released"
                  class="flex items-center gap-4 px-3 py-2.5 rounded-lg text-base transition-colors"
                  :class="isActiveRoute('/admin/service-requests/released') ? 'bg-[#2a2f3f] text-white' : 'text-gray-400 hover:bg-[#252a3a] hover:text-white'"
                  @click="closeMobileSidebar"
                >
                  <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                  <span class="flex-1">Released Requests</span>
                </router-link>
              </div>
            </Transition>
          </li>
          
          <!-- Service Requests for residents -->
          <li v-if="userRole === 'resident'">
            <router-link
              to="/resident/service-requests"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/resident/service-requests') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Service Requests' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
              <span v-if="!isCollapsed">Service Requests</span>
            </router-link>
          </li>
          
          <!-- Complaints & Disputes -->
          <li v-if="userRole !== 'resident'">
            <router-link
              to="/admin/complaints"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/complaints') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Complaints & Disputes' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Complaints & Disputes</span>
            </router-link>
          </li>

           <!-- Complaints & Disputes -->
          <li v-if="userRole === 'resident'">
            <router-link
              to="/resident/complaints"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/resident/complaints') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Complaints & Disputes' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Complaints & Reports</span>
            </router-link>
          </li>
          
          <!-- Reports & Analytics -->
          <li v-if="userRole !== 'resident'">
            <router-link
              to="/admin/reports"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/reports') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Reports & Analytics' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Reports & Analytics</span>
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Content Section -->
      <div v-if="userRole !== 'resident'">
        <p v-if="!isCollapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">CONTENT</p>
        <ul class="space-y-1">
          <li>
            <router-link
              to="/admin/announcements"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/announcements') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Announcements' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Announcements</span>
            </router-link>
          </li>
          <li>
            <router-link
              to="/admin/settings"
              class="flex items-center gap-4 px-3 py-3 rounded-lg text-base transition-colors"
              :class="isActiveRoute('/admin/settings') ? 'bg-[#2a2f3f] text-white' : 'text-gray-300 hover:bg-[#252a3a] hover:text-white'"
              :title="isCollapsed ? 'Settings' : ''"
              @click="closeMobileSidebar"
            >
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span v-if="!isCollapsed" class="flex-1">Settings</span>
            </router-link>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Bottom Section with Logout -->
    <div class="p-4 border-t border-[#2a2f3f] mt-auto">
      <button
        @click="confirmLogout"
        class="flex items-center gap-4 px-3 py-3 rounded-lg text-base text-gray-300 hover:bg-red-500/10 hover:text-red-400 w-full transition-colors"
        :title="isCollapsed ? 'Logout' : ''"
      >
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span v-if="!isCollapsed" class="flex-1 text-left">Log out</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  isDesktop: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['toggle', 'close'])

const route = useRoute()
const router = useRouter()
const isCollapsed = ref(false)
const isDesktop = ref(window.innerWidth >= 1024)
const serviceRequestsDropdownOpen = ref(false)

const userRole = ref(localStorage.getItem('user_role') || '')
const userName = ref(localStorage.getItem('user_name') || 'User')
const userInitials = computed(() => {
  if (!userName.value) return 'U'
  const parts = userName.value.split(' ')
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || 'U'
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
})

const isServiceRequestsActive = computed(() => {
  return route.path.startsWith('/admin/service-requests')
})

const handleImageError = (event) => {
  const img = event.target
  img.src = 'https://placehold.co/40x40/1e3a8a/ffffff?text=BD'
}

const isActiveRoute = (path) => {
  if (path === '/resident/service-requests') {
    return route.path === '/resident/service-requests'
  }
  if (path === '/admin') {
    return route.path === '/admin'
  }
  return route.path === path || route.path.startsWith(path + '/')
}

const handleResize = () => {
  const wasDesktop = isDesktop.value
  isDesktop.value = window.innerWidth >= 1024
  
  if (!wasDesktop && isDesktop.value) {
    emit('close') 
  }
  
  if (isCollapsed.value) {
    serviceRequestsDropdownOpen.value = false
  }
}

const toggleCollapse = () => {
  if (isDesktop.value) {
    isCollapsed.value = !isCollapsed.value
    localStorage.setItem('sidebarCollapsed', isCollapsed.value.toString())
    if (isCollapsed.value) {
      serviceRequestsDropdownOpen.value = false
    }
  }
}

const toggleServiceRequestsDropdown = () => {
  serviceRequestsDropdownOpen.value = !serviceRequestsDropdownOpen.value
}

const closeMobileSidebar = () => {
  if (!isDesktop.value) {
    emit('close')
  }
}

const handleIconError = (event) => {
  const img = event.target
  img.style.display = 'none'
  const parent = img.parentNode
  const fallback = document.createElement('span')
  fallback.className = 'text-gray-400 group-hover:text-white text-lg'
  fallback.textContent = img.alt === 'Collapse sidebar' ? '<<' : '>>'
  parent.appendChild(fallback)
}

onMounted(() => {
  const saved = localStorage.getItem('sidebarCollapsed')
  if (saved !== null) {
    isCollapsed.value = saved === 'true'
  }
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

const confirmLogout = async () => {
  const result = await Swal.fire({
    title: 'Logout?',
    text: 'Are you sure you want to logout?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, logout',
    cancelButtonText: 'Cancel'
  })

  if (result.isConfirmed) {
    await handleLogout()
  }
}

const handleLogout = async () => {
  try {
    import('@/utils/dataStore').then(({ clearData }) => {
      clearData();
      console.log('✅ Cache cleared on logout');
    });

    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    localStorage.removeItem('user_name')
    localStorage.removeItem('user_role')
    localStorage.removeItem('user_email')
    sessionStorage.removeItem('auth_token')
    
    await Swal.fire({
      icon: 'success',
      title: 'Logged out!',
      text: 'You have been successfully logged out.',
      timer: 1500,
      showConfirmButton: false
    })
    
    await router.push('/login')
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to logout. Please try again.',
      confirmButtonColor: '#3085d6'
    })
  }
}
</script>

<style scoped>
.scrollbar-thin {
  scrollbar-width: thin;
}
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #2a2f3f;
  border-radius: 20px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #3a4050;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

</style>