import api from "@/api/api";

class AuditLogsService {
  async getAll() {
    try {
      const { data } = await api.get("/api/admin/audit-logs");
      return data;
    } catch (error) {
      console.error('Error fetching audit logs:', error);
      throw error;
    }
  }

  async getById(id) {
    try {
      const { data } = await api.get(`/api/admin/audit-logs/${id}`);
      return data;
    } catch (error) {
      console.error(`Error fetching audit log ${id}:`, error);
      throw error;
    }
  }
}

export default new AuditLogsService();