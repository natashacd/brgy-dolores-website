import api from "@/api/api";

const UserService = {
  async getUsers() {
    const { data } = await api.get("/api/users");
    return data;
  },

  async getRoles() {
    const { data } = await api.get("/api/users/roles");
    return data;
  },

  async createUser(payload) {
    const { data } = await api.post("/api/users", payload);
    return data;
  },

  async updateUser(id, payload) {
    const { data } = await api.put(`/api/users/${id}`, payload);
    return data;
  },

  async resetPassword(id) {
    const { data } = await api.patch(`/api/users/${id}/reset-password`);
    return data;
  },

  async deleteUser(id) {
    const { data } = await api.delete(`/api/users/${id}`);
    return data;
  },
};

export default UserService;
