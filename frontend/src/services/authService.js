import api from "@/api/api";

const authService = {
  async getCsrfCookie() {
    await api.get("/sanctum/csrf-cookie");
  },

  async login(payload) {
    await this.getCsrfCookie();
    const { data } = await api.post("/api/login", payload);
    
    if (data.user) {
      localStorage.setItem('auth_token', data.token || 'authenticated')
      localStorage.setItem('user_name', `${data.user.first_name} ${data.user.last_name}`)
      localStorage.setItem('user_role', data.user.role)
      localStorage.setItem('user_email', data.user.email)
    }
    
    return data;
  },

  async logout() {
    const { data } = await api.post("/api/logout");
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_name')
    localStorage.removeItem('user_role')
    localStorage.removeItem('user_email')
    sessionStorage.removeItem('auth_token')
    return data;
  },

  async getUser() {
    const { data } = await api.get("/api/user");
    return data;
  },
};

export default authService;