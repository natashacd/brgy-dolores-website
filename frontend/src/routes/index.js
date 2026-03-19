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
import ApprovedRequests from "@/pages/admin/ApprovedRequests.vue";
import ReleasedRequests from "@/pages/admin/ReleasedRequests.vue";
import Reports from "@/pages/admin/Reports.vue";
import Resident from "@/pages/admin/Resident.vue";
import AdminServiceRequest from "@/pages/admin/ServiceRequest.vue";
import DisapprovedRequests from "@/pages/admin/DisapprovedRequests.vue";
import Settings from "@/pages/admin/Settings.vue";

// Resident routes
import ResidentDashboard from "@/pages/resident/Dashboard.vue";
import ResidentServiceRequest from "@/pages/resident/ServiceRequest.vue";
import ResidentComplaints from "@/pages/resident/Complaints.vue";

// Lupon routes
import LuponComplaints from "@/pages/lupon/Complaints.vue";

// Secretary routes
import SecretaryResident from "@/pages/secretary/Resident.vue";

const ADMIN_ROLES = ['admin', 'punong barangay', 'treasurer', 'kagawad', 'sk chairman', 'health worker'];

const LUPON_ROLES = ['lupon'];

const routes = [
  // Public routes
  {
    path: "/",
    component: () => import("@/layout/Layout.vue"),
    children: [
      {
        path: "",
        name: "home",
        component: Home,
        meta: { title: "Home" }
      },
      {
        path: "about",
        name: "about",
        component: About,
        meta: { title: "About Us" }
      },
      {
        path: "services",
        name: "services",
        component: Services,
        meta: { title: "Services" }
      },
      {
        path: "news",
        name: "news",
        component: News,
        meta: { title: "News" }
      },
    ],
  },

  // Resident routes
  {
    path: "/resident",
    component: () => import("@/layout/resident/Layout.vue"),
    meta: { requiresAuth: true, role: 'resident' },
    children: [
      {
        path: "dashboard",
        name: "resident.dashboard",
        component: ResidentDashboard,
        meta: { title: "Dashboard" },
      },
      {
        path: "service-requests",
        name: "resident.service-requests",
        component: ResidentServiceRequest,
        meta: { title: "Service Requests" },
      },
      {
        path: "complaints",
        name: "resident.complaints",
        component: ResidentComplaints,
        meta: { title: "Complaints" },
      }
    ],
  },

  // Lupon routes
  {
    path: "/lupon",
    component: () => import("@/layout/lupon/Layout.vue"),
    meta: { requiresAuth: true, role: 'lupon' },
    children: [
      {
        path: "",                                    // ← ADD THIS
        redirect: { name: "lupon.residents" }        // ← ADD THIS
      },
      {
        path: "residents",
        name: "lupon.residents",
        component: SecretaryResident,
        meta: { title: "Resident Management" },
      },
      {
        path: "complaints",
        name: "lupon.complaints",
        component: LuponComplaints,
        meta: { title: "Lupon Cases" },
      },
    ],
  },

  // Secretary routes
  {
    path: "/secretary",
    component: () => import("@/layout/admin/Layout.vue"),
    meta: { requiresAuth: true, role: 'secretary' },
    children: [
      {
        path: "",
        redirect: { name: "secretary.residents" }
      },
      {
        path: "residents",
        name: "secretary.residents",
        component: SecretaryResident,
        meta: { title: "Resident Management" },
      },
      {
        path: "service-requests",
        name: "secretary.service-requests",
        component: AdminServiceRequest,
        meta: { title: "Service Requests" },
      },
      {
        path: "service-requests/approved",
        name: "secretary.service-requests.approved",
        component: ApprovedRequests,
        meta: { title: "Approved Requests" },
      },
      {
        path: "service-requests/disapproved",
        name: "secretary.service-requests.disapproved",
        component: DisapprovedRequests,
        meta: { title: "Disapproved Requests" },
      },
      {
        path: "service-requests/released",
        name: "secretary.service-requests.released",
        component: ReleasedRequests,
        meta: { title: "Released Requests" },
      },
    ]
  },

  // Admin routes
  {
    path: "/admin",
    component: () => import("@/layout/admin/Layout.vue"),
    meta: { requiresAuth: true, role: 'admin' },
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
        meta: { title: "Barangay Officials" },
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
        meta: { title: "Complaints & Disputes" },
      },
      {
        path: "residents",
        name: "admin.residents",
        component: Resident,
        meta: { title: "Resident Management" },
      },
      {
        path: "service-requests",
        name: "admin.service-requests",
        component: AdminServiceRequest,
        meta: { title: "Service Requests" },
      },
      {
        path: "service-requests/approved",
        name: "admin.service-requests.approved",
        component: ApprovedRequests,
        meta: { title: "Approved Requests" },
      },
      {
        path: "service-requests/disapproved",
        name: "admin.service-requests.disapproved",
        component: DisapprovedRequests,
        meta: { title: "Disapproved Requests" },
      },
      {
        path: "service-requests/released",
        name: "admin.service-requests.released",
        component: ReleasedRequests,
        meta: { title: "Released Requests" },
      },
      {
        path: "reports",
        name: "admin.reports",
        component: Reports,
        meta: { title: "Reports & Analytics" },
      },
      {
        path: "settings",
        name: "admin.settings",
        component: Settings,
        meta: { title: "Settings" },
      }
    ],
  },

  {
    path: "/login",
    name: "login",
    component: Login,
    meta: { guest: true },
  },

  // 404 / catch-all
  {
    path: "/:pathMatch(.*)*",
    name: "not-found",
    redirect: () => {
      const isAuthenticated = localStorage.getItem("auth_token");
      const userRole = localStorage.getItem("user_role")?.toLowerCase();

      if (!isAuthenticated) return { name: "login" };
      if (userRole === "resident") return { name: "resident.dashboard" };
      if (userRole === "lupon") return { name: "lupon.residents" };
      if (userRole === "secretary") return { name: "secretary.residents" };

      return { name: "admin.dashboard" };
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0 };
  },
});

