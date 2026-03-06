<script setup>
import { computed } from 'vue'
import { useRoute, RouterView } from 'vue-router'
import Layout from '@/layout/Layout.vue'

const route = useRoute()

const isAuthPage = computed(() => {
  return route.path === '/login' || route.path === '/register'
})
</script>

<template>
  <Layout v-if="!isAuthPage">
    <RouterView />
  </Layout>
  <RouterView v-else />
</template>