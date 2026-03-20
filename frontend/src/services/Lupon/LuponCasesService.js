import api from "@/api/api";

const LuponCasesService = {
  // ── Lupon / Admin ──────────────────────────────────────────────

  async adminCases() {
    const { data } = await api.get("/api/lupon/cases");
    return data;
  },

  async getCase(id) {
    const { data } = await api.get(`/api/lupon/cases/${id}`);
    return data;
  },

  async approveCase(id, remarks = "") {
    const { data } = await api.put(`/api/lupon/cases/${id}/approve`, { remarks });
    return data;
  },

  async disapproveCase(id, remarks) {
    const { data } = await api.put(`/api/lupon/cases/${id}/disapprove`, { remarks });
    return data;
  },

  // ── Resident ───────────────────────────────────────────────────

  async getMyCases() {
    const { data } = await api.get("/api/resident/lupon-cases");
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