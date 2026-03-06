import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

export default {
  // Visitor Counter
  getVisitorCount() {
    return api.get('/visitor-count')
  },
  
  // Track visitor
  trackVisitor(pageUrl) {
    return api.post('/visitor/track', { page_url: pageUrl })
  }
}