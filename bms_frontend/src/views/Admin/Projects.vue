<template>
  <div class="admin-projects">
    <div class="page-header">
      <h1>Project Management</h1>
      <p>Oversee all projects across branches</p>
    </div>

    <div class="projects-content">
      <div class="projects-filters">
        <div class="filter-group">
          <label>Status:</label>
          <select v-model="statusFilter" class="filter-select">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="on-hold">On Hold</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Branch:</label>
          <select v-model="branchFilter" class="filter-select">
            <option value="">All Branches</option>
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.name }}
            </option>
          </select>
        </div>
        <button @click="clearFilters" class="btn btn-secondary">Clear Filters</button>
      </div>

      <div class="projects-grid">
        <div v-for="project in filteredProjects" :key="project.id" class="project-card">
          <div class="project-header">
            <h3>{{ project.name }}</h3>
            <span :class="`status-badge status-${project.status}`">
              {{ project.status }}
            </span>
          </div>
          <div class="project-details">
            <p><strong>Branch:</strong> {{ project.branch }}</p>
            <p><strong>Manager:</strong> {{ project.manager }}</p>
            <p><strong>Team Size:</strong> {{ project.team_size }}</p>
            <p><strong>Start Date:</strong> {{ formatDate(project.start_date) }}</p>
            <p><strong>End Date:</strong> {{ formatDate(project.end_date) }}</p>
            <div class="progress-bar">
              <div class="progress-fill" :style="{ width: project.progress + '%' }"></div>
            </div>
            <p class="progress-text">{{ project.progress }}% Complete</p>
          </div>
          <div class="project-actions">
            <button @click="viewProject(project)" class="btn btn-sm btn-primary">View Details</button>
            <button @click="editProject(project)" class="btn btn-sm btn-secondary">Edit</button>
          </div>
        </div>
      </div>

      <div class="projects-stats">
        <div class="stat-card">
          <h3>Total Projects</h3>
          <div class="stat-number">{{ projects.length }}</div>
        </div>
        <div class="stat-card">
          <h3>Active Projects</h3>
          <div class="stat-number">{{ activeProjectsCount }}</div>
        </div>
        <div class="stat-card">
          <h3>Completed Projects</h3>
          <div class="stat-number">{{ completedProjectsCount }}</div>
        </div>
        <div class="stat-card">
          <h3>Average Progress</h3>
          <div class="stat-number">{{ averageProgress }}%</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Mock data - replace with API calls
const projects = ref([
  {
    id: 1,
    name: 'Website Redesign',
    branch: 'Main Branch',
    manager: 'John Doe',
    team_size: 5,
    status: 'active',
    start_date: '2024-01-15',
    end_date: '2024-03-15',
    progress: 75
  },
  {
    id: 2,
    name: 'Mobile App Development',
    branch: 'North Branch',
    manager: 'Jane Smith',
    team_size: 8,
    status: 'active',
    start_date: '2024-02-01',
    end_date: '2024-05-01',
    progress: 45
  },
  {
    id: 3,
    name: 'Database Migration',
    branch: 'South Branch',
    manager: 'Bob Johnson',
    team_size: 3,
    status: 'completed',
    start_date: '2023-11-01',
    end_date: '2024-01-15',
    progress: 100
  }
])

const branches = ref([
  { id: 1, name: 'Main Branch' },
  { id: 2, name: 'North Branch' },
  { id: 3, name: 'South Branch' }
])

const statusFilter = ref('')
const branchFilter = ref('')

const filteredProjects = computed(() => {
  return projects.value.filter(project => {
    const statusMatch = !statusFilter.value || project.status === statusFilter.value
    const branchMatch = !branchFilter.value || project.branch === branches.value.find(b => b.id == branchFilter.value)?.name
    return statusMatch && branchMatch
  })
})

const activeProjectsCount = computed(() => {
  return projects.value.filter(project => project.status === 'active').length
})

const completedProjectsCount = computed(() => {
  return projects.value.filter(project => project.status === 'completed').length
})

const averageProgress = computed(() => {
  if (projects.value.length === 0) return 0
  const total = projects.value.reduce((sum, project) => sum + project.progress, 0)
  return Math.round(total / projects.value.length)
})

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

const clearFilters = () => {
  statusFilter.value = ''
  branchFilter.value = ''
}

const viewProject = (project) => {
  console.log('View project:', project)
  // Implement view project logic
}

const editProject = (project) => {
  console.log('Edit project:', project)
  // Implement edit project logic
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
