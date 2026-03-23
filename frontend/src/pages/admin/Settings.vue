<template>
  <div class="min-h-full bg-[#f5f6fa] p-4 lg:p-8" style="font-family: 'Inter', sans-serif;">

    <component :is="'style'">
      @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </component>

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 px-4 sm:px-6 py-4 mb-6">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="w-1.5 h-10 rounded-full flex-shrink-0" style="background:#3d4f7c"></div>
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15; color:#3d4f7c">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl sm:text-3xl font-semibold text-slate-800 tracking-tight">Account Settings</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">Manage your profile and security</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

      <!-- ── Sidebar ── -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden sticky top-6">

          <!-- Profile section -->
          <div class="px-5 py-8 border-b border-slate-100 flex flex-col items-center text-center gap-4">

            <!-- Circular avatar -->
            <div class="relative group">
              <div class="w-28 h-28 rounded-full overflow-hidden shadow-sm"
                   style="outline: 6px solid #f5f5f5;">
                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white"
                  :style="{ background: 'linear-gradient(135deg, #3d4f7c 0%, #252b3b 100%)' }">
                  {{ getInitials(form.first_name + ' ' + form.last_name) }}
                </div>
              </div>
              <!-- Online status dot -->
              <div class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white"
                :class="form.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></div>
            </div>

            <!-- Name / email / role -->
            <div>
              <p class="text-base font-bold text-slate-800 leading-tight">{{ form.first_name }} {{ form.last_name }}</p>
              <p class="text-xs text-slate-400 mt-1">{{ form.email }}</p>
              <span class="inline-flex items-center gap-1.5 mt-2.5 text-xs font-semibold px-3 py-1 rounded-full border"
                style="color:#3d4f7c; background:#3d4f7c10; border-color:#3d4f7c25">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                {{ roleLabel }}
              </span>
            </div>
          </div>

          <!-- Nav -->
          <nav class="p-2">
            <button v-for="tab in tabs" :key="tab.id"
              @click="activeTab = tab.id"
              class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all cursor-pointer mb-0.5"
              :class="activeTab === tab.id ? 'text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800'"
              :style="activeTab === tab.id ? 'background:#3d4f7c' : ''">
              <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                :class="activeTab === tab.id ? 'bg-white/20' : 'bg-slate-100'"
                v-html="tab.icon"></div>
              {{ tab.label }}
            </button>
          </nav>
        </div>
      </div>

      <!-- ── Main Content ── -->
      <div class="lg:col-span-3 space-y-5">

        <!-- Loading State -->
        <div v-if="loading" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
          <div class="flex flex-col items-center justify-center gap-4">
            <div class="relative w-11 h-11">
              <div class="absolute inset-0 border-[3px] border-slate-100 rounded-full"></div>
              <div class="absolute inset-0 border-[3px] border-[#3d4f7c] border-t-transparent rounded-full animate-spin"></div>
            </div>
            <p class="text-sm text-slate-400 font-medium">Loading account information...</p>
          </div>
        </div>

        <template v-else>
          <!-- ═══ Profile Tab ═══ -->
          <template v-if="activeTab === 'profile'">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
              <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:#3d4f7c10">
                  <svg class="w-3 h-3" style="color:#3d4f7c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Personal Information</p>
              </div>
              <div class="px-5 sm:px-6 py-5 space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">First Name <span class="text-red-400">*</span></label>
                    <input v-model="form.first_name" type="text" placeholder="Juan"
                      class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="errors.first_name ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                    <p v-if="errors.first_name" class="text-[10px] text-red-400 mt-1">{{ errors.first_name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Last Name <span class="text-red-400">*</span></label>
                    <input v-model="form.last_name" type="text" placeholder="Dela Cruz"
                      class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="errors.last_name ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                    <p v-if="errors.last_name" class="text-[10px] text-red-400 mt-1">{{ errors.last_name }}</p>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Address <span class="text-red-400">*</span></label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input v-model="form.email" type="email" placeholder="juan@example.com"
                      class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="errors.email ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                  </div>
                  <p v-if="errors.email" class="text-[10px] text-red-400 mt-1">{{ errors.email }}</p>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1.5">Phone Number</label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <input v-model="form.phone" type="tel" placeholder="+63 912 345 6789"
                      class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c] transition-all bg-slate-50 focus:bg-white" />
                  </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                  <p v-if="profileSaved" class="text-xs text-emerald-600 font-semibold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Profile updated successfully
                  </p>
                  <span v-else class="text-[11px] text-slate-400">Fields marked * are required</span>
                  <button @click="saveProfile" :disabled="savingProfile"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all active:scale-[0.98] cursor-pointer disabled:opacity-60"
                    style="background:#3d4f7c"
                    @mouseenter="e => !savingProfile && (e.currentTarget.style.backgroundColor='#252b3b')"
                    @mouseleave="e => e.currentTarget.style.backgroundColor='#3d4f7c'">
                    <svg v-if="savingProfile" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ savingProfile ? 'Saving…' : 'Save Changes' }}
                  </button>
                </div>
              </div>
            </div>
          </template>

          <!-- ═══ Security Tab ═══ -->
          <template v-if="activeTab === 'security'">

            <!-- Change Password -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
              <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <div class="w-5 h-5 rounded-md flex items-center justify-center bg-amber-100">
                  <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Change Password</p>
              </div>
              <div class="px-5 sm:px-6 py-5 space-y-4">

                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1.5">Current Password <span class="text-red-400">*</span></label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input v-model="passwords.current" :type="showPasswords.current ? 'text' : 'password'" placeholder="••••••••"
                      class="w-full border rounded-xl pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="passErrors.current ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                    <button @click="showPasswords.current = !showPasswords.current" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path v-if="showPasswords.current" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>
                  </div>
                  <p v-if="passErrors.current" class="text-[10px] text-red-400 mt-1">{{ passErrors.current }}</p>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1.5">New Password <span class="text-red-400">*</span></label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <input v-model="passwords.new" :type="showPasswords.new ? 'text' : 'password'" placeholder="Min. 8 characters"
                      class="w-full border rounded-xl pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="passErrors.new ? 'border-red-300 focus:ring-red-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                    <button @click="showPasswords.new = !showPasswords.new" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path v-if="showPasswords.new" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>
                  </div>
                  <div v-if="passwords.new" class="mt-2 space-y-1.5">
                    <div class="flex gap-1">
                      <div v-for="i in 4" :key="i" class="h-1.5 flex-1 rounded-full transition-all duration-300"
                        :class="i <= passwordStrength.score ? passwordStrength.color : 'bg-slate-100'"></div>
                    </div>
                    <p class="text-[10px] font-semibold" :class="passwordStrength.textColor">{{ passwordStrength.label }}</p>
                  </div>
                  <p v-if="passErrors.new" class="text-[10px] text-red-400 mt-1">{{ passErrors.new }}</p>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1.5">Confirm New Password <span class="text-red-400">*</span></label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <input v-model="passwords.confirm" :type="showPasswords.confirm ? 'text' : 'password'" placeholder="••••••••"
                      class="w-full border rounded-xl pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all bg-slate-50 focus:bg-white"
                      :class="passErrors.confirm ? 'border-red-300 focus:ring-red-200' : passwords.confirm && passwords.new === passwords.confirm ? 'border-emerald-300 focus:ring-emerald-200' : 'border-slate-200 focus:ring-[#3d4f7c]/20 focus:border-[#3d4f7c]'" />
                    <button @click="showPasswords.confirm = !showPasswords.confirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path v-if="showPasswords.confirm" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </button>
                  </div>
                  <p v-if="passErrors.confirm" class="text-[10px] text-red-400 mt-1">{{ passErrors.confirm }}</p>
                  <p v-else-if="passwords.confirm && passwords.new === passwords.confirm" class="text-[10px] text-emerald-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Passwords match
                  </p>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                  <p v-if="passwordSaved" class="text-xs text-emerald-600 font-semibold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Password changed successfully
                  </p>
                  <span v-else class="text-[11px] text-slate-400">Use at least 8 characters</span>
                  <button @click="changePassword" :disabled="savingPassword"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl transition-all active:scale-[0.98] cursor-pointer disabled:opacity-60 bg-amber-500 hover:bg-amber-600">
                    <svg v-if="savingPassword" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    {{ savingPassword ? 'Updating…' : 'Update Password' }}
                  </button>
                </div>
              </div>
            </div>

          </template>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import Swal from 'sweetalert2'
