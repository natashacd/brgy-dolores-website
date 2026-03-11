import api from "@/api/api";

const ServiceRequestService = {
  async getRequests(params = {}) {
    const { data } = await api.get("/api/admin/service-requests", { params });
    return data;
  },

  async getRequest(id) {
    const { data } = await api.get(`/api/admin/service-requests/${id}`);
    return data;
  },

  async getStaff() {
    const { data } = await api.get("/api/admin/service-requests/staff");
    return data;
  },

  async reassign(id, staffId) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/reassign`, {
      staff_id: staffId,
    });
    return data;
  },

  async approve(id) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/approve`);
    return data;
  },

  async cancel(id) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/cancel`);
    return data;
  },
};

export default ServiceRequestService;