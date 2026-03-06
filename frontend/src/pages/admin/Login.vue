<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6 font-sans">
 
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
      
      <div class="h-2 bg-blue-900"></div>
      
      <div class="p-10">
        
        <div class="flex flex-col items-center text-center mb-10">
          <div class="relative mb-5">
            <div class="absolute -inset-1.5 rounded-full opacity-60"
                 style="background: radial-gradient(circle, rgba(245,197,24,0.5) 0%, transparent 70%); animation: glowPulse 2.5s ease-in-out infinite">
            </div>
            <img
              src="@/assets/images/dolores_logo.png"
              alt="Barangay Dolores"
              class="relative z-10 w-24 h-24 rounded-full border-3 border-white shadow-lg object-cover"
              @error="$event.target.src = 'https://placehold.co/96x96/1e3a8a/ffffff?text=BD'"
            />
          </div>
          
          <h2 class="text-3xl font-bold text-gray-900">Barangay Dolores</h2>
          <p class="text-base text-gray-500 mt-1">Capas, Tarlac</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">

          <!-- Username -->
          <div>
            <label class="block text-sm font-semibold uppercase tracking-wider text-gray-500 mb-2">
              Username
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </span>
              <input
                type="text"
                v-model="username"
                placeholder="Enter your username"
                required
                class="w-full pl-10 pr-4 py-3 text-base border border-gray-200 rounded-lg focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 transition"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-sm font-semibold uppercase tracking-wider text-gray-500">
                Password
              </label>
            </div>
            <div class="relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </span>
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                placeholder="Enter your password"
                required
                class="w-full pl-10 pr-12 py-3 text-base border border-gray-200 rounded-lg focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 transition"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.977 9.977 0 015.258-5.588M8 5.363A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.985 9.985 0 01-1.678 3.023M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full bg-blue-900 hover:bg-blue-800 text-white text-base font-semibold py-3.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group mt-8"
          >
            Sign In
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)

const handleLogin = () => {
  console.log('Login:', { 
    username: username.value, 
    password: password.value,
    rememberMe: rememberMe.value 
  })
  // Add your authentication logic here
}
</script>

<style scoped>
@keyframes glowPulse {
  0%, 100% { opacity: 0.5; transform: scale(1); }
  50% { opacity: 0.8; transform: scale(1.2); }
}
</style>