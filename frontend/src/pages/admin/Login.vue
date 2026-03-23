<template>
  <div
    class="min-h-screen bg-gray-50 flex items-center justify-center p-6 font-sans"
  >
    <div
      class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden"
    >
      <div class="h-2 bg-blue-900"></div>

      <div class="p-10">
        <div class="flex flex-col items-center text-center mb-10">
          <div class="relative mb-5">
            <div
              class="absolute -inset-1.5 rounded-full opacity-60"
              style="
                background: radial-gradient(
                  circle,
                  rgba(245, 197, 24, 0.5) 0%,
                  transparent 70%
                );
                animation: glowPulse 2.5s ease-in-out infinite;
              "
            ></div>
            <img
              src="@/assets/images/dolores_logo.png"
              alt="Barangay Dolores"
              class="relative z-10 w-24 h-24 rounded-full border-3 border-white shadow-lg object-cover"
              @error="
                $event.target.src =
                  'https://placehold.co/96x96/1e3a8a/ffffff?text=BD'
              "
            />
          </div>
          <h2 class="text-3xl font-bold text-gray-900">Barangay Dolores</h2>
          <p class="text-base text-gray-500 mt-1">Capas, Tarlac</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email -->
          <div>
            <label
              class="block text-sm font-semibold uppercase tracking-wider text-gray-500 mb-2"
            >
              Username
            </label>
            <div class="relative">
              <span
                class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
              </span>
              <input
                type="text"
                v-model="form.email"
                placeholder="Enter your username"
                required
                :disabled="isLoading"
                class="w-full pl-10 pr-4 py-3 text-base border border-gray-200 rounded-lg focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 transition disabled:opacity-60 disabled:cursor-not-allowed"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <label
              class="block text-sm font-semibold uppercase tracking-wider text-gray-500 mb-2"
            >
              Password
            </label>
            <div class="relative">
              <span
                class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  />
                </svg>
              </span>
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="form.password"
                placeholder="Enter your password"
                required
                :disabled="isLoading"
                class="w-full pl-10 pr-12 py-3 text-base border border-gray-200 rounded-lg focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 transition disabled:opacity-60 disabled:cursor-not-allowed"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                :disabled="isLoading"
                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition disabled:cursor-not-allowed"
              >
                <svg
                  v-if="!showPassword"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>
                <svg
                  v-else
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.977 9.977 0 015.258-5.588M8 5.363A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.985 9.985 0 01-1.678 3.023M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full bg-blue-900 hover:bg-blue-800 disabled:opacity-60 disabled:cursor-not-allowed text-white text-base font-semibold py-3.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group mt-8"
          >
            <svg
              v-if="isLoading"
              class="w-4 h-4 animate-spin"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              />
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v8z"
              />
            </svg>
            {{ isLoading ? "Signing in..." : "Sign In" }}
            <svg
              v-if="!isLoading"
              class="w-4 h-4 group-hover:translate-x-1 transition-transform"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7l5 5m0 0l-5 5m5-5H6"
              />
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from 'vue-router'
import Swal from "sweetalert2";
import authService from "@/services/authService";
import UserService from "@/services/Admin/UserService";
import ResidentService from "@/services/Admin/ResidentService";
import ServiceRequestService from "@/services/Admin/ServiceRequestService";
import LuponCasesService from "@/services/Resident/LuponCasesService";
import { 
  setUsers, 
  setRoles, 
  setResidents, 
  setServiceRequests, 
  setLuponCases
} from "@/utils/dataStore";

const router = useRouter()

const form = reactive({ email: "", password: "" });
const showPassword = ref(false);
const isLoading = ref(false);

const handleLogin = async () => {
  if (!form.email || !form.password) {
    Swal.fire({ icon: "warning", title: "Invalid Inputs", text: "Please fill in all fields", showConfirmButton: false, timer: 1500 })
    return
  }

  isLoading.value = true

  Swal.fire({
    title: "Signing in...",
    text: "Please wait",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => Swal.showLoading(),
  })

  try {
    const data = await authService.login(form)
    const { first_name, last_name, role } = data.user

    localStorage.setItem('auth_token', data.token || 'authenticated')
    localStorage.setItem('user_role', role.toLowerCase())
    localStorage.setItem('user_name', `${first_name} ${last_name}`)
    localStorage.setItem('user_id', data.user.id)

    if (role.toLowerCase() === 'resident') {
      Swal.fire({ icon: 'success', title: `Welcome, ${first_name}!`, showConfirmButton: false, timer: 800 })
      router.push({ name: 'resident.dashboard' })

    } else if (role.toLowerCase() === 'lupon') {
      Swal.fire({ icon: 'success', title: `Welcome, ${first_name}!`, showConfirmButton: false, timer: 800 })
      router.push({ name: 'lupon.residents' })

    } else {
      // ── Prefetch while "Signing in..." spinner is still showing ──
      const [users, rolesData, residents, serviceRequests, luponCases] = await Promise.all([
        UserService.getUsers(),
        UserService.getRoles(),
        ResidentService.getResidents(),
        ServiceRequestService.getAll(),
        LuponCasesService.adminCases(),
      ])

      setUsers(users)
      setRoles(rolesData)
      setResidents(residents)
      setServiceRequests(serviceRequests)
      setLuponCases(luponCases)

      Swal.fire({ icon: 'success', title: `Welcome, ${first_name}!`, showConfirmButton: false, timer: 800 })
      router.push({ name: 'admin.dashboard' })
    }

  } catch (err) {
    const errorMessage = err.response?.data?.message || err.response?.data?.error || err.message || "Invalid credentials."
    Swal.fire({ icon: "error", title: "Login Failed", text: errorMessage, confirmButtonColor: "#3d4f7c" })
  } finally {
    isLoading.value = false
    form.password = ""
  }
}
</script>

<style scoped>
@keyframes glowPulse {
  0%,
  100% {
    opacity: 0.5;
    transform: scale(1);
  }
  50% {
    opacity: 0.8;
    transform: scale(1.2);
  }
}
</style>
