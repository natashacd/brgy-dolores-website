<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/45"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-lg w-full max-w-md shadow-xl overflow-hidden">
      <!-- Header -->
      <div
        class="flex items-center justify-between px-5 py-4 border-b border-gray-200"
      >
        <h2 class="text-base font-semibold text-gray-900">Edit User</h2>
        <button
          class="text-gray-400 hover:text-gray-700 text-xl leading-none"
          @click="$emit('close')"
        >
          &times;
        </button>
      </div>

      <!-- Body -->
      <div class="px-5 py-4 flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700"
            >Email <span class="text-red-500">*</span></label
          >
          <input
            v-model="form.email"
            type="email"
            placeholder="Email address"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
          />
          <span v-if="errors.email" class="text-xs text-red-500">{{
            errors.email
          }}</span>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700"
            >Role <span class="text-red-500">*</span></label
          >
          <select
            v-model="form.role_id"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
          >
            <option value="" disabled>Select a role</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">
              {{ role.role_name }}
            </option>
          </select>
          <span v-if="errors.role_id" class="text-xs text-red-500">{{
            errors.role_id
          }}</span>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700">Password</label>
          <div class="flex gap-2">
            <input
              type="text"
              value="********"
              disabled
              class="flex-1 border border-gray-200 bg-gray-100 text-gray-500 rounded-md px-3 py-2 text-sm cursor-not-allowed"
            />
            <button
              class="bg-amber-500 hover:bg-amber-600 disabled:opacity-60 disabled:cursor-not-allowed text-white text-xs font-medium px-3 py-2 rounded-md whitespace-nowrap"
              @click="handleResetPassword"
              :disabled="resetLoading"
            >
              {{ resetLoading ? "Resetting..." : "Reset" }}
            </button>
          </div>
          <span class="text-xs text-gray-400"
            >Resets password to default (adminadmin)</span
          >
        </div>
      </div>

      <!-- Footer -->
      <div
        class="flex justify-end gap-2 px-5 py-3 border-t border-gray-200 bg-gray-50"
      >
        <button
          class="border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-md"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          class="bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium px-4 py-2 rounded-md"
          @click="submit"
          :disabled="loading"
        >
          {{ loading ? "Saving..." : "Save Changes" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import UserService from "@/services/Admin/UserService";

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

const form = ref({
  email: "",
  role_id: "",
});

watch(
  () => props.user,
  (user) => {
    if (user) {
      form.value = {
        email: user.email ?? "",
        role_id: user.role_id ?? "",
      };
    }
  },
  { immediate: true },
);

function validate() {
  errors.value = {};
  if (!form.value.email) errors.value.email = "Email is required.";
  if (!form.value.role_id) errors.value.role_id = "Role is required.";
  return Object.keys(errors.value).length === 0;
}

async function submit() {
  if (!validate()) return;
  loading.value = true;
  try {
    await UserService.updateUser(props.user.id, form.value);
    emit("saved");
    emit("close");
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = Object.fromEntries(
        Object.entries(err.response.data.errors).map(([k, v]) => [k, v[0]]),
      );
    }
  } finally {
    loading.value = false;
  }
}

async function handleResetPassword() {
  if (!confirm("Reset this user's password to default (adminadmin)?")) return;
  resetLoading.value = true;
  try {
    await UserService.resetPassword(props.user.id);
    alert("Password has been reset successfully.");
  } catch {
    alert("Failed to reset password.");
  } finally {
    resetLoading.value = false;
  }
}
</script>
