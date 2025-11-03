<template>
  <div class="admin-projects">
    <div class="page-header">
      <h1>Project Management</h1>
      <p>Oversee all projects across branches</p>
    </div>

    <div class="projects-content">
      <!-- Dashboard Metrics -->
      <div class="projects-stats">
        <div class="stat-card">
          <h3>Total Projects</h3>
          <div class="stat-number">{{ metrics.total_projects }}</div>
        </div>
        <div class="stat-card">
          <h3>Active Projects</h3>
          <div class="stat-number">{{ metrics.active_projects }}</div>
        </div>
        <div class="stat-card">
          <h3>Completed Projects</h3>
          <div class="stat-number">{{ metrics.completed_projects }}</div>
        </div>
        <div class="stat-card">
          <h3>Average Completion Rate</h3>
          <div class="stat-number">{{ metrics.average_completion_rate }}%</div>
        </div>
      </div>

      <!-- Projects Table Component -->
      <ProjectsTable />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import ProjectsTable from './ProjectsTable.vue'
import axios from 'axios'

// Dashboard metrics
const metrics = ref({
  total_projects: 0,
  active_projects: 0,
  completed_projects: 0,
  delayed_projects: 0,
  average_completion_rate: 0
})

const loading = ref(false)

onMounted(() => {
  fetchDashboardMetrics()
})

const fetchDashboardMetrics = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/dashboard/projects')
    metrics.value = response.data
  } catch (error) {
    console.error('Error fetching dashboard metrics:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.admin-projects {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 1rem;
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

.projects-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.projects-filters {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-group label {
  color: #384e74;
  font-weight: 600;
}

.filter-select {
  background: rgba(56, 78, 116, 0.05);
  border: 1px solid rgba(56, 78, 116, 0.1);
  border-radius: 0.5rem;
  padding: 0.5rem;
  color: #636a75;
  font-size: 0.875rem;
}

.filter-select:focus {
  outline: none;
  border-color: rgba(56, 78, 116, 0.3);
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1rem;
}

.project-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  transition: all 0.3s ease;
}

.project-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.08) 0%, rgba(56, 78, 116, 0.04) 100%);
}

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.project-header h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-active {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-completed {
  background-color: rgba(59, 130, 246, 0.1);
  color: #3B82F6;
}

.status-on-hold {
  background-color: rgba(245, 158, 11, 0.1);
  color: #F59E0B;
}

.status-cancelled {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.project-details p {
  color: #636a75;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.project-details strong {
  color: #384e74;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: rgba(56, 78, 116, 0.1);
  border-radius: 4px;
  margin: 0.5rem 0;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.progress-text {
  color: #9CA3AF;
  font-size: 0.75rem;
  text-align: center;
}

.project-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}

.projects-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  text-align: center;
}

.stat-card h3 {
  color: #9CA3AF;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.stat-number {
  color: #384e74;
  font-size: 2rem;
  font-weight: 700;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  color: #0F1419;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 78, 116, 0.3);
}

.btn-secondary {
  background: transparent;
  border: 2px solid rgba(56, 78, 116, 0.3);
  color: #384e74;
}

.btn-secondary:hover {
  background: rgba(56, 78, 116, 0.1);
  border-color: rgba(56, 78, 116, 0.5);
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
}

@media (max-width: 768px) {
  .admin-projects {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .projects-filters {
    flex-direction: column;
    align-items: flex-start;
  }

  .projects-grid {
    grid-template-columns: 1fr;
  }

  .projects-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .project-actions {
    flex-direction: column;
  }
}
</style>
