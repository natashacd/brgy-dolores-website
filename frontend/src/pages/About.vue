<template>
  <div class="min-h-screen bg-gray-50">

    <!-- ─────────────────────────────────────────
         HERO
    ───────────────────────────────────────── -->
    <section
      class="relative overflow-hidden hero-section"
      :class="{ 'hero-entered': heroVisible }"
      style="background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 55%, #b91c1c 100%)"
    >
      <!-- Wave Pattern -->
      <div class="absolute inset-0 opacity-20 pointer-events-none">
        <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            style="animation: waveAnimation 15s ease-in-out infinite alternate"></path>
          <path fill="rgba(255,255,255,0.05)" d="M0,192L48,197.3C96,203,192,213,288,202.7C384,192,480,160,576,149.3C672,139,768,149,864,165.3C960,181,1056,203,1152,192C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            style="animation: waveAnimation 18s ease-in-out infinite alternate-reverse"></path>
        </svg>
      </div>

      <!-- Floating Orbs -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 right-[20%] w-64 h-64 rounded-full bg-yellow-400/5 blur-3xl" style="animation: glowFloat 12s ease-in-out infinite"></div>
        <div class="absolute bottom-10 left-[10%] w-80 h-80 rounded-full bg-white/5 blur-3xl" style="animation: glowFloat 15s ease-in-out infinite 2s"></div>
      </div>

      <!-- Particles -->
      <div class="absolute inset-0 pointer-events-none">
        <div v-for="p in particles" :key="p.id" class="hero-particle"
          :style="`left:${p.x}%; top:${p.y}%; width:${p.size}px; height:${p.size}px; animation-delay:${p.delay}s; animation-duration:${p.dur}s`">
        </div>
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
        <div class="text-center">

          <!-- Badge -->
          <div class="hero-badge inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 hover:scale-105 transition-transform duration-300">
            <span class="text-yellow-400 animate-pulse">🏛️</span>
            <span class="text-sm font-bold tracking-widest uppercase text-white/90">Capas, Tarlac — A Progressive Community</span>
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
          </div>

          <!-- Title -->
          <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
            About
            <span class="relative inline-block ml-3">
              <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300"
                    style="animation: gradientShift 3s ease infinite; background-size: 200% 200%;">
                Barangay Dolores
              </span>
              <span class="absolute -bottom-2 left-0 right-0 h-3 bg-yellow-400/30 blur-xl"></span>
            </span>
          </h1>

          <!-- Description -->
          <p class="hero-desc text-xl text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">
            Building a progressive, peaceful, and self-reliant community through dedicated public service
            and active citizen participation since 1954.
          </p>

          <!-- Stats -->
          <div class="hero-stats flex flex-wrap justify-center gap-8 mt-10">
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">1954</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Established</div>
            </div>
            <div class="w-px h-12 bg-white/20 self-center"></div>
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">2,706</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Residents</div>
            </div>
            <div class="w-px h-12 bg-white/20 self-center"></div>
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">70+</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Years of Service</div>
            </div>
          </div>

        </div>
      </div>

      <!-- Curved Bottom -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-16 text-gray-50">
          <path d="M0 120L1440 0V120H0Z" fill="currentColor"></path>
        </svg>
      </div>
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent"></div>
    </section>


    <!-- ─────────────────────────────────────────
         STATS BAR
    ───────────────────────────────────────── -->
    <section
      ref="statsRef"
      class="bg-white border-b border-gray-100 stats-section"
      :class="{ 'stats-entered': statsVisible }"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
          <div
            v-for="(stat, i) in quickStats" :key="stat.label"
            class="text-center py-7 px-4 hover:bg-gray-50 transition-colors stat-bar-item"
            :style="`animation-delay: ${i * 0.1}s`"
          >
            <div class="text-3xl md:text-4xl font-black tabular-nums" :style="{ color: stat.color }">
              {{ formatCount(stat) }}
            </div>
            <div class="text-xs text-gray-400 uppercase tracking-widest mt-1.5 font-semibold">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>


    <!-- ─────────────────────────────────────────
         HISTORY
    ───────────────────────────────────────── -->
    <section
      ref="historyRef"
      class="py-20 bg-gray-50 history-section"
      :class="{ 'history-entered': historyVisible }"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

          <!-- Text -->
          <div class="history-text">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 mb-5">
              <span class="text-xs">📜</span>
              <span class="text-xs font-bold tracking-widest uppercase text-blue-900">Our History</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-blue-900 leading-snug mb-6">
              Barangay Dolores, established in 1954, is one of the most progressive barangays of Capas, Tarlac.
            </h2>
            <div class="space-y-4 text-gray-500 text-[15px] leading-relaxed">
              <p>Named after Our Lady of Sorrows <em>(Nuestra Señora de los Dolores)</em>, the barangay has grown from a small agricultural community to a vibrant residential and commercial area.</p>
              <p>Through the years, Barangay Dolores has been committed to promoting good governance, public service, and community development.</p>
              <p>Today, we continue to honor our rich history while embracing innovation and progress to improve the quality of life for every Dolores resident.</p>
            </div>
            <div class="inline-flex items-center gap-4 mt-8 px-6 py-4 bg-white rounded-xl border border-gray-200 shadow-sm founded-badge" style="border-left: 4px solid #0f2d6b">
              <span class="text-3xl font-black text-blue-900">1954</span>
              <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Year Founded</span>
            </div>
          </div>

          <!-- Image -->
          <div class="relative history-image">
            <div class="absolute -top-2 -left-2 w-10 h-10 border-t-4 border-l-4 border-yellow-400 rounded-tl-lg z-10 corner-tl"></div>
            <div class="absolute -bottom-2 -right-2 w-10 h-10 border-b-4 border-r-4 border-yellow-400 rounded-br-lg z-10 corner-br"></div>
            <div class="relative rounded-2xl overflow-hidden shadow-2xl">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10"></div>
              <img
                src="@/assets/images/dolores_lg.png"
                alt="Barangay Dolores History"
                class="w-full h-auto block"
                @error="$event.target.src = 'https://placehold.co/800x600/0f2d6b/b91c1c?text=Barangay+Dolores'"
              >
            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- ─────────────────────────────────────────
         MISSION & VISION
    ───────────────────────────────────────── -->
    <section
      ref="mvRef"
      class="py-20 bg-white mv-section"
      :class="{ 'mv-entered': mvVisible }"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12 mv-header">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 mb-4">
            <span class="text-xs font-bold tracking-widest uppercase text-blue-900">Our Purpose</span>
          </div>
          <h2 class="text-3xl md:text-4xl font-black text-gray-900">Mission &amp; Vision</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-6">

          <!-- Mission -->
          <div class="mv-card bg-white rounded-2xl border border-gray-100 shadow-lg p-8 md:p-10 hover:-translate-y-1 transition-transform duration-300 border-t-4 overflow-hidden relative"
            style="border-top-color: #b91c1c; animation-delay: 0.1s">
            <div class="mv-card-shimmer"></div>
            <div class="w-14 h-14 rounded-xl bg-red-600 flex items-center justify-center mb-6 shadow-md mv-icon">
              <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.59M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-800 mb-3">Our Mission</h3>
            <p class="text-gray-500 leading-relaxed">
              To provide efficient, transparent, and inclusive public service that promotes peace, order, and sustainable development for all residents of Barangay Dolores.
            </p>
            <div class="flex items-center gap-2 mt-6 text-xs text-red-600 font-semibold">
              <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
              Commitment to service excellence
            </div>
          </div>

          <!-- Vision -->
          <div class="mv-card bg-white rounded-2xl border border-gray-100 shadow-lg p-8 md:p-10 hover:-translate-y-1 transition-transform duration-300 border-t-4 overflow-hidden relative"
            style="border-top-color: #0f2d6b; animation-delay: 0.2s">
            <div class="mv-card-shimmer"></div>
            <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 shadow-md mv-icon" style="background: #0f2d6b">
              <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-800 mb-3">Our Vision</h3>
            <p class="text-gray-500 leading-relaxed">
              A progressive, united, and resilient barangay with empowered citizens and responsive local governance — where families thrive in a safe, clean, and sustainable environment.
            </p>
            <div class="flex items-center gap-2 mt-6 text-xs font-semibold" style="color: #0f2d6b">
              <span class="w-2 h-2 rounded-full animate-pulse" style="background: #0f2d6b"></span>
              Building a better future together
            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- ─────────────────────────────────────────
         CORE VALUES
    ───────────────────────────────────────── -->
    <section
      ref="valuesRef"
      class="py-20 bg-gray-50 values-section"
      :class="{ 'values-entered': valuesVisible }"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 values-header">
          <div class="inline-block px-4 py-1.5 bg-white border border-gray-200 rounded-full mb-4">
            <span class="text-xs font-bold tracking-widest uppercase text-gray-500">Our Guiding Principles</span>
          </div>
          <h2 class="text-3xl md:text-4xl font-black text-gray-900">
            Core <span class="text-blue-900">Values</span>
          </h2>
          <p class="text-gray-400 text-sm mt-3 max-w-lg mx-auto">The foundation of our service and commitment to the Barangay Dolores community.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="(val, i) in coreValues" :key="val.name"
            class="value-card group relative bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden"
            :style="`animation-delay: ${i * 0.1}s`"
          >
            <div class="p-8">
              <div class="w-11 h-11 mb-6 flex items-center justify-center rounded-lg value-icon" :class="val.iconBg">
                <component :is="val.icon" class="w-6 h-6" :class="val.iconColor" />
              </div>
              <h3 class="text-lg font-bold text-gray-900 mb-2">{{ val.name }}</h3>
              <p class="text-gray-400 text-sm leading-relaxed">{{ val.desc }}</p>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-0.5 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left" :class="val.bar"></div>
          </div>
        </div>

        <div class="mt-12 text-center values-footer">
          <p class="inline-flex items-center gap-2 text-sm text-gray-400 bg-white px-5 py-2.5 rounded-lg border border-gray-200">
            These values guide every decision we make at
            <span class="font-semibold text-blue-900">Barangay Dolores</span>
          </p>
        </div>

      </div>
    </section>


    <!-- ─────────────────────────────────────────
         COMMUNITY & EVENTS
    ───────────────────────────────────────── -->
    <section
      ref="eventsRef"
      class="py-20 bg-white events-section"
      :class="{ 'events-entered': eventsVisible }"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12 events-header">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 mb-4">
            <span class="text-xs">🎉</span>
            <span class="text-xs font-bold tracking-widest uppercase text-red-600">Community &amp; Events</span>
          </div>
          <h2 class="text-3xl md:text-4xl font-black text-gray-900">Celebrating Together</h2>
          <p class="text-gray-400 text-sm mt-2">A glimpse of the events that bring our barangay closer</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(event, i) in communityEvents" :key="event.name"
            class="event-card group flex flex-col items-center gap-3 cursor-pointer"
            :style="`animation-delay: ${i * 0.1}s`"
          >
            <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:-translate-y-1 transition-all duration-300 relative">
              <img
                :src="event.img"
                :alt="event.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                @error="$event.target.src = 'https://placehold.co/600x450/0f2d6b/ffffff?text=' + encodeURIComponent(event.name)"
              >
              <!-- Overlay on hover -->
              <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                <span class="text-white text-sm font-bold">{{ event.name }}</span>
              </div>
            </div>
            <span class="text-sm font-semibold text-gray-600 group-hover:text-blue-900 transition-colors duration-200">{{ event.name }}</span>
          </div>
        </div>

      </div>
    </section>

  </div>
