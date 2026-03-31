<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl shadow-gray-300/50 overflow-hidden">

      <!-- Header -->
      <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-blue-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h2 class="text-base font-bold text-gray-900 leading-tight">Edit User</h2>
          <p class="text-xs text-gray-400 mt-0.5">Update user account details</p>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all cursor-pointer border-0 bg-transparent"
          @click="$emit('close')"
        >
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-5 flex flex-col gap-5 max-h-[70vh] overflow-y-auto scrollbar-thin">

        <!-- Two Column Layout -->
        <div class="grid grid-cols-2 gap-4">
          <!-- Email -->
          <div class="flex flex-col gap-1.5 col-span-2 md:col-span-1">
            <label class="text-xs font-semibold text-gray-600">
              Email Address <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="name@example.com"
              class="border rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition-all w-full"
              :class="errors.email
                ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400'
                : 'border-gray-200 focus:ring-blue-500/20 focus:border-blue-500 hover:border-gray-300'"
            />
            <span v-if="errors.email" class="text-[11px] text-red-500 flex items-center gap-1 mt-0.5">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errors.email }}
            </span>
          </div>

          <!-- Role -->
          <div class="flex flex-col gap-1.5 col-span-2 md:col-span-1">
            <label class="text-xs font-semibold text-gray-600">
              Role <span class="text-red-400">*</span>
            </label>
            <div class="relative">
              <select
                v-model="form.role_id"
                class="appearance-none w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 transition-all pr-9"
                :class="errors.role_id
                  ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400 text-gray-800'
                  : 'border-gray-200 focus:ring-blue-500/20 focus:border-blue-500 hover:border-gray-300 text-gray-800'"
              >
                <option value="" disabled class="text-gray-400">Select a role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
              </select>
              <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
            <span v-if="errors.role_id" class="text-[11px] text-red-500 flex items-center gap-1 mt-0.5">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errors.role_id }}
            </span>
          </div>
        </div>

        <!-- User Info Display (optional - shows current user name) -->
        <div v-if="props.user" class="bg-blue-50/30 rounded-xl p-3 border border-blue-100">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
              <span class="text-xs font-semibold text-blue-700">{{ getUserInitials() }}</span>
            </div>
            <div>
              <p class="text-xs text-gray-500">Editing user</p>
              <p class="text-sm font-medium text-gray-800">{{ getUserFullName() }}</p>
            </div>
          </div>
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-gray-600">Password</label>
          <div class="flex gap-2">
            <div class="relative flex-1">
              <input
                type="text"
                value="********"
                disabled
                class="w-full border border-gray-100 bg-gray-50 text-gray-400 rounded-xl px-3.5 py-2.5 text-sm cursor-not-allowed"
              />
              <span class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-gray-300">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </span>
            </div>
            <button
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 hover:border-amber-300 disabled:opacity-50 disabled:cursor-not-allowed px-3.5 py-2.5 rounded-xl transition-all cursor-pointer whitespace-nowrap"
              @click="handleResetPassword"
              :disabled="resetLoading"
            >
              <svg v-if="resetLoading" class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
              </svg>
              <svg v-else width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              {{ resetLoading ? 'Resetting...' : 'Reset' }}
            </button>
          </div>
          <p class="text-[11px] text-gray-400 flex items-center gap-1 mt-1">
            <svg width="9" height="9" fill="currentColor" viewBox="0 0 20 20" class="text-gray-300 flex-shrink-0">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Resets password to default (adminadmin)
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-gray-100 bg-gray-50/60">
        <button
          class="text-sm font-semibold text-gray-500 hover:text-gray-800 bg-white hover:bg-gray-100 border border-gray-200 px-5 py-2.5 rounded-xl transition-all cursor-pointer"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 hover:shadow-lg hover:shadow-blue-200 transition-all cursor-pointer border-0"
          @click="submit"
          :disabled="loading"
        >
          <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
          <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          {{ loading ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import UserService from "@/services/Admin/UserService";
import Swal from 'sweetalert2';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  roles: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["close", "saved"]);

const loading = ref(false);
const resetLoading = ref(false);
const errors = ref({});
const initialForm = ref(null);

const form = ref({
  email: "",
  role_id: "",
});

// Store initial values to compare changes
watch(
  () => props.user,
  (user) => {
    if (user) {
      form.value = {
        email: user.email ?? "",
        role_id: user.role_id ?? "",
      };
      // Store initial values
      initialForm.value = { ...form.value };
    }
  },
  { immediate: true },
);

// Check if form has changes
const hasChanges = computed(() => {
  if (!initialForm.value) return true;
  return (
    form.value.email !== initialForm.value.email ||
    form.value.role_id !== initialForm.value.role_id
  );
});

// Helper functions for user info
function getUserFullName() {
  if (!props.user?.information) return "Unknown User";
  const info = props.user.information;
  return [info.first_name, info.last_name].filter(Boolean).join(" ");
}

function getUserInitials() {
  const name = getUserFullName();
  if (name === "Unknown User") return "?";
  const parts = name.split(" ");
  if (parts.length === 1) return parts[0][0]?.toUpperCase() || "?";
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

function validate() {
  errors.value = {};
  if (!form.value.email) errors.value.email = "Email is required.";
  else if (!/^\S+@\S+\.\S+$/.test(form.value.email)) {
    errors.value.email = "Invalid email format.";
  }
  if (!form.value.role_id) errors.value.role_id = "Role is required.";
  return Object.keys(errors.value).length === 0;
}

async function submit() {
  if (!validate()) return;
  
  // Check if there are actual changes
  if (!hasChanges.value) {
    await Swal.fire({
      icon: 'info',
      title: 'No Changes',
      text: 'No changes were made to the user.',
      timer: 1500,
      showConfirmButton: false
    });
    emit("close");
    return;
  }
  
  loading.value = true;
  
  try {
    await UserService.updateUser(props.user.id, form.value);
    
    // Emit saved event without waiting for Swal to close
    emit("saved");
    
    // Show success message
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'User has been updated successfully.',
      timer: 1500,
      showConfirmButton: false
    });
    
    // Close modal after success
    emit("close");
    
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = Object.fromEntries(
        Object.entries(err.response.data.errors).map(([k, v]) => [k, v[0]]),
      );
    } else {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to update user. Please try again.',
        confirmButtonColor: '#3085d6'
      });
    }
  } finally {
    loading.value = false;
  }
}

async function handleResetPassword() {
  const result = await Swal.fire({
    title: 'Reset Password?',
    text: "Reset this user's password to default (adminadmin)?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, reset',
    cancelButtonText: 'Cancel'
  });
  
  if (!result.isConfirmed) return;
  
  resetLoading.value = true;
  
  try {
    await UserService.resetPassword(props.user.id);
    
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Password has been reset successfully.',
      timer: 1500,
      showConfirmButton: false
    });
  } catch {
    await Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to reset password.',
      confirmButtonColor: '#3085d6'
    });
  } finally {
    resetLoading.value = false;
  }
}
</script>

<style scoped>
.scrollbar-thin {
  scrollbar-width: thin;
}
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 20px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}
</style>