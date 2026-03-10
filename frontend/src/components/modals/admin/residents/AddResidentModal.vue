<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/45 p-4"
    @click.self="$emit('close')"
  >
    <div
      class="bg-white rounded-lg w-full max-w-5xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col"
    >
      <!-- Header -->
      <div
        class="flex items-center justify-between px-5 py-4 border-b border-gray-200 flex-shrink-0"
      >
        <h2 class="text-base font-semibold text-gray-900">Add Resident</h2>
        <button
          class="text-gray-400 hover:text-gray-700 text-xl leading-none"
          @click="$emit('close')"
        >
          &times;
        </button>
      </div>

      <!-- Body - Scrollable -->
      <div class="px-5 py-4 flex flex-col gap-6 overflow-y-auto flex-1">
        <!-- Personal Information -->
        <div>
          <h3 class="text-sm font-semibold text-gray-800 mb-3">
            Personal Information
          </h3>
          <div class="grid grid-cols-4 gap-3">
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
              <label class="text-xs font-medium text-gray-700"
                >Middle Name</label
              >
              <input
                v-model="form.middle_name"
                type="text"
                placeholder="Middle name"
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
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700">Suffix</label>
              <input
                v-model="form.suffix"
                type="text"
                placeholder="Jr., Sr., III"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mt-3">
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
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Sex <span class="text-red-500">*</span></label
              >
              <select
                v-model="form.sex"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              >
                <option value="" disabled>Select sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <span v-if="errors.sex" class="text-xs text-red-500">{{
                errors.sex
              }}</span>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mt-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Birth Place <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.birth_place"
                type="text"
                placeholder="Birth place"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.birth_place" class="text-xs text-red-500">{{
                errors.birth_place
              }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Birth Date <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.birth_date"
                type="date"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.birth_date" class="text-xs text-red-500">{{
                errors.birth_date
              }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Civil Status <span class="text-red-500">*</span></label
              >
              <select
                v-model="form.civil_status"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              >
                <option value="" disabled>Select status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Annulled">Annulled</option>
                <option value="Divorced">Divorced</option>
                <option value="Legally Separated">Legally Separated</option>
              </select>
              <span v-if="errors.civil_status" class="text-xs text-red-500">{{
                errors.civil_status
              }}</span>
            </div>
          </div>

          <div class="grid grid-cols-4 gap-3 mt-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700">Religion</label>
              <input
                v-model="form.religion"
                type="text"
                placeholder="Religion"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700">PWD</label>
              <input
                v-model="form.pwd"
                type="text"
                placeholder="PWD ID (if applicable)"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Nationality <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.nationality"
                type="text"
                placeholder="Nationality"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.nationality" class="text-xs text-red-500">{{
                errors.nationality
              }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Occupation</label
              >
              <input
                v-model="form.occupation"
                type="text"
                placeholder="Occupation"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
            </div>
          </div>

          <div class="flex flex-col gap-1 mt-3">
            <label class="text-xs font-medium text-gray-700"
              >Educational Attainment</label
            >
            <input
              v-model="form.educational_attainment"
              type="text"
              placeholder="Highest education level"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Emergency Contact -->
        <div>
          <h3 class="text-sm font-semibold text-gray-800 mb-3">
            Emergency Contact
          </h3>
          <div class="grid grid-cols-3 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Contact Person <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.emergency_contact_person"
                type="text"
                placeholder="Full name"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span
                v-if="errors.emergency_contact_person"
                class="text-xs text-red-500"
                >{{ errors.emergency_contact_person }}</span
              >
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Contact Number <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.emergency_contact_number"
                type="text"
                placeholder="Contact number"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span
                v-if="errors.emergency_contact_number"
                class="text-xs text-red-500"
                >{{ errors.emergency_contact_number }}</span
              >
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Relationship <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.emergency_contact_relationship"
                type="text"
                placeholder="e.g., Spouse, Parent"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span
                v-if="errors.emergency_contact_relationship"
                class="text-xs text-red-500"
                >{{ errors.emergency_contact_relationship }}</span
              >
            </div>
          </div>
        </div>

        <!-- Address -->
        <div>
          <h3 class="text-sm font-semibold text-gray-800 mb-3">Address</h3>
          <div class="grid grid-cols-3 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >House No. <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.house_no"
                type="text"
                placeholder="House number"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.house_no" class="text-xs text-red-500">{{
                errors.house_no
              }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700">Purok</label>
              <input
                v-model="form.purok"
                type="text"
                placeholder="Purok"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Sitio <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.sitio"
                type="text"
                placeholder="Sitio"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.sitio" class="text-xs text-red-500">{{
                errors.sitio
              }}</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Latitude <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.latitude"
                type="text"
                placeholder="e.g., 15.4899"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.latitude" class="text-xs text-red-500">{{
                errors.latitude
              }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-medium text-gray-700"
                >Longitude <span class="text-red-500">*</span></label
              >
              <input
                v-model="form.longitude"
                type="text"
                placeholder="e.g., 120.5994"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
              />
              <span v-if="errors.longitude" class="text-xs text-red-500">{{
                errors.longitude
              }}</span>
            </div>
          </div>
        </div>

        <!-- Role & Password -->
        <div>
          <h3 class="text-sm font-semibold text-gray-800 mb-3">
            Account Details
          </h3>
          <div class="grid grid-cols-2 gap-3">
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
              <input
                type="text"
                value="adminadmin"
                disabled
                class="border border-gray-200 bg-gray-100 text-gray-500 rounded-md px-3 py-2 text-sm cursor-not-allowed"
              />
              <span class="text-xs text-gray-400"
                >Temporary default password</span
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div
        class="flex justify-end gap-2 px-5 py-3 border-t border-gray-200 bg-gray-50 flex-shrink-0"
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
          {{ loading ? "Saving..." : "Add Resident" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import ResidentService from "@/services/Admin/ResidentService";

defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["close", "saved"]);

const loading = ref(false);
const errors = ref({});

const form = ref({
  first_name: "",
  middle_name: "",
  last_name: "",
  suffix: "",
  email: "",
  contact_number: "",
  sex: "",
  birth_place: "",
  birth_date: "",
  civil_status: "",
  religion: "",
  pwd: "",
  nationality: "",
  occupation: "",
  educational_attainment: "",
  emergency_contact_person: "",
  emergency_contact_number: "",
  emergency_contact_relationship: "",
  house_no: "",
  purok: "",
  sitio: "",
  latitude: "",
  longitude: "",
  role_id: "",
});

function validate() {
  errors.value = {};
  if (!form.value.first_name)
    errors.value.first_name = "First name is required.";
  if (!form.value.last_name) errors.value.last_name = "Last name is required.";
  if (!form.value.email) errors.value.email = "Email is required.";
  if (!form.value.contact_number)
    errors.value.contact_number = "Contact number is required.";
  if (!form.value.sex) errors.value.sex = "Sex is required.";
  if (!form.value.birth_place)
    errors.value.birth_place = "Birth place is required.";
  if (!form.value.birth_date)
    errors.value.birth_date = "Birth date is required.";
  if (!form.value.civil_status)
    errors.value.civil_status = "Civil status is required.";
  if (!form.value.nationality)
    errors.value.nationality = "Nationality is required.";
  if (!form.value.emergency_contact_person)
    errors.value.emergency_contact_person =
      "Emergency contact person is required.";
  if (!form.value.emergency_contact_number)
    errors.value.emergency_contact_number =
      "Emergency contact number is required.";
  if (!form.value.emergency_contact_relationship)
    errors.value.emergency_contact_relationship =
      "Emergency contact relationship is required.";
  if (!form.value.house_no) errors.value.house_no = "House number is required.";
  if (!form.value.sitio) errors.value.sitio = "Sitio is required.";
  if (!form.value.latitude) errors.value.latitude = "Latitude is required.";
  if (!form.value.longitude) errors.value.longitude = "Longitude is required.";
  if (!form.value.role_id) errors.value.role_id = "Role is required.";
  return Object.keys(errors.value).length === 0;
}

async function submit() {
  if (!validate()) return;
  loading.value = true;
  try {
    await ResidentService.createResident(form.value);
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
</script>
