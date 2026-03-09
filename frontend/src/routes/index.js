import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home.vue";
import News from "@/pages/News.vue";
import About from "@/pages/About.vue";
import Services from "@/pages/Services.vue";
import Login from "@/pages/admin/Login.vue";

// Admin routes
import AdminDashboard from "@/pages/admin/Dashboard.vue";
import AdminUsers from "@/pages/admin/Users.vue";
import Announcement from "@/pages/admin/Announcement.vue";
import Complaints from "@/pages/admin/Complaints.vue";
import Document from "@/pages/admin/Document.vue";
import Reports from "@/pages/admin/Reports.vue";
import Resident from "@/pages/admin/Resident.vue";
import ServiceRequest from "@/pages/admin/ServiceRequest.vue";
import Settings from "@/pages/admin/Settings.vue";

const routes = [
  // Public routes
  {
    path: "/",
    name: "home",
    component: Home,
  },
  {
    path: "/about",
    name: "about",
    component: About,
  },
  {
    path: "/services",
    name: "services",
    component: Services,
  },
  {
    path: "/news",
    name: "news",
    component: News,
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: { guest: true },
  },

  // Admin routes
  {
    path: "/admin",
    component: () => import("@/layout/admin/Layout.vue"),
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        name: "admin.dashboard",
        component: AdminDashboard,
        meta: { title: "Dashboard" },
      },
      {
        path: "users",
        name: "admin.users",
        component: AdminUsers,
        meta: { title: "Users" },
      },
      {
        path: "announcements",
        name: "admin.announcements",
        component: Announcement,
        meta: { title: "Announcements" },
      },
      {
        path: "complaints",
        name: "admin.complaints",
        component: Complaints,
        meta: { title: "Complaints" },
      },
      {
        path: "documents",
        name: "admin.documents",
        component: Document,
        meta: { title: "Documents" },
      },
      {
        path: "residents",
        name: "admin.residents",
        component: Resident,
        meta: { title: "Residents" },
      },
      {
        path: "service-requests",
        name: "admin.service-requests",
        component: ServiceRequest,
        meta: { title: "Service Requests" },
      },
      {
        path: "reports",
        name: "admin.reports",
        component: Reports,
        meta: { title: "Reports" },
      },
      {
        path: "settings",
        name: "admin.settings",
        component: Settings,
        meta: { title: "Settings" },
      }
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

// Updated navigation guard - no more next() calls
router.beforeEach((to, from) => {
  const isAuthenticated =
    localStorage.getItem("auth_token") || sessionStorage.getItem("auth_token");

  // If route requires auth and user is not authenticated
  if (
    to.matched.some((record) => record.meta.requiresAuth) &&
    !isAuthenticated
  ) {
    return { name: "login", query: { redirect: to.fullPath } };
  }

  // If route is for guests only (login) and user is already authenticated
  if (to.matched.some((record) => record.meta.guest) && isAuthenticated) {
    return { name: "admin.dashboard" };
  }

  // Allow access
  return true;
});

export default router;
