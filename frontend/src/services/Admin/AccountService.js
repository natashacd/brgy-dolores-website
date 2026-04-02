import api from "@/api/api";

class AccountService {
  async getProfile() {
    try {
      const { data } = await api.get("/api/account");
      return data;
    } catch (error) {
      console.error('Error fetching profile:', error);
      throw error;
    }
  }

  async updateProfile(payload) {
    try {
      const { data } = await api.put("/api/account/profile", payload);
      return data;
    } catch (error) {
      console.error('Error updating profile:', error);
      throw error;
    }
  }

  async changePassword(payload) {
    try {
      const { data } = await api.put("/api/account/password", payload);
      return data;
    } catch (error) {
      console.error('Error changing password:', error);
      throw error;
    }
  }

  async uploadPhoto(file) {
    try {
      const formData = new FormData();
      formData.append('photo', file);
      const { data } = await api.post("/api/account/photo", formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return data;
    } catch (error) {
      console.error('Error uploading photo:', error);
      throw error;
    }
  }

  async getActiveSessions() {
    try {
      const { data } = await api.get("/api/account/sessions");
      return data;
    } catch (error) {
      console.error('Error fetching sessions:', error);
      throw error;
    }
  }

  async revokeSession(sessionId) {
    try {
      const { data } = await api.delete(`/api/account/sessions/${sessionId}`);
      return data;
    } catch (error) {
      console.error('Error revoking session:', error);
      throw error;
    }
  }
}

export default new AccountService();