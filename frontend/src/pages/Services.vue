<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Enhanced Hero Section -->
    <section class="relative overflow-hidden" style="background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 55%, #b91c1c 100%)">
      <!-- Animated Wave Pattern -->
      <div class="absolute inset-0 opacity-20">
        <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" 
            style="animation: waveAnimation 15s ease-in-out infinite alternate;"></path>
          <path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,202.7C384,192,480,160,576,149.3C672,139,768,149,864,165.3C960,181,1056,203,1152,192C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" 
            style="animation: waveAnimation 18s ease-in-out infinite alternate-reverse;"></path>
        </svg>
      </div>

      <!-- Animated Floating Elements -->
      <div class="absolute inset-0 pointer-events-none">   
        <!-- Glowing Orbs -->
        <div class="absolute top-10 right-[20%] w-64 h-64 rounded-full bg-yellow-400/5 blur-3xl floating-element" style="animation: glowFloat 12s ease-in-out infinite"></div>
        <div class="absolute bottom-10 left-[10%] w-80 h-80 rounded-full bg-white/5 blur-3xl floating-element" style="animation: glowFloat 15s ease-in-out infinite 2s"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
        <div class="text-center">
          <!-- Animated Badge -->
          <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 transform hover:scale-105 transition-transform duration-300">
            <span class="text-yellow-400 animate-pulse">⚙️</span>
            <span class="text-sm font-bold tracking-widest uppercase text-white/90">Barangay Services</span>
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
          </div>
          
          <!-- Main Title with Gradient Animation -->
          <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
            Services 
            <span class="relative inline-block">
              <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300" 
                    style="animation: gradientShift 3s ease infinite; background-size: 200% 200%;">
                We Offer
              </span>
              <span class="absolute -bottom-2 left-0 right-0 h-3 bg-yellow-400/30 blur-xl"></span>
            </span>
          </h1>
          
          <!-- Description with Fade In -->
          <p class="text-xl text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed animate-fadeIn">
            Complete your requests online or visit the Barangay Hall for in-person processing.
          </p>
          
          <!-- Service Stats with Counters -->
          <div class="flex flex-wrap justify-center gap-8 mt-10">
            <div class="text-center group hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">5+</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Services Available</div>
            </div>
            <div class="w-px h-12 bg-white/20"></div>
            <div class="text-center group hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">24/7</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Online Access</div>
            </div>
            <div class="w-px h-12 bg-white/20"></div>
            <div class="text-center group hover:scale-110 transition-transform duration-300">
              <div class="text-3xl font-black text-yellow-400">100%</div>
              <div class="text-xs text-white/60 uppercase tracking-wider">Transparent</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Enhanced Curved Bottom with Animation -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-16 text-gray-50">
          <path d="M0 120L1440 0V120H0Z" fill="currentColor" style="animation: curveSlide 1s ease-out"></path>
        </svg>
      </div>

      <!-- Decorative Bottom Border -->
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent"></div>
    </section>

    <!-- Services Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Brgy ID Request -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100">
          <div class="h-2" style="background: linear-gradient(90deg, #0f2d6b, #1a3f8f)"></div>
          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                🆔
              </div>
              <span class="text-xs font-bold uppercase px-3 py-1 bg-yellow-400/10 text-yellow-600 rounded-full group-hover:bg-yellow-400/20 transition-colors duration-300">Request</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-2 group-hover:text-blue-900 transition-colors duration-300">BRGY ID REQUEST</h2>
            <p class="text-gray-500 leading-relaxed">
              Apply for your Barangay ID or community identification card.
            </p>
          </div>
        </div>

        <!-- Business Clearance -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100">
          <div class="h-2" style="background: linear-gradient(90deg, #b91c1c, #dc2626)"></div>
          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                🏪
              </div>
              <span class="text-xs font-bold uppercase px-3 py-1 bg-red-400/10 text-red-600 rounded-full group-hover:bg-red-400/20 transition-colors duration-300">Permit</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-2 group-hover:text-red-600 transition-colors duration-300">BRGY Business Clearance/Permit</h2>
            <p class="text-gray-500 leading-relaxed">
              Apply or renew business clearance and permits in your barangay.
            </p>
          </div>
        </div>

        <!-- Certificate of Indigency -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100">
          <div class="h-2" style="background: linear-gradient(90deg, #0f2d6b, #b91c1c)"></div>
          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-500 flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                📜
              </div>
              <span class="text-xs font-bold uppercase px-3 py-1 bg-purple-400/10 text-purple-600 rounded-full group-hover:bg-purple-400/20 transition-colors duration-300">Certificate</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-2 group-hover:text-purple-600 transition-colors duration-300">BRGY Certificate of Indigency</h2>
            <p class="text-gray-500 leading-relaxed">
              Request a Barangay Certificate of Indigency for official purposes.
            </p>
          </div>
        </div>

        <!-- Certificate of Residency -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100">
          <div class="h-2" style="background: linear-gradient(90deg, #1a3f8f, #2563eb)"></div>
          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-400 flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                🏠
              </div>
              <span class="text-xs font-bold uppercase px-3 py-1 bg-blue-400/10 text-blue-600 rounded-full group-hover:bg-blue-400/20 transition-colors duration-300">Certificate</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-300">BRGY Certificate of Residency</h2>
            <p class="text-gray-500 leading-relaxed">
              Obtain your Certificate of Residency for official requirements.
            </p>
          </div>
        </div>

        <!-- Certifications for Specific Purposes -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden border border-gray-100 md:col-span-2 lg:col-span-1">
          <div class="h-2" style="background: linear-gradient(90deg, #f5c518, #eab308)"></div>
          <div class="p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
              <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-500 to-yellow-400 flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                📋
              </div>
              <span class="text-xs font-bold uppercase px-3 py-1 bg-yellow-400/10 text-yellow-600 rounded-full group-hover:bg-yellow-400/20 transition-colors duration-300">Multiple</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-2 group-hover:text-yellow-600 transition-colors duration-300">Certifications for Specific Purposes</h2>
            <p class="text-gray-500 leading-relaxed">
              Request Barangay certifications for school, employment, or other purposes.
            </p>
          </div>
        </div>

      </div>
    </section>

    <!-- How to Apply Section -->
    <section class="bg-white py-16 border-t border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">How to <span style="background: linear-gradient(135deg,#0f2d6b,#b91c1c); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text">Apply</span></h2>
          <p class="text-gray-500 max-w-2xl mx-auto">Simple steps to access barangay services</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center group hover:-translate-y-2 transition-transform duration-300">
            <div class="w-16 h-16 rounded-full bg-blue-900/5 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 group-hover:bg-blue-900/10 transition-all duration-300">1️⃣</div>
            <h3 class="font-bold text-gray-900 mb-2 group-hover:text-blue-900 transition-colors duration-300">Visit</h3>
            <p class="text-sm text-gray-400">Visit the barangay hall during office hours.</p>
          </div>
          <div class="text-center group hover:-translate-y-2 transition-transform duration-300">
            <div class="w-16 h-16 rounded-full bg-red-600/5 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 group-hover:bg-red-600/10 transition-all duration-300">2️⃣</div>
            <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors duration-300">Submit Requirements</h3>
            <p class="text-sm text-gray-400">Provide the necessary documents and information for processing.</p>
          </div>
          <div class="text-center group hover:-translate-y-2 transition-transform duration-300">
            <div class="w-16 h-16 rounded-full bg-yellow-400/10 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 group-hover:bg-yellow-400/20 transition-all duration-300">3️⃣</div>
            <h3 class="font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors duration-300">Claim Your Document</h3>
            <p class="text-sm text-gray-400">Receive your processed document at the barangay hall.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// No additional logic needed for static page
</script>

<style scoped>
/* Smooth hover effects */
.group:hover .group-hover\:gap-2 {
  gap: 0.5rem;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes glowFloat {
  0%, 100% { transform: translateY(0px) scale(1); opacity: 0.05; }
  50% { transform: translateY(-30px) scale(1.1); opacity: 0.08; }
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes waveAnimation {
  0% { transform: translateX(0) scaleY(1); }
  100% { transform: translateX(-10%) scaleY(0.8); }
}

@keyframes curveSlide {
  from { transform: translateY(100%); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.floating-element {
  animation: float 6s ease-in-out infinite;
}

.animate-fadeIn {
  animation: fadeIn 1s ease-out forwards;
}

/* Add more dynamic hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}
</style>