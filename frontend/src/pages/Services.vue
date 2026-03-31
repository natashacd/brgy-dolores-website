<template>
  <div class="min-h-screen bg-gray-50">

    <!-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════ -->
    <section
      class="relative overflow-hidden hero-section"
      :class="{ 'hero-entered': heroVisible }"
      style="background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 55%, #b91c1c 100%)"
    >
      <!-- Wave SVG -->
      <div class="absolute inset-0 opacity-20 pointer-events-none">
        <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            style="animation: waveAnimation 15s ease-in-out infinite alternate;"></path>
          <path fill="rgba(255,255,255,0.05)" d="M0,192L48,197.3C96,203,192,213,288,202.7C384,192,480,160,576,149.3C672,139,768,149,864,165.3C960,181,1056,203,1152,192C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            style="animation: waveAnimation 18s ease-in-out infinite alternate-reverse;"></path>
        </svg>
      </div>

      <!-- Floating orbs -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 right-[20%] w-64 h-64 rounded-full bg-yellow-400/5 blur-3xl" style="animation: glowFloat 12s ease-in-out infinite"></div>
        <div class="absolute bottom-10 left-[10%] w-80 h-80 rounded-full bg-white/5 blur-3xl" style="animation: glowFloat 15s ease-in-out infinite 2s"></div>
      </div>

      <!-- Particles -->
      <div class="absolute inset-0 pointer-events-none">
        <div v-for="p in particles" :key="p.id" class="particle"
          :style="`left:${p.x}%; top:${p.y}%; width:${p.size}px; height:${p.size}px; animation-delay:${p.delay}s; animation-duration:${p.dur}s`">
        </div>
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
        <div class="text-center">

          <!-- Badge -->
          <div class="hero-badge inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 hover:scale-105 transition-transform duration-300">
            <span class="text-yellow-400 animate-pulse">⚙️</span>
            <span class="text-sm font-bold tracking-widest uppercase text-white/90">Barangay Services</span>
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
          </div>

          <!-- Title -->
          <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
            Services
            <span class="relative inline-block">
              <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300"
                style="animation: gradientShift 3s ease infinite; background-size: 200% 200%;">
                We Offer
              </span>
              <span class="absolute -bottom-2 left-0 right-0 h-3 bg-yellow-400/30 blur-xl"></span>
            </span>
          </h1>

          <!-- Description -->
          <p class="hero-desc text-xl text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">
            Complete your requests online or visit the Barangay Hall for in-person processing.
          </p>

          <!-- Stats -->
          <div class="hero-stats flex flex-wrap justify-center gap-8 mt-10">
            <div v-for="(stat, i) in heroStats" :key="stat.label"
              class="text-center hover:scale-110 transition-transform duration-300 stat-item"
              :style="`animation-delay: ${i * 0.12}s`">
              <div class="text-3xl font-black text-yellow-400">{{ stat.value }}</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">{{ stat.label }}</div>
            </div>
            <div v-if="i < heroStats.length - 1" :key="'div'+i" class="w-px h-12 bg-white/20 self-center"></div>
          </div>
        </div>
      </div>

      <!-- Curved bottom -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-16 text-gray-50">
          <path d="M0 120L1440 0V120H0Z" fill="currentColor"></path>
        </svg>
      </div>
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent"></div>
    </section>


    <!-- ═══════════════════════════════════════════
         SERVICES GRID
    ════════════════════════════════════════════ -->
    <section
      ref="gridRef"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 grid-section"
      :class="{ 'grid-entered': gridVisible }"
    >
      <div class="text-center mb-12 grid-header">
        <p class="text-xs font-extrabold tracking-[0.22em] uppercase mb-2" style="color:#0f2d6b">What We Provide</p>
        <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Available <span style="background:linear-gradient(135deg,#0f2d6b,#b91c1c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Services</span></h2>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <div
          v-for="(svc, i) in services"
          :key="svc.title"
          class="service-card group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100"
          :style="`animation-delay: ${i * 0.1}s`"
          :class="{ 'md:col-span-2 lg:col-span-1': svc.wide }"
        >
          <!-- Top color bar -->
          <div class="h-2 transition-all duration-500 group-hover:h-3" :style="`background: linear-gradient(90deg, ${svc.bar1}, ${svc.bar2})`"></div>

          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <!-- Icon -->
              <div
                class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300"
                :style="`background: linear-gradient(135deg, ${svc.iconBg1}, ${svc.iconBg2})`"
              >{{ svc.icon }}</div>
              <!-- Tag -->
              <span
                class="text-xs font-bold uppercase px-3 py-1 rounded-full transition-colors duration-300"
                :style="`background: ${svc.tagBg}; color: ${svc.tagColor}`"
              >{{ svc.tag }}</span>
            </div>

            <h2 class="text-2xl font-black text-gray-800 mb-2 transition-colors duration-300"
              :style="`--hover-color: ${svc.hoverColor}`"
              :class="'group-hover:text-[var(--hover-color)]'"
            >{{ svc.title }}</h2>
            <p class="text-gray-500 leading-relaxed">{{ svc.desc }}</p>

            <!-- Animated bottom line -->
            <div class="mt-5 h-0.5 w-0 group-hover:w-full transition-all duration-500 rounded-full"
              :style="`background: linear-gradient(90deg, ${svc.bar1}, ${svc.bar2})`">
            </div>
          </div>

          <!-- Shimmer on hover -->
          <div class="card-shimmer"></div>
        </div>
      </div>
    </section>

  <!-- ═══════════════════════════════════════════
          HOW TO APPLY
      ════════════════════════════════════════════ -->
      <section
        ref="stepsRef"
        class="bg-white py-16 border-t border-gray-100 steps-section"
        :class="{ 'steps-entered': stepsVisible }"
      >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12 steps-header">
            <p class="text-xs font-extrabold tracking-[0.22em] uppercase mb-2" style="color:#0f2d6b">Simple Process</p>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
              How to <span style="background: linear-gradient(135deg,#0f2d6b,#b91c1c); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text">Apply</span>
            </h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Three simple steps to access barangay services</p>
          </div>

          <div class="grid md:grid-cols-3 gap-8 relative">
            <!-- Connector line (desktop) -->
            <div class="hidden md:block absolute top-8 left-[17%] right-[17%] h-0.5 steps-connector"
              style="background: linear-gradient(90deg, #0f2d6b22, #0f2d6b55, #b91c1c55, #b91c1c22)">
            </div>

            <!-- Step 1: Visit or Login -->
            <div
              class="text-center step-item"
              style="animation-delay: 0s"
            >
              <div class="relative inline-block mb-4">
                <div
                  class="w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto transition-all duration-300 group hover:scale-110"
                  style="background: #0f2d6b15"
                >
                  <svg class="w-8 h-8" style="color: #0f2d6b" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                  </svg>
                </div>
                <div
                  class="absolute -top-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-black text-white shadow-md"
                  style="background: #0f2d6b"
                >1</div>
              </div>
              <h3 class="font-bold text-gray-900 mb-2 text-base">Visit or Login</h3>
              <p class="text-sm text-gray-400 max-w-xs mx-auto leading-relaxed">Visit the barangay hall or log in to your resident account to start your application</p>
            </div>

            <!-- Step 2: Submit Request -->
            <div
              class="text-center step-item"
              style="animation-delay: 0.15s"
            >
              <div class="relative inline-block mb-4">
                <div
                  class="w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto transition-all duration-300 group hover:scale-110"
                  style="background: #0f2d6b15"
                >
                  <svg class="w-8 h-8" style="color: #0f2d6b" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div
                  class="absolute -top-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-black text-white shadow-md"
                  style="background: #b91c1c"
                >2</div>
              </div>
              <h3 class="font-bold text-gray-900 mb-2 text-base">Submit Request</h3>
              <p class="text-sm text-gray-400 max-w-xs mx-auto leading-relaxed">Fill out the request form with your details and required documents</p>
            </div>

            <!-- Step 3: Claim Document -->
            <div
              class="text-center step-item"
              style="animation-delay: 0.3s"
            >
              <div class="relative inline-block mb-4">
                <div
                  class="w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto transition-all duration-300 group hover:scale-110"
                  style="background: #0f2d6b15"
                >
                  <svg class="w-8 h-8" style="color: #0f2d6b" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <div
                  class="absolute -top-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-black text-white shadow-md"
                  style="background: #0f2d6b"
                >3</div>
              </div>
              <h3 class="font-bold text-gray-900 mb-2 text-base">Claim Document</h3>
              <p class="text-sm text-gray-400 max-w-xs mx-auto leading-relaxed">Once approved, visit the barangay hall to claim your document or certificate</p>
            </div>
          </div>

          <!-- Additional Info Banner -->
          <div class="mt-12 bg-gradient-to-r from-[#0f2d6b08] to-[#b91c1c08] rounded-2xl p-6 text-center">
            <p class="text-sm text-gray-600">
              <span class="font-semibold" style="color:#0f2d6b">Need assistance?</span> Our staff at the barangay hall is ready to help you with your application.
            </p>
          </div>
        </div>
      </section>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Particles
