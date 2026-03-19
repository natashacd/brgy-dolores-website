import api from "@/api/api";

const LuponCasesService = {
  async getMyCases() {
    const { data } = await api.get("/api/resident/lupon-cases");
    return data;
  },

  async getCase(id) {
    const { data } = await api.get(`/api/resident/lupon-cases/${id}`);
    return data;
  },

  async createCase(payload) {
    const { data } = await api.post("/api/resident/lupon-cases", payload);
    return data;
  },

  async deleteCase(id) {
    const { data } = await api.delete(`/api/resident/lupon-cases/${id}`);
    return data;
  },
};

export default LuponCasesService;