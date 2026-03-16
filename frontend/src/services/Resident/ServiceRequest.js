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
};

export default ServiceRequestService;
