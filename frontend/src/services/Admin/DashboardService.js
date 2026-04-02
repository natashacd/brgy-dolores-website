import api from "@/api/api";

class DashboardService {
  async getStats() {
    try {
      const { data } = await api.get("/api/admin/dashboard/stats");
      return data;
    } catch (error) {
      console.error('Error fetching dashboard stats:', error);
      throw error;
    }
  }
}

export default new DashboardService();