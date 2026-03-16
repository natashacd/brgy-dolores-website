import api from "@/api/api";

const ServiceRequestService = {

  async getPending() {
    const { data } = await api.get("/api/admin/service-requests", {
      params: { status: "pending" },
    });
    return data;
  },

  async getApproved() {
    const { data } = await api.get("/api/admin/service-requests", {
      params: { status: "approved" },
    });
    return data;
  },

  async getDisapproved() {
    const { data } = await api.get("/api/admin/service-requests", {
      params: { status: "disapproved" },
    });
    return data;
  },

  async getReleased() {
    const { data } = await api.get("/api/admin/service-requests", {
      params: { status: "released" },
    });
    return data;
  },

  async approve(id) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/approve`);
    return data;
  },

  async reject(id, payload) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/reject`, payload);
    return data;
  },

  async release(id) {
    const { data } = await api.put(`/api/admin/service-requests/${id}/release`);
    return data;
  },
};

export default ServiceRequestService;