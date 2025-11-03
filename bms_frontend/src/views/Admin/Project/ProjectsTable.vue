<template>
  <div class="projects-table-container">
    <div class="table-header">
      <h3>Projects Overview</h3>
      <div class="table-actions">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search projects..."
          class="search-input"
          @input="handleSearch"
        />
        <select v-model="statusFilter" class="filter-select" @change="handleFilter">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <select v-model="branchFilter" class="filter-select" @change="handleFilter">
          <option value="">All Branches</option>
          <option v-for="branch in branches" :key="branch.id" :value="branch.id">
            {{ branch.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="table-wrapper">
      <table class="projects-table">
        <thead>
          <tr>
            <th @click="sortBy('title')" class="sortable">
              Project Name/Code
              <span v-if="sortField === 'title'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th @click="sortBy('branch.name')" class="sortable">
              Branch/Manager
              <span v-if="sortField === 'branch.name'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th @click="sortBy('progress_percentage')" class="sortable">
              Tasks & Progress
              <span v-if="sortField === 'progress_percentage'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th @click="sortBy('start_date')" class="sortable">
              Start-End Date
              <span v-if="sortField === 'start_date'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th @click="sortBy('priority')" class="sortable">
              Status / Priority
              <span v-if="sortField === 'priority'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th @click="sortBy('performance_score')" class="sortable">
              Performance / Remarks
              <span v-if="sortField === 'performance_score'" class="sort-icon">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in filteredProjects" :key="project.id">
            <td>
              <div class="project-name">
                <strong>{{ project.title }}</strong>
                <small v-if="project.code" class="project-code">{{ project.code }}</small>
              </div>
            </td>
            <td>
              <div class="branch-manager">
                <div class="branch-name">{{ project.branch?.name }}</div>
                <div class="manager-name">{{ project.manager?.name }}</div>
              </div>
            </td>
            <td>
              <div class="tasks-progress">
                <div class="progress-info">{{ project.total_tasks || 0 }} / {{ project.completed_tasks || 0 }} tasks</div>
                <div class="progress-bar">
                  <div class="progress-fill" :style="{ width: project.progress_percentage + '%' }"></div>
                </div>
                <div class="progress-percentage">{{ project.progress_percentage }}%</div>
              </div>
            </td>
            <td>
              <div class="date-range">
                {{ formatDate(project.start_date) }} → {{ formatDate(project.end_date) }}
              </div>
            </td>
            <td>
              <span :class="`status-badge status-${project.status}`">
                {{ project.status.replace('_', ' ') }}
              </span>
              <span :class="`priority-badge priority-${project.priority}`">
                {{ project.priority }}
              </span>
            </td>
            <td>
              <div class="performance-remarks">
                <div class="performance-score">{{ project.performance_score }}/100</div>
                <div class="performance-remark">{{ project.performance_remark }}</div>
              </div>
            </td>
            <td>
              <div class="actions">
                <button @click="viewProject(project)" class="action-btn view-btn" title="View">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
                <button @click="editProject(project)" class="action-btn edit-btn" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button @click="deleteProject(project)" class="action-btn delete-btn" title="Delete">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
                <button @click="generateReport(project)" class="action-btn report-btn" title="Report">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

interface Branch {
  id: number
  name: string
}

interface Manager {
  id: number
  name: string
}

interface Project {
  id: number
  title: string
  code?: string
  description?: string
  branch_id: number
  manager_id: number
  branch?: Branch
  manager?: Manager
  total_tasks?: number
  completed_tasks?: number
  progress_percentage: number
  start_date: string
  end_date: string
  status: string
  priority: string
  performance_score: number
  performance_remark: string
}

const router = useRouter()

const projects = ref<Project[]>([])
const branches = ref<Branch[]>([])
const searchQuery = ref('')
const statusFilter = ref('')
const branchFilter = ref('')
const managerFilter = ref('')
const sortField = ref('title')
const sortDirection = ref('asc')
const loading = ref(false)

const filteredProjects = computed(() => {
  let filtered = [...projects.value]

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(project =>
      project.title.toLowerCase().includes(query) ||
      project.description?.toLowerCase().includes(query) ||
      project.branch?.name.toLowerCase().includes(query) ||
      project.manager?.name.toLowerCase().includes(query)
    )
  }

  // Status filter
  if (statusFilter.value) {
    filtered = filtered.filter(project => project.status === statusFilter.value)
  }

  // Branch filter
  if (branchFilter.value) {
    filtered = filtered.filter(project => project.branch_id == branchFilter.value)
  }

  // Manager filter
  if (managerFilter.value) {
    filtered = filtered.filter(project => project.manager_id == managerFilter.value)
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = getNestedValue(a, sortField.value)
    let bVal = getNestedValue(b, sortField.value)

    if (typeof aVal === 'string') aVal = aVal.toLowerCase()
    if (typeof bVal === 'string') bVal = bVal.toLowerCase()

    if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1
    if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1
    return 0
  })

  return filtered
})

onMounted(() => {
  fetchProjects()
  fetchBranches()
})

const fetchProjects = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/projects', {
      params: {
        include: 'branch,manager,tasks,members'
      }
    })
    projects.value = response.data.data
  } catch (error) {
    console.error('Error fetching projects:', error)
    // Assuming toast is available globally or via plugin
    // this.$toast.error('Failed to load projects')
  } finally {
    loading.value = false
  }
}

const fetchBranches = async () => {
  try {
    const response = await axios.get('/api/branches')
    branches.value = response.data.data
  } catch (error) {
    console.error('Error fetching branches:', error)
  }
}

const sortBy = (field: string): void => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
}

