<template>
  <div class="admin-departments">
    <!-- Topbar Cards -->
    <div class="topbar-cards">
      <div class="stat-card">
        <h3>Total Departments</h3>
        <div class="stat-number">{{ totalDepartments }} <span class="stat-subtext">({{ activeDepartmentsCount }} active)</span></div>
      </div>
      <div class="stat-card">
        <h3>Department with Highest Employees</h3>
        <div class="stat-number">{{ departmentWithHighestEmployees?.users?.length || 0 }}</div>
        <div class="stat-branch-name">{{ departmentWithHighestEmployees?.name || 'N/A' }}</div>
      </div>
      <div class="stat-card">
        <h3>Department with Highest Projects</h3>
        <div class="stat-number">{{ departmentWithHighestProjects?.projects?.length || 0 }}</div>
        <div class="stat-branch-name">{{ departmentWithHighestProjects?.name || 'N/A' }}</div>
      </div>
      <div class="stat-card top-performers">
        <h3>Top Performing Departments</h3>
        <div class="top-departments-list">
          <div v-for="department in topPerformingDepartments" :key="department.id" class="top-department-item">
            <span class="department-name">{{ department.name }}</span>
            <span :class="`performance-badge performance-${department.performance_level}`">
              {{ department.performance_level }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Departments Table -->
    <div class="departments-section">
      <div class="section-header">
        <h2>All Departments</h2>
        <router-link to="/admin/departments/create" class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add Department
        </router-link>
      </div>

      <div class="departments-table-container">
        <table class="departments-table">
          <thead>
            <tr>
              <th>Basic Information</th>
              <th>Statistics</th>
              <th>Management</th>
              <th>Status & Performance</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="department in departments" :key="department.id">
              <td>
                <div class="department-info">
                  <div class="department-name">{{ department.name }}</div>
                  <div class="department-description">{{ department.description || 'No description' }}</div>
                  <div class="department-code">{{ department.code }}</div>
                </div>
              </td>
              <td>
                <div class="counts-info">
                  <div class="employee-count">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg><span>Employees</span>
                    {{ department.users?.length || 0 }}
                  </div>
                  <div class="project-count">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg><span>Projects</span>
                    {{ department.projects?.length || 0 }}
                  </div>
                </div>
              </td>
              <td>
                <div class="manager-info">
                  <div class="manager-name">{{ department.head?.name || 'Not Assigned' }}</div>
                  <div class="manager-designation">{{ department.head?.designation || 'N/A' }}</div>
                </div>
              </td>
              <td>
                <div class="status-info">
                  <span :class="`status-badge status-${department.status}`">
                    {{ department.status }}
                  </span>
                  <div class="created-date">{{ department.created_at ? formatDate(department.created_at) : 'N/A' }}</div>
                  <span :class="`performance-badge performance-${department.performance_level}`">
                    {{ department.performance_level }}
                  </span>
                </div>
              </td>
              <td>
                <div class="actions">
                  <router-link :to="`/admin/departments/${department.id}`" class="btn btn-sm btn-primary">View</router-link>
                  <router-link :to="`/admin/departments/${department.id}/edit`" class="btn btn-sm btn-secondary">Edit</router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { api } from '@/services/api'

const departments = ref([])
const loading = ref(false)

const totalDepartments = computed(() => departments.value.length)

const activeDepartmentsCount = computed(() => {
  return departments.value.filter(department => department.status === 'active').length
})

const topPerformingDepartments = computed(() => {
  return departments.value
    .filter(department => department.performance_score)
    .sort((a, b) => b.performance_score - a.performance_score)
    .slice(0, 3)
})

const departmentWithHighestEmployees = computed(() => {
  if (departments.value.length === 0) return null
  return departments.value.reduce((max, department) =>
    (department.users?.length || 0) > (max.users?.length || 0) ? department : max
  )
})

const departmentWithHighestProjects = computed(() => {
  if (departments.value.length === 0) return null
  return departments.value.reduce((max, department) =>
    (department.projects?.length || 0) > (max.projects?.length || 0) ? department : max
  )
})

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

const fetchDepartments = async () => {
  try {
    loading.value = true
    const response = await api.get('/departments')
    if (response.success) {
      departments.value = response.data.data
    }
  } catch (error) {
    console.error('Error fetching departments:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchDepartments()
})
</script>

<style scoped>
.admin-departments {
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

/* Topbar Cards */
.topbar-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  text-align: center;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
}

.stat-card h3 {
  color: #384e74;
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

.stat-subtext {
  color: #9CA3AF;
  font-size: 0.875rem;
  font-weight: 500;
}

.stat-branch-name {
  color: #636a75;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.25rem;
}

.top-performers .top-departments-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.top-department-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  background: rgba(56, 78, 116, 0.05);
  border-radius: 0.375rem;
}

.department-name {
  font-weight: 500;
  color: #384e74;
}

/* Departments Table */
.departments-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.section-header h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
}

.departments-table-container {
  overflow-x: auto;
}

.departments-table {
  width: 100%;
  border-collapse: collapse;
}

.departments-table th {
  background: rgba(56, 78, 116, 0.05);
  color: #384e74;
  font-weight: 600;
  padding: 1rem;
  text-align: left;
  border-bottom: 2px solid rgba(56, 78, 116, 0.1);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.departments-table td {
  padding: 1rem;
  border-bottom: 1px solid rgba(56, 78, 116, 0.05);
  vertical-align: top;
}

.department-info .department-name {
  font-weight: 600;
  color: #384e74;
  margin-bottom: 0.25rem;
}

.department-info .department-description {
  color: #636a75;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.department-info .department-code {
  color: #9CA3AF;
  font-size: 0.75rem;
  font-weight: 500;
}

.counts-info {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.employee-count,
.project-count {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #636a75;
  font-size: 0.875rem;
}

.manager-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.manager-name {
  font-weight: 500;
  color: #384e74;
}

.manager-designation {
  color: #636a75;
  font-size: 0.875rem;
}

.status-info {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  width: fit-content;
}

.status-active {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-inactive {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.created-date {
  color: #9CA3AF;
  font-size: 0.75rem;
}

.performance-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
  width: fit-content;
  text-transform: capitalize;
}

.performance-best {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.performance-good {
  background-color: rgba(59, 130, 246, 0.1);
  color: #3B82F6;
}

.performance-average {
  background-color: rgba(251, 191, 36, 0.1);
  color: #F59E0B;
}

.performance-bad {
  background-color: rgba(245, 101, 101, 0.1);
  color: #F56565;
}

.performance-weak {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.actions {
  display: flex;
  gap: 0.5rem;
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
  text-decoration: none;
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

@media (max-width: 1024px) {
  .topbar-cards {
    grid-template-columns: repeat(2, 1fr);
  }

  .departments-table-container {
    overflow-x: auto;
  }

  .departments-table {
    min-width: 800px;
  }
}

@media (max-width: 768px) {
  .admin-departments {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .topbar-cards {
    grid-template-columns: 1fr;
  }

  .section-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .actions {
    flex-direction: column;
  }
}
</style>