const hasAdminAccess = (role) => {
  if (!role) return false;
  return ADMIN_ROLES.includes(role.toLowerCase());
};

const hasLuponAccess = (role) => {
  if (!role) return false;
  return LUPON_ROLES.includes(role.toLowerCase());
};

router.beforeEach((to, from) => {
  const isAuthenticated = localStorage.getItem("auth_token") || sessionStorage.getItem("auth_token");
  const userRole = localStorage.getItem("user_role")?.toLowerCase() || '';

  // Require auth
  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    return { name: "login", query: { redirect: to.fullPath } };
  }

  if (isAuthenticated) {

    // --- /admin routes ---
    if (to.path.startsWith('/admin')) {
      if (userRole === 'resident') return { name: "resident.service-requests" };
      if (userRole === 'lupon') return { name: "lupon.residents" };
      if (userRole === 'secretary') return { name: "secretary.residents" };
      if (hasAdminAccess(userRole)) return true;
      return { name: "login" };
    }

    // --- /secretary routes ---
    if (to.path.startsWith('/secretary')) {
      if (userRole === 'secretary') return true;
      if (userRole === 'resident') return { name: "resident.dashboard" };
      if (userRole === 'lupon') return { name: "lupon.residents" };
      if (hasAdminAccess(userRole)) return { name: "admin.dashboard" };
      return { name: "login" };
    }

    // --- /lupon routes ---
    if (to.path.startsWith('/lupon')) {
      if (userRole === 'lupon') return true;
      if (userRole === 'resident') return { name: "resident.dashboard" };
      if (userRole === 'secretary') return { name: "secretary.residents" };
      if (hasAdminAccess(userRole)) return { name: "admin.dashboard" };
      return { name: "login" };
    }

    // --- /resident routes ---
    if (to.path.startsWith('/resident')) {
      if (userRole === 'resident') return true;
      if (userRole === 'lupon') return { name: "lupon.residents" };
      if (userRole === 'secretary') return { name: "secretary.residents" };
      if (hasAdminAccess(userRole)) return { name: "admin.dashboard" };
      return { name: "login" };
    }
  }

  // Redirect already-logged-in users away from /login
  if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
    if (userRole === 'resident') return { name: "resident.service-requests" };
    if (userRole === 'lupon') return { name: "lupon.residents" };
    if (userRole === 'secretary') return { name: "secretary.residents" };
    if (hasAdminAccess(userRole)) return { name: "admin.dashboard" };
    return { name: "login" };
  }

  return true;
});

export default router;