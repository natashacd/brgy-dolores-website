import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import News from '@/pages/News.vue'
import About from '@/pages/About.vue'
import Services from '@/pages/Services.vue'
import Login from '@/pages/admin/Login.vue'

// Routes
const routes = [
  // Public home pages
  {
    path: '/',
    name: 'index',
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
    component: Login
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

export default router