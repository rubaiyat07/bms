<template>
  <div class="department-show">
    <div class="page-header">
      <div class="header-content">
        <div>
          <h1>{{ department.name }}</h1>
          <p>Department Code: {{ department.code }}</p>
        </div>
        <div class="header-actions">
          <router-link :to="`/admin/departments/${department.id}/edit`" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Department
          </router-link>
          <button v-if="isAdmin" @click="showDeleteModal = true" class="btn btn-danger">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete Department
          </button>
          <button @click="$router.go(-1)" class="btn btn-secondary">Back to List</button>
        </div>
      </div>
    </div>

    <div class="department-content">
      <!-- Department Overview -->
      <div class="overview-section">
        <h2>Department Overview</h2>
        <div class="overview-grid">
          <div class="overview-card">
            <div class="card-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <div class="card-content">
              <h3>{{ department.users?.length || 0 }}</h3>
              <p>Total Employees</p>
            </div>
          </div>

          <div class="overview-card">
            <div class="card-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="card-content">
              <h3>{{ department.projects?.length || 0 }}</h3>
              <p>Active Projects</p>
            </div>
          </div>

          <div class="overview-card">
            <div class="card-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="card-content">
              <h3>{{ department.performance_level }}</h3>
              <p>Performance Level</p>
            </div>
          </div>

          <div class="overview-card">
            <div class="card-icon">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="card-content">
              <h3>{{ department.created_at ? formatDate(department.created_at) : 'N/A' }}</h3>
              <p>Created Date</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Department Details -->
      <div class="details-section">
        <h2>Department Details</h2>
        <div class="details-grid">
          <div class="detail-item">
            <label>Department Name</label>
            <p>{{ department.name }}</p>
          </div>
          <div class="detail-item">
            <label>Department Code</label>
            <p>{{ department.code }}</p>
          </div>
          <div class="detail-item full-width">
            <label>Description</label>
            <p>{{ department.description || 'No description provided' }}</p>
          </div>
          <div class="detail-item">
            <label>Status</label>
            <span :class="`status-badge status-${department.status}`">{{ department.status }}</span>
          </div>
          <div class="detail-item">
            <label>Department Head</label>
            <p>{{ department.head?.name || 'Not assigned' }}</p>
          </div>
        </div>
      </div>

      <!-- Projects Section -->
      <div class="projects-section">
        <h2>Projects ({{ department.projects?.length || 0 }})</h2>

        <div v-if="department.projects?.length" class="projects-grid">
          <div v-for="project in department.projects" :key="project.id" class="project-card">
            <div class="project-header">
              <h3>{{ project.name }}</h3>
              <span :class="`status-badge status-${project.status}`">{{ project.status }}</span>
            </div>
            <div class="project-details">
              <p><strong>Budget:</strong> ${{ project.budget?.toLocaleString() || 'N/A' }}</p>
              <p><strong>Start Date:</strong> {{ project.start_date ? formatDate(project.start_date) : 'N/A' }}</p>
              <p><strong>End Date:</strong> {{ project.end_date ? formatDate(project.end_date) : 'N/A' }}</p>
              <p><strong>Progress:</strong> {{ project.progress || 0 }}%</p>
            </div>
            <div class="project-actions">
              <router-link :to="`/admin/projects/${project.id}`" class="btn btn-sm btn-primary">View</router-link>
              <router-link :to="`/admin/projects/${project.id}/edit`" class="btn btn-sm btn-secondary">Edit</router-link>
            </div>
          </div>
        </div>
        <div v-else class="empty-state">
          <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3>No Projects Yet</h3>
          <p>This department doesn't have any projects assigned yet.</p>
        </div>
      </div>

      <!-- Employees Section -->
      <div class="employees-section">
        <h2>Employees ({{ department.users?.length || 0 }})</h2>

        <div v-if="department.users?.length" class="employees-grid">
          <div v-for="user in department.users" :key="user.id" class="employee-card">
            <div class="employee-avatar">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="employee-info">
              <h3>{{ user.name }}</h3>
              <p>{{ user.email }}</p>
              <span :class="`role-badge role-${user.role}`">{{ user.role }}</span>
            </div>
            <div class="employee-actions">
              <router-link :to="`/admin/users/${user.id}`" class="btn btn-sm btn-primary">View</router-link>
              <router-link :to="`/admin/users/${user.id}/edit`" class="btn btn-sm btn-secondary">Edit</router-link>
            </div>
          </div>
        </div>
        <div v-else class="empty-state">
          <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
          <h3>No Employees Yet</h3>
          <p>This department doesn't have any employees assigned yet.</p>
        </div>
      </div>
    </div>

    <DeleteDepartmentModal
      :show="showDeleteModal"
      :department="department"
      @close="showDeleteModal = false"
      @confirm="handleDeleteDepartment"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { api } from '@/services/api'
import DeleteDepartmentModal from './DeleteDepartmentModal.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const { isInitialized, user } = storeToRefs(authStore)

const department = ref({})
const loading = ref(false)
const showDeleteModal = ref(false)

const isAdmin = computed(() => isInitialized.value && user.value?.role === 'admin')

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

const fetchDepartment = async () => {
  try {
    loading.value = true
    const response = await api.get(`/departments/${route.params.id}`)
    if (response.success) {
      department.value = response.data
    }
  } catch (error) {
    console.error('Error fetching department:', error)
  } finally {
    loading.value = false
  }
}

const handleDeleteDepartment = async (reason: string) => {
  try {
    const response = await api.delete(`/departments/${department.value.id}`, { reason })
    if (response.success) {
      router.push('/admin/departments')
    }
  } catch (error) {
    console.error('Error deleting department:', error)
  }
}

onMounted(() => {
  fetchDepartment()
})
</script>

<style scoped>
.department-show {
  padding: 1rem;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
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

.header-actions {
  display: flex;
  gap: 1rem;
}

.department-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

/* Overview Section */
.overview-section h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.overview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.overview-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
}

.overview-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
}

.card-icon {
  color: #384e74;
}

.card-content h3 {
  color: #384e74;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.card-content p {
  color: #9CA3AF;
  font-size: 0.875rem;
  font-weight: 500;
}

/* Details Section */
.details-section h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.detail-item {
  background: white;
  border-radius: 0.5rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.detail-item.full-width {
  grid-column: 1 / -1;
}

.detail-item label {
  font-weight: 600;
  color: #384e74;
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.detail-item p {
  color: #636a75;
  font-size: 0.875rem;
  line-height: 1.5;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-active {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-inactive {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

/* Projects and Employees Sections */
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

.projects-grid,
.employees-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem;
}

.project-card,
.employee-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  transition: all 0.3s ease;
}

.project-card:hover,
.employee-card:hover {
  transform: translateY(-2px);
  border-color: rgba(56, 78, 116, 0.3);
}

.project-header,
.employee-info h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.project-details p,
.employee-info p {
  color: #636a75;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.project-details strong,
.employee-info strong {
  color: #384e74;
}

.project-actions,
.employee-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}

.employee-avatar {
  color: #384e74;
  margin-bottom: 1rem;
}

.role-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
}

.role-admin {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.role-manager {
  background-color: rgba(59, 130, 246, 0.1);
  color: #3B82F6;
}

.role-employee {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem;
  color: #9CA3AF;
}

.empty-state h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin: 1rem 0 0.5rem;
}

.empty-state p {
  margin-bottom: 1.5rem;
}

/* Buttons */
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

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .overview-grid {
    grid-template-columns: 1fr;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }

  .projects-grid,
  .employees-grid {
    grid-template-columns: 1fr;
  }

  .section-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }
}
</style>