</template>


<script setup>
import { ref, reactive, onMounted, onUnmounted, h } from 'vue'

// ── Particles ────────────────────────────────────
const particles = Array.from({ length: 14 }, (_, i) => ({
  id: i,
  x: Math.random() * 100,
  y: Math.random() * 100,
  size: Math.random() * 3 + 1,
  delay: Math.random() * 4,
  dur: 3 + Math.random() * 5,
}))

// ── Counting animation ───────────────────────────
const statsRef        = ref(null)
const animationStarted = ref(false)

const quickStats = reactive([
  { target: 2706, current: 0, label: 'Residents',       color: '#0f2d6b', suffix: '',   comma: true  },
  { target: 70,   current: 0, label: 'Years of Service', color: '#b91c1c', suffix: '+',  comma: false },
  { target: 24,   current: 0, label: 'Service Hours',    color: '#d97706', suffix: '/7', comma: false },
  { target: 100,  current: 0, label: 'Commitment',       color: '#16a34a', suffix: '%',  comma: false },
])

function formatCount(stat) {
  const num = stat.comma ? stat.current.toLocaleString() : stat.current
  return `${num}${stat.suffix}`
}

function animateCounters() {
  if (animationStarted.value) return
  animationStarted.value = true
  const duration = 2000
  const fps      = 60
  const steps    = Math.round(duration / (1000 / fps))
  quickStats.forEach(stat => {
    let step = 0
    const timer = setInterval(() => {
      step++
      const progress = 1 - Math.pow(1 - step / steps, 3)
      stat.current   = Math.min(Math.round(stat.target * progress), stat.target)
      if (step >= steps) { stat.current = stat.target; clearInterval(timer) }
    }, 1000 / fps)
  })
}

