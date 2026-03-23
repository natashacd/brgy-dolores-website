import api from "@/api/api";

const visitorService = {
  getVisitorCount() {
    return api.get("/api/visitor-count");
  },

  trackVisitor(pageUrl) {
    return api.post("/api/visitor/track", { page_url: pageUrl });
  },
};

export default visitorService;
