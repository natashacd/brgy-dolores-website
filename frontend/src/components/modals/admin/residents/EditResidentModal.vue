<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-2 sm:p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl overflow-hidden max-h-[98vh] sm:max-h-[90vh] flex flex-col">

      <!-- Header with stepper -->
      <div class="px-4 sm:px-6 pt-4 sm:pt-5 pb-0 flex-shrink-0 bg-white">
        <div class="flex items-center justify-between mb-3 sm:mb-5">
          <div class="min-w-0 flex-1 pr-2">
            <h2 class="text-sm sm:text-base font-bold text-slate-800 truncate">Edit Resident</h2>
            <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5 truncate">Step {{ currentStep }} of {{ steps.length }} — {{ steps[currentStep - 1].label }}</p>
          </div>
          <button
            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer border-0 bg-transparent flex-shrink-0"
            @click="$emit('close')"
          >
            <svg width="14" height="14" sm:width="16" sm:height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Edit indicator - Responsive -->
        <div class="flex items-center gap-2 mb-3 sm:mb-4">
          <div class="w-1 h-6 sm:w-1.5 sm:h-8 rounded-full bg-[#3d4f7c]"></div>
          <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm min-w-0">
            <span class="font-medium text-slate-600 flex-shrink-0">Editing:</span>
            <span class="font-bold text-[#3d4f7c] truncate">{{ fullName }}</span>
            <span class="text-[9px] sm:text-xs text-slate-400 flex-shrink-0">(ID: {{ props.resident.id }})</span>
          </div>
        </div>

        <!-- Stepper - Scrollable on mobile -->
        <div class="flex items-center gap-0 mb-0 overflow-x-auto pb-2 scrollbar-thin" style="-webkit-overflow-scrolling: touch;">
          <template v-for="(step, i) in steps" :key="step.id">
            <!-- Step -->
            <div class="flex flex-col items-center min-w-[50px] sm:min-w-[80px] flex-1">
              <div class="flex items-center w-full">
                <!-- Left line -->
                <div
                  class="flex-1 h-0.5 transition-all duration-300"
                  :class="i === 0 ? 'bg-transparent' : (i < currentStep ? 'bg-[#3d4f7c]' : 'bg-slate-200')"
                ></div>
                <!-- Circle -->
                <div
                  class="w-6 h-6 sm:w-9 sm:h-9 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 border-2 text-[8px] sm:text-xs font-bold"
                  :class="
                    i + 1 < currentStep
                      ? 'bg-[#3d4f7c] border-[#3d4f7c] text-white'
                      : i + 1 === currentStep
                        ? 'bg-white border-[#3d4f7c] text-[#3d4f7c] shadow-md shadow-[#3d4f7c]/20'
                        : 'bg-white border-slate-200 text-slate-300'
                  "
                >
                  <!-- Checkmark for completed -->
                  <svg v-if="i + 1 < currentStep" width="10" height="10" sm:width="16" sm:height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  <!-- Icon for current/future -->
                  <component :is="'svg'" v-else width="10" height="10" sm:width="15" sm:height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="step.icon"/>
                  </component>
                </div>
                <!-- Right line -->
                <div
                  class="flex-1 h-0.5 transition-all duration-300"
                  :class="i === steps.length - 1 ? 'bg-transparent' : (i + 1 < currentStep ? 'bg-[#3d4f7c]' : 'bg-slate-200')"
                ></div>
              </div>
              <!-- Label -->
              <span
                class="text-[7px] sm:text-[10px] font-semibold mt-1 transition-all duration-200 text-center leading-tight px-0.5"
                :class="i + 1 === currentStep ? 'text-[#3d4f7c]' : i + 1 < currentStep ? 'text-slate-500' : 'text-slate-300'"
              >{{ step.label }}</span>
            </div>
          </template>
        </div>

        <!-- Step underline -->
        <div class="mt-2 sm:mt-4 h-px bg-slate-100"></div>
      </div>

      <!-- Body -->
      <div class="px-3 sm:px-6 py-3 sm:py-5 overflow-y-auto flex-1 scrollbar-thin">

        <!-- Step 1: Profile Image -->
        <div v-if="currentStep === 1" class="space-y-3 sm:space-y-4">
          <div class="flex items-center gap-2 mb-1">
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
              <svg width="11" height="11" sm:width="13" sm:height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-700">Profile Image</h3>
          </div>

          <div class="flex flex-col gap-2 sm:gap-3">
            <!-- Image preview area -->
            <div class="w-full border-2 border-dashed border-[#3d4f7c]/30 rounded-xl p-4 sm:p-6 bg-[#3d4f7c]/5 flex flex-col items-center justify-center text-center transition-all hover:border-[#3d4f7c]/50 hover:bg-[#3d4f7c]/10 cursor-pointer" @click="triggerFileInput">
              <template v-if="imagePreview || currentImage">
                <div class="relative w-full max-w-[150px] sm:max-w-xs mx-auto">
                  <img :src="imagePreview || currentImage" class="w-full h-auto rounded-lg shadow-md" alt="Preview" />
                  <button class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all shadow-md" @click.stop="removeImage">
                    <svg width="10" height="10" sm:width="14" sm:height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </template>
              <template v-else>
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-[#3d4f7c]/10 flex items-center justify-center mb-2 sm:mb-3">
                  <svg width="18" height="18" sm:width="24" sm:height="24" fill="none" stroke="#3d4f7c" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-700 mb-1">Click to upload or drag and drop</p>
                <p class="text-[10px] sm:text-xs text-slate-400 mb-2 sm:mb-3">JPG, PNG or GIF (Max 4MB)</p>
                <div class="inline-flex items-center gap-1 sm:gap-2 px-2 py-1 sm:px-4 sm:py-2 bg-[#3d4f7c] text-white text-[10px] sm:text-sm font-semibold rounded-xl hover:bg-[#252b3b] transition-all cursor-pointer">
                  <svg width="10" height="10" sm:width="14" sm:height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                  </svg>
                  Upload Image
                </div>
              </template>
            </div>
            
            <!-- Hidden file input -->
            <input ref="fileInput" @change="handleImageUpload" type="file" accept="image/*" class="hidden" />
            
            <span v-if="errors.image" class="text-[9px] sm:text-[10px] text-red-400">{{ errors.image }}</span>
          </div>
        </div>

        <!-- Step 2: Personal Information - Responsive Grid -->
        <div v-if="currentStep === 2" class="space-y-3 sm:space-y-4">
          <div class="flex items-center gap-2 mb-1">
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
              <svg width="11" height="11" sm:width="13" sm:height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-700">Personal Information</h3>
          </div>

          <!-- Name row - responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">First Name <span class="text-red-400">*</span></label>
              <input v-model="form.first_name" type="text" placeholder="First name"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.first_name ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.first_name" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.first_name }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Middle Name</label>
              <input v-model="form.middle_name" type="text" placeholder="Middle name"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Last Name <span class="text-red-400">*</span></label>
              <input v-model="form.last_name" type="text" placeholder="Last name"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.last_name ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.last_name" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.last_name }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Suffix</label>
              <input v-model="form.suffix" type="text" placeholder="Jr., Sr., III"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
          </div>

          <!-- Contact row - responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Email <span class="text-red-400">*</span></label>
              <input v-model="form.email" type="email" placeholder="Email address"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.email ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.email" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.email }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Contact Number <span class="text-red-400">*</span></label>
              <input v-model="form.contact_number" type="text" placeholder="0912 345 6789"
                @input="formatPhoneNumber('contact_number', $event)" maxlength="13"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.contact_number ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.contact_number" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.contact_number }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Sex <span class="text-red-400">*</span></label>
              <select v-model="form.sex"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer"
                :class="errors.sex ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'">
                <option value="" disabled>Select sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <span v-if="errors.sex" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.sex }}</span>
            </div>
          </div>

          <!-- Birth / Civil row - responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Birth Place <span class="text-red-400">*</span></label>
              <input v-model="form.birth_place" type="text" placeholder="Birth place"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.birth_place ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.birth_place" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.birth_place }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Birth Date <span class="text-red-400">*</span></label>
              <input v-model="form.birth_date" type="date"
                :max="maxDate"
                @input="validateBirthDate"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
                :class="errors.birth_date ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.birth_date" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.birth_date }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Civil Status <span class="text-red-400">*</span></label>
              <select v-model="form.civil_status"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer"
                :class="errors.civil_status ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'">
                <option value="" disabled>Select status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Annulled">Annulled</option>
                <option value="Divorced">Divorced</option>
                <option value="Legally Separated">Legally Separated</option>
              </select>
              <span v-if="errors.civil_status" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.civil_status }}</span>
            </div>
          </div>

          <!-- Extra row - responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Religion</label>
              <input v-model="form.religion" type="text" placeholder="Religion"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">PWD</label>
              <input v-model="form.pwd" type="text" placeholder="PWD ID"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Nationality <span class="text-red-400">*</span></label>
              <input v-model="form.nationality" type="text" placeholder="Nationality"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.nationality ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.nationality" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.nationality }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Occupation</label>
              <input v-model="form.occupation" type="text" placeholder="Occupation"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Educational Attainment</label>
              <input v-model="form.educational_attainment" type="text" placeholder="Highest level"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
          </div>
        </div>

        <!-- Step 3: Emergency Contact - Responsive -->
        <div v-if="currentStep === 3" class="space-y-3 sm:space-y-4">
          <div class="flex items-center gap-2 mb-1">
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#ef444415">
              <svg width="11" height="11" sm:width="13" sm:height="13" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-700">Emergency Contact</h3>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Contact Person <span class="text-red-400">*</span></label>
              <input v-model="form.emergency_contact_person" type="text" placeholder="Full name"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_person ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.emergency_contact_person" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.emergency_contact_person }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Contact Number <span class="text-red-400">*</span></label>
              <input v-model="form.emergency_contact_number" type="text" placeholder="0912 345 6789"
                @input="formatPhoneNumber('emergency_contact_number', $event)" maxlength="13"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_number ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.emergency_contact_number" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.emergency_contact_number }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Relationship <span class="text-red-400">*</span></label>
              <input v-model="form.emergency_contact_relationship" type="text" placeholder="e.g., Spouse, Parent"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_relationship ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.emergency_contact_relationship" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.emergency_contact_relationship }}</span>
            </div>
          </div>
        </div>

        <!-- Step 4: Address - Responsive -->
        <div v-if="currentStep === 4" class="space-y-3 sm:space-y-4">
          <div class="flex items-center gap-2 mb-1">
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#05966915">
              <svg width="11" height="11" sm:width="13" sm:height="13" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-700">Address</h3>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">House No. <span class="text-red-400">*</span></label>
              <input v-model="form.house_no" type="text" placeholder="House number"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.house_no ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.house_no" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.house_no }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Purok</label>
              <input v-model="form.purok" type="text" placeholder="Purok"
                class="border border-slate-200 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none focus:border-[#3d4f7c]/50 transition-all bg-slate-50 focus:bg-white placeholder-slate-300"/>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Sitio <span class="text-red-400">*</span></label>
              <input v-model="form.sitio" type="text" placeholder="Sitio"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.sitio ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.sitio" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.sitio }}</span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Latitude <span class="text-red-400">*</span></label>
              <input v-model="form.latitude" type="text" placeholder="e.g., 15.4899"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.latitude ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.latitude" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.latitude }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Longitude <span class="text-red-400">*</span></label>
              <input v-model="form.longitude" type="text" placeholder="e.g., 120.5994"
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.longitude ? 'border-red-300' : 'border-slate-200 focus:border-[#3d4f7c]/50'"/>
              <span v-if="errors.longitude" class="text-[8px] sm:text-[10px] text-red-400">{{ errors.longitude }}</span>
            </div>
          </div>
        </div>

        <!-- Step 5: Account Details - Responsive -->
        <div v-if="currentStep === 5" class="space-y-3 sm:space-y-4">
          <div class="flex items-center gap-2 mb-1">
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#7c3aed15">
              <svg width="11" height="11" sm:width="13" sm:height="13" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-700">Account Details</h3>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Role <span class="text-red-400">*</span></label>
              <select 
                v-model="form.role_id"
                disabled
                class="border rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm focus:outline-none transition-all bg-slate-100 cursor-not-allowed text-slate-500 border-slate-200"
              >
                <option :value="residentRoleId">Resident</option>
              </select>
              <span class="text-[8px] sm:text-[10px] text-slate-400">Role is automatically set to Resident</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Password</label>
              <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                <input type="text" value="********" disabled
                  class="w-full sm:flex-1 border border-slate-200 bg-slate-100 text-slate-400 rounded-xl px-2 py-2 sm:px-3 sm:py-2 text-xs sm:text-sm cursor-not-allowed" />
                <button
                  class="w-full sm:w-auto text-white text-[10px] sm:text-xs font-semibold px-3 py-2 sm:px-4 sm:py-2 rounded-xl whitespace-nowrap transition-all active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                  style="background:#d97706"
                  @mouseenter="e => e.currentTarget.style.background='#b45309'"
                  @mouseleave="e => e.currentTarget.style.background='#d97706'"
                  @click="handleResetPassword"
                  :disabled="resetLoading"
                >
                  {{ resetLoading ? "Resetting..." : "Reset" }}
                </button>
              </div>
              <span class="text-[8px] sm:text-[10px] text-slate-400">Resets password to default (adminadmin)</span>
            </div>
          </div>

          <!-- Status radio buttons - Responsive -->
          <div class="flex flex-col gap-1 sm:gap-2">
            <label class="text-[10px] sm:text-xs font-semibold text-slate-600">Status</label>
            <div class="flex flex-col sm:flex-row gap-2">
              <label
                class="flex items-center gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl border cursor-pointer transition-all text-xs sm:text-sm font-medium select-none flex-1"
                :class="form.status === 1 ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-slate-300'"
              >
                <input type="radio" v-model="form.status" :value="1" class="hidden" />
                <span class="w-2 h-2 rounded-full" :class="form.status === 1 ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'"></span>
                Active
              </label>
              <label
                class="flex items-center gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl border cursor-pointer transition-all text-xs sm:text-sm font-medium select-none flex-1"
                :class="form.status === 0 ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-slate-300'"
              >
                <input type="radio" v-model="form.status" :value="0" class="hidden" />
                <span class="w-2 h-2 rounded-full" :class="form.status === 0 ? 'bg-amber-400' : 'bg-slate-300'"></span>
                Inactive
              </label>
            </div>
          </div>

          <!-- Summary card with image preview - Responsive -->
          <div class="mt-2 p-3 sm:p-4 bg-[#3d4f7c]/5 border border-[#3d4f7c]/15 rounded-xl">
            <p class="text-[10px] sm:text-xs font-bold text-[#3d4f7c] mb-2 sm:mb-3 flex items-center gap-1.5">
              <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Review Summary
            </p>
            
            <!-- Image preview in summary -->
            <div v-if="imagePreview || currentImage" class="mb-2 sm:mb-3 flex justify-center">
              <img :src="imagePreview || currentImage" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover shadow-sm border border-slate-200" alt="Profile preview" />
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 sm:gap-x-6 sm:gap-y-1.5 text-[10px] sm:text-xs">
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Full Name</span>
                <span class="font-semibold text-slate-700 text-right ml-2 truncate max-w-[120px] sm:max-w-none">{{ [form.first_name, form.middle_name, form.last_name, form.suffix].filter(Boolean).join(' ') || '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Email</span>
                <span class="font-semibold text-slate-700 truncate max-w-[100px] sm:max-w-[140px] text-right ml-2">{{ form.email || '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Contact</span>
                <span class="font-semibold text-slate-700 text-right ml-2">{{ form.contact_number || '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Sex</span>
                <span class="font-semibold text-slate-700 text-right ml-2">{{ form.sex || '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Emergency Contact</span>
                <span class="font-semibold text-slate-700 text-right ml-2">{{ form.emergency_contact_person || '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Address</span>
                <span class="font-semibold text-slate-700 text-right ml-2">{{ form.house_no ? `${form.house_no}, ${form.sitio}` : '—' }}</span>
              </div>
              <div class="flex justify-between py-1 border-b border-slate-100 sm:border-0">
                <span class="text-slate-400">Status</span>
                <span class="font-semibold text-right ml-2" :class="form.status === 1 ? 'text-emerald-600' : 'text-amber-600'">
                  {{ form.status === 1 ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <div class="flex justify-between py-1">
                <span class="text-slate-400">Role</span>
                <span class="font-semibold text-slate-700 text-right ml-2">Resident</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer - Responsive -->
      <div class="flex items-center justify-between px-3 sm:px-6 py-2 sm:py-4 border-t border-slate-100 bg-slate-50/60 flex-shrink-0">
        <button
          v-if="currentStep > 1"
          class="inline-flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-4 sm:py-2.5 text-[10px] sm:text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer active:scale-95"
          @click="currentStep--"
        >
          <svg width="10" height="10" sm:width="14" sm:height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
          </svg>
          <span class="hidden sm:inline">Back</span>
        </button>
        <button v-else class="invisible px-2 py-1.5 sm:px-4 sm:py-2.5">placeholder</button>

        <div class="flex items-center gap-1 sm:gap-2">
          <button
            class="px-2 py-1.5 sm:px-4 sm:py-2.5 text-[10px] sm:text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all cursor-pointer border-0 bg-transparent"
            @click="$emit('close')"
          >Cancel</button>

          <!-- Next button (steps 1–4) -->
          <button
            v-if="currentStep < steps.length"
            class="inline-flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-5 sm:py-2.5 text-[10px] sm:text-sm font-semibold text-white rounded-xl transition-all cursor-pointer active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed border-0"
            style="background:#3d4f7c"
            :disabled="!isStepValid(currentStep)"
            @click="nextStep"
            @mouseenter="e => { if(!e.currentTarget.disabled) e.currentTarget.style.background='#252b3b' }"
            @mouseleave="e => e.currentTarget.style.background='#3d4f7c'"
          >
            <span>Next</span>
            <svg width="10" height="10" sm:width="14" sm:height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </button>

          <!-- Submit button (step 5) -->
          <button
            v-else
            class="inline-flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-5 sm:py-2.5 text-[10px] sm:text-sm font-semibold text-white rounded-xl transition-all cursor-pointer active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed border-0"
            style="background:#3d4f7c"
            :disabled="loading"
            @click="submit"
            @mouseenter="e => { if(!e.currentTarget.disabled) e.currentTarget.style.background='#252b3b' }"
            @mouseleave="e => e.currentTarget.style.background='#3d4f7c'"
          >
            <svg v-if="loading" class="animate-spin w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            <span class="sm:hidden">Save</span>
            <span class="hidden sm:inline">{{ loading ? 'Saving...' : 'Save Changes' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import ResidentService from "@/services/Admin/ResidentService";
import Swal from 'sweetalert2';

const props = defineProps({
  resident: { type: Object, required: true },
  roles: { type: Array, default: () => [] },
});

const emit = defineEmits(["close", "saved"]);

const currentStep = ref(1);
const loading = ref(false);
const resetLoading = ref(false);
const errors = ref({});

// Image upload
const imagePreview = ref(null);
const imageFile = ref(null);
const fileInput = ref(null);
const currentImage = ref(null);

const steps = [
  {
    id: 1, label: 'Profile Image',
    icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
  },
  {
    id: 2, label: 'Personal Info',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
  },
  {
    id: 3, label: 'Emergency Contact',
    icon: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'
  },
  {
    id: 4, label: 'Address',
    icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'
  },
  {
    id: 5, label: 'Account',
    icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'
  },
];

const fullName = computed(() => {
  if (!props.resident?.information) return '';
  const info = props.resident.information;
  return [info.first_name, info.middle_name, info.last_name].filter(Boolean).join(' ');
});

const form = ref({
  first_name: "", middle_name: "", last_name: "", suffix: "",
  email: "", contact_number: "", sex: "", birth_place: "", birth_date: "",
  civil_status: "", religion: "", pwd: "", nationality: "", occupation: "",
  educational_attainment: "", emergency_contact_person: "",
  emergency_contact_number: "", emergency_contact_relationship: "",
  house_no: "", purok: "", sitio: "", latitude: "", longitude: "",
  role_id: "", status: 1,
});

const maxDate = computed(() => {
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
});

function validateBirthDate(event) {
  const selectedDate = new Date(event.target.value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  
  if (selectedDate > today) {
    form.value.birth_date = '';
  } else {
    errors.value.birth_date = '';
  }
}

function isStepValid(step) {
  const f = form.value;
  const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email);
  
  if (step === 1) return true; 
  if (step === 2) return !!(f.first_name && f.last_name && f.email && emailValid && f.contact_number && f.sex && f.birth_place && f.birth_date && f.civil_status && f.nationality && f.occupation && f.educational_attainment);
  if (step === 3) return !!(f.emergency_contact_person && f.emergency_contact_number && f.emergency_contact_relationship);
  if (step === 4) return !!(f.house_no && f.sitio && f.latitude && f.longitude);
  return true;
}

function nextStep() {
  if (isStepValid(currentStep.value)) {
    currentStep.value++;
  }
}

function triggerFileInput() {
  fileInput.value?.click();
}

function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    if (file.size > 4 * 1024 * 1024) {
      errors.value.image = 'File size must be less than 4MB';
      return;
    }
    
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
      errors.value.image = 'Only JPG, PNG or GIF files are allowed';
      return;
    }
    
    imageFile.value = file;
    
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
      errors.value.image = '';
    };
    reader.readAsDataURL(file);
  }
}

function removeImage() {
  imagePreview.value = null;
  imageFile.value = null;
  currentImage.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
}

function formatPhoneNumber(field, event) {
  let value = event.target.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);
  if (value.length >= 5) {
    value = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7, 11);
  } else if (value.length >= 4) {
    value = value.slice(0, 4) + ' ' + value.slice(4);
  }
  form.value[field] = value.trim();
}

function formatPhoneNumberForDisplay(phone) {
  if (!phone) return '';
  const cleaned = phone.replace(/\D/g, '');
  if (cleaned.length >= 5) {
    return cleaned.slice(0, 4) + ' ' + cleaned.slice(4, 7) + ' ' + cleaned.slice(7, 11);
  } else if (cleaned.length >= 4) {
    return cleaned.slice(0, 4) + ' ' + cleaned.slice(4);
  }
  return cleaned;
}

function isValidPhoneNumber(phone) {
  const cleaned = phone.replace(/\D/g, '');
  return cleaned.length === 11 && cleaned.startsWith('0');
}

// Add this computed property to get the Resident role ID
const residentRoleId = computed(() => {
  if (!props.roles || props.roles.length === 0) {
    return '';
  }
  const residentRole = props.roles.find(role => {
    const roleName = (role.role_name || role.name || '').toLowerCase();
    return roleName === 'resident' || 
           roleName.includes('resident') || 
           roleName === 'residents';
  });
  
  return residentRole?.id || '';
});

// Update the watch to use residentRoleId
watch(() => props.resident, (resident) => {
  if (resident) {
    const info = resident.information || {};
    const addr = resident.address || {};
    
    if (info.image_path) {
      const baseUrl = import.meta.env.VITE_API_URL || '';
      currentImage.value = `${baseUrl}/storage/${info.image_path}`;
    }
    
    form.value = {
      first_name: info.first_name ?? "", 
      middle_name: info.middle_name ?? "",
      last_name: info.last_name ?? "", 
      suffix: info.suffix ?? "",
      email: resident.email ?? "", 
      contact_number: formatPhoneNumberForDisplay(info.contact_number ?? ""),
      sex: info.sex ?? "", 
      birth_place: info.birth_place ?? "",
      birth_date: info.birth_date ?? "", 
      civil_status: info.civil_status ?? "",
      religion: info.religion ?? "", 
      pwd: info.pwd ?? "",
      nationality: info.nationality ?? "", 
      occupation: info.occupation ?? "",
      educational_attainment: info.educational_attainment ?? "",
      emergency_contact_person: info.emergency_contact_person ?? "",
      emergency_contact_number: formatPhoneNumberForDisplay(info.emergency_contact_number ?? ""),
      emergency_contact_relationship: info.emergency_contact_relationship ?? "",
      house_no: addr.house_no ?? "", 
      purok: addr.purok ?? "",
      sitio: addr.sitio ?? "", 
      latitude: addr.latitude ?? "",
      longitude: addr.longitude ?? "", 
      role_id: residentRoleId.value, // Always set to Resident role ID
      status: resident.status?.status ? 1 : 0,
    };
  }
}, { immediate: true });

watch(() => props.roles, (newRoles) => {
  if (newRoles && newRoles.length > 0 && props.resident) {
    form.value.role_id = residentRoleId.value;
  }
}, { immediate: true });

function validate() {
  const newErrors = {};
  
  if (!form.value.first_name) newErrors.first_name = "First name is required.";
  if (!form.value.last_name) newErrors.last_name = "Last name is required.";
  if (!form.value.email) newErrors.email = "Email is required.";
  
  if (!form.value.contact_number) {
    newErrors.contact_number = "Contact number is required.";
  } else if (!isValidPhoneNumber(form.value.contact_number)) {
    newErrors.contact_number = "Must be 11 digits starting with 0 (e.g., 0912 345 6789)";
  }
  
  if (!form.value.sex) newErrors.sex = "Sex is required.";
  if (!form.value.birth_place) newErrors.birth_place = "Birth place is required.";
  
  if (!form.value.birth_date) {
    newErrors.birth_date = "Birth date is required.";
  } else {
    const selectedDate = new Date(form.value.birth_date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate > today) {
      newErrors.birth_date = "Birth date cannot be in the future.";
    }
  }
  
  if (!form.value.civil_status) newErrors.civil_status = "Civil status is required.";
  if (!form.value.nationality) newErrors.nationality = "Nationality is required.";
  
  if (!form.value.emergency_contact_person) newErrors.emergency_contact_person = "Emergency contact person is required.";
  
  if (!form.value.emergency_contact_number) {
    newErrors.emergency_contact_number = "Emergency contact number is required.";
  } else if (!isValidPhoneNumber(form.value.emergency_contact_number)) {
    newErrors.emergency_contact_number = "Must be 11 digits starting with 0 (e.g., 0912 345 6789)";
  }
  
  if (!form.value.emergency_contact_relationship) newErrors.emergency_contact_relationship = "Emergency contact relationship is required.";
  if (!form.value.house_no) newErrors.house_no = "House number is required.";
  if (!form.value.sitio) newErrors.sitio = "Sitio is required.";
  if (!form.value.latitude) newErrors.latitude = "Latitude is required.";
  if (!form.value.longitude) newErrors.longitude = "Longitude is required.";
  
  
  errors.value = newErrors;
  
  return Object.keys(newErrors).length === 0;
}


async function submit() {
  if (!validate()) {
    await Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: 'Please fill in all required fields correctly.',
      confirmButtonColor: '#3d4f7c'
    });
    return;
  }
  
  loading.value = true;
  
  Swal.fire({
    title: 'Please wait...',
    text: 'Updating resident information...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  try {
    const formData = new FormData();
    
    Object.keys(form.value).forEach(key => {
      if (form.value[key] !== '' && form.value[key] !== null) {
        if (key === 'contact_number' || key === 'emergency_contact_number') {
          formData.append(key, form.value[key].replace(/\s/g, ''));
        } else {
          formData.append(key, form.value[key]);
        }
      }
    });
    
    if (imageFile.value) {
      formData.append('image', imageFile.value);
    }
    
    formData.append('_method', 'PUT');
    
    await ResidentService.updateResident(props.resident.id, formData);
    
    Swal.close();
    
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Resident has been updated successfully.',
      timer: 2000,
      showConfirmButton: false
    });
    
    emit("saved");
    emit("close");
  } catch (err) {
    Swal.close();
    
    if (err.response?.data?.errors) {
      errors.value = Object.fromEntries(
        Object.entries(err.response.data.errors).map(([k, v]) => [k, v[0]])
      );
      
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please check the form for errors.',
        confirmButtonColor: '#3d4f7c'
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to update resident. Please try again.',
        confirmButtonColor: '#3d4f7c'
      });
    }
  } finally {
    loading.value = false;
  }
}

async function handleResetPassword() {
  const result = await Swal.fire({
    title: 'Reset Password?',
    text: "Reset this resident's password to default (adminadmin)?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3d4f7c',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, reset',
    cancelButtonText: 'Cancel'
  });
  
  if (!result.isConfirmed) return;
  
  resetLoading.value = true;
  
  Swal.fire({
    title: 'Please wait...',
    text: 'Resetting password...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  try {
    await ResidentService.resetPassword(props.resident.id);
    
    Swal.close();
    
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Password has been reset successfully.',
      timer: 2000,
      showConfirmButton: false
    });
  } catch {
    Swal.close();
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to reset password.',
      confirmButtonColor: '#3d4f7c'
    });
  } finally {
    resetLoading.value = false;
  }
}
</script>

<style scoped>
.scrollbar-thin { scrollbar-width: thin; }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

/* Better touch targets on mobile */
@media (max-width: 640px) {
  button, 
  [role="button"],
  input,
  select {
    min-height: 40px;
  }
  
  .cursor-pointer {
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
  }
}
</style>