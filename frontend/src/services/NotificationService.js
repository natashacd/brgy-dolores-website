import api from "@/api/api";

const NotificationService = {
  async getAll() {
    const { data } = await api.get("/api/notifications");
    return data;
  },

  async getUnreadCount() {
    const { data } = await api.get("/api/notifications/unread-count");
    return data.count;
  },

  async markAsRead(id) {
    const { data } = await api.patch(`/api/notifications/${id}/read`);
    return data;
  },

  async markAllAsRead() {
    const { data } = await api.patch("/api/notifications/read-all");
    return data;
  },
};

export default NotificationService;
