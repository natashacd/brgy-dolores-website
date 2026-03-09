import api from '@/api/api'
const API_URL = '/api/admin'

export const announcementService = {
  // Existing methods...
  async getAnnouncements(params = {}) {
    const response = await api.get(`${API_URL}/announcements`, { 
      params: {
        ...params,
        _t: Date.now()
      }
    })
    return response.data
  },

  async getAnnouncement(id) {
    const response = await api.get(`${API_URL}/announcements/${id}`)
    return response.data
  },

  async createAnnouncement(data) {
    const formData = new FormData()
    
    if (data.title) formData.append('title', data.title)
    if (data.content) formData.append('content', data.content)
    if (data.category) formData.append('category', data.category)
    if (data.status) formData.append('status', data.status)
    if (data.is_urgent !== undefined) formData.append('is_urgent', data.is_urgent ? '1' : '0')
    
    if (data.featured_image && data.featured_image instanceof File) {
      formData.append('featured_image', data.featured_image)
    }
    
    if (data.attachments && data.attachments.length > 0) {
      data.attachments.forEach(file => {
        if (file instanceof File) {
          formData.append('attachments[]', file)
        }
      })
    }

    const response = await api.post(`${API_URL}/announcements`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    return response.data
  },

  async updateAnnouncement(id, data) {
    const response = await api.put(`${API_URL}/announcements/${id}`, data)
    return response.data
  },

  async deleteAnnouncement(id) {
    const response = await api.delete(`${API_URL}/announcements/${id}`)
    return response.data
  },

  async toggleUrgent(id) {
    const response = await api.post(`${API_URL}/announcements/${id}/toggle-urgent`)
    return response.data
  },

  async duplicateAnnouncement(id) {
    const response = await api.post(`${API_URL}/announcements/${id}/duplicate`)
    return response.data
  },

  async bulkDelete(data) {
    const response = await api.post(`${API_URL}/announcements/bulk-delete`, data)
    return response.data
  },

  async bulkUpdateStatus(data) {
    const response = await api.post(`${API_URL}/announcements/bulk-update-status`, data)
    return response.data
  },

  async getTrashedAnnouncements(params = {}) {
    const response = await api.get(`${API_URL}/announcements/trashed`, { 
      params: {
        ...params,
        _t: Date.now()
      }
    })
    return response.data
  },

  // Restore single announcement
  async restoreAnnouncement(id) {
    const response = await api.post(`${API_URL}/announcements/${id}/restore`)
    return response.data
  },

  // Permanently delete single announcement
  async forceDeleteAnnouncement(id) {
    const response = await api.delete(`${API_URL}/announcements/${id}/force-delete`)
    return response.data
  },

  // Bulk restore announcements
  async bulkRestore(data) {
    const response = await api.post(`${API_URL}/announcements/bulk-restore`, data)
    return response.data
  },

  // Bulk permanently delete announcements
  async bulkForceDelete(data) {
    const response = await api.post(`${API_URL}/announcements/bulk-force-delete`, data)
    return response.data
  },

  // Empty trash (permanently delete all)
  async emptyTrash() {
    const response = await api.post(`${API_URL}/announcements/empty-trash`)
    return response.data
  }
}