import AccountService from '@/services/Admin/AccountService'
import { getUsers } from '@/utils/dataStore'

const activeTab    = ref('profile')
const loading      = ref(true)

const form = reactive({
  id: null,
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  address: '',
  role: '',
  is_active: true,
})

const errors         = reactive({})
const passwords      = reactive({ current: '', new: '', confirm: '' })
const passErrors     = reactive({})
const showPasswords  = reactive({ current: false, new: false, confirm: false })
const savingProfile  = ref(false)
const savingPassword = ref(false)
const profileSaved   = ref(false)
const passwordSaved  = ref(false)


function getInitials(name = '') {
  const p = name.trim().split(' ').filter(Boolean)
  if (!p.length) return '?'
  return p.length === 1 ? p[0][0].toUpperCase() : (p[0][0] + p[p.length - 1][0]).toUpperCase()
}

const roleLabel = computed(() => {
  const roles = {
    admin: 'Punong Barangay',
    lupon: 'Lupon Member',
    resident: 'Resident',
    secretary: 'Secretary',
    kagawad: 'Barangay Kagawad'
  }
  return roles[form.role] || form.role || 'User'
})

const tabs = [
  { id: 'profile',  label: 'Profile',  icon: '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' },
  { id: 'security', label: 'Security', icon: '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' },
]

