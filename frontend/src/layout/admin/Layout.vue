<template>
  <div class="h-screen bg-gray-100 flex overflow-hidden">
    <!-- Sidebar Component - Set isOpen to true by default -->
    <Sidebar 
      :is-open="true" 
      @toggle="toggleSidebar" 
      @close="closeSidebar" 
    />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <!-- Top Header - Empty -->
      <header class="bg-white shadow-sm h-16 flex-shrink-0 flex items-center px-4">
        <div class="flex-1"></div>
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
import { ref } from 'vue'
import Sidebar from '@/components/admin/Sidebar.vue'

const isSidebarOpen = ref(true) // Set to true by default

const toggleSidebar = () => { 
  isSidebarOpen.value = !isSidebarOpen.value 
}

const closeSidebar = () => { 
  isSidebarOpen.value = false 
}
</script>

<style scoped>
main { scroll-behavior: smooth; }
main::-webkit-scrollbar { width: 8px; height: 8px; }
main::-webkit-scrollbar-track { background: #f1f1f1; }
main::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
main::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>