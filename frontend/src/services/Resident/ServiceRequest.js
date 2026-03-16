import api from "@/api/api";

const ServiceRequestService = {
  async createRequest(formData) {
    const { data } = await api.post(
      "/api/resident/service-requests",
      formData,
      {
        headers: { "Content-Type": undefined },
      },
    );
    return data;
  },

  async getMyRequests() {
    const { data } = await api.get("/api/resident/service-requests");
    return data;
  },

  async resubmitRequest(id, formData) {
    const { data } = await api.post(
      `/api/resident/service-requests/${id}/resubmit`,
      formData,
      {
        headers: { "Content-Type": undefined },
      },
    );
    return data;
  },

  async cancelRequest(id) {
    const { data } = await api.delete(
      `/api/resident/service-requests/${id}/cancel`,
    );
    return data;
  },
};

export default ServiceRequestService;
