<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from "@/components/home/Header.vue";
import Footer from "@/components/home/Footer.vue";

const isHeaderVisible = ref(true)
let lastScrollY = 0
let scrollTimeout = null

const handleScroll = () => {
  if (scrollTimeout) {
    cancelAnimationFrame(scrollTimeout)
  }

  scrollTimeout = requestAnimationFrame(() => {
    const currentScrollY = window.scrollY
    
    if (currentScrollY > lastScrollY && currentScrollY > 50) {
      isHeaderVisible.value = false
    } else {
      isHeaderVisible.value = true
    }
    
    lastScrollY = currentScrollY
  })
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  if (scrollTimeout) {
    cancelAnimationFrame(scrollTimeout)
  }
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <header 
      class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 will-change-transform"
      :class="[isHeaderVisible ? 'translate-y-0' : '-translate-y-full']"
    >
      <Header />
    </header>
    
    <main class="flex-1 pt-[76px] md:pt-[96px]">
      <router-view />
    </main>
    
    <Footer />
  </div>
</template>