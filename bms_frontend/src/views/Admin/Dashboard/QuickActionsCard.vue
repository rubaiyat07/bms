<template>
  <div class="quick-actions-card">
    <h3>Quick Actions</h3>
    <div class="actions-list">
      <button @click="updateProfile" class="action-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Update Profile
      </button>
      <button @click="changePassword" class="action-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
        Change Password
      </button>
      <button @click="viewNotifications" class="action-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21V9m0 0l-4 4m4-4l4 4"></path>
        </svg>
        Notifications
      </button>
      <button @click="viewReports" class="action-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        View Reports
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const updateProfile = () => {
  router.push('/profile')
}

const changePassword = () => {
  router.push('/change-password')
}

const viewNotifications = () => {
  router.push('/notifications')
}

const viewReports = () => {
  router.push('/admin/reports')
}
</script>

<style scoped>
.quick-actions-card {
  background: linear-gradient(135deg, rgba(87, 109, 151, 0.267) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 0.5rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
}

.quick-actions-card h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.actions-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.75rem 1rem;
  background: transparent;
  border: 1px solid rgba(189, 212, 255, 0.2);
  border-radius: 0.5rem;
  color: #636a75;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: left;
}

.action-btn:hover {
  background: rgba(90, 106, 134, 0.281);
  border-color: rgba(189, 212, 255, 0.4);
  transform: translateY(-1px);
}

.action-btn svg {
  flex-shrink: 0;
}
</style>
