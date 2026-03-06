<template>
  <!-- Header with logo and title - Larger -->
  <div class="px-5 py-7 border-b border-gray-700/50">
    <div class="flex items-center gap-4">
      <!-- Logo - Larger -->
      <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
        <img
          src="@/assets/images/dolores_logo.png"
          alt="Barangay Dolores"
          class="w-10 h-10 rounded-md object-cover"
          @error="$event.target.src = 'https://placehold.co/40x40/1e3a8a/ffffff?text=BD'"
        />
      </div>
      
      <div>
        <h2 class="font-semibold text-lg text-white">Barangay Dolores</h2>
        <p class="text-sm text-gray-400">Admin Dashboard</p>
      </div>
    </div>
  </div>
</template>