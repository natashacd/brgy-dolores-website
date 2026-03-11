<template>
  <div class="h-screen bg-gray-100 flex overflow-hidden">
    <!-- Mobile overlay -->
    <Transition name="fade">
      <div
        v-if="isSidebarOpen && !isDesktop"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
        @click="closeSidebar"
      />
    </Transition>

    <!-- Sidebar Component -->
    <Sidebar 
      :is-open="isSidebarOpen" 
      :is-desktop="isDesktop"
      @toggle="toggleSidebar" 
      @close="closeSidebar" 
    />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <!-- Top Header with Mobile Menu Button -->
      <header class="bg-white shadow-sm h-16 flex-shrink-0 flex items-center px-4">
        <!-- Mobile menu button - only visible on mobile -->
        <button
          v-if="!isDesktop"
          @click="toggleSidebar"
          class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors mr-2"
          aria-label="Toggle menu"
        >
          <svg 
            class="w-6 h-6 text-gray-600" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path 
              v-if="!isSidebarOpen"
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M4 6h16M4 12h16M4 18h16"
            />
            <path 
              v-else
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
        
        <!-- Empty space or title can go here -->
        <div class="flex-1">
          <h1 class="text-lg font-semibold text-gray-800 lg:hidden">Barangay Dolores</h1>
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
import { ref, onMounted, onUnmounted } from 'vue'
import Sidebar from '@/components/admin/Sidebar.vue'

const isSidebarOpen = ref(false) // Start closed on mobile
const isDesktop = ref(window.innerWidth >= 1024)

const toggleSidebar = () => { 
  isSidebarOpen.value = !isSidebarOpen.value 
}

const closeSidebar = () => { 
  isSidebarOpen.value = false 
}

// Handle window resize
const handleResize = () => {
  isDesktop.value = window.innerWidth >= 1024
  
  // Auto-open sidebar on desktop, close on mobile
  if (isDesktop.value) {
    isSidebarOpen.value = true
  } else {
    isSidebarOpen.value = false
  }
}

// Close sidebar when route changes on mobile
const handleRouteChange = () => {
  if (!isDesktop.value) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
  // Initial check
  handleResize()
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
main { scroll-behavior: smooth; }
main::-webkit-scrollbar { width: 8px; height: 8px; }
main::-webkit-scrollbar-track { background: #f1f1f1; }
main::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
main::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>