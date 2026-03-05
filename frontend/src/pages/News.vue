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
            <span class="text-yellow-400 animate-pulse">📰</span>
            <span class="text-sm font-bold tracking-widest uppercase text-white/90">Barangay Dolores — Stay Informed</span>
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
          </div>

          <!-- Title -->
          <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
            News &amp;
            <span class="relative inline-block ml-3">
              <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300"
                    style="animation: gradientShift 3s ease infinite; background-size: 200% 200%;">
                Updates
              </span>
              <span class="absolute -bottom-2 left-0 right-0 h-3 bg-yellow-400/30 blur-xl"></span>
            </span>
          </h1>

          <!-- Description -->
          <p class="hero-desc text-xl text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">
            Stay up to date with the latest happenings, programs, and achievements of Barangay Dolores.
          </p>

          <!-- Stats -->
          <div class="hero-stats flex flex-wrap justify-center gap-8 mt-10">
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">{{ news.length }}</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Latest Posts</div>
            </div>
            <div class="w-px h-12 bg-white/20 self-center"></div>
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">2025</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Current Year</div>
            </div>
            <div class="w-px h-12 bg-white/20 self-center"></div>
            <div class="text-center hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">100%</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Transparent</div>
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
         FEATURED POST
    ───────────────────────────────────────── -->
    <section
      ref="featuredRef"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-4 featured-section"
      :class="{ 'featured-entered': featuredVisible }"
    >
      <div class="featured-label inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 mb-8">
        <span class="w-2 h-2 rounded-full bg-blue-900 animate-pulse"></span>
        <span class="text-xs font-bold tracking-widest uppercase text-blue-900">Featured Story</span>
      </div>

      <div
        @click="openModal(news[0])"
        class="featured-card group grid lg:grid-cols-2 gap-0 rounded-2xl overflow-hidden shadow-xl border border-gray-100 bg-white cursor-pointer hover:shadow-2xl transition-shadow duration-300 relative"
      >
        <!-- Shimmer -->
        <div class="featured-shimmer"></div>

        <!-- Image -->
        <div class="aspect-[4/3] lg:aspect-auto overflow-hidden relative">
          <img
            :src="`/src/assets/images/about/${news[0].image}`"
            :alt="news[0].alt"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            @error="$event.target.src = 'https://placehold.co/800x600/0f2d6b/ffffff?text=' + encodeURIComponent(news[0].title)"
          >
          <!-- Image overlay on hover -->
          <div class="absolute inset-0 bg-gradient-to-r from-transparent to-blue-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-12 flex flex-col justify-center">
          <div class="flex items-center gap-3 mb-4">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-full">
              <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
              Latest
            </span>
            <span class="text-xs text-gray-400 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              {{ news[0].date }}
            </span>
          </div>

          <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4 leading-snug group-hover:text-blue-900 transition-colors duration-300">
            {{ news[0].title }}
          </h2>

          <p class="text-gray-500 leading-relaxed line-clamp-4">{{ news[0].description }}</p>

          <!-- Animated arrow on hover -->
          <div class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-blue-900 group-hover:gap-4 transition-all duration-300">
            Read Full Story
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </div>

          <!-- Bottom accent line -->
          <div class="mt-6 h-0.5 w-0 group-hover:w-24 transition-all duration-500 rounded-full" style="background: linear-gradient(90deg,#0f2d6b,#b91c1c)"></div>
        </div>
      </div>
    </section>


    <!-- ─────────────────────────────────────────
         NEWS GRID
    ───────────────────────────────────────── -->
    <section
      ref="gridRef"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid-section"
      :class="{ 'grid-entered': gridVisible }"
    >
      <div class="flex items-center justify-between mb-8 grid-header">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 border border-gray-200">
          <span class="text-xs font-bold tracking-widest uppercase text-gray-500">All Updates</span>
        </div>
        <span class="text-sm text-gray-400">{{ news.length - 1 }} more articles</span>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(item, i) in news.slice(1)"
          :key="item.title"
          @click="openModal(item)"
          class="news-card group bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden cursor-pointer relative"
          :style="`animation-delay: ${i * 0.09}s`"
        >
          <!-- Image -->
          <div class="aspect-[4/3] overflow-hidden relative">
            <img
              :src="`/src/assets/images/about/${item.image}`"
              :alt="item.alt"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="$event.target.src = 'https://placehold.co/600x450/0f2d6b/ffffff?text=' + encodeURIComponent(item.title)"
            >
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
          </div>

          <!-- Card shimmer -->
          <div class="card-shimmer"></div>

          <!-- Content -->
          <div class="p-6">
            <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-3">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              {{ item.date }}
            </div>

            <h3 class="text-lg font-black text-gray-900 mb-2 leading-snug group-hover:text-blue-900 transition-colors duration-300">
              {{ item.title }}
            </h3>

            <p class="text-gray-400 text-sm leading-relaxed line-clamp-3">{{ item.description }}</p>

            <!-- Read more — slides in on hover -->
            <div class="mt-4 flex items-center gap-1.5 text-xs font-bold text-blue-900 read-more-link">
              Read More
              <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </div>

            <!-- Bottom accent bar -->
            <div class="mt-4 h-0.5 w-0 group-hover:w-full transition-all duration-500 rounded-full" style="background: linear-gradient(90deg,#0f2d6b,#b91c1c)"></div>
          </div>
        </div>
      </div>
    </section>


    <!-- ─────────────────────────────────────────
         EMPTY STATE
    ───────────────────────────────────────── -->
    <section v-if="news.length === 0" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
      <div class="text-6xl mb-4 animate-bounce">📭</div>
      <h3 class="text-xl font-bold text-gray-400">No updates yet</h3>
      <p class="text-gray-400 text-sm mt-2">Check back soon for the latest barangay news.</p>
    </section>


    <!-- ─────────────────────────────────────────
         NEWS DETAIL MODAL
    ───────────────────────────────────────── -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Backdrop -->
          <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" aria-hidden="true" @click="closeModal"></div>

          <!-- Panel -->
          <div class="modal-panel inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

            <!-- Close button -->
            <button @click="closeModal"
              class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-white transition-all duration-200 shadow-lg hover:rotate-90 hover:scale-110">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>

            <!-- Content -->
            <div v-if="selectedNews" class="flex flex-col lg:flex-row">
              <!-- Image section -->
              <div class="lg:w-2/5 bg-gradient-to-br from-blue-900 to-red-600 p-8 flex items-center justify-center modal-img-side">
                <div class="relative">
                  <div class="absolute -inset-4 bg-yellow-400/20 rounded-full blur-2xl"></div>
                  <img
                    :src="`/src/assets/images/about/${selectedNews.image}`"
                    :alt="selectedNews.alt"
                    class="relative z-10 w-full h-auto rounded-2xl shadow-2xl border-4 border-white/20 modal-img"
                    @error="$event.target.src = 'https://placehold.co/600x600/0f2d6b/ffffff?text=' + encodeURIComponent(selectedNews.title)"
                  >
                </div>
              </div>

              <!-- Text section -->
              <div class="lg:w-3/5 p-8 md:p-10 max-h-[80vh] overflow-y-auto modal-content-side">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                  <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-red-600 bg-red-50 border border-red-100 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    Barangay News
                  </span>
                  <span class="text-sm text-gray-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ selectedNews.date }}
                  </span>
                </div>

                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 leading-tight modal-title">
                  {{ selectedNews.title }}
                </h2>

                <div class="prose prose-lg text-gray-600 modal-body">
                  <p class="leading-relaxed whitespace-pre-line">{{ selectedNews.description }}</p>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-400">Share:</span>
                    <button class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-blue-100 flex items-center justify-center text-gray-500 hover:text-blue-600 transition-colors hover:scale-110 duration-200">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                      </svg>
                    </button>
                  </div>
                  <button @click="closeModal" class="px-4 py-2 bg-blue-900 text-white rounded-lg text-sm font-bold hover:bg-blue-800 transition-colors hover:scale-105">
                    Close
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>


