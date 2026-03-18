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

    <!-- Lupon Sidebar Component -->
    <LuponSidebar 
      :is-open="isSidebarOpen" 
      :is-desktop="isDesktop"
      @toggle="toggleSidebar" 
      @close="closeSidebar" 
    />

    <!-- Notification Toast - Wider -->
    <Transition name="notification">
      <div 
        v-if="showNotification" 
        class="fixed top-4 right-4 z-50 max-w-lg w-full bg-white rounded-xl shadow-2xl border-l-4 overflow-hidden"
        :class="notificationBorderColor"
      >
        <div class="p-5">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 rounded-full flex items-center justify-center" :class="notificationIconBg">
                <svg class="w-6 h-6" :class="notificationIconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="notificationType === 'assigned'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  <path v-else-if="notificationType === 'escalated'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  <path v-else-if="notificationType === 'resolved'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  <path v-else-if="notificationType === 'reassigned'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-base font-semibold text-gray-900">{{ notificationTitle }}</h3>
              <p class="text-sm text-gray-600 mt-1">{{ notificationMessage }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ notificationTime }}</p>
            </div>
            <button @click="showNotification = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <!-- Progress bar -->
        <div class="h-1.5" :class="notificationProgressColor" :style="{ width: notificationProgress + '%' }"></div>
      </div>
    </Transition>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      
      <!-- Top Header with Mobile Menu Button and Notification Bell -->
      <header class="bg-white shadow-sm h-20 flex-shrink-0 flex items-center px-6">
        <!-- Mobile menu button - only visible on mobile -->
        <button
          v-if="!isDesktop"
          @click="toggleSidebar"
          class="lg:hidden p-2.5 rounded-lg hover:bg-gray-100 transition-colors mr-3"
          aria-label="Toggle menu"
        >
          <svg 
            class="w-7 h-7 text-gray-600" 
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
        
        <!-- Title - Larger on mobile -->
        <div class="flex-1">
        </div>

        <!-- Desktop Title and Notifications -->
        <div class="hidden lg:flex items-center gap-6">
          
          <!-- Notification Bell with Badge -->
          <div class="relative">
            <button 
              @click="showNotificationPanel = !showNotificationPanel"
              class="p-2.5 rounded-lg hover:bg-gray-100 transition-colors relative"
              title="Notifications"
            >
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span v-if="unreadCount > 0" 
                class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
              <span v-if="unreadCount > 0" 
                class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
            </button>

            <!-- Notification Panel -->
            <Transition name="dropdown">
              <div v-if="showNotificationPanel" 
                class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                <div class="p-4 bg-gradient-to-r from-[#3d4f7c] to-[#252b3b] text-white">
                  <h3 class="text-base font-semibold">Notifications</h3>
                  <p class="text-sm text-white/70">You have {{ unreadCount }} unread</p>
                </div>
                <div class="max-h-96 overflow-y-auto">
                  <div v-if="notifications.length === 0" class="p-6 text-center text-sm text-gray-500">
                    No notifications yet
                  </div>
                  <div v-else>
                    <div 
                      v-for="notification in notifications" 
                      :key="notification.id"
                      class="p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer"
                      :class="{ 'bg-blue-50/30': !notification.read }"
                      @click="markAsRead(notification)"
                    >
                      <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                          <div class="w-8 h-8 rounded-full" :class="notificationIconBgClass(notification.type)">
                            <svg class="w-4 h-4 m-2" :class="notificationIconColorClass(notification.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path v-if="notification.type === 'assigned'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                              <path v-else-if="notification.type === 'escalated'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                              <path v-else-if="notification.type === 'resolved'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                              <path v-else-if="notification.type === 'reassigned'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </div>
                        </div>
                        <div class="flex-1">
                          <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                          <p class="text-xs text-gray-500 mt-0.5">{{ notification.message }}</p>
                          <p class="text-[10px] text-gray-400 mt-1">{{ formatTime(notification.created_at) }}</p>
                        </div>
                        <span v-if="!notification.read" class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="p-3 border-t border-slate-100 bg-slate-50">
                  <button @click="markAllAsRead" class="text-sm text-[#3d4f7c] hover:text-[#252b3b] w-full text-center font-medium">
                    Mark all as read
                  </button>
                </div>
              </div>
            </Transition>
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
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'
import LuponSidebar from '@/components/admin/Sidebar.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isSidebarOpen = ref(false)
const isDesktop = ref(window.innerWidth >= 1024)
const userName = ref(localStorage.getItem('user_name') || 'Lupon Member')

// Notification state
const showNotification = ref(false)
const notificationType = ref('info')
const notificationTitle = ref('')
const notificationMessage = ref('')
const notificationTime = ref('')
const notificationProgress = ref(100)
const notificationTimer = ref(null)
const showNotificationPanel = ref(false)
const notifications = ref([])
let notificationId = 1

const notificationBorderColor = computed(() => {
  return {
    'assigned': 'border-emerald-500',
    'escalated': 'border-amber-500',
    'resolved': 'border-green-500',
    'reassigned': 'border-blue-500',
    'info': 'border-blue-500'
  }[notificationType.value] || 'border-blue-500'
})

