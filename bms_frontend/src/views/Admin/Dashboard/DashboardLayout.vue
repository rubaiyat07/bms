<template>
  <div class="dashboard-layout">
    <!-- Admin Sidebar -->
    <AdminSidebar v-if="hasRole('admin')" />

    <!-- Main Content Area -->
    <div class="main-content" :class="{ 'with-sidebar': hasRole('admin') }">
      <!-- Top Header -->
      <header class="dashboard-header">
        <div class="header-left">
          <button v-if="hasRole('admin')" @click="toggleSidebar" class="sidebar-toggle-mobile">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
          <h1 class="page-title">{{ pageTitle }}</h1>
        </div>

        <div class="header-right">
          <NotificationDropdown />
          <UserMenu />
        </div>
      </header>

      <!-- Page Content -->
      <main class="page-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { mapState } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import AdminSidebar from '../../../components/layout/AdminSidebar.vue'
import NotificationDropdown from '../../../components/common/NotificationDropdown.vue'
import UserMenu from '../../../components/common/UserMenu.vue'

const route = useRoute()
const authStore = useAuthStore()

const sidebarCollapsed = ref(false)

const hasRole = (roleName) => {
  return authStore.user && authStore.user.roles && authStore.user.roles.some(role => role.name === roleName)
}

const pageTitle = computed(() => {
  return route.meta.title || 'Dashboard'
})

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value
}
</script>

<style scoped>
.dashboard-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
}

.main-content {
  margin-left: 0;
  transition: margin-left 0.3s ease;
}

.main-content.with-sidebar {
  margin-left: 250px;
}

.dashboard-header {
  background: linear-gradient(135deg, rgb(60, 75, 104) 0%, rgb(189, 212, 255) 100%);
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.sidebar-toggle-mobile {
  display: none;
  background: none;
  border: none;
  color: #BDD4FF;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: background-color 0.2s;
}

.sidebar-toggle-mobile:hover {
  background-color: rgba(189, 212, 255, 0.1);
}

.page-title {
  color: #BDD4FF;
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.page-content {
  padding: 1rem;
  max-width: 100%;
}

@media (max-width: 768px) {
  .main-content.with-sidebar {
    margin-left: 0;
  }

  .sidebar-toggle-mobile {
    display: block;
  }

  .dashboard-header {
    padding: 1rem;
  }

  .page-content {
    padding: 1rem;
  }

  .page-title {
    font-size: 1.25rem;
  }
}
</style>