<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// ── Particles ────────────────────────────────────
const particles = Array.from({ length: 14 }, (_, i) => ({
  id: i,
  x: Math.random() * 100,
  y: Math.random() * 100,
  size: Math.random() * 3 + 1,
  delay: Math.random() * 4,
  dur: 3 + Math.random() * 5,
}))

// ── News Data ────────────────────────────────────
const news = [
  {
    image: 'year.jpg',
    alt: 'Year End Assessment/Evaluation',
    title: 'Year End Assessment/Evaluation',
    date: 'December 17, 2025',
    description: 'This Year End Assessment/Evaluation is more than just a gathering—it is a sincere recognition of the dedication, perseverance, and selfless service of the hardworking workers of Barangay Dolores. Day in and day out, you continue to serve the community with commitment, integrity, and compassion, often putting the needs of others before your own.',
  },
  {
    image: 'pms.jpg',
    alt: 'Parol Making Contest',
    title: 'Parol Making Contest',
    date: 'December 16, 2025',
    description: 'Sa pangalawang taon na proyekto ng ating Brgy Council at Dolores Elementary School matagumpay at masaya naidaos ang PAROL MAKING CONTEST. Makikita sa ngiti ng bawat isa ang saya at Pagkakaisa ng mga guro, magulang at mga istudyante.',
  },
  {
    image: 'float.jpg',
    alt: 'Float Competition',
    title: 'Float Competition',
    date: 'December 10, 2025',
    description: 'We proudly celebrate our achievement as 2nd Runner-Up in the Float Competition for the 313th Founding Anniversary of Capas! This recognition is a true reflection of the creativity, unity, and hard work of every volunteer, council member, and resident who poured their time and passion into bringing our float to life.',
  },
  {
    image: 'slide2.png',
    alt: 'Urban & Backyard Gardening',
    title: 'Urban & Backyard Gardening',
    date: 'December 7, 2025',
    description: 'Today, we proudly celebrate your remarkable achievement as Champion in the "Search for Best in Urban and Backyard Gardening 2025"! This award is more than just a title—it is a testament to your collective hard work, creativity, and unwavering commitment to environmental stewardship.',
  },
  {
    image: 'a_rabies.jpg',
    alt: 'Anti Rabies Vaccination',
    title: 'Anti Rabies Vaccination',
    date: 'October 16, 2025',
    description: 'Matagumpay isinagawa last October 16, 2025 ang pagbibigay anti rabies vaccination sa mga alagang aso at pusa sa ating Barangay. Pinangunahan ng Capas Municipal Veterinary Office Team headed by Dr. Hensly Hope A. Baun, student Volunteers, Brgy Council at mga Bpats.',
  },
  {
    image: 'clean.jpg',
    alt: 'Weekly Clean Up Drive',
    title: 'Weekly Clean Up Drive',
    date: 'October 10, 2025',
    description: 'KALINISAN ang isa sa prioridad ng ating Brgy Council. Salamat sa mga volunteers at sa mga kabarangay na nakikiisa. \'Cleanliness is next to Godliness\'',
  },
]

