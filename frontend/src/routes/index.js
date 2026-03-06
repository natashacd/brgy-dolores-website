import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import News from '@/pages/News.vue'
import About from '@/pages/About.vue'
import Services from '@/pages/Services.vue'
import Login from '@/pages/admin/Login.vue'

// Admin routes
import AdminDashboard from '@/pages/admin/Dashboard.vue'
// ... other admin imports

const routes = [
  // Public routes
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/about',
    name: 'about',
    component: About
  },
  {
    path: '/services',
    name: 'services',
    component: Services
  },
  {
    path: '/news',
    name: 'news',
    component: News
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  
  // Admin routes
  {
    path: '/admin',
    component: () => import('@/layout/admin/Layout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: { title: 'Dashboard' }
      }
      // ... other admin routes
    ]
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

// Updated navigation guard - no more next() calls
router.beforeEach((to, from) => {
  const isAuthenticated = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token')
  
  // If route requires auth and user is not authenticated
  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }
  
  // If route is for guests only (login) and user is already authenticated
  if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
    return { name: 'admin.dashboard' }
  }
  
  // Allow access
  return true
})

export default router