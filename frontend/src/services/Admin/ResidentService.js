import api from "@/api/api";

const ResidentService = {
  async getResidents() {
    const { data } = await api.get("/api/residents");
    return data;
  },

  async getRoles() {
    const { data } = await api.get("/api/residents/roles");
    return data;
  },

  async createResident(payload) {
    const { data } = await api.post("/api/residents", payload);
    return data;
  },

  async updateResident(id, payload) {
    const { data } = await api.put(`/api/residents/${id}`, payload);
    return data;
  },

  async resetPassword(id) {
    const { data } = await api.patch(`/api/residents/${id}/reset-password`);
    return data;
  },

  async deleteResident(id) {
    const { data } = await api.delete(`/api/residents/${id}`);
    return data;
  },
};

export default ResidentService;