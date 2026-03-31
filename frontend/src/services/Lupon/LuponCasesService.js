import api from "@/api/api";

const LuponCasesService = {
  // ── Resident ──────────────────────────────────────────────
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

  // ── Admin ─────────────────────────────────────────────────
  async adminCases() {
    const { data } = await api.get("/api/admin/lupon-cases");
    return data;
  },

   // ── Lupon ─────────────────────────────────────────────────
  async allCases() {
    const { data } = await api.get("/api/lupon/cases");
    return data;
  },
  async approvedCases() {
    const { data } = await api.get("/api/lupon/cases/approved");
    return data;
  },
  async disapprovedCases() {
    const { data } = await api.get("/api/lupon/cases/disapproved");
    return data;
  },
  async getCase(id) {
    const { data } = await api.get(`/api/lupon/cases/${id}`);
    return data;
  },
  async approveCase(id) {
    const { data } = await api.put(`/api/lupon/cases/${id}/approve`);
    return data;
  },
  async disapproveCase(id, payload) {
    const { data } = await api.put(`/api/lupon/cases/${id}/disapprove`, payload);
    return data;
  },
  async closeCase(id) {
    const { data } = await api.put(`/api/lupon/cases/${id}/close`);
    return data;
  },
  async closedCases() {
    const { data } = await api.get("/api/lupon/cases/closed");
    return data;
  },
  async scheduleSummon(id, payload) {
    // payload: { date: 'datetime string', notes: 'optional string' }
    const { data } = await api.post(`/api/lupon/cases/${id}/summon`, payload);
    return data;
  },
  async issueCertificate(id) {
    const { data } = await api.post(`/api/lupon/cases/${id}/issue-certificate`);
    return data;
  },
};

export default LuponCasesService;