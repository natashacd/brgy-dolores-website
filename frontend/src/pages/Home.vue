<template>
  <div class="min-h-screen bg-gray-50">

    <!-- ═══════════════════════════════════════════
         PRELOADER
    ════════════════════════════════════════════ -->
    <Transition name="preloader">
      <div v-if="isLoading" class="preloader-overlay">
        <div class="preloader-content">
          <!-- Rings + Logo centered together -->
          <div class="crest-rings">
            <div class="ring ring-1"></div>
            <div class="ring ring-2"></div>
            <div class="ring ring-3"></div>
            <div class="ring ring-4"></div>
            <!-- Logo sits in the center of the rings -->
            <div class="preloader-emblem">
              <img src="@/assets/images/dolores_logo.png" alt="Barangay Dolores Logo" class="emblem-logo" />
            </div>
          </div>
        </div>
      </div>
    </Transition>


    <!-- ═══════════════════════════════════════════
         HERO — SVG COLLAPSING FRAME CAROUSEL
    ════════════════════════════════════════════ -->
    <section
      class="relative overflow-hidden select-none hero-section"
      :class="{ 'hero-entered': heroEntered }"
      style="height: clamp(580px, 92vh, 860px)"
    >
      <!-- Hidden SVG clipPath -->
      <svg class="absolute w-0 h-0 overflow-hidden" aria-hidden="true" focusable="false">
        <defs>
          <clipPath id="slide-clip" clipPathUnits="objectBoundingBox">
            <rect
              :x="clipX / 100"
              y="0"
              :width="clipW / 100"
              height="1"
            />
          </clipPath>
        </defs>
      </svg>

      <!-- Background (next) slide — always fully visible behind -->
      <div
        class="absolute inset-0"
        :style="{ background: slides[nextIdx].bg }"
      >
        <div class="absolute inset-0 opacity-[0.025]"
          style="background-image: repeating-linear-gradient(45deg, white 0, white 1px, transparent 0, transparent 50%); background-size: 36px 36px">
        </div>
      </div>

      <!-- Foreground (current) slide — clipped by SVG rect animation -->
      <div
        class="absolute inset-0"
        :style="{
          background: slides[current].bg,
          clipPath: 'url(#slide-clip)',
          WebkitClipPath: 'url(#slide-clip)',
        }"
      >
        <!-- Floating orbs -->
        <div class="orb orb-1" :style="`background: ${slides[current].accent}18`"></div>
        <div class="orb orb-2" :style="`background: ${slides[current].accent}10`"></div>
        <div class="orb orb-3" :style="`background: ${slides[current].accent}08`"></div>

        <!-- Decorative rings -->
        <div class="absolute -top-40 -right-40 w-[700px] h-[700px] rounded-full pointer-events-none ring-pulse"
          :style="`border: 1px solid ${slides[current].accent}20`"></div>
        <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] rounded-full pointer-events-none ring-pulse-slow"
          :style="`border: 1px solid ${slides[current].accent}15`"></div>
        <div class="absolute top-1/2 right-[18%] w-64 h-64 rounded-full -translate-y-1/2 pointer-events-none"
          :style="`border: 1px solid ${slides[current].accent}10`"></div>

        <!-- Particles -->
        <div v-for="p in particles" :key="p.id"
          class="particle"
          :style="`left:${p.x}%; top:${p.y}%; width:${p.size}px; height:${p.size}px; background:${slides[current].accent}; animation-delay:${p.delay}s; animation-duration:${p.dur}s`">
        </div>

        <!-- Grid texture -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
          style="background-image: repeating-linear-gradient(45deg, white 0, white 1px, transparent 0, transparent 50%); background-size: 36px 36px">
        </div>
        <!-- Bottom vignette -->
        <div class="absolute bottom-0 inset-x-0 h-52 bg-gradient-to-t from-black/45 to-transparent pointer-events-none"></div>
      </div>

      <!-- ── Slide content — re-keyed to re-trigger animations ── -->
      <div
        class="relative h-full flex items-center"
        style="z-index: 10"
        :key="animKey"
      >
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full">
          <div class="max-w-2xl xl:max-w-3xl">

            <!-- Badge -->
            <div
              class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6"
              :style="`
                border: 1px solid ${slides[current].accent}50;
                background: ${slides[current].accent}10;
                color: ${slides[current].accent};
                animation: slideUp 0.55s ease both 0.05s
              `"
            >
              {{ slides[current].badge }}
            </div>

            <!-- Title -->
            <h1
              class="font-black text-white leading-none tracking-tight mb-4"
              style="font-size: clamp(2.6rem, 7vw, 5.5rem); text-shadow: 0 4px 32px rgba(0,0,0,0.5); animation: slideUp 0.55s ease both 0.15s"
            >
              {{ slides[current].title }}<br>
              <span :style="`color: ${slides[current].accent}`">{{ slides[current].titleAccent }}</span>
            </h1>

            <!-- Subtitle rule -->
            <div
              class="flex items-center gap-3 mb-5"
              style="animation: slideUp 0.55s ease both 0.25s"
            >
              <div class="h-0.5 w-10 rounded-full flex-shrink-0 rule-pulse"
                :style="`background: ${slides[current].accent}`"></div>
              <span class="text-sm font-semibold tracking-widest uppercase text-white/60">
                {{ slides[current].subtitle }}
              </span>
            </div>

            <!-- Description -->
            <p
              class="text-white/73 leading-relaxed mb-8 max-w-xl"
              style="font-size: clamp(0.875rem, 1.5vw, 1.05rem); animation: slideUp 0.55s ease both 0.35s"
            >
              {{ slides[current].desc }}
            </p>

            <!-- CTA buttons -->
            <div class="flex flex-wrap gap-3 mb-10" style="animation: slideUp 0.55s ease both 0.45s">
              <router-link
                :to="slides[current].cta.path"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-extrabold no-underline shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-2xl active:scale-95"
                :style="`background: ${slides[current].accent}; color: #0a1a3a`"
              >
                {{ slides[current].cta.label }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
              </router-link>
              <router-link
                :to="slides[current].ctaSecondary.path"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold no-underline border border-white/30 text-white bg-white/5 backdrop-blur-sm transition-all duration-200 hover:bg-white/15 hover:-translate-y-0.5 active:scale-95"
              >
                {{ slides[current].ctaSecondary.label }}
              </router-link>
            </div>

            <!-- Stats -->
            <div class="flex items-center gap-7" style="animation: slideUp 0.55s ease both 0.55s">
              <div>
                <div class="font-black" style="font-size: clamp(1.6rem, 3vw, 2rem)"
                  :style="`color: ${slides[current].accent}`">
                  {{ slides[current].stat1.value }}
                </div>
                <div class="text-xs text-white/45 uppercase tracking-wider mt-0.5">
                  {{ slides[current].stat1.label }}
                </div>
              </div>
              <div class="w-px h-10 bg-white/15"></div>
              <div>
                <div class="font-black" style="font-size: clamp(1.6rem, 3vw, 2rem)"
                  :style="`color: ${slides[current].accent}`">
                  {{ slides[current].stat2.value }}
                </div>
                <div class="text-xs text-white/45 uppercase tracking-wider mt-0.5">
                  {{ slides[current].stat2.label }}
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- ── Vertical thumbnail nav (right side) ── -->
      <div
        class="absolute right-5 top-1/2 -translate-y-1/2 flex flex-col gap-3"
        style="z-index: 20"
      >
        <button
          v-for="(s, i) in slides"
          :key="s.id"
          @click="goTo(i)"
          class="flex flex-col items-center gap-1.5 cursor-pointer"
          :aria-label="`Go to slide ${i + 1}`"
        >
          <div
            class="rounded-xl overflow-hidden border-2 transition-all duration-400 flex items-center justify-center"
            :style="`
              width:  ${i === current ? '54px' : '38px'};
              height: ${i === current ? '54px' : '38px'};
              border-color: ${i === current ? s.accent : 'rgba(255,255,255,0.2)'};
              background: ${s.bg};
              box-shadow: ${i === current ? `0 0 16px ${s.accent}40` : 'none'};
            `"
          >
            <span :style="`font-size: ${i === current ? '1.4rem' : '1rem'}; opacity: ${i === current ? 0.9 : 0.55}; transition: all 0.3s`">
              {{ s.icon }}
            </span>
          </div>
          <!-- Active indicator bar -->
          <div
            class="rounded-full transition-all duration-400"
            :style="`
              width:  ${i === current ? '4px' : '3px'};
              height: ${i === current ? '22px' : '8px'};
              background: ${i === current ? s.accent : 'rgba(255,255,255,0.2)'};
            `"
          ></div>
        </button>
      </div>

      <!-- ── Slide counter bottom-left ── -->
      <div
        class="absolute bottom-7 left-6 flex items-baseline gap-1"
        style="z-index: 20"
      >
        <span class="font-black text-white" style="font-size: 1.5rem; line-height:1">
          {{ String(current + 1).padStart(2, '0') }}
        </span>
        <span class="text-white/30 text-sm font-medium">/</span>
        <span class="text-white/40 text-sm font-medium">
          {{ String(slides.length).padStart(2, '0') }}
        </span>
      </div>

      <!-- ── Dot indicators (centered bottom) ── -->
      <div
        class="absolute bottom-7 left-1/2 -translate-x-1/2 flex items-center gap-2"
        style="z-index: 20"
      >
        <button
          v-for="(s, i) in slides"
          :key="s.id"
          @click="goTo(i)"
          class="rounded-full transition-all duration-300"
          :style="i === current
            ? `width: 28px; height: 8px; background: ${s.accent}`
            : 'width: 8px; height: 8px; background: rgba(255,255,255,0.3)'"
          :aria-label="`Go to slide ${i + 1}`"
        ></button>
      </div>

      <!-- ── Progress bar ── -->
      <div class="absolute bottom-0 inset-x-0 h-[3px] bg-white/10" style="z-index: 20">
        <div
          class="h-full"
          :key="animKey"
          :style="`background: ${slides[current].accent}; animation: progressFill 5.5s linear both`"
        ></div>
      </div>
    </section>


    <!-- ═══════════════════════════════════════════
         QUICK ACCESS CARDS
    ════════════════════════════════════════════ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative" style="z-index: 30">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <router-link
          v-for="(card, i) in quickCards" :key="card.path" :to="card.path"
          class="group flex items-center gap-3 sm:gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-gray-100 shadow-xl hover:shadow-2xl no-underline transition-all duration-300 hover:-translate-y-1 quick-card"
          :style="`animation-delay: ${i * 0.08 + 0.1}s`"
          :class="{ 'quick-card-entered': sectionEntered }"
        >
          <div
            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 text-xl transition-transform duration-300 group-hover:scale-110"
            :style="`background: ${card.bgColor}`"
          >{{ card.icon }}</div>
          <div class="min-w-0">
            <p class="text-xs sm:text-sm font-bold text-gray-800 m-0 truncate">{{ card.title }}</p>
            <p class="text-[0.65rem] sm:text-xs text-gray-400 m-0 mt-0.5 truncate">{{ card.sub }}</p>
          </div>
          <svg class="w-4 h-4 text-gray-300 ml-auto flex-shrink-0 group-hover:text-gray-500 group-hover:translate-x-0.5 transition-all duration-200"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </router-link>
      </div>
    </section>


    <!-- ═══════════════════════════════════════════
         ABOUT
    ════════════════════════════════════════════ -->
    <section
      ref="aboutRef"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 about-section"
      :class="{ 'about-entered': aboutVisible }"
    >
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div>
          <p class="text-xs font-extrabold tracking-[0.22em] uppercase mb-3 m-0 about-fade" style="color:#0f2d6b; animation-delay:0s">About Barangay Dolores</p>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight mb-5 m-0 about-fade" style="animation-delay:0.1s">
            A Community of<br>
            <span style="background: linear-gradient(135deg,#0f2d6b,#c8102e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text">
              Strength &amp; Service
            </span>
          </h2>
          <p class="text-gray-500 leading-relaxed mb-4 text-sm sm:text-base about-fade" style="animation-delay:0.2s">
            Barangay Dolores in Capas, Tarlac is home to thousands of hardworking families. Our local government is committed to delivering efficient, transparent, and people-centered governance.
          </p>
          <p class="text-gray-500 leading-relaxed mb-7 text-sm sm:text-base about-fade" style="animation-delay:0.3s">
            From community programs to essential certifications, we strive to make government services accessible and hassle-free for every resident.
          </p>
          <router-link to="/about"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-extrabold no-underline text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl about-fade"
            style="background: linear-gradient(135deg,#0f2d6b,#1a3f8f); animation-delay:0.4s">
            Read Our Story
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
          </router-link>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div v-for="(stat, i) in aboutStats" :key="stat.label"
            class="p-6 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 text-center about-stat"
            :style="`animation-delay: ${i * 0.1 + 0.2}s`">
            <div class="text-3xl mb-2">{{ stat.icon }}</div>
            <div class="text-2xl sm:text-3xl font-black mb-1" :style="`color:${stat.color}`">{{ stat.value }}</div>
            <div class="text-[0.65rem] text-gray-400 font-semibold uppercase tracking-widest">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>


    <!-- ═══════════════════════════════════════════
         SERVICES
    ════════════════════════════════════════════ -->
    <section
      ref="servicesRef"
      class="py-16 sm:py-20 services-section"
      :class="{ 'services-entered': servicesVisible }"
      style="background: linear-gradient(135deg,#0a1f4e 0%,#0f2d6b 100%)"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 svc-fade" style="animation-delay:0s">
          <p class="text-xs font-extrabold tracking-[0.22em] uppercase text-yellow-400 mb-3">What We Offer</p>
          <h2 class="text-3xl sm:text-4xl font-black text-white m-0">Barangay Services</h2>
          <p class="text-white/50 mt-3 max-w-lg mx-auto text-sm">Access essential services quickly and conveniently from your local barangay hall.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
          <router-link v-for="(svc, i) in services" :key="svc.name" to="/services"
            class="group flex flex-col items-center gap-3 p-4 sm:p-5 rounded-2xl no-underline border border-white/10 bg-white/5 hover:bg-white/10 hover:border-yellow-400/40 transition-all duration-200 hover:-translate-y-1 text-center svc-card"
            :style="`animation-delay: ${i * 0.07 + 0.15}s`">
            <span class="text-3xl sm:text-4xl group-hover:scale-110 transition-transform duration-200">{{ svc.icon }}</span>
            <span class="text-xs font-semibold text-white/65 group-hover:text-white transition-colors leading-tight">{{ svc.name }}</span>
          </router-link>
        </div>
        <div class="text-center mt-10 svc-fade" style="animation-delay:0.55s">
          <router-link to="/services"
            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-extrabold no-underline text-blue-900 bg-yellow-400 hover:bg-yellow-300 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-2xl btn-glow">
            View All Services
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
          </router-link>
        </div>
      </div>
    </section>


    <!-- ═══════════════════════════════════════════
         LATEST NEWS
    ════════════════════════════════════════════ -->
    <section
      ref="newsRef"
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 news-section"
      :class="{ 'news-entered': newsVisible }"
    >
      <div class="flex items-end justify-between mb-10 news-header">
        <div>
          <p class="text-xs font-extrabold tracking-[0.22em] uppercase mb-2 m-0" style="color:#0f2d6b">What's New</p>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900 m-0">Latest News</h2>
        </div>
        <router-link to="/news"
          class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold no-underline transition-colors hover:opacity-70"
          style="color:#0f2d6b">
          View all
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
          </svg>
        </router-link>
      </div>
      <div class="grid md:grid-cols-3 gap-5 sm:gap-6">
        <div v-for="(news, i) in latestNews" :key="news.title"
          class="group rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 cursor-pointer news-card"
          :style="`animation-delay: ${i * 0.12 + 0.1}s`">
          <div class="h-44 relative overflow-hidden flex items-center justify-center"
            :style="`background: linear-gradient(135deg,${news.color1},${news.color2})`">
            <span class="text-7xl opacity-20 group-hover:opacity-35 group-hover:scale-110 transition-all duration-500 select-none">{{ news.emoji }}</span>
            <div class="absolute top-3 left-3">
              <span class="px-2.5 py-1 rounded-full text-[0.62rem] font-extrabold uppercase tracking-wider text-white bg-black/30 backdrop-blur-sm">
                {{ news.category }}
              </span>
            </div>
            <div class="news-shimmer"></div>
          </div>
          <div class="p-5">
            <p class="text-[0.62rem] text-gray-400 uppercase tracking-widest font-semibold m-0 mb-2">{{ news.date }}</p>
            <h3 class="text-sm font-bold text-gray-800 leading-snug m-0 mb-3 group-hover:text-blue-700 transition-colors">{{ news.title }}</h3>
            <p class="text-xs text-gray-400 leading-relaxed m-0 line-clamp-2">{{ news.excerpt }}</p>
            <div class="mt-4 flex items-center gap-1 text-xs font-bold" style="color:#0f2d6b">
              Read more
              <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ═══════════════════════════════════════════
         CTA BANNER
    ════════════════════════════════════════════ -->
    <section
      ref="ctaRef"
      class="mx-4 sm:mx-6 lg:mx-8 mb-16 rounded-3xl overflow-hidden relative cta-section"
      :class="{ 'cta-entered': ctaVisible }"
      style="background: linear-gradient(135deg,#0f2d6b 0%,#c8102e 100%)"
    >
      <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: repeating-linear-gradient(45deg,white 0,white 1px,transparent 0,transparent 50%); background-size:32px 32px">
      </div>
      <div class="cta-glare"></div>
      <div class="relative max-w-3xl mx-auto text-center px-6 py-14 sm:py-16">
        <p class="text-xs font-extrabold tracking-[0.2em] uppercase text-white/50 mb-3">Ready to Get Started?</p>
        <h2 class="text-2xl sm:text-3xl font-black text-white mb-4 leading-tight">
          Need a Barangay Certificate<br>or Document?
        </h2>
        <p class="text-white/60 text-sm mb-8 max-w-md mx-auto leading-relaxed">
          Visit the barangay hall during office hours or learn about our services online. We're here to help!
        </p>
        <div class="flex flex-wrap justify-center gap-3">
          <router-link to="/services"
            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-extrabold no-underline text-blue-900 bg-yellow-400 hover:bg-yellow-300 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl btn-glow">
            Browse Services
          </router-link>
          <router-link to="/about"
            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-bold no-underline text-white border border-white/30 bg-white/10 hover:bg-white/20 transition-all duration-200 hover:-translate-y-0.5">
            Learn About Us
          </router-link>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// ── Preloader ────────────────────────────────────────────────────
const isLoading      = ref(true)
const loadProgress   = ref(0)
const heroEntered    = ref(false)
const sectionEntered = ref(false)

function runPreloader() {
  const duration = 1200
  const start    = performance.now()
  function tick(now) {
    const t = Math.min((now - start) / duration, 1)
    loadProgress.value = (1 - Math.pow(1 - t, 3)) * 100
    if (t < 1) {
      requestAnimationFrame(tick)
    } else {
      loadProgress.value = 100
      setTimeout(() => {
        isLoading.value = false
        setTimeout(() => {
          heroEntered.value    = true
          sectionEntered.value = true
        }, 120)
      }, 350)
    }
  }
  requestAnimationFrame(tick)
}

// ── Particles ────────────────────────────────────────────────────
const particles = Array.from({ length: 18 }, (_, i) => ({
  id: i,
  x:    Math.random() * 100,
  y:    Math.random() * 100,
  size: Math.random() * 3 + 1,
  delay: Math.random() * 4,
  dur:   3 + Math.random() * 4,
}))

// ── Carousel state ───────────────────────────────────────────────
const slides = [
  {
    id: 1,
    badge: '🏛️ Welcome to Our Community',
    title: 'Barangay',
    titleAccent: 'Dolores',
    subtitle: 'Capas, Tarlac',
    desc: 'A community built on unity, integrity, and progress. Serving every resident with dedication and transparency since 1950.',
    cta: { label: 'Explore Services', path: '/services' },
    ctaSecondary: { label: 'Learn About Us', path: '/about' },
    stat1: { value: '75+', label: 'Years of Service' },
    stat2: { value: '12K+', label: 'Residents Served' },
    bg: 'linear-gradient(135deg, #0a1f4e 0%, #0f2d6b 60%, #163a90 100%)',
    accent: '#f5c518',
    icon: '🏛️',
  },
  {
    id: 2,
    badge: '📢 Stay Informed',
    title: 'News &',
    titleAccent: 'Updates',
    subtitle: 'Community Announcements',
    desc: 'Keep up with the latest barangay announcements, programs, events, and important notices affecting our community.',
    cta: { label: 'Read News', path: '/news' },
    ctaSecondary: { label: 'View Announcements', path: '/news' },
    stat1: { value: '24/7', label: 'Online Access' },
    stat2: { value: '100%', label: 'Transparency' },
    bg: 'linear-gradient(135deg, #1a0812 0%, #6b0f2d 60%, #8f1a3f 100%)',
    accent: '#fba94c',
    icon: '📰',
  },
  {
    id: 3,
    badge: "🤝 We're Here to Help",
    title: 'Barangay',
    titleAccent: 'Services',
    subtitle: 'Fast & Accessible',
    desc: 'From clearances and certificates to health programs and livelihood support — access all barangay services easily.',
    cta: { label: 'View All Services', path: '/services' },
    ctaSecondary: { label: 'Learn More', path: '/about' },
    stat1: { value: '15+', label: 'Services' },
    stat2: { value: '3 Days', label: 'Avg. Processing' },
    bg: 'linear-gradient(135deg, #071a0f 0%, #0d3320 60%, #14522f 100%)',
    accent: '#4ade80',
    icon: '⚙️',
  },
]

const current  = ref(0)
const nextIdx  = ref(1)
const animKey  = ref(0)
const phase    = ref('idle')
let autoTimer  = null
let rafId      = null
let startTime  = null

const clipW = ref(100)
const clipX = ref(0)

const COLLAPSE_DUR = 700
const EXPAND_DUR   = 700

function easeInOut(t) { return t < 0.5 ? 2*t*t : -1+(4-2*t)*t }
function lerp(a, b, t) { return a + (b - a) * t }

function startTransition(target) {
  if (phase.value !== 'idle') return
  nextIdx.value = target
  phase.value   = 'collapsing'
  clipW.value   = 100
  clipX.value   = 0
  startTime     = performance.now()
  cancelAnimationFrame(rafId)
  rafId = requestAnimationFrame(animateCollapse)
}

function animateCollapse(now) {
  const t = Math.min((now - startTime) / COLLAPSE_DUR, 1)
  const e = easeInOut(t)
  clipW.value = lerp(100, 0, e)
  clipX.value = lerp(0, 50, e)
  if (t < 1) {
    rafId = requestAnimationFrame(animateCollapse)
  } else {
    current.value = nextIdx.value
    animKey.value++
    phase.value   = 'expanding'
    clipW.value   = 0
    clipX.value   = 50
    startTime     = performance.now()
    rafId = requestAnimationFrame(animateExpand)
  }
}

function animateExpand(now) {
  const t = Math.min((now - startTime) / EXPAND_DUR, 1)
  const e = easeInOut(t)
  clipW.value = lerp(0, 100, e)
  clipX.value = lerp(50, 0, e)
  if (t < 1) {
    rafId = requestAnimationFrame(animateExpand)
  } else {
    clipW.value = 100
    clipX.value = 0
    phase.value = 'idle'
    resetAuto()
  }
}

function goTo(index) {
  if (index === current.value || phase.value !== 'idle') return
  clearInterval(autoTimer)
  startTransition((index + slides.length) % slides.length)
}

function goNext() {
  goTo((current.value + 1) % slides.length)
}

function resetAuto() {
  clearInterval(autoTimer)
  autoTimer = setInterval(goNext, 5500)
}

// ── Intersection observers ───────────────────────────────────────
const aboutRef    = ref(null)
const servicesRef = ref(null)
const newsRef     = ref(null)
const ctaRef      = ref(null)

const aboutVisible    = ref(false)
const servicesVisible = ref(false)
const newsVisible     = ref(false)
const ctaVisible      = ref(false)

let observers = []

function observeSection(el, visibleRef) {
  if (!el) return
  const obs = new IntersectionObserver(
    ([entry]) => { if (entry.isIntersecting) { visibleRef.value = true; obs.disconnect() } },
    { threshold: 0.12 }
  )
  obs.observe(el)
  observers.push(obs)
}

onMounted(() => {
  runPreloader()
  resetAuto()
  setTimeout(() => {
    observeSection(aboutRef.value,    aboutVisible)
    observeSection(servicesRef.value, servicesVisible)
    observeSection(newsRef.value,     newsVisible)
    observeSection(ctaRef.value,      ctaVisible)
  }, 100)
})

onUnmounted(() => {
  clearInterval(autoTimer)
  cancelAnimationFrame(rafId)
  observers.forEach(o => o.disconnect())
})

// ── Static data ──────────────────────────────────────────────────
const quickCards = [
  { title: 'Request Certificate', sub: 'Clearance & more',      icon: '📄', path: '/services', bgColor: 'rgba(15,45,107,0.1)'   },
  { title: 'News & Updates',      sub: 'Latest announcements',  icon: '📰', path: '/news',     bgColor: 'rgba(200,16,46,0.1)'   },
  { title: 'Barangay Services',   sub: 'View all services',     icon: '⚙️', path: '/services', bgColor: 'rgba(34,197,94,0.12)'  },
  { title: 'About Barangay',      sub: 'Our history & mission', icon: '🏛️', path: '/about',   bgColor: 'rgba(245,197,24,0.15)' },
]

const aboutStats = [
  { value: '75+',  label: 'Years Serving', icon: '🏛️', color: '#0f2d6b' },
  { value: '12K+', label: 'Residents',     icon: '👨‍👩‍👧‍👦', color: '#c8102e' },
  { value: '15+',  label: 'Services',      icon: '⚙️',  color: '#0f2d6b' },
  { value: '100%', label: 'Transparency',  icon: '✅',  color: '#16a34a' },
]

const services = [
  { name: 'Barangay Clearance',       icon: '📋' },
  { name: 'Certificate of Residency', icon: '🏠' },
  { name: 'Indigency Certificate',    icon: '📜' },
  { name: 'Business Clearance',       icon: '🏪' },
  { name: 'Health Programs',          icon: '💊' },
  { name: 'Livelihood Support',       icon: '🌱' },
]

const latestNews = [
  {
    title: 'Barangay Clean-Up Drive This Saturday',
    category: 'Environment', date: 'March 1, 2025',
    excerpt: 'All residents are encouraged to join our monthly clean-up. Materials will be provided by the barangay.',
    emoji: '🌿', color1: '#0a4a1a', color2: '#16a34a',
  },
  {
    title: 'Free Medical Mission for All Residents',
    category: 'Health', date: 'February 22, 2025',
    excerpt: 'The barangay health center will conduct a free medical mission in partnership with the municipal health office.',
    emoji: '🏥', color1: '#0a2d6b', color2: '#1d4ed8',
  },
  {
    title: 'New Livelihood Program Opens Applications',
    category: 'Programs', date: 'February 15, 2025',
    excerpt: 'Qualified residents may now apply for the new livelihood assistance program at the barangay hall.',
    emoji: '💼', color1: '#4a1a0a', color2: '#c8102e',
  },
]
</script>


<style scoped>

/* ═══════════════════════════════════════
   PRELOADER
═══════════════════════════════════════ */
.preloader-overlay {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: #060f26;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.preloader-enter-active { transition: opacity 0.5s ease, transform 0.5s ease; }
.preloader-leave-active { transition: opacity 0.65s cubic-bezier(0.4,0,0.2,1), transform 0.65s cubic-bezier(0.4,0,0.2,1); }
.preloader-enter-from  { opacity: 0; }
.preloader-leave-to    { opacity: 0; transform: scale(1.04); }

.preloader-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}

/* Crest rings */
.crest-rings {
  position: relative;
  width: 200px;
  height: 200px;
  margin-bottom: 24px;
  flex-shrink: 0;
}
.ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 1.5px solid transparent;
  animation: ringRotate linear infinite;
  will-change: transform;
}
.ring-1 { border-top-color: #f5c518; border-right-color: #f5c51840; animation-duration: 4s; }
.ring-2 { inset: 16px; border-top-color: #ffffff25; border-left-color: #f5c51855; animation-duration: 6s; animation-direction: reverse; }
.ring-3 { inset: 32px; border-bottom-color: #f5c518; border-left-color: #f5c51825; animation-duration: 8s; }
.ring-4 { inset: 48px; border-top-color: #ffffff15; border-right-color: #c8102e70; animation-duration: 10s; animation-direction: reverse; }
@keyframes ringRotate { to { transform: rotate(360deg); } }

/* Center emblem — absolutely centered inside .crest-rings */
.preloader-emblem {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 96px;
  height: 96px;
  border-radius: 50%;
  overflow: hidden;
  z-index: 10;
  box-shadow: 0 0 0 2px rgba(245,197,24,0.35), 0 0 24px rgba(245,197,24,0.2);
  background: #060f26;
}
.emblem-logo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
  display: block;
}

/* Text */
.preloader-text { text-align: center; margin-bottom: 24px; }
.preloader-title {
  display: flex; gap: 3px; justify-content: center;
  font-size: 0.6rem; font-weight: 900; letter-spacing: 0.35em;
  color: rgba(255,255,255,0.5); margin-bottom: 4px;
}
.preloader-subtitle {
  display: flex; gap: 2px; justify-content: center;
  font-size: 2.2rem; font-weight: 900; letter-spacing: 0.08em;
  line-height: 1; margin-bottom: 6px;
}
.preloader-tagline {
  font-size: 0.65rem; color: rgba(255,255,255,0.3);
  letter-spacing: 0.25em; text-transform: uppercase;
  animation: fadeIn 0.6s ease both 1.2s;
}

.letter {
  display: inline-block; color: white;
  animation: letterDrop 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
}
.letter-accent {
  background: linear-gradient(135deg, #f5c518, #fba94c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
@keyframes letterDrop {
  from { opacity: 0; transform: translateY(-20px) scale(0.8); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Loading bar */
.preloader-bar-wrap {
  width: 220px; height: 3px;
  background: rgba(255,255,255,0.08);
  border-radius: 99px; overflow: hidden; margin-bottom: 8px;
}
.preloader-bar {
  height: 100%;
  background: linear-gradient(90deg, #0f2d6b, #f5c518, #c8102e);
  border-radius: 99px;
  transition: width 0.08s linear;
  box-shadow: 0 0 12px #f5c51860;
}
.preloader-percent {
  font-size: 0.65rem; color: rgba(255,255,255,0.3);
  letter-spacing: 0.1em; text-align: center;
}


/* ═══════════════════════════════════════
   HERO ENTRANCE
═══════════════════════════════════════ */
.hero-section { opacity: 0; transform: scale(0.99); transition: opacity 0.8s ease, transform 0.8s ease; }
.hero-section.hero-entered { opacity: 1; transform: scale(1); }

/* Floating orbs */
.orb {
  position: absolute; border-radius: 50%;
  filter: blur(60px); pointer-events: none;
  animation: orbFloat ease-in-out infinite;
}
.orb-1 { width: 400px; height: 400px; top: -100px; right: -80px; animation-duration: 8s; }
.orb-2 { width: 300px; height: 300px; bottom: 50px; left: 10%; animation-duration: 11s; animation-delay: -3s; }
.orb-3 { width: 200px; height: 200px; top: 40%; right: 25%; animation-duration: 6s; animation-delay: -5s; }
@keyframes orbFloat {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33%       { transform: translate(15px, -20px) scale(1.05); }
  66%       { transform: translate(-10px, 12px) scale(0.97); }
}

/* Particles */
.particle {
  position: absolute; border-radius: 50%; opacity: 0;
  animation: particleFade ease-in-out infinite; pointer-events: none;
}
@keyframes particleFade {
  0%   { opacity: 0; transform: translateY(0) scale(0.5); }
  30%  { opacity: 0.6; }
  70%  { opacity: 0.3; }
  100% { opacity: 0; transform: translateY(-40px) scale(1.2); }
}

/* Ring pulse */
.ring-pulse { animation: ringPulseAnim 4s ease-in-out infinite; }
.ring-pulse-slow { animation: ringPulseAnim 6s ease-in-out infinite 1s; }
@keyframes ringPulseAnim {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(1.03); }
}

/* Rule pulse */
.rule-pulse { animation: rulePulse 2s ease-in-out infinite; }
@keyframes rulePulse {
  0%, 100% { opacity: 1; width: 40px; }
  50%       { opacity: 0.6; width: 52px; }
}


/* ═══════════════════════════════════════
   QUICK CARDS
═══════════════════════════════════════ */
.quick-card { opacity: 0; transform: translateY(24px); }
.quick-card.quick-card-entered {
  animation: cardRise 0.55s cubic-bezier(0.34,1.2,0.64,1) both;
}
@keyframes cardRise {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}


/* ═══════════════════════════════════════
   ABOUT SCROLL ANIMATIONS
═══════════════════════════════════════ */
.about-fade { opacity: 0; transform: translateX(-24px); }
.about-section.about-entered .about-fade {
  animation: aboutSlideIn 0.6s cubic-bezier(0.34,1.1,0.64,1) both;
}
@keyframes aboutSlideIn {
  from { opacity: 0; transform: translateX(-24px); }
  to   { opacity: 1; transform: translateX(0); }
}

.about-stat { opacity: 0; transform: translateY(20px) scale(0.95); }
.about-section.about-entered .about-stat {
  animation: statPop 0.55s cubic-bezier(0.34,1.4,0.64,1) both;
}
@keyframes statPop {
  from { opacity: 0; transform: translateY(20px) scale(0.95); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}


/* ═══════════════════════════════════════
   SERVICES SCROLL ANIMATIONS
═══════════════════════════════════════ */
.svc-fade { opacity: 0; transform: translateY(20px); }
.services-section.services-entered .svc-fade {
  animation: fadeUp 0.6s ease both;
}

.svc-card { opacity: 0; transform: translateY(20px) scale(0.9); }
.services-section.services-entered .svc-card {
  animation: svcReveal 0.5s cubic-bezier(0.34,1.3,0.64,1) both;
}
@keyframes svcReveal {
  from { opacity: 0; transform: translateY(20px) scale(0.9); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Glow pulse on yellow CTA */
.btn-glow { position: relative; overflow: hidden; }
.btn-glow::after {
  content: '';
  position: absolute; inset: 0; border-radius: inherit;
  animation: btnGlowPulse 2.5s ease-in-out infinite;
}
@keyframes btnGlowPulse {
  0%   { box-shadow: 0 0 0 0 rgba(245,197,24,0.5); }
  70%  { box-shadow: 0 0 0 10px rgba(245,197,24,0); }
  100% { box-shadow: 0 0 0 0 rgba(245,197,24,0); }
}


/* ═══════════════════════════════════════
   NEWS SCROLL ANIMATIONS
═══════════════════════════════════════ */
.news-header { opacity: 0; transform: translateY(16px); }
.news-section.news-entered .news-header { animation: fadeUp 0.5s ease both; }

.news-card { opacity: 0; transform: translateY(32px); }
.news-section.news-entered .news-card {
  animation: cardRise 0.6s cubic-bezier(0.34,1.2,0.64,1) both;
}

/* Shimmer on news image hover */
.news-shimmer {
  position: absolute; inset: 0;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.12) 50%, transparent 60%);
  transform: translateX(-100%);
}
.group:hover .news-shimmer { animation: shimmerSlide 0.6s ease forwards; }
@keyframes shimmerSlide {
  from { transform: translateX(-100%); }
  to   { transform: translateX(100%); }
}


/* ═══════════════════════════════════════
   CTA BANNER
═══════════════════════════════════════ */
.cta-section { opacity: 0; transform: translateY(24px) scale(0.98); }
.cta-section.cta-entered {
  animation: ctaReveal 0.7s cubic-bezier(0.34,1.1,0.64,1) both;
}
@keyframes ctaReveal {
  from { opacity: 0; transform: translateY(24px) scale(0.98); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.cta-glare {
  position: absolute; inset: 0;
  background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,0.07) 50%, transparent 65%);
  animation: glareSweep 5s ease-in-out infinite 1.5s;
  pointer-events: none;
}
@keyframes glareSweep {
  0%, 100% { transform: translateX(-100%); }
  50%       { transform: translateX(100%); }
}


/* ═══════════════════════════════════════
   SHARED KEYFRAMES
═══════════════════════════════════════ */
@keyframes slideUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes progressFill {
  from { width: 0% }
  to   { width: 100% }
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>