// ── Intersection observers ───────────────────────
const historyRef = ref(null)
const mvRef      = ref(null)
const valuesRef  = ref(null)
const eventsRef  = ref(null)

const heroVisible    = ref(false)
const statsVisible   = ref(false)
const historyVisible = ref(false)
const mvVisible      = ref(false)
const valuesVisible  = ref(false)
const eventsVisible  = ref(false)

let observers = []

function observe(el, flag, cb) {
  if (!el) return
  const obs = new IntersectionObserver(
    ([e]) => {
      if (e.isIntersecting) {
        flag.value = true
        if (cb) cb()
        obs.disconnect()
      }
    },
    { threshold: 0.1 }
  )
  obs.observe(el)
  observers.push(obs)
}

onMounted(() => {
  setTimeout(() => { heroVisible.value = true }, 60)
  observe(statsRef.value,   statsVisible,   animateCounters)
  observe(historyRef.value, historyVisible)
  observe(mvRef.value,      mvVisible)
  observe(valuesRef.value,  valuesVisible)
  observe(eventsRef.value,  eventsVisible)
})
onUnmounted(() => observers.forEach(o => o.disconnect()))


// ── Core Values icons ────────────────────────────
const IconCheck = {
  render: () => h('svg', { viewBox:'0 0 24 24', fill:'none', stroke:'currentColor', 'stroke-width':'1.5' }, [
    h('path', { 'stroke-linecap':'round','stroke-linejoin':'round', d:'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })
  ])
}
const IconEye = {
  render: () => h('svg', { viewBox:'0 0 24 24', fill:'none', stroke:'currentColor', 'stroke-width':'1.5' }, [
    h('path', { 'stroke-linecap':'round','stroke-linejoin':'round', d:'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z' }),
    h('path', { 'stroke-linecap':'round','stroke-linejoin':'round', d:'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })
  ])
}
const IconHeart = {
  render: () => h('svg', { viewBox:'0 0 24 24', fill:'none', stroke:'currentColor', 'stroke-width':'1.5' }, [
    h('path', { 'stroke-linecap':'round','stroke-linejoin':'round', d:'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z' })
  ])
}
const IconUsers = {
  render: () => h('svg', { viewBox:'0 0 24 24', fill:'none', stroke:'currentColor', 'stroke-width':'1.5' }, [
    h('path', { 'stroke-linecap':'round','stroke-linejoin':'round', d:'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z' })
  ])
}

const coreValues = [
  { name: 'Integrity',     desc: 'Upholding the highest standards of honesty and moral principles in every decision.',              icon: IconCheck, iconBg: 'bg-blue-50',   iconColor: 'text-blue-900',   bar: 'bg-blue-900'   },
  { name: 'Transparency', desc: 'Maintaining open communication and clear processes that build public trust.',                    icon: IconEye,   iconBg: 'bg-yellow-50', iconColor: 'text-yellow-600', bar: 'bg-yellow-500' },
  { name: 'Service',      desc: 'Putting the needs of our community first with compassion, efficiency, and dedication.',          icon: IconHeart, iconBg: 'bg-red-50',    iconColor: 'text-red-600',    bar: 'bg-red-600'    },
  { name: 'Unity',        desc: 'Fostering collaboration between leaders and residents to build a stronger community.',           icon: IconUsers, iconBg: 'bg-green-50',  iconColor: 'text-green-600',  bar: 'bg-green-600'  },
]

// ── Community Events ─────────────────────────────
const communityEvents = [
  { name: 'Float Competition',         img: '/src/assets/images/about/float.jpg'         },
  { name: 'Annual Barangay Fiesta',    img: '/src/assets/images/about/fiesta.jpg'        },
  { name: 'Weekly Clean Up Drive',     img: '/src/assets/images/about/clean.jpg'         },
  { name: 'Parol Making Contest',      img: '/src/assets/images/about/pms.jpg'           },
  { name: 'Mobile Legends Tournament', img: '/src/assets/images/about/sk.jpg'            },
  { name: 'Tree Planting',             img: '/src/assets/images/about/tree_planting.jpg' },
]
</script>


<style scoped>

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.hero-section { opacity: 0; transform: translateY(-12px); transition: opacity 0.7s ease, transform 0.7s ease; }
.hero-section.hero-entered { opacity: 1; transform: translateY(0); }

.hero-badge, .hero-title, .hero-desc, .hero-stats { opacity: 0; transform: translateY(18px); }
.hero-section.hero-entered .hero-badge  { animation: fadeUp 0.55s ease both 0.1s;  }
.hero-section.hero-entered .hero-title  { animation: fadeUp 0.55s ease both 0.22s; }
.hero-section.hero-entered .hero-desc   { animation: fadeUp 0.55s ease both 0.34s; }
.hero-section.hero-entered .hero-stats  { animation: fadeUp 0.55s ease both 0.46s; }

/* Particles */
.hero-particle {
  position: absolute; border-radius: 50%;
  background: rgba(245,197,24,0.55);
  opacity: 0; pointer-events: none;
  animation: particleFade ease-in-out infinite;
}
@keyframes particleFade {
  0%   { opacity: 0; transform: translateY(0) scale(0.5); }
  40%  { opacity: 0.5; }
  100% { opacity: 0; transform: translateY(-50px) scale(1.2); }
}


/* ═══════════════════════════════════════
   STATS BAR
═══════════════════════════════════════ */
.stat-bar-item { opacity: 0; transform: translateY(16px); }
.stats-section.stats-entered .stat-bar-item {
  animation: fadeUp 0.5s cubic-bezier(0.34,1.2,0.64,1) both;
}


/* ═══════════════════════════════════════
   HISTORY
═══════════════════════════════════════ */
.history-text {
  opacity: 0; transform: translateX(-32px);
}
.history-section.history-entered .history-text {
  animation: slideInLeft 0.7s cubic-bezier(0.34,1.1,0.64,1) both 0.05s;
}
@keyframes slideInLeft {
  from { opacity: 0; transform: translateX(-32px); }
  to   { opacity: 1; transform: translateX(0); }
}

.history-image {
  opacity: 0; transform: translateX(32px);
}
.history-section.history-entered .history-image {
  animation: slideInRight 0.7s cubic-bezier(0.34,1.1,0.64,1) both 0.15s;
}
@keyframes slideInRight {
  from { opacity: 0; transform: translateX(32px); }
  to   { opacity: 1; transform: translateX(0); }
}

.founded-badge {
  opacity: 0; transform: translateY(10px);
}
.history-section.history-entered .founded-badge {
  animation: fadeUp 0.5s ease both 0.5s;
}

/* Corner decorations draw in */
.corner-tl { animation: none; }
.corner-br { animation: none; }
.history-section.history-entered .corner-tl {
  animation: cornerGrow 0.5s ease both 0.6s;
}
.history-section.history-entered .corner-br {
  animation: cornerGrow 0.5s ease both 0.7s;
}
@keyframes cornerGrow {
  from { opacity: 0; transform: scale(0); }
  to   { opacity: 1; transform: scale(1); }
}


/* ═══════════════════════════════════════
   MISSION & VISION
═══════════════════════════════════════ */
.mv-header { opacity: 0; transform: translateY(18px); }
.mv-section.mv-entered .mv-header {
  animation: fadeUp 0.55s ease both 0s;
}

.mv-card {
  opacity: 0; transform: translateY(24px) scale(0.97);
}
.mv-section.mv-entered .mv-card {
  animation: cardPop 0.6s cubic-bezier(0.34,1.2,0.64,1) both;
}

.mv-icon {
  transition: transform 0.3s ease;
}
.mv-card:hover .mv-icon {
  transform: scale(1.1) rotate(6deg);
}

/* Shimmer on hover */
.mv-card-shimmer {
  position: absolute; inset: 0; pointer-events: none;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.4) 50%, transparent 60%);
  transform: translateX(-100%);
}
.mv-card:hover .mv-card-shimmer {
  animation: shimmerSlide 0.65s ease forwards;
}


/* ═══════════════════════════════════════
   CORE VALUES
═══════════════════════════════════════ */
.values-header, .values-footer { opacity: 0; transform: translateY(18px); }
.values-section.values-entered .values-header {
  animation: fadeUp 0.55s ease both 0s;
}
.values-section.values-entered .values-footer {
  animation: fadeUp 0.5s ease both 0.5s;
}

.value-card {
  opacity: 0; transform: translateY(24px) scale(0.95);
}
.values-section.values-entered .value-card {
  animation: cardPop 0.55s cubic-bezier(0.34,1.3,0.64,1) both;
}

.value-icon {
  transition: transform 0.3s cubic-bezier(0.34,1.5,0.64,1);
}
.value-card:hover .value-icon {
  transform: scale(1.2) rotate(-8deg);
}


/* ═══════════════════════════════════════
   EVENTS
═══════════════════════════════════════ */
.events-header { opacity: 0; transform: translateY(18px); }
.events-section.events-entered .events-header {
  animation: fadeUp 0.55s ease both 0s;
}

.event-card {
  opacity: 0; transform: translateY(28px);
}
.events-section.events-entered .event-card {
  animation: cardPop 0.6s cubic-bezier(0.34,1.2,0.64,1) both;
}


/* ═══════════════════════════════════════
   SHARED
═══════════════════════════════════════ */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes cardPop {
  from { opacity: 0; transform: translateY(24px) scale(0.96); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes shimmerSlide {
  from { transform: translateX(-100%); }
  to   { transform: translateX(100%); }
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