// ── Modal ────────────────────────────────────────
const showModal    = ref(false)
const selectedNews = ref(null)

const openModal = (item) => {
  selectedNews.value = item
  showModal.value    = true
  document.body.style.overflow = 'hidden'
}
const closeModal = () => {
  showModal.value = false
  document.body.style.overflow = 'auto'
  setTimeout(() => { selectedNews.value = null }, 350)
}

// ── Intersection observers ───────────────────────
const featuredRef = ref(null)
const gridRef     = ref(null)

const heroVisible     = ref(false)
const featuredVisible = ref(false)
const gridVisible     = ref(false)

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
  observe(featuredRef.value, featuredVisible)
  observe(gridRef.value,     gridVisible)
})
onUnmounted(() => {
  observers.forEach(o => o.disconnect())
  document.body.style.overflow = 'auto'
})
</script>


<style scoped>

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.hero-section { opacity: 0; transform: translateY(-12px); transition: opacity 0.7s ease, transform 0.7s ease; }
.hero-section.hero-entered { opacity: 1; transform: translateY(0); }

.hero-badge, .hero-title, .hero-desc, .hero-stats { opacity: 0; transform: translateY(18px); }
.hero-section.hero-entered .hero-badge  { animation: fadeUp 0.55s ease both 0.10s; }
.hero-section.hero-entered .hero-title  { animation: fadeUp 0.55s ease both 0.22s; }
.hero-section.hero-entered .hero-desc   { animation: fadeUp 0.55s ease both 0.34s; }
.hero-section.hero-entered .hero-stats  { animation: fadeUp 0.55s ease both 0.46s; }

