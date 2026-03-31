import api from "@/api/api";

const ResidentService = {
  async getMyRequests() {
    const { data } = await api.get("/api/resident/service-requests");
    return data;
  },

  async createRequest(payload) {
    const { data } = await api.post("/api/resident/service-requests", payload);
    return data;
  },

  async cancelRequest(id) {
    const { data } = await api.delete(`/api/resident/service-requests/${id}`);
    return data;
  },

  async getRequestDetails(id) {
    const { data } = await api.get(`/api/resident/service-requests/${id}`);
    return data;
  }
};

export default ResidentService;