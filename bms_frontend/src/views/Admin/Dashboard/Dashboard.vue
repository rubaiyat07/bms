<template>
  <div class="admin-dashboard">
    <!-- <div class="page-header">
      <h1>Admin Dashboard</h1>
      <p>Overview of system performance and key metrics</p>
    </div> -->

    <div class="dashboard-content">
      <!-- Main Content -->
      <div class="main-content">
        <!-- Key Metrics Cards -->
        <div class="metrics-grid">
          <div class="metric-card">
            <div class="metric-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div class="metric-content">
              <h3>{{ metrics.total_branches }}</h3>
              <p>Total Branches</p>
              <span class="metric-change positive">+{{ metrics.new_branches_this_month }} this month</span>
            </div>
          </div>

          <div class="metric-card">
            <div class="metric-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div class="metric-content">
              <h3>{{ metrics.total_employees }}</h3>
              <p>Total Employees</p>
              <span class="metric-change positive">+{{ metrics.new_employees_this_month }} this month</span>
            </div>
          </div>

          <div class="metric-card">
            <div class="metric-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="metric-content">
              <h3>{{ metrics.active_projects }}</h3>
              <p>Active Projects</p>
              <span class="metric-change">{{ metrics.project_completion_rate }}% completion rate</span>
            </div>
          </div>

          <div class="metric-card">
            <div class="metric-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="metric-content">
              <h3>${{ metrics.total_budget?.toLocaleString() }}</h3>
              <p>Total Budget</p>
              <span class="metric-change">{{ metrics.budget_utilization }}% utilized</span>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
          <BranchPerformanceChart />
          <div class="chart-container">
            <h3>Project Status Overview</h3>
            <div class="chart-placeholder">
              <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
              </svg>
              <p>Chart will be implemented with Chart.js</p>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="activities-section">
          <h2>Recent Activities</h2>
          <div class="activities-list">
            <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
              <div class="activity-icon">
                <svg v-if="activity.type === 'user_created'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <svg v-else-if="activity.type === 'project_created'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <svg v-else-if="activity.type === 'branch_created'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <div class="activity-content">
                <p>{{ activity.description }}</p>
                <span class="activity-time">{{ formatTime(activity.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="sidebar">
        <UserDetailsCard />
        <QuickActionsCard />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import UserDetailsCard from './UserDetailsCard.vue'
import QuickActionsCard from './QuickActionsCard.vue'
import BranchPerformanceChart from './BranchPerformanceChart.vue'

const metrics = ref({
  total_branches: 0,
  new_branches_this_month: 0,
  total_employees: 0,
  new_employees_this_month: 0,
  active_projects: 0,
  project_completion_rate: 0,
  total_budget: 0,
  budget_utilization: 0
})

const recentActivities = ref([])

const loadMetrics = async () => {
  try {
    const response = await api.get('/admin/dashboard/metrics')
    if (response.success) {
      metrics.value = response.data
    }
  } catch (error) {
    console.error('Error loading metrics:', error)
  }
}

const loadRecentActivities = async () => {
  try {
    const response = await api.get('/admin/dashboard/activities')
    if (response.success) {
      recentActivities.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading activities:', error)
  }
}

const formatTime = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now - date) / (1000 * 60 * 60))

  if (diffInHours < 1) return 'Just now'
  if (diffInHours < 24) return `${diffInHours} hours ago`
  return date.toLocaleDateString()
}

onMounted(async () => {
  await Promise.all([
    loadMetrics(),
    loadRecentActivities()
  ])
})
</script>

<style scoped>
.admin-dashboard {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  color: #384e74;
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #9CA3AF;
  font-size: 1.125rem;
}

.dashboard-content {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 2rem;
  align-items: start;
}

.main-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.sidebar {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Metrics Grid */
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.metric-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
}

.metric-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
}

.metric-icon {
  color: #384e74;
  flex-shrink: 0;
}

.metric-content h3 {
  color: #384e74;
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
}

.metric-content p {
  color: #9CA3AF;
  font-size: 0.875rem;
  margin: 0.25rem 0 0 0;
}

.metric-change {
  font-size: 0.75rem;
  font-weight: 500;
  margin-top: 0.25rem;
  display: block;
}

.metric-change.positive {
  color: #22C55E;
}

/* Charts Section */
.charts-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.chart-container {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.chart-container h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.chart-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  color: #9CA3AF;
  text-align: center;
}

.chart-placeholder svg {
  margin-bottom: 1rem;
}

.chart-placeholder p {
  font-size: 0.875rem;
}

/* Activities Section */
.activities-section h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.activities-list {
  background: white;
  border-radius: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  overflow: hidden;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
  transition: background-color 0.3s ease;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-item:hover {
  background: rgba(56, 78, 116, 0.02);
}

.activity-icon {
  color: #384e74;
  flex-shrink: 0;
}

.activity-content p {
  color: #384e74;
  font-size: 0.875rem;
  margin: 0;
}

.activity-time {
  color: #9CA3AF;
  font-size: 0.75rem;
  margin-top: 0.25rem;
  display: block;
}

/* Actions Section */
.actions-section h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.action-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  text-decoration: none;
  transition: all 0.3s ease;
}

.action-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
  box-shadow: 0 5px 15px rgba(56, 78, 116, 0.1);
}

.action-icon {
  color: #384e74;
  flex-shrink: 0;
}

.action-content h3 {
  color: #384e74;
  font-size: 1.125rem;
  font-weight: 600;
  margin: 0;
}

.action-content p {
  color: #9CA3AF;
  font-size: 0.875rem;
  margin: 0.25rem 0 0 0;
}

@media (max-width: 1024px) {
  .dashboard-content {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .sidebar {
    order: -1;
  }

  .metrics-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  }

  .charts-section {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .admin-dashboard {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .metrics-grid {
    grid-template-columns: 1fr;
  }

  .actions-grid {
    grid-template-columns: 1fr;
  }

  .activity-item {
    padding: 1rem;
  }

  .action-card {
    padding: 1rem;
  }
}
</style>