const notificationIconBg = computed(() => {
  return {
    'assigned': 'bg-emerald-100',
    'escalated': 'bg-amber-100',
    'resolved': 'bg-green-100',
    'reassigned': 'bg-blue-100',
    'info': 'bg-blue-100'
  }[notificationType.value] || 'bg-blue-100'
})

const notificationIconColor = computed(() => {
  return {
    'assigned': 'text-emerald-600',
    'escalated': 'text-amber-600',
    'resolved': 'text-green-600',
    'reassigned': 'text-blue-600',
    'info': 'text-blue-600'
  }[notificationType.value] || 'text-blue-600'
})

const notificationProgressColor = computed(() => {
  return {
    'assigned': 'bg-emerald-500',
    'escalated': 'bg-amber-500',
    'resolved': 'bg-green-500',
    'reassigned': 'bg-blue-500',
    'info': 'bg-blue-500'
  }[notificationType.value] || 'bg-blue-500'
})

const notificationIconBgClass = (type) => {
  return {
    'assigned': 'bg-emerald-100',
    'escalated': 'bg-amber-100',
    'resolved': 'bg-green-100',
    'reassigned': 'bg-blue-100',
    'info': 'bg-blue-100'
  }[type] || 'bg-blue-100'
}

const notificationIconColorClass = (type) => {
  return {
    'assigned': 'text-emerald-600',
    'escalated': 'text-amber-600',
    'resolved': 'text-green-600',
    'reassigned': 'text-blue-600',
    'info': 'text-blue-600'
  }[type] || 'text-blue-600'
}

const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.read).length
})

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

const showToastNotification = (title, message, type = 'info') => {
  if (notificationTimer.value) {
    clearInterval(notificationTimer.value)
    notificationTimer.value = null
  }
  
  notificationTitle.value = title
  notificationMessage.value = message
  notificationType.value = type
  notificationTime.value = 'Just now'
  showNotification.value = true
  notificationProgress.value = 100

  const startTime = Date.now()
  const duration = 5000 // 5 seconds
  
  notificationTimer.value = setInterval(() => {
    const elapsed = Date.now() - startTime
    const remaining = Math.max(0, duration - elapsed)
    notificationProgress.value = (remaining / duration) * 100
    
    if (remaining <= 0) {
      clearInterval(notificationTimer.value)
      notificationTimer.value = null
      showNotification.value = false
    }
  }, 100)
}

const addNotification = (title, message, type = 'info') => {
  const newNotification = {
    id: notificationId++,
    title,
    message,
    type,
    read: false,
    created_at: new Date().toISOString()
  }
  notifications.value.unshift(newNotification)
  
  showToastNotification(title, message, type)
  
  if (notifications.value.length > 50) {
    notifications.value = notifications.value.slice(0, 50)
  }
  
  saveNotifications()
}

const markAsRead = (notification) => {
  notification.read = true
  saveNotifications()
}

const markAllAsRead = () => {
  notifications.value.forEach(n => n.read = true)
  saveNotifications()
  showNotificationPanel.value = false
}

const saveNotifications = () => {
  localStorage.setItem('lupon_notifications', JSON.stringify(notifications.value))
}

const loadNotifications = () => {
  const saved = localStorage.getItem('lupon_notifications')
  if (saved) {
    try {
      notifications.value = JSON.parse(saved)
      const maxId = Math.max(...notifications.value.map(n => n.id), 0)
      notificationId = maxId + 1
    } catch (e) {
      console.error('Failed to load notifications', e)
    }
  }
}

const stopAllNotifications = () => {
  if (notificationTimer.value) {
    clearInterval(notificationTimer.value)
    notificationTimer.value = null
  }
  showNotification.value = false
}

const triggerSampleNotifications = () => {
  setTimeout(() => {
    addNotification(
      'New Case Assigned',
      'You have been assigned to Case #L-2024-001 - Land Dispute',
      'assigned'
    )
  }, 3000)
  
  setTimeout(() => {
    addNotification(
      'Case Reassigned',
      'Case #L-2024-003 has been reassigned to Lupon Member Juan Dela Cruz',
      'reassigned'
    )
  }, 8000)
  
  setTimeout(() => {
    addNotification(
      'Case Escalated',
      'Case #L-2024-002 has been escalated to Barangay Captain',
      'escalated'
    )
  }, 15000)
}

const toggleSidebar = () => { 
  isSidebarOpen.value = !isSidebarOpen.value 
}

const closeSidebar = () => { 
  isSidebarOpen.value = false 
}

const handleResize = () => {
  isDesktop.value = window.innerWidth >= 1024
  
  if (isDesktop.value) {
    isSidebarOpen.value = true
  } else {
    isSidebarOpen.value = false
  }
}

watch(() => router.currentRoute.value, () => {
  showNotificationPanel.value = false
})

onMounted(() => {
  window.addEventListener('resize', handleResize)
  handleResize()
  loadNotifications()
  
  setTimeout(() => {
    addNotification(
      'Welcome to Lupon Panel',
      'You will receive notifications for case assignments and updates.',
      'info'
    )
  }, 2000)
  
  triggerSampleNotifications()
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
  stopAllNotifications()
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

.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}
.notification-enter-from {
  opacity: 0;
  transform: translateX(100%);
}
.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
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

.bg-emerald-500, .bg-amber-500, .bg-green-500, .bg-blue-500 {
  transition: width 0.1s linear;
}
</style>