// ── Password strength ──
const passwordStrength = computed(() => {
  const p = passwords.new
  if (!p) return { score: 0, label: '', color: 'bg-slate-100', textColor: 'text-slate-400' }
  let s = 0
  if (p.length >= 8)          s++
  if (/[A-Z]/.test(p))        s++
  if (/[0-9]/.test(p))        s++
  if (/[^a-zA-Z0-9]/.test(p)) s++
  const strengths = [
    { score: 1, label: 'Weak',   color: 'bg-red-400',     textColor: 'text-red-500' },
    { score: 2, label: 'Fair',   color: 'bg-amber-400',   textColor: 'text-amber-500' },
    { score: 3, label: 'Good',   color: 'bg-blue-400',    textColor: 'text-blue-500' },
    { score: 4, label: 'Strong', color: 'bg-emerald-500', textColor: 'text-emerald-600' },
  ]
  return strengths[s - 1] || { score: 1, label: 'Weak', color: 'bg-red-400', textColor: 'text-red-500' }
})

// ── API Methods ──
async function fetchProfile() {
  loading.value = true
  try {
    const data = await AccountService.getProfile()
    
    form.id = data.id
    form.first_name = data.first_name || ''
    form.last_name = data.last_name || ''
    form.email = data.email || ''
    form.phone = data.phone || ''
    form.address = data.address || ''
    form.role = data.role || ''
    form.is_active = data.is_active ?? true
  } catch (error) {
    console.error('Failed to fetch profile:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load profile data. Please refresh the page.',
      confirmButtonColor: '#3d4f7c'
    })
  } finally {
    loading.value = false
  }
}