const getNestedValue = (obj: Record<string, unknown>, path: string): unknown => {
  return path.split('.').reduce((current, key) => (current as Record<string, unknown>)?.[key], obj) || ''
}

const formatDate = (date: string | null | undefined): string => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const handleSearch = (): void => {
  // Debounced search can be implemented here
}

const handleFilter = (): void => {
  // Additional filter logic can be implemented here
}

const viewProject = (project: Project): void => {
  router.push(`/admin/projects/${project.id}`)
}

const editProject = (project: Project): void => {
  router.push(`/admin/projects/${project.id}/edit`)
}

const deleteProject = async (project: Project): Promise<void> => {
  if (confirm(`Are you sure you want to delete "${project.title}"?`)) {
    try {
      await axios.delete(`/api/projects/${project.id}`)
      projects.value = projects.value.filter(p => p.id !== project.id)
      // this.$toast.success('Project deleted successfully')
    } catch (error) {
      console.error('Error deleting project:', error)
      // this.$toast.error('Failed to delete project')
    }
  }
}

const generateReport = (project: Project): void => {
  // Implement report generation
  console.log('Generating report for project:', project.title)
  // this.$toast.info('Report generation coming soon')
}
</script>

<style scoped>
.projects-table-container {
  background: white;
  border-radius: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  overflow: hidden;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid rgba(56, 78, 116, 0.1);
  background: rgba(56, 78, 116, 0.02);
}

.table-header h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.table-actions {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.search-input {
  padding: 0.5rem 1rem;
  border: 1px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 0.875rem;
  min-width: 200px;
}

.search-input:focus {
  outline: none;
  border-color: #384e74;
  box-shadow: 0 0 0 3px rgba(56, 78, 116, 0.1);
}

.filter-select {
  padding: 0.5rem 1rem;
  border: 1px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 0.875rem;
  background: white;
}

.filter-select:focus {
  outline: none;
  border-color: #384e74;
}

.table-wrapper {
  overflow-x: auto;
}

.projects-table {
  width: 100%;
  border-collapse: collapse;
}

.projects-table th {
  background: rgba(56, 78, 116, 0.05);
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
  text-align: left;
  padding: 1rem;
  border-bottom: 1px solid rgba(56, 78, 116, 0.1);
  white-space: nowrap;
}

.projects-table th.sortable {
  cursor: pointer;
  user-select: none;
  transition: background-color 0.2s;
}

.projects-table th.sortable:hover {
  background: rgba(56, 78, 116, 0.1);
}

.sort-icon {
  margin-left: 0.5rem;
  font-size: 0.75rem;
}

.projects-table td {
  padding: 1rem;
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
  vertical-align: top;
}

.projects-table tbody tr:hover {
  background: rgba(56, 78, 116, 0.02);
}

.project-name strong {
  color: #384e74;
  display: block;
  font-size: 0.875rem;
}

.project-code {
  color: #9CA3AF;
  font-size: 0.75rem;
}

.branch-manager {
  font-size: 0.875rem;
}

.branch-name {
  color: #384e74;
  font-weight: 500;
}

.manager-name {
  color: #6B7280;
  font-size: 0.75rem;
}

.tasks-progress {
  min-width: 150px;
}

.progress-info {
  font-size: 0.75rem;
  color: #6B7280;
  margin-bottom: 0.25rem;
}

.progress-bar {
  width: 100%;
  height: 6px;
  background: rgba(56, 78, 116, 0.1);
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 0.25rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  border-radius: 3px;
  transition: width 0.3s ease;
}

.progress-percentage {
  font-size: 0.75rem;
  font-weight: 600;
  color: #384e74;
}

.date-range {
  font-size: 0.875rem;
  color: #384e74;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
  margin-right: 0.5rem;
}

.status-pending {
  background: rgba(251, 191, 36, 0.1);
  color: #D97706;
}

.status-in_progress {
  background: rgba(59, 130, 246, 0.1);
  color: #2563EB;
}

.status-completed {
  background: rgba(34, 197, 94, 0.1);
  color: #16A34A;
}

.status-cancelled {
  background: rgba(239, 68, 68, 0.1);
  color: #DC2626;
}

.priority-badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
}

.priority-low {
  background: rgba(156, 163, 175, 0.1);
  color: #6B7280;
}

.priority-medium {
  background: rgba(251, 191, 36, 0.1);
  color: #D97706;
}

.priority-high {
  background: rgba(239, 68, 68, 0.1);
  color: #DC2626;
}

.performance-remarks {
  font-size: 0.875rem;
}

.performance-score {
  color: #384e74;
  font-weight: 600;
}

.performance-remark {
  color: #6B7280;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.view-btn {
  background: rgba(59, 130, 246, 0.1);
  color: #2563EB;
}

.view-btn:hover {
  background: #2563EB;
  color: white;
}

.edit-btn {
  background: rgba(251, 191, 36, 0.1);
  color: #D97706;
}

.edit-btn:hover {
  background: #D97706;
  color: white;
}

.delete-btn {
  background: rgba(239, 68, 68, 0.1);
  color: #DC2626;
}

.delete-btn:hover {
  background: #DC2626;
  color: white;
}

.report-btn {
  background: rgba(34, 197, 94, 0.1);
  color: #16A34A;
}

.report-btn:hover {
  background: #16A34A;
  color: white;
}

@media (max-width: 768px) {
  .table-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .table-actions {
    width: 100%;
    justify-content: space-between;
  }

  .search-input {
    min-width: auto;
    flex: 1;
  }

  .projects-table th,
  .projects-table td {
    padding: 0.5rem;
    font-size: 0.75rem;
  }

  .actions {
    flex-direction: column;
    gap: 0.25rem;
  }

  .action-btn {
    width: 28px;
    height: 28px;
  }
}
</style>
