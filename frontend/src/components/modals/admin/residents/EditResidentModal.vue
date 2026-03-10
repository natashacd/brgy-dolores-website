<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-7xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 flex-shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-8 rounded-full" style="background:#3d4f7c"></div>
          <div>
            <h2 class="text-base font-semibold text-slate-800">Edit Resident</h2>
            <p class="text-xs text-slate-400 mt-0.5">Update resident information below</p>
          </div>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer"
          @click="$emit('close')"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-5 flex flex-col gap-7 overflow-y-auto flex-1 scrollbar-thin">

        <!-- Personal Information -->
        <section>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#3d4f7c15">
              <svg width="13" height="13" fill="none" stroke="#3d4f7c" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-700">Personal Information</h3>
          </div>

          <div class="grid grid-cols-4 gap-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">First Name <span class="text-red-400">*</span></label>
              <input v-model="form.first_name" type="text" placeholder="First name"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.first_name ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.first_name" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.first_name }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Middle Name</label>
              <input v-model="form.middle_name" type="text" placeholder="Middle name"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Last Name <span class="text-red-400">*</span></label>
              <input v-model="form.last_name" type="text" placeholder="Last name"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.last_name ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.last_name" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.last_name }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Suffix</label>
              <input v-model="form.suffix" type="text" placeholder="Jr., Sr., III"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mt-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Email <span class="text-red-400">*</span></label>
              <input v-model="form.email" type="email" placeholder="Email address"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.email ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.email" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.email }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Contact Number <span class="text-red-400">*</span></label>
              <input 
                v-model="form.contact_number" 
                type="text" 
                placeholder="0912 345 6789"
                @input="formatPhoneNumber('contact_number', $event)"
                maxlength="13"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.contact_number ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.contact_number" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.contact_number }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Sex <span class="text-red-400">*</span></label>
              <select v-model="form.sex"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer"
                :class="errors.sex ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'">
                <option value="" disabled>Select sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <span v-if="errors.sex" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.sex }}
              </span>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mt-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Birth Place <span class="text-red-400">*</span></label>
              <input v-model="form.birth_place" type="text" placeholder="Birth place"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.birth_place ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.birth_place" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.birth_place }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Birth Date <span class="text-red-400">*</span></label>
              <input v-model="form.birth_date" type="date"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white"
                :class="errors.birth_date ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.birth_date" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.birth_date }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Civil Status <span class="text-red-400">*</span></label>
              <select v-model="form.civil_status"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer"
                :class="errors.civil_status ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'">
                <option value="" disabled>Select status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Annulled">Annulled</option>
                <option value="Divorced">Divorced</option>
                <option value="Legally Separated">Legally Separated</option>
              </select>
              <span v-if="errors.civil_status" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.civil_status }}
              </span>
            </div>
          </div>

          <!-- Religion, PWD, Nationality, Occupation, Educational Attainment — all in one row -->
          <div class="grid grid-cols-5 gap-3 mt-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Religion</label>
              <input v-model="form.religion" type="text" placeholder="Religion"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">PWD</label>
              <input v-model="form.pwd" type="text" placeholder="PWD ID (if applicable)"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Nationality <span class="text-red-400">*</span></label>
              <input v-model="form.nationality" type="text" placeholder="Nationality"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.nationality ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.nationality" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.nationality }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Occupation</label>
              <input v-model="form.occupation" type="text" placeholder="Occupation"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Educational Attainment</label>
              <input v-model="form.educational_attainment" type="text" placeholder="Highest education level"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
          </div>
        </section>

        <!-- Divider -->
        <div class="border-t border-slate-100"></div>

        <!-- Emergency Contact -->
        <section>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#ef444415">
              <svg width="13" height="13" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-700">Emergency Contact</h3>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Contact Person <span class="text-red-400">*</span></label>
              <input v-model="form.emergency_contact_person" type="text" placeholder="Full name"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_person ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.emergency_contact_person" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.emergency_contact_person }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Contact Number <span class="text-red-400">*</span></label>
              <input 
                v-model="form.emergency_contact_number" 
                type="text" 
                placeholder="0912 345 6789"
                @input="formatPhoneNumber('emergency_contact_number', $event)"
                maxlength="13"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_number ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.emergency_contact_number" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.emergency_contact_number }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Relationship <span class="text-red-400">*</span></label>
              <input v-model="form.emergency_contact_relationship" type="text" placeholder="e.g., Spouse, Parent"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.emergency_contact_relationship ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.emergency_contact_relationship" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.emergency_contact_relationship }}
              </span>
            </div>
          </div>
        </section>

        <!-- Divider -->
        <div class="border-t border-slate-100"></div>

        <!-- Address -->
        <section>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#05966915">
              <svg width="13" height="13" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-700">Address</h3>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">House No. <span class="text-red-400">*</span></label>
              <input v-model="form.house_no" type="text" placeholder="House number"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.house_no ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.house_no" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.house_no }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Purok</label>
              <input v-model="form.purok" type="text" placeholder="Purok"
                class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-slate-400 transition-all bg-slate-50 focus:bg-white placeholder-slate-300" />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Sitio <span class="text-red-400">*</span></label>
              <input v-model="form.sitio" type="text" placeholder="Sitio"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.sitio ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.sitio" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.sitio }}
              </span>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Latitude <span class="text-red-400">*</span></label>
              <input v-model="form.latitude" type="text" placeholder="e.g., 15.4899"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.latitude ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.latitude" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.latitude }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Longitude <span class="text-red-400">*</span></label>
              <input v-model="form.longitude" type="text" placeholder="e.g., 120.5994"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-300"
                :class="errors.longitude ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'" />
              <span v-if="errors.longitude" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.longitude }}
              </span>
            </div>
          </div>
        </section>

        <!-- Divider -->
        <div class="border-t border-slate-100"></div>

        <!-- Account Details -->
        <section>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background:#7c3aed15">
              <svg width="13" height="13" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-700">Account Details</h3>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Role <span class="text-red-400">*</span></label>
              <select v-model="form.role_id"
                class="border rounded-xl px-3 py-2 text-sm focus:outline-none transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer"
                :class="errors.role_id ? 'border-red-300 focus:border-red-400' : 'border-slate-200 focus:border-slate-400'">
                <option value="" disabled>Select a role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
              </select>
              <span v-if="errors.role_id" class="text-xs text-red-400 flex items-center gap-1">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ errors.role_id }}
              </span>
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-semibold text-slate-600">Password</label>
              <div class="flex gap-2">
                <input type="text" value="********" disabled
                  class="flex-1 border border-slate-200 bg-slate-100 text-slate-400 rounded-xl px-3 py-2 text-sm cursor-not-allowed" />
                <button
                  class="text-white text-xs font-semibold px-4 py-2 rounded-xl whitespace-nowrap transition-all active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                  style="background:#d97706"
                  @mouseenter="e => e.currentTarget.style.background='#b45309'"
                  @mouseleave="e => e.currentTarget.style.background='#d97706'"
                  @click="handleResetPassword"
                  :disabled="resetLoading"
                >
                  {{ resetLoading ? "Resetting..." : "Reset Password" }}
                </button>
              </div>
              <span class="text-xs text-slate-400">Resets password to default (adminadmin)</span>
            </div>
          </div>

          <div class="flex flex-col gap-2 mt-3">
            <label class="text-xs font-semibold text-slate-600">Status <span class="text-red-400">*</span></label>
            <div class="flex gap-3">
              <label
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border cursor-pointer transition-all text-sm font-medium select-none"
                :class="form.status === 1 ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-slate-300'"
              >
                <input type="radio" v-model="form.status" :value="1" class="hidden" />
                <span class="w-2 h-2 rounded-full" :class="form.status === 1 ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                Active
              </label>
              <label
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border cursor-pointer transition-all text-sm font-medium select-none"
                :class="form.status === 0 ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-slate-300'"
              >
                <input type="radio" v-model="form.status" :value="0" class="hidden" />
                <span class="w-2 h-2 rounded-full" :class="form.status === 0 ? 'bg-amber-400' : 'bg-slate-300'"></span>
                Inactive
              </label>
            </div>
          </div>
        </section>
      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex-shrink-0">
        <button
          class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer active:scale-95"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-all cursor-pointer active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2"
          style="background:#3d4f7c"
          @mouseenter="e => { if(!e.currentTarget.disabled) e.currentTarget.style.background='#2e3d6b' }"
          @mouseleave="e => e.currentTarget.style.background='#3d4f7c'"
          @click="submit"
          :disabled="loading"
        >
          <svg v-if="loading" class="animate-spin" width="14" height="14" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
          </svg>
          {{ loading ? "Saving..." : "Save Changes" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import ResidentService from "@/services/Admin/ResidentService";
import Swal from 'sweetalert2';

const props = defineProps({
  resident: { type: Object, required: true },
  roles: { type: Array, default: () => [] },
});

const emit = defineEmits(["close", "saved"]);

const loading = ref(false);
const resetLoading = ref(false);
const errors = ref({});

const form = ref({
  first_name: "", middle_name: "", last_name: "", suffix: "",
  email: "", contact_number: "", sex: "", birth_place: "", birth_date: "",
  civil_status: "", religion: "", pwd: "", nationality: "", occupation: "",
  educational_attainment: "", emergency_contact_person: "",
  emergency_contact_number: "", emergency_contact_relationship: "",
  house_no: "", purok: "", sitio: "", latitude: "", longitude: "",
  role_id: "", status: 1,
});

// Format phone number as user types (0912 345 6789)
const formatPhoneNumber = (field, event) => {
  let value = event.target.value.replace(/\D/g, ''); // Remove non-digits
  
  // Limit to 11 digits
  if (value.length > 11) {
    value = value.slice(0, 11);
  }
  
  // Format as 0912 345 6789
  if (value.length >= 5) {
    value = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7, 11);
  } else if (value.length >= 4) {
    value = value.slice(0, 4) + ' ' + value.slice(4);
  }
  
  form.value[field] = value.trim();
}

// Format phone number for display when loading from API
const formatPhoneNumberForDisplay = (phone) => {
  if (!phone) return '';
  const cleaned = phone.replace(/\D/g, '');
  if (cleaned.length >= 5) {
    return cleaned.slice(0, 4) + ' ' + cleaned.slice(4, 7) + ' ' + cleaned.slice(7, 11);
  } else if (cleaned.length >= 4) {
    return cleaned.slice(0, 4) + ' ' + cleaned.slice(4);
  }
  return cleaned;
}

// Validate phone number (must be 11 digits starting with 0)
const isValidPhoneNumber = (phone) => {
  const cleaned = phone.replace(/\D/g, '');
  return cleaned.length === 11 && cleaned.startsWith('0');
}

watch(() => props.resident, (resident) => {
  if (resident) {
    const info = resident.information || {};
    const addr = resident.address || {};
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
      role_id: resident.role_id || "",
      status: resident.status?.status ? 1 : 0,
    };
  }
}, { immediate: true });

