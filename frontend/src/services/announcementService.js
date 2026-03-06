import axios from 'axios'

const API_URL = '/api/admin'

export const announcementService = {
  async getAnnouncements(params = {}) {
    const response = await axios.get(`${API_URL}/announcements`, { params })
    return response.data
  },

  async getAnnouncement(id) {
    const response = await axios.get(`${API_URL}/announcements/${id}`)
    return response.data
  },

  async createAnnouncement(data) {
    const formData = new FormData()
    
    Object.keys(data).forEach(key => {
      if (key === 'attachments' && data[key]) {
        data[key].forEach(file => {
          formData.append('attachments[]', file)
        })
      } else if (key === 'audience') {
        formData.append(key, JSON.stringify(data[key]))
      } else if (data[key] !== null && data[key] !== undefined) {
        formData.append(key, data[key])
      }
    })

    const response = await axios.post(`${API_URL}/announcements`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    return response.data
  },

  async updateAnnouncement(id, data) {
    const response = await axios.put(`${API_URL}/announcements/${id}`, data)
    return response.data
  },

  async deleteAnnouncement(id) {
    const response = await axios.delete(`${API_URL}/announcements/${id}`)
    return response.data
  },

  async togglePin(id) {
    const response = await axios.post(`${API_URL}/announcements/${id}/pin`)
    return response.data
  },

  async uploadAttachment(id, file) {
    const formData = new FormData()
    formData.append('attachment', file)

    const response = await axios.post(`${API_URL}/announcements/${id}/attachments`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    return response.data
  },

  async deleteAttachment(id) {
    const response = await axios.delete(`${API_URL}/announcements/attachments/${id}`)
    return response.data
  },

  async getStats() {
    const response = await axios.get(`${API_URL}/announcements/stats`)
    return response.data
  }
}