/* Particles */
.hero-particle {
  position: absolute; border-radius: 50%;
  background: rgba(245, 197, 24, 0.55);
  opacity: 0; pointer-events: none;
  animation: particleFade ease-in-out infinite;
}
@keyframes particleFade {
  0%   { opacity: 0; transform: translateY(0) scale(0.5); }
  40%  { opacity: 0.5; }
  100% { opacity: 0; transform: translateY(-50px) scale(1.2); }
}


/* ═══════════════════════════════════════
   FEATURED
═══════════════════════════════════════ */
.featured-label { opacity: 0; transform: translateY(14px); }
.featured-section.featured-entered .featured-label {
  animation: fadeUp 0.5s ease both 0s;
}

.featured-card {
  opacity: 0;
  transform: translateY(28px) scale(0.98);
}
.featured-section.featured-entered .featured-card {
  animation: featuredReveal 0.7s cubic-bezier(0.34, 1.1, 0.64, 1) both 0.1s;
}
@keyframes featuredReveal {
  from { opacity: 0; transform: translateY(28px) scale(0.98); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Shimmer sweep on hover */
.featured-shimmer {
  position: absolute; inset: 0; z-index: 10; pointer-events: none;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.15) 50%, transparent 60%);
  transform: translateX(-100%);
}
.featured-card:hover .featured-shimmer {
  animation: shimmerSlide 0.8s ease forwards;
}


/* ═══════════════════════════════════════
   NEWS GRID
═══════════════════════════════════════ */
.grid-header { opacity: 0; transform: translateY(14px); }
.grid-section.grid-entered .grid-header {
  animation: fadeUp 0.5s ease both 0s;
}

.news-card {
  opacity: 0;
  transform: translateY(32px) scale(0.96);
}
.grid-section.grid-entered .news-card {
  animation: cardReveal 0.6s cubic-bezier(0.34, 1.2, 0.64, 1) both;
}
@keyframes cardReveal {
  from { opacity: 0; transform: translateY(32px) scale(0.96); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Read more slides up from hidden on hover */
.read-more-link {
  opacity: 0;
  transform: translateY(6px);
  transition: opacity 0.25s ease, transform 0.25s ease;
}
.group:hover .read-more-link {
  opacity: 1;
  transform: translateY(0);
}

/* Card shimmer */
.card-shimmer {
  position: absolute; inset: 0; pointer-events: none;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
  transform: translateX(-100%);
}
.group:hover .card-shimmer {
  animation: shimmerSlide 0.65s ease forwards;
}


/* ═══════════════════════════════════════
   MODAL
═══════════════════════════════════════ */
.modal-enter-active { transition: opacity 0.3s ease; }
.modal-leave-active { transition: opacity 0.35s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }

.modal-panel {
  transition: transform 0.35s cubic-bezier(0.34, 1.2, 0.64, 1), opacity 0.3s ease;
}
.modal-enter-from .modal-panel,
.modal-leave-to   .modal-panel {
  transform: scale(0.92) translateY(24px);
  opacity: 0;
}
.modal-enter-to .modal-panel,
.modal-leave-from .modal-panel {
  transform: scale(1) translateY(0);
  opacity: 1;
}

/* Modal inner elements stagger in */
.modal-img-side  { animation: slideInLeft  0.45s cubic-bezier(0.34,1.1,0.64,1) both 0.1s; }
.modal-img       { animation: imgReveal    0.5s cubic-bezier(0.34,1.2,0.64,1) both 0.2s; }
.modal-title     { animation: fadeUp       0.45s ease both 0.15s; }
.modal-body      { animation: fadeUp       0.45s ease both 0.25s; }
.modal-content-side { animation: slideInRight 0.45s cubic-bezier(0.34,1.1,0.64,1) both 0.05s; }

@keyframes slideInLeft {
  from { opacity: 0; transform: translateX(-20px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes slideInRight {
  from { opacity: 0; transform: translateX(20px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes imgReveal {
  from { opacity: 0; transform: scale(0.92) rotate(-1deg); }
  to   { opacity: 1; transform: scale(1) rotate(0deg); }
}

/* Close button spin on hover */
button.hover\:rotate-90:hover { transform: rotate(90deg) scale(1.1); }

/* Scrollbar */
.overflow-y-auto::-webkit-scrollbar       { width: 6px; }
.overflow-y-auto::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.overflow-y-auto::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
.overflow-y-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }


/* ═══════════════════════════════════════
   SHARED
═══════════════════════════════════════ */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
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

.line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
</style>