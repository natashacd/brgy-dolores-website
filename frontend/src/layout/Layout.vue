<script setup>
import Header from "@/components/home/Header.vue";
import Footer from "@/components/home/Footer.vue";
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />
    <main class="flex-1">
      <router-view />
    </main>
    <Footer />
  </div>
</template>