<template>
  <div class="user-details-card">
    <h3>User Details</h3>
    <div class="user-info">
      <div class="info-item">
        <label>Name:</label>
        <span>{{ user?.name || 'Not provided' }}</span>
      </div>
      <div class="info-item">
        <label>Employee ID:</label>
        <span>{{ user?.employee_id || 'Not assigned' }}</span>
      </div>
      <div class="info-item">
        <label>Role:</label>
        <span>{{ user?.roles?.[0]?.display_name || user?.roles?.[0]?.name || 'No role' }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="js">
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const user = computed(() => {
  // Get user data from localStorage to ensure we have the latest data
  const userData = localStorage.getItem('user_data')
  return userData ? JSON.parse(userData) : authStore.user
})
</script>

<style scoped>
.user-details-card {
  background: linear-gradient(135deg, rgba(87, 109, 151, 0.267) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 0.5rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
  margin-bottom: 1rem;
}

.user-details-card h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
}

.info-item:last-child {
  border-bottom: none;
}

.info-item label {
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
}

.info-item span {
  color: #636a75;
  font-size: 0.875rem;
}
</style>
