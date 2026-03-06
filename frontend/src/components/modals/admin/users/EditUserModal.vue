<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/45"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-lg w-full max-w-2xl shadow-xl overflow-hidden">
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
        <div class="grid grid-cols-3 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700"
              >First Name <span class="text-red-500">*</span></label
            >
            <input
              v-model="form.first_name"
              type="text"
              placeholder="First name"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
            />
            <span v-if="errors.first_name" class="text-xs text-red-500">{{
              errors.first_name
            }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Middle Name</label>
            <input
              v-model="form.middle_name"
              type="text"
              placeholder="Middle name (optional)"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
            />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700"
              >Last Name <span class="text-red-500">*</span></label
            >
            <input
              v-model="form.last_name"
              type="text"
              placeholder="Last name"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
            />
            <span v-if="errors.last_name" class="text-xs text-red-500">{{
              errors.last_name
            }}</span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
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
              >Contact Number <span class="text-red-500">*</span></label
            >
            <input
              v-model="form.contact_number"
              type="text"
              placeholder="Contact number"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
            />
            <span v-if="errors.contact_number" class="text-xs text-red-500">{{
              errors.contact_number
            }}</span>
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700"
            >Address <span class="text-red-500">*</span></label
          >
          <input
            v-model="form.address"
            type="text"
            placeholder="Address"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
          />
          <span v-if="errors.address" class="text-xs text-red-500">{{
            errors.address
          }}</span>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-700">Role</label>
            <select
              v-model="form.role_id"
              disabled
              class="border border-gray-200 bg-gray-100 text-gray-500 rounded-md px-3 py-2 text-sm cursor-not-allowed"
            >
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.role_name }}
              </option>
            </select>
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
                {{ resetLoading ? "Resetting..." : "Reset Password" }}
              </button>
            </div>
            <span class="text-xs text-gray-400"
              >Resets password back to default (adminadmin)</span
            >
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-gray-700"
            >Status <span class="text-red-500">*</span></label
          >
          <div class="flex gap-5">
            <label
              class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer"
            >
              <input type="radio" v-model="form.status" :value="1" /> Active
            </label>
            <label
              class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer"
            >
              <input type="radio" v-model="form.status" :value="0" /> Inactive
            </label>
          </div>
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
  first_name: "",
  middle_name: "",
  last_name: "",
  email: "",
  contact_number: "",
  address: "",
  role_id: "",
  status: 1,
});

watch(
  () => props.user,
  (user) => {
    if (user) {
      form.value = {
        first_name: user.information?.first_name ?? "",
        middle_name: user.information?.middle_name ?? "",
        last_name: user.information?.last_name ?? "",
        email: user.email ?? "",
        contact_number: user.information?.contact_number ?? "",
        address: user.information?.address ?? "",
        role_id: user.role_id ?? "",
        status: user.status?.status ?? 1,
      };
    }
  },
  { immediate: true },
);

function validate() {
  errors.value = {};
  if (!form.value.first_name)
    errors.value.first_name = "First name is required.";
  if (!form.value.last_name) errors.value.last_name = "Last name is required.";
  if (!form.value.email) errors.value.email = "Email is required.";
  if (!form.value.contact_number)
    errors.value.contact_number = "Contact number is required.";
  if (!form.value.address) errors.value.address = "Address is required.";
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
