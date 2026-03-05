<template>
  <header class="sticky top-0 z-50 shadow-2xl" style="background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 55%, #b91c1c 100%)">

    <!-- Announcement Bar -->
    <div class="bg-white/10 border-b border-white/15 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center justify-center gap-2.5">
        <span class="text-sm" style="animation: flagWave 3s ease-in-out infinite">🇵🇭</span>
        <p class="text-xs font-bold tracking-widest uppercase text-white/90 m-0">
          Barangay Dolores &mdash; Your community, our priority.
        </p>
        <span class="text-sm" style="animation: flagWave 3s ease-in-out infinite 1.5s">🇵🇭</span>
      </div>
    </div>

    <!-- Main Nav -->
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-[76px] gap-4">

        <!-- Brand -->
        <div class="flex items-center gap-3.5 flex-shrink-0">
          <!-- Logo -->
          <div class="relative" style="width:52px;height:52px">
            <div
              class="absolute -inset-1 rounded-full"
              style="background: radial-gradient(circle, rgba(245,197,24,0.5) 0%, transparent 70%); animation: glowPulse 2.5s ease-in-out infinite"
            ></div>
            <img
              src="@/assets/images/dolores_logo.png"
              alt="Barangay Dolores Logo"
              class="rounded-full border-2 border-white/90 object-cover relative z-10"
              style="width:52px;height:52px;box-shadow:0 0 0 3px rgba(245,197,24,0.3)"
              @error="$event.target.src = 'https://placehold.co/52x52/1e3a8a/dc2626?text=BD'"
            >
          </div>

          <!-- Name + Tagline -->
          <div class="flex flex-col gap-0.5">
            <h1 class="m-0 leading-none flex flex-col gap-0.5">
              <span class="text-[0.6rem] font-semibold tracking-[0.22em] uppercase text-white/70">Barangay</span>
              <span class="text-[1.4rem] font-extrabold text-white tracking-tight" style="text-shadow:0 2px 8px rgba(0,0,0,0.3)">Dolores</span>
            </h1>
            <p class="text-[0.6rem] text-white/55 tracking-wide m-0">City of San Fernando, Pampanga</p>
          </div>
        </div>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center gap-0.5">
          <router-link
            v-for="link in navLinks"
            :key="link.path"
            :to="link.path"
            class="relative flex items-center px-4 py-2 rounded-lg text-sm font-semibold tracking-wide no-underline transition-all duration-200 overflow-hidden group"
            :class="isActiveRoute(link.path)
              ? 'text-white bg-white/15'
              : 'text-white/80 hover:text-white hover:bg-white/10'"
          >
            <span>{{ link.name }}</span>
            <!-- Gold underline indicator -->
            <span
              class="absolute bottom-1 left-3 right-3 h-0.5 rounded-full transition-transform duration-200 origin-center group-hover:scale-x-100"
              style="background:#f5c518"
              :class="isActiveRoute(link.path) ? 'scale-x-100' : 'scale-x-0'"
            ></span>
          </router-link>
        </div>

        <!-- CTA Button -->
        <div class="hidden md:block flex-shrink-0">
          <router-link
            to="/contact"
            class="inline-flex items-center gap-2 bg-white text-blue-900 px-5 py-2.5 rounded-xl text-sm font-bold tracking-wide no-underline border-2 border-transparent shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-600 hover:text-white hover:border-white/40"
            style="transition: all 0.2s ease"
          >
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Visit Hall</span>
          </router-link>
        </div>

        <!-- Mobile Hamburger -->
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden flex flex-col gap-1.5 p-2 bg-white/10 border-0 rounded-lg cursor-pointer transition-colors duration-200 hover:bg-white/20"
          aria-label="Toggle menu"
        >
          <span
            class="block bg-white rounded-sm transition-all duration-300 origin-center"
            style="width:22px;height:2.5px"
            :class="mobileMenuOpen ? 'rotate-45 translate-y-2' : ''"
          ></span>
          <span
            class="block bg-white rounded-sm transition-all duration-300"
            style="width:22px;height:2.5px"
            :class="mobileMenuOpen ? 'opacity-0' : 'opacity-100'"
          ></span>
          <span
            class="block bg-white rounded-sm transition-all duration-300 origin-center"
            style="width:22px;height:2.5px"
            :class="mobileMenuOpen ? '-rotate-45 -translate-y-2' : ''"
          ></span>
        </button>

      </div>

      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div v-show="mobileMenuOpen" class="md:hidden border-t border-white/15">
          <div class="flex flex-col gap-1 py-3 pb-4">
            <router-link
              v-for="link in navLinks"
              :key="link.path"
              :to="link.path"
              class="block py-3 text-[0.95rem] font-semibold no-underline rounded-lg transition-all duration-200"
              :class="isActiveRoute(link.path)
                ? 'bg-white/15 text-white border-l-[3px] border-yellow-400 pl-4 pr-5'
                : 'text-white/85 hover:bg-white/10 hover:text-white px-5'"
              @click="mobileMenuOpen = false"
            >
              {{ link.name }}
            </router-link>

            <router-link
              to="/contact"
              class="block mt-2 mx-0 px-5 py-3.5 bg-white text-blue-900 text-[0.9rem] font-bold text-center no-underline rounded-xl transition-all duration-200 hover:bg-red-600 hover:text-white"
              @click="mobileMenuOpen = false"
            >
              🏛️ Visit Barangay Hall
            </router-link>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Shimmer Accent Line -->
    <div
      class="h-[3px]"
      style="background: linear-gradient(90deg, #0f2d6b 0%, #f5c518 30%, #c8102e 60%, #0f2d6b 100%); background-size: 200% 100%; animation: shimmer 4s linear infinite"
    ></div>

  </header>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const mobileMenuOpen = ref(false)

const navLinks = [
  { name: 'Home',     path: '/' },
  { name: 'Services', path: '/services' },
  { name: 'About',    path: '/about' },
  { name: 'News',     path: '/news' },
]

const isActiveRoute = (path) => {
  if (!route?.path) return false
  if (path === '/') return route.path === '/'
  return route.path.startsWith(path)
}

watchEffect(() => {
  if (route?.path) mobileMenuOpen.value = false
})
</script>

<style scoped>
@keyframes flagWave {
  0%, 100% { transform: rotate(0deg); }
  25%       { transform: rotate(8deg); }
  75%       { transform: rotate(-8deg); }
}
@keyframes glowPulse {
  0%, 100% { opacity: 0.6; transform: scale(1); }
  50%       { opacity: 1;   transform: scale(1.15); }
}
@keyframes shimmer {
  0%   { background-position: 100% 0; }
  100% { background-position: -100% 0; }
}
</style>