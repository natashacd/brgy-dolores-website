import api from "@/api/api";

const authService = {
  async getCsrfCookie() {
    await api.get("/sanctum/csrf-cookie");
  },

  async login(payload) {
    await this.getCsrfCookie();
    const { data } = await api.post("/api/login", payload);
    return data;
  },

  async logout() {
    const { data } = await api.post("/api/logout");
    return data;
  },

  async getUser() {
    const { data } = await api.get("/api/user");
    return data;
  },
};

export default authService;