const particles = Array.from({ length: 14 }, (_, i) => ({
  id: i,
  x: Math.random() * 100,
  y: Math.random() * 100,
  size: Math.random() * 3 + 1,
  delay: Math.random() * 4,
  dur: 3 + Math.random() * 5,
}))

const heroStats = [
  { value: '5+',   label: 'Services Available' },
  { value: '24/7', label: 'Online Access' },
  { value: '100%', label: 'Transparent' },
]

const services = [
  {
    title: 'BRGY ID Request',
    desc: 'Apply for your Barangay ID or community identification card.',
    icon: '🆔', tag: 'Request',
    bar1: '#0f2d6b', bar2: '#1a3f8f',
    iconBg1: '#1e3a8a', iconBg2: '#1d4ed8',
    tagBg: 'rgba(245,197,24,0.12)', tagColor: '#a16207',
    hoverColor: '#1e3a8a',
  },
  {
    title: 'BRGY Business Clearance / Permit',
    desc: 'Apply or renew business clearance and permits in your barangay.',
    icon: '🏪', tag: 'Permit',
    bar1: '#b91c1c', bar2: '#dc2626',
    iconBg1: '#b91c1c', iconBg2: '#dc2626',
    tagBg: 'rgba(220,38,38,0.10)', tagColor: '#b91c1c',
    hoverColor: '#b91c1c',
  },
  {
    title: 'BRGY Certificate of Indigency',
    desc: 'Request a Barangay Certificate of Indigency for official purposes.',
    icon: '📜', tag: 'Certificate',
    bar1: '#0f2d6b', bar2: '#b91c1c',
    iconBg1: '#7c3aed', iconBg2: '#8b5cf6',
    tagBg: 'rgba(124,58,237,0.10)', tagColor: '#7c3aed',
    hoverColor: '#7c3aed',
  },
  {
    title: 'BRGY Certificate of Residency',
    desc: 'Obtain your Certificate of Residency for official requirements.',
    icon: '🏠', tag: 'Certificate',
    bar1: '#1a3f8f', bar2: '#2563eb',
    iconBg1: '#2563eb', iconBg2: '#3b82f6',
    tagBg: 'rgba(37,99,235,0.10)', tagColor: '#1d4ed8',
    hoverColor: '#2563eb',
  },
  {
    title: 'Certifications for Specific Purposes',
    desc: 'Request Barangay certifications for school, employment, or other purposes.',
    icon: '📋', tag: 'Multiple',
    bar1: '#f5c518', bar2: '#eab308',
    iconBg1: '#ca8a04', iconBg2: '#eab308',
    tagBg: 'rgba(245,197,24,0.12)', tagColor: '#a16207',
    hoverColor: '#ca8a04',
    wide: true,
  },
]