function loadProfile() {
  const userId   = Number(localStorage.getItem('user_id'))
  const users    = getUsers()
  const usersArr = Array.isArray(users) ? users : (users?.data ?? [])
  const me       = usersArr.find(u => u.id === userId)

  if (me) {
    const info = me.information || {}   // ← names are nested here

    form.id         = me.id
    form.first_name = info.first_name || info.firstname || ''
    form.last_name  = info.last_name  || info.lastname  || ''
    form.email      = me.email        || ''
    form.phone      = info.phone      || info.contact_number || ''
    form.address    = info.address    || ''
    form.role       = me.role?.role_name?.toLowerCase() || me.role || ''
    form.is_active  = me.status?.is_active ?? me.is_active ?? true
    loading.value   = false
  } else {
    fetchProfile()
  }
}

async function saveProfile() {
  // Clear previous errors
  Object.keys(errors).forEach(k => delete errors[k])
  
  // Validation
  if (!form.first_name?.trim()) errors.first_name = 'First name is required'
  if (!form.last_name?.trim())  errors.last_name  = 'Last name is required'
  if (!form.email?.trim())      errors.email      = 'Email is required'
  else if (!/^\S+@\S+\.\S+$/.test(form.email)) errors.email = 'Invalid email format'
  
  if (Object.keys(errors).length) return
  
  savingProfile.value = true
  try {
    await AccountService.updateProfile({
      first_name: form.first_name,
      last_name: form.last_name,
      email: form.email,
      phone: form.phone,
    })
    
    profileSaved.value = true
    setTimeout(() => profileSaved.value = false, 3000)
    
    Swal.fire({
      icon: 'success',
      title: 'Profile Updated',
      text: 'Your profile has been updated successfully.',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    })
  } catch (error) {
    console.error('Update failed:', error)
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors)
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.response?.data?.message || 'Failed to update profile. Please try again.',
        confirmButtonColor: '#3d4f7c'
      })
    }
  } finally {
    savingProfile.value = false
  }
}

async function changePassword() {
  // Clear previous errors
  Object.keys(passErrors).forEach(k => delete passErrors[k])
  
  // Validation
  if (!passwords.current) {
    passErrors.current = 'Current password is required'
  }
  if (!passwords.new) {
    passErrors.new = 'New password is required'
  } else if (passwords.new.length < 8) {
    passErrors.new = 'Password must be at least 8 characters'
  } else if (passwords.new === passwords.current) {
    passErrors.new = 'New password must be different from current password'
  }
  if (passwords.new !== passwords.confirm) {
    passErrors.confirm = 'Passwords do not match'
  }
  
  if (Object.keys(passErrors).length) return
  
  savingPassword.value = true
  try {
    await AccountService.changePassword({
      current_password: passwords.current,
      new_password: passwords.new,
      confirm_password: passwords.confirm
    })
    
    passwordSaved.value = true
    setTimeout(() => passwordSaved.value = false, 3000)
    
    // Clear password fields
    passwords.current = ''
    passwords.new = ''
    passwords.confirm = ''
    
    Swal.fire({
      icon: 'success',
      title: 'Password Changed',
      text: 'Your password has been updated successfully.',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    })
  } catch (error) {
    console.error('Password change failed:', error)
    if (error.response?.data?.errors) {
      Object.assign(passErrors, error.response.data.errors)
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.response?.data?.message || 'Failed to change password. Please check your current password.',
        confirmButtonColor: '#3d4f7c'
      })
    }
  } finally {
    savingPassword.value = false
  }
}

async function revokeSession(sessionId) {
  try {
    await AccountService.revokeSession(sessionId)
    sessions.value = sessions.value.filter(s => s.id !== sessionId)
    
    Swal.fire({
      icon: 'success',
      title: 'Session Revoked',
      text: 'The session has been terminated successfully.',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    })
  } catch (error) {
    console.error('Revoke session failed:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to revoke session. Please try again.',
      confirmButtonColor: '#3d4f7c'
    })
  }
}

onMounted(() => {
  loadProfile()
})
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
</style>