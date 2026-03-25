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

      <!-- Top Header -->
      <header class="bg-white shadow-sm h-20 flex-shrink-0 flex items-center px-6">
        <!-- Mobile menu button -->
        <button
          v-if="!isDesktop"
          @click="toggleSidebar"
          class="lg:hidden p-2.5 rounded-lg hover:bg-gray-100 transition-colors mr-3"
          aria-label="Toggle menu"
        >
          <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!isSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="flex-1">
          <h1 class="text-xl font-semibold text-gray-800 lg:hidden">Resident Portal</h1>
        </div>

        <!-- Notification Bell -->
        <div class="relative">
          <button
            @click="togglePanel"
            class="p-2.5 rounded-lg hover:bg-gray-100 transition-colors relative"
            title="Notifications"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping" />
            <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full" />
          </button>

          <!-- Notification Panel -->
          <Transition name="dropdown">
            <div
              v-if="showNotificationPanel"
              class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-50"
            >
              <div class="p-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] text-white">
                <h3 class="text-base font-semibold">Notifications</h3>
                <p class="text-sm text-white/70">You have {{ unreadCount }} unread</p>
              </div>

              <div class="max-h-96 overflow-y-auto">
                <!-- Loading -->
                <div v-if="loading" class="p-6 text-center text-sm text-gray-500">
                  Loading...
                </div>

                <!-- Empty -->
                <div v-else-if="notifications.length === 0" class="p-6 text-center text-sm text-gray-500">
                  No notifications yet
                </div>

                <!-- List -->
                <div v-else>
                  <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer"
                    :class="{ 'bg-blue-50/30': !notification.read_at }"
                    @click="markAsRead(notification)"
                  >
                    <div class="flex items-start gap-3">
                      <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="iconBgClass(notification.type)">
                          <svg class="w-4 h-4" :class="iconColorClass(notification.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="notification.type === 'approved'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path v-else-if="notification.type === 'rejected'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            <path v-else-if="notification.type === 'completed'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path v-else-if="notification.type === 'processing'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                      </div>
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ notification.message }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ formatTime(notification.created_at) }}</p>
                      </div>
                      <span v-if="!notification.read_at" class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="p-3 border-t border-slate-100 bg-slate-50">
                <button
                  @click="markAllAsRead"
                  class="text-sm text-[#3d4f7c] hover:text-[#252b3b] w-full text-center font-medium"
                >
                  Mark all as read
                </button>
              </div>
            </div>
          </Transition>
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
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'
import Sidebar from '@/components/admin/Sidebar.vue'
import { useRouter } from 'vue-router'
import NotificationService from '@/services/NotificationService'

const router = useRouter()
const isSidebarOpen = ref(false)
const isDesktop = ref(window.innerWidth >= 1024)
const showNotificationPanel = ref(false)
const notifications = ref([])
const loading = ref(false)

const unreadCount = computed(() => notifications.value.filter(n => !n.read_at).length)

const formatTime = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffMs = now - date
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMs / 3600000)
  const diffDays = Math.floor(diffMs / 86400000)
  if (diffMins < 1) return 'Just now'
  if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`
  if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`
  return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`
}

const iconBgClass = (type) => ({
  approved:   'bg-emerald-100',
  rejected:   'bg-red-100',
  completed:  'bg-green-100',
  processing: 'bg-blue-100',
  info:       'bg-blue-100',
}[type] ?? 'bg-blue-100')

const iconColorClass = (type) => ({
  approved:   'text-emerald-600',
  rejected:   'text-red-600',
  completed:  'text-green-600',
  processing: 'text-blue-600',
  info:       'text-blue-600',
}[type] ?? 'text-blue-600')

const loadNotifications = async () => {
  loading.value = true
  try {
    notifications.value = await NotificationService.getAll()
  } catch (e) {
    console.error('Failed to load notifications', e)
  } finally {
    loading.value = false
  }
}

const togglePanel = async () => {
  showNotificationPanel.value = !showNotificationPanel.value
  if (showNotificationPanel.value) {
    await loadNotifications()
  }
}

const markAsRead = async (notification) => {
  if (notification.read_at) return
  try {
    await NotificationService.markAsRead(notification.id)
    notification.read_at = new Date().toISOString()
  } catch (e) {
    console.error('Failed to mark as read', e)
  }
}

const markAllAsRead = async () => {
  try {
    await NotificationService.markAllAsRead()
    notifications.value.forEach(n => {
      if (!n.read_at) n.read_at = new Date().toISOString()
    })
    showNotificationPanel.value = false
  } catch (e) {
    console.error('Failed to mark all as read', e)
  }
}

const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value }
const closeSidebar  = () => { isSidebarOpen.value = false }

const handleResize = () => {
  isDesktop.value = window.innerWidth >= 1024
  isSidebarOpen.value = isDesktop.value
}

watch(() => router.currentRoute.value, () => {
  showNotificationPanel.value = false
})

onMounted(() => {
  window.addEventListener('resize', handleResize)
  handleResize()
  loadNotifications()
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
.fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }

.dropdown-enter-active,
.dropdown-leave-active { transition: all 0.2s ease; }
.dropdown-enter-from,
.dropdown-leave-to { opacity: 0; transform: translateY(-10px); }
</style>