function validate() {
  const newErrors = {};
  
  if (!form.value.first_name) newErrors.first_name = "First name is required.";
  if (!form.value.last_name) newErrors.last_name = "Last name is required.";
  if (!form.value.email) newErrors.email = "Email is required.";
  
  // Phone number validation
  if (!form.value.contact_number) {
    newErrors.contact_number = "Contact number is required.";
  } else if (!isValidPhoneNumber(form.value.contact_number)) {
    newErrors.contact_number = "Must be 11 digits starting with 0 (e.g., 0912 345 6789)";
  }
  
  if (!form.value.sex) newErrors.sex = "Sex is required.";
  if (!form.value.birth_place) newErrors.birth_place = "Birth place is required.";
  if (!form.value.birth_date) newErrors.birth_date = "Birth date is required.";
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
  if (!form.value.role_id) newErrors.role_id = "Role is required.";
  
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
  
  // Show loading alert
  Swal.fire({
    title: 'Please wait...',
    text: 'Updating resident information...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  // Clean phone numbers before sending (remove spaces)
  const formData = {
    ...form.value,
    contact_number: form.value.contact_number.replace(/\s/g, ''),
    emergency_contact_number: form.value.emergency_contact_number.replace(/\s/g, '')
  };
  
  try {
    await ResidentService.updateResident(props.resident.id, formData);
    
    // Close loading alert
    Swal.close();
    
    // Show success message
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
    // Close loading alert
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
</style>