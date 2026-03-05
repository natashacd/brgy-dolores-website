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
  <footer class="relative text-white" style="background: linear-gradient(135deg, #0a1f4e 0%, #0f2d6b 50%, #b91c1c 100%)">

    <!-- Top shimmer line with enhanced red -->
    <div
      class="h-[3px]"
      style="background: linear-gradient(90deg, #0f2d6b 0%, #f5c518 30%, #b91c1c 60%, #0f2d6b 100%); background-size: 200% 100%; animation: shimmer 4s linear infinite"
    ></div>

    <!-- Subtle dot grid background with red tint -->
    <div
      class="absolute inset-0 opacity-5 pointer-events-none"
      style="background-image: radial-gradient(circle, rgba(185,28,28,0.8) 1px, transparent 1px); background-size: 28px 28px"
    ></div>

    <!-- Red overlay gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 via-transparent to-transparent pointer-events-none"></div>

    <!-- Main Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">

        <!-- ── Column 1: Brand & Info ── -->
        <div class="flex flex-col gap-4 items-center text-center md:items-start md:text-left">

          <!-- Logo + Name with red glow -->
          <div class="flex items-center gap-3">
            <div class="relative flex-shrink-0">
              <div
                class="absolute -inset-1.5 rounded-full opacity-60"
                style="background: radial-gradient(circle, rgba(185,28,28,0.5) 0%, rgba(245,197,24,0.3) 70%); animation: glowPulse 2.5s ease-in-out infinite"
              ></div>
              <img
                src="@/assets/images/dolores_logo.png"
                alt="Barangay Dolores"
                class="relative z-10 w-12 h-12 rounded-full border-2 border-white/80 object-cover"
                style="box-shadow: 0 0 0 3px rgba(185,28,28,0.25)"
                @error="$event.target.src = 'https://placehold.co/56x56/1e3a8a/b91c1c?text=BD'"
              >
            </div>
            <div>
              <p class="text-base font-black tracking-wide text-white">Barangay Dolores</p>
              <p class="text-xs text-white/45 tracking-widest">Capas, Tarlac</p>
            </div>
          </div>

          <!-- Tagline with red highlight -->
          <p class="text-sm text-white/55 leading-relaxed max-w-xs">
            Serving the community with <span class="text-red-400">integrity</span>, <span class="text-yellow-400">transparency</span>, and dedication since 1950.
          </p>

          <!-- Divider with red -->
          <div class="w-full h-px bg-gradient-to-r from-transparent via-red-500/30 to-transparent"></div>

          <!-- Office Hours -->
          <div class="w-full">
            <p class="text-[0.65rem] font-bold tracking-[0.2em] uppercase mb-3" style="color: #b91c1c">Office Hours</p>
            <div class="flex flex-col gap-2">
              <div class="flex items-center justify-between gap-4">
                <span class="text-sm text-white/50">Mon – Fri</span>
                <span class="text-sm font-semibold text-white/85 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20">8:00 AM – 5:00 PM</span>
              </div>
              <div class="flex items-center justify-between gap-4">
                <span class="text-sm text-white/50">Saturday</span>
                <span class="text-sm font-semibold text-white/85 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20">8:00 AM – 12:00 PM</span>
              </div>
              <div class="flex items-center justify-between gap-4">
                <span class="text-sm text-white/50">Sunday</span>
                <span class="text-sm font-semibold text-red-400 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20">Closed</span>
              </div>
            </div>
          </div>

          <!-- Social with red hover -->
          <div class="flex gap-2.5 mt-2">
            <a
              href="#"
              class="w-8 h-8 rounded-xl bg-white/8 hover:bg-red-600 flex items-center justify-center border border-white/10 hover:border-red-400 transition-all duration-200"
              aria-label="Facebook"
            >
              <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- ── Column 2: Quick Links ── -->
        <div class="flex flex-col items-center text-center md:items-start md:text-left">
          <p class="text-[0.65rem] font-bold tracking-[0.2em] uppercase mb-4" style="color: #b91c1c">Quick Links</p>
          <ul class="flex flex-col gap-1 w-full">
            <li v-for="link in quickLinks" :key="link.path">
              <router-link
                :to="link.path"
                class="flex items-center justify-center md:justify-start gap-3 py-2 px-3 -mx-3 rounded-xl text-sm text-white/60 hover:text-white hover:bg-red-600/20 no-underline transition-all duration-200 border border-transparent hover:border-red-500/30"
              >
                <span class="w-5 h-5 rounded-lg flex items-center justify-center flex-shrink-0 bg-white/8 group-hover:bg-red-500/20 border border-white/8 hover:border-red-500/30">
                  <span class="block w-1.5 h-1.5 rounded-full bg-white/35 group-hover:bg-red-400"></span>
                </span>
                <span class="flex-1 text-left">{{ link.name }}</span>
                <svg class="w-3 h-3 opacity-0 group-hover:opacity-60 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </router-link>
            </li>
          </ul>

          <!-- Decorative badge with red -->
          <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/10 border border-red-500/20">
            <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
            <span class="text-xs text-white/70">Official Barangay Website</span>
          </div>
        </div>

        <!-- ── Column 3: Contact ── -->
        <div class="flex flex-col items-center text-center md:items-start md:text-left">
          <p class="text-[0.65rem] font-bold tracking-[0.2em] uppercase mb-4" style="color: #b91c1c">Contact Us</p>
          <div class="flex flex-col gap-3 w-full">

            <!-- Address with red accent -->
            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/6 border border-white/8 hover:border-red-500/30 hover:bg-red-500/5 transition-all duration-200">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(185,28,28,0.15)">
                <svg class="w-3.5 h-3.5" style="color: #b91c1c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="text-left">
                <p class="text-[0.6rem] font-bold uppercase tracking-widest text-white/35">Address</p>
                <p class="text-sm text-white/70 leading-snug">Barangay Hall, Dolores,<br>Capas, Tarlac</p>
              </div>
            </div>

            <!-- Phone with red accent -->
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/6 border border-white/8 hover:border-red-500/30 hover:bg-red-500/5 transition-all duration-200">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(185,28,28,0.15)">
                <svg class="w-3.5 h-3.5" style="color: #b91c1c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <div class="text-left">
                <p class="text-[0.6rem] font-bold uppercase tracking-widest text-white/35">Phone</p>
                <p class="text-sm text-white/70">(045) 123-4567</p>
              </div>
            </div>

            <!-- Email with red accent -->
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/6 border border-white/8 hover:border-red-500/30 hover:bg-red-500/5 transition-all duration-200">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(185,28,28,0.15)">
                <svg class="w-3.5 h-3.5" style="color: #b91c1c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="text-left">
                <p class="text-[0.6rem] font-bold uppercase tracking-widest text-white/35">Email</p>
                <p class="text-sm text-white/70">info@brgydolores.gov.ph</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

  <!-- Bottom Bar with red accent and enhanced visitor counter placement -->
  <div class="relative border-t border-red-500/20 bg-gradient-to-r from-black/30 via-red-900/20 to-black/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex flex-col items-center justify-center gap-2 text-center">
        
        <!-- Copyright and Visitor Counter combined - larger text -->
        <p class="text-sm sm:text-base text-white/50">
          &copy; {{ new Date().getFullYear() }} Barangay Dolores, Capas, Tarlac. All rights reserved. || 
          <span class="text-white/45">Site Visits:</span>
          <span class="font-bold text-red-400 bg-red-500/10 px-2.5 py-1 rounded-md border border-red-500/20 ml-1.5 text-sm sm:text-base">{{ count }}</span>
        </p>
        
      </div>
    </div>
  </div>

  <!-- Bottom red glow -->
  <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
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