import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'
import LandingPage from '../views/LandingPage/LandingPage.vue'

const routes = [
  {
    path: '/',
    name: 'LandingPage',
    component: LandingPage
  },
  {
    path: '/features',
    name: 'Features',
    component: () => import('../views/Features.vue')
  },
  {
    path: '/pricing',
    name: 'Pricing',
    component: () => import('../views/Pricing.vue')
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('../views/About.vue')
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('../views/Contact.vue')
  },
  {
    path: '/finance',
    name: 'Finance',
    component: () => import('../views/Finance.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Auth/Login.vue')
  },

  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: () => import('../views/Admin/Dashboard/Dashboard.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Admin Dashboard' }
  },
  {
    path: '/admin/employees',
    name: 'AdminEmployees',
    component: () => import('../views/Admin/Employee/Employees.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Employee Management' }
  },
  {
    path: '/admin/employees/create',
    name: 'AdminEmployeeCreate',
    component: () => import('../views/Admin/Employee/EmployeeForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Create Employee' }
  },
  {
    path: '/admin/employees/:id',
    name: 'AdminEmployeeShow',
    component: () => import('../views/Admin/Employee/EmployeeShow.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Employee Details' }
  },
  {
    path: '/admin/employees/:id/edit',
    name: 'AdminEmployeeEdit',
    component: () => import('../views/Admin/Employee/EmployeeForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Edit Employee' }
  },
  {
    path: '/admin/branches',
    name: 'AdminBranches',
    component: () => import('../views/Admin/Branch/Branches.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Branch Management' }
  },
  {
    path: '/admin/branches/create',
    name: 'AdminBranchCreate',
    component: () => import('../views/Admin/Branch/BranchForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Create Branch' }
  },
  {
    path: '/admin/branches/:id',
    name: 'AdminBranchShow',
    component: () => import('../views/Admin/Branch/BranchShow.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Branch Details' }
  },
  {
    path: '/admin/branches/:id/edit',
    name: 'AdminBranchEdit',
    component: () => import('../views/Admin/Branch/BranchForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Edit Branch' }
  },
  {
    path: '/admin/departments',
    name: 'AdminDepartments',
    component: () => import('../views/Admin/Department/Departments.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Department Management' }
  },
  {
    path: '/admin/departments/create',
    name: 'AdminDepartmentCreate',
    component: () => import('../views/Admin/Department/DepartmentForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Create Department' }
  },
  {
    path: '/admin/departments/:id',
    name: 'AdminDepartmentShow',
    component: () => import('../views/Admin/Department/DepartmentShow.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Department Details' }
  },
  {
    path: '/admin/departments/:id/edit',
    name: 'AdminDepartmentEdit',
    component: () => import('../views/Admin/Department/DepartmentForm.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Edit Department' }
  },
  {
    path: '/admin/projects',
    name: 'AdminProjects',
    component: () => import('../views/Admin/Project/Projects.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Project Management' }
  },
  {
    path: '/admin/inventory',
    name: 'AdminInventory',
    component: () => import('../views/Admin/Inventory.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Inventory Management' }
  },
  {
    path: '/admin/reports',
    name: 'AdminReports',
    component: () => import('../views/Admin/Reports.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Reports & Analytics' }
  },
  {
    path: '/admin/settings',
    name: 'AdminSettings',
    component: () => import('../views/Admin/Settings.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'System Settings' }
  },
  {
    path: '/admin/finance',
    name: 'AdminFinance',
    component: () => import('../views/Admin/Finance.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'Finance Management' }
  },
  {
    path: '/admin/users',
    name: 'AdminUsers',
    component: () => import('../views/Admin/Users.vue'),
    meta: { requiresAuth: true, layout: 'dashboard', requiresRole: 'admin', title: 'User Management' }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation guard for authentication
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  // Wait for auth initialization if not yet complete
  if (!authStore.isInitialized) {
    await authStore.initialize()
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
    return
  }

  // Check if route requires specific role
  if (to.meta.requiresRole) {
    const userRoles = authStore.user?.roles || []
    const hasRequiredRole = userRoles.some(role => role.name === to.meta.requiresRole)
    if (!hasRequiredRole) {
      next('/login')
      return
    }
  }

  next()
})

export default router