const steps = [
  {
    icon: '🏛️', title: 'Visit',
    desc: 'Visit the barangay hall during office hours.',
    bg: 'rgba(15,45,107,0.07)', badge: '#0f2d6b',
  },
  {
    icon: '📄', title: 'Submit Requirements',
    desc: 'Provide the necessary documents and information for processing.',
    bg: 'rgba(185,28,28,0.07)', badge: '#b91c1c',
  },
  {
    icon: '✅', title: 'Claim Your Document',
    desc: 'Receive your processed document at the barangay hall.',
    bg: 'rgba(245,197,24,0.12)', badge: '#ca8a04',
  },
]

// Intersection observers
const gridRef  = ref(null)
const stepsRef = ref(null)

const heroVisible  = ref(false)
const gridVisible  = ref(false)
const stepsVisible = ref(false)

let observers = []

function observe(el, flag) {
  if (!el) return
  const obs = new IntersectionObserver(
    ([e]) => { if (e.isIntersecting) { flag.value = true; obs.disconnect() } },
    { threshold: 0.1 }
  )
  obs.observe(el)
  observers.push(obs)
}

onMounted(() => {
  setTimeout(() => { heroVisible.value = true }, 60)
  observe(gridRef.value,  gridVisible)
  observe(stepsRef.value, stepsVisible)
})
onUnmounted(() => observers.forEach(o => o.disconnect()))
</script>


<style scoped>

/* ═══════════════════════════════════════
   HERO ENTRANCE
═══════════════════════════════════════ */
.hero-section { opacity: 0; transform: translateY(-12px); transition: opacity 0.7s ease, transform 0.7s ease; }
.hero-section.hero-entered { opacity: 1; transform: translateY(0); }

.hero-badge  { opacity: 0; transform: translateY(16px); }
.hero-title  { opacity: 0; transform: translateY(20px); }
.hero-desc   { opacity: 0; transform: translateY(20px); }
.hero-stats  { opacity: 0; transform: translateY(20px); }

.hero-section.hero-entered .hero-badge  { animation: fadeUp 0.55s ease both 0.1s; }
.hero-section.hero-entered .hero-title  { animation: fadeUp 0.55s ease both 0.22s; }
.hero-section.hero-entered .hero-desc   { animation: fadeUp 0.55s ease both 0.34s; }
.hero-section.hero-entered .hero-stats  { animation: fadeUp 0.55s ease both 0.46s; }

.stat-item { display: inline-block; }

/* Particles */
.particle {
  position: absolute; border-radius: 50%;
  background: rgba(245,197,24,0.5);
  opacity: 0; pointer-events: none;
  animation: particleFade ease-in-out infinite;
}
@keyframes particleFade {
  0%   { opacity: 0; transform: translateY(0) scale(0.5); }
  40%  { opacity: 0.5; }
  100% { opacity: 0; transform: translateY(-50px) scale(1.2); }
}


/* ═══════════════════════════════════════
   SERVICES GRID
═══════════════════════════════════════ */
.grid-header {
  opacity: 0; transform: translateY(18px);
}
.grid-section.grid-entered .grid-header {
  animation: fadeUp 0.55s ease both 0s;
}

.service-card {
  opacity: 0;
  transform: translateY(28px) scale(0.97);
  position: relative;
  overflow: hidden;
}
.grid-section.grid-entered .service-card {
  animation: cardReveal 0.6s cubic-bezier(0.34,1.2,0.64,1) both;
}

@keyframes cardReveal {
  from { opacity: 0; transform: translateY(28px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Shimmer on hover */
.card-shimmer {
  position: absolute;
  inset: 0;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.35) 50%, transparent 60%);
  transform: translateX(-100%);
  pointer-events: none;
}
.group:hover .card-shimmer {
  animation: shimmerSlide 0.65s ease forwards;
}
@keyframes shimmerSlide {
  from { transform: translateX(-100%); }
  to   { transform: translateX(100%); }
}


/* ═══════════════════════════════════════
   HOW TO APPLY
═══════════════════════════════════════ */
.steps-header, .steps-cta {
  opacity: 0; transform: translateY(18px);
}
.steps-section.steps-entered .steps-header {
  animation: fadeUp 0.55s ease both 0s;
}
.steps-section.steps-entered .steps-cta {
  animation: fadeUp 0.55s ease both 0.5s;
}

.step-item {
  opacity: 0; transform: translateY(24px);
}
.steps-section.steps-entered .step-item {
  animation: stepPop 0.6s cubic-bezier(0.34,1.3,0.64,1) both;
}
@keyframes stepPop {
  from { opacity: 0; transform: translateY(24px) scale(0.92); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.steps-connector {
  opacity: 0;
}
.steps-section.steps-entered .steps-connector {
  animation: connectorGrow 0.8s ease both 0.4s;
}
@keyframes connectorGrow {
  from { opacity: 0; transform: scaleX(0); }
  to   { opacity: 1; transform: scaleX(1); }
}

/* CTA button glow pulse */
.cta-btn {
  position: relative;
  overflow: hidden;
}
.cta-btn::after {
  content: '';
  position: absolute; inset: 0; border-radius: inherit;
  animation: btnGlow 2.5s ease-in-out infinite;
}
@keyframes btnGlow {
  0%   { box-shadow: 0 0 0 0 rgba(15,45,107,0.45); }
  70%  { box-shadow: 0 0 0 10px rgba(15,45,107,0); }
  100% { box-shadow: 0 0 0 0 rgba(15,45,107,0); }
}


/* ═══════════════════════════════════════
   SHARED KEYFRAMES
═══════════════════════════════════════ */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes glowFloat {
  0%, 100% { transform: translateY(0) scale(1); opacity: 0.05; }
  50%       { transform: translateY(-30px) scale(1.1); opacity: 0.08; }
}
@keyframes gradientShift {
  0%   { background-position: 0% 50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
@keyframes waveAnimation {
  0%   { transform: translateX(0) scaleY(1); }
  100% { transform: translateX(-10%) scaleY(0.8); }
}
</style>