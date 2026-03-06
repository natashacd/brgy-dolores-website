import { ref, onMounted, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/api/api";

export function useVisitorCounter() {
  const count = ref(0);
  const loading = ref(false);
  const route = useRoute();

  // Track timeout to prevent multiple track calls
  let trackTimeout = null;

  const fetchCount = async () => {
    try {
      const response = await api.getVisitorCount();
      count.value = response.data.data?.count || response.data.count || 0;
    } catch (error) {
      console.error("Error fetching visitor count:", error);
    }
  };

  const trackCurrentPage = async () => {
    try {
      // Clear any pending track timeout
      if (trackTimeout) {
        clearTimeout(trackTimeout);
      }

      // Small delay to prevent tracking during rapid navigation
      trackTimeout = setTimeout(async () => {
        loading.value = true;
        const currentUrl = window.location.pathname;
        const response = await api.trackVisitor(currentUrl);
        count.value = response.data.data?.count || response.data.count || 0;
        loading.value = false;
        trackTimeout = null;
      }, 500);
    } catch (error) {
      console.error("Error tracking visitor:", error);
      loading.value = false;
    }
  };

  // Track on mount (first page load)
  onMounted(() => {
    fetchCount();

    // Track this page view
    trackCurrentPage();
  });

  // Track on route changes
  const unwatch = route ? route.path : null;
  if (route) {
    // Using watchEffect would be better, but for simplicity:
    const originalPushState = history.pushState;
    history.pushState = function () {
      originalPushState.apply(this, arguments);
      trackCurrentPage();
    };

    const originalReplaceState = history.replaceState;
    history.replaceState = function () {
      originalReplaceState.apply(this, arguments);
      trackCurrentPage();
    };

    // Listen for popstate (back/forward buttons)
    window.addEventListener("popstate", trackCurrentPage);
  }

  // Cleanup
  onUnmounted(() => {
    if (trackTimeout) {
      clearTimeout(trackTimeout);
    }
    if (route) {
      window.removeEventListener("popstate", trackCurrentPage);
    }
  });

  return {
    count,
    loading,
  };
}
