<template>
  <div class="h-screen bg-gray-100 flex overflow-hidden">
    <!-- Sidebar Component -->
    <Sidebar :is-open="isSidebarOpen" @toggle="toggleSidebar" @close="closeSidebar" />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <!-- Top Header -->
      <header class="bg-white shadow-sm sticky top-0 z-40 shrink-0">
        <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
          <!-- Mobile menu button -->
          <button 
            @click="toggleSidebar" 
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
            aria-label="Toggle menu"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <div class="flex items-center gap-3">
            <h1 class="text-xl font-semibold text-gray-800">{{ pageTitle }}</h1>
            <span v-if="isDashboard" class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
              Overview
            </span>
          </div>

          <!-- User Menu -->
          <div class="flex items-center gap-4">
            <div class="relative" ref="userMenuRef">
              <button 
                @click="isUserMenuOpen = !isUserMenuOpen"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors"
              >
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                  {{ userInitials }}
                </div>
                <div class="hidden md:block text-left">
                  <p class="text-sm font-medium text-gray-700">{{ userName }}</p>
                  <p class="text-xs text-gray-500">{{ userRole }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': isUserMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <Transition name="dropdown">
                <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border z-50">
                  <router-link to="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Your Profile
                  </router-link>
                  <router-link to="/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Settings
                  </router-link>
                  <hr class="my-1 border-gray-200">
                  <button @click="logout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    Logout
                  </button>
                </div>
              </Transition>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto bg-gray-100">
        <div class="p-2 sm:p-4 lg:p-6">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Sidebar from '@/components/admin/Sidebar.vue'

const route = useRoute()
const router = useRouter()
const isSidebarOpen = ref(false)
const isUserMenuOpen = ref(false)
const userMenuRef = ref(null)

const userName = ref('Hon. Juan Dela Cruz')
const userRole = ref('Punong Barangay')

const pageTitle = computed(() => route.meta?.title || 'Dashboard')
const isDashboard = computed(() => route.path === '/admin' || route.path === '/admin/')
const userInitials = computed(() => userName.value.split(' ').map(n => n[0]).join('').toUpperCase())

const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value }
const closeSidebar = () => { isSidebarOpen.value = false }

const logout = () => {
  localStorage.removeItem('auth_token')
  sessionStorage.removeItem('auth_token')
  router.push('/login')
}

const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    isUserMenuOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
main { scroll-behavior: smooth; }
main::-webkit-scrollbar { width: 8px; height: 8px; }
main::-webkit-scrollbar-track { background: #f1f1f1; }
main::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
main::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

.dropdown-enter-active, .dropdown-leave-active { transition: all 0.2s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-10px); }
.rotate-180 { transform: rotate(180deg); }
</style>