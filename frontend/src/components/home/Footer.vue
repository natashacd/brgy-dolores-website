<script setup>
import { useVisitorCounter } from '@/composables/useVisitorCounter'
const { count } = useVisitorCounter()

const quickLinks = [
  { name: 'Home',           path: '/' },
  { name: 'About',          path: '/about' },
  { name: 'Services',       path: '/services' },
  { name: 'News & Updates', path: '/news' },
]
</script>

<template>
  <footer class="relative text-white" style="background: linear-gradient(135deg, #0a1f4e 0%, #0f2d6b 50%, #1a3f8f 100%)">

    <div
      class="h-[3px]"
      style="background: linear-gradient(90deg, #0f2d6b 0%, #f5c518 30%, #c8102e 60%, #0f2d6b 100%); background-size: 200% 100%; animation: shimmer 4s linear infinite"
    ></div>

    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-14">

        <!-- About Section -->
        <div class="flex flex-col gap-5">
          <!-- Mini Brand -->
          <div class="flex items-center gap-3.5">
            <div class="relative flex-shrink-0" style="width:50px;height:50px">
              <div
                class="absolute -inset-1 rounded-full"
                style="background: radial-gradient(circle, rgba(245,197,24,0.5) 0%, transparent 70%); animation: glowPulse 2.5s ease-in-out infinite"
              ></div>
              <img
                src="@/assets/images/dolores_logo.png"
                alt="Barangay Dolores"
                class="rounded-full border-2 border-white/85 object-cover relative z-10"
                style="width:50px;height:50px;box-shadow:0 0 0 3px rgba(245,197,24,0.25)"
                @error="$event.target.src = 'https://placehold.co/50x50/1e3a8a/dc2626?text=BD'"
              >
            </div>
            <div class="flex flex-col">
              <p class="text-[0.58rem] font-semibold tracking-[0.22em] uppercase text-white/55 m-0">Barangay</p>
              <p class="text-xl font-extrabold text-white tracking-tight m-0 leading-tight" style="text-shadow:0 2px 8px rgba(0,0,0,0.3)">Dolores</p>
              <p class="text-[0.6rem] text-white/40 tracking-wide m-0">Capas, Tarlac</p>
            </div>
          </div>

          <p class="text-sm text-white/58 leading-relaxed m-0">
            Serving the community with integrity, transparency, and dedication since 1950.
          </p>

          <div class="h-px bg-gradient-to-r from-white/15 to-transparent"></div>

          <!-- Office Hours -->
          <div>
            <p class="text-[0.65rem] font-bold tracking-[0.18em] uppercase text-white/40 mb-3 m-0">Office Hours</p>
            <div class="flex flex-col gap-2">
              <div class="flex items-center justify-between">
                <span class="text-sm text-white/50">Mon – Fri</span>
                <span class="text-sm font-semibold text-white/80">8:00 AM – 5:00 PM</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-white/50">Saturday</span>
                <span class="text-sm font-semibold text-white/80">8:00 AM – 12:00 PM</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-white/50">Sunday</span>
                <span class="text-sm font-semibold text-red-400">Closed</span>
              </div>
            </div>
          </div>

          <!-- Social -->
          <div class="flex gap-2 pt-1">
            <a
              href="#"
              class="w-9 h-9 rounded-lg bg-white/10 hover:bg-blue-600 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg border border-white/10 hover:border-blue-500"
              aria-label="Facebook"
            >
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-[0.65rem] font-bold tracking-[0.18em] uppercase text-white/40 mb-5 m-0">Quick Links</h3>
          <ul class="flex flex-col gap-0.5 list-none p-0 m-0">
            <li v-for="link in quickLinks" :key="link.path">
              <router-link
                :to="link.path"
                class="group flex items-center gap-3 py-2.5 px-3 -mx-3 rounded-xl text-sm text-white/58 hover:text-white hover:bg-white/8 no-underline transition-all duration-200"
              >
                <span class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 bg-white/8 group-hover:bg-yellow-400/20 border border-white/8 group-hover:border-yellow-400/30 transition-all duration-200">
                  <span class="block w-1.5 h-1.5 rounded-full bg-white/35 group-hover:bg-yellow-400 transition-colors duration-200"></span>
                </span>
                <span class="flex-1">{{ link.name }}</span>
                <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-50 -translate-x-1 group-hover:translate-x-0 transition-all duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h3 class="text-[0.65rem] font-bold tracking-[0.18em] uppercase text-white/40 mb-5 m-0">Contact Us</h3>
          <div class="flex flex-col gap-3">

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white/6 border border-white/8 hover:bg-white/10 transition-colors duration-200 group cursor-default">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-110 transition-transform duration-200" style="background: rgba(245,197,24,0.15)">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-[0.62rem] font-bold uppercase tracking-widest text-white/35 m-0 mb-1">Address</p>
                <p class="text-sm text-white/68 leading-snug m-0">Barangay Hall, Dolores,<br>Capas, Tarlac</p>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-white/6 border border-white/8 hover:bg-white/10 transition-colors duration-200 group cursor-default">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200" style="background: rgba(245,197,24,0.15)">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <div>
                <p class="text-[0.62rem] font-bold uppercase tracking-widest text-white/35 m-0 mb-1">Phone</p>
                <p class="text-sm text-white/68 m-0">(045) 123-4567</p>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-white/6 border border-white/8 hover:bg-white/10 transition-colors duration-200 group cursor-default">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200" style="background: rgba(245,197,24,0.15)">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-[0.62rem] font-bold uppercase tracking-widest text-white/35 m-0 mb-1">Email</p>
                <p class="text-sm text-white/68 m-0">info@brgydolores.gov.ph</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/10 bg-black/25">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3">

        <p class="text-xs text-white/38 m-0">
          &copy; {{ new Date().getFullYear() }} Barangay Dolores, Capas, Tarlac. All rights reserved.
        </p>

        <!-- Visitor Counter Pill -->
        <div class="flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/12 bg-white/6 backdrop-blur-sm">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-60"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
          </span>
          <svg class="w-3.5 h-3.5 text-white/45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
          <span class="text-xs text-white/45">Visitors today:</span>
          <span class="text-xs font-bold text-yellow-400 tabular-nums">{{ count }}</span>
        </div>

      </div>
    </div>

  </footer>
</template>

<style scoped>
@keyframes shimmer {
  0%   { background-position: 100% 0; }
  100% { background-position: -100% 0; }
}
@keyframes glowPulse {
  0%, 100% { opacity: 0.6; transform: scale(1); }
  50%       { opacity: 1;   transform: scale(1.15); }
}
</style>