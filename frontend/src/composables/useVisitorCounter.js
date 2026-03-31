import { ref, onMounted, onUnmounted, watch } from "vue";
import { useRoute } from "vue-router";
import visitorService from "@/services/visitorService";

export function useVisitorCounter() {
  const count = ref(0);
  const loading = ref(false);
  const route = useRoute();
  let trackTimeout = null;

  const fetchCount = async () => {
    try {
      const response = await visitorService.getVisitorCount();
      count.value = response.data?.data?.count ?? 0; // ← data.data.count
    } catch (error) {
      console.error("Error fetching visitor count:", error);
    }
  };

  const trackCurrentPage = () => {
    if (trackTimeout) clearTimeout(trackTimeout);
    trackTimeout = setTimeout(async () => {
      try {
        loading.value = true;
        const response = await visitorService.trackVisitor(
          window.location.pathname,
        );
        count.value = response.data?.data?.count ?? 0; // ← data.data.count
      } catch (error) {
        console.error("Error tracking visitor:", error);
      } finally {
        loading.value = false;
        trackTimeout = null;
      }
    }, 500);
  };

  // Watch route changes
  watch(
    () => route.path,
    () => trackCurrentPage(),
  );

  onMounted(() => {
    fetchCount();
    trackCurrentPage();
  });

  onUnmounted(() => {
    if (trackTimeout) clearTimeout(trackTimeout);
  });

  return { count, loading };
}
