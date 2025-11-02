<template>
  <div class="admin-employees">
    <!-- <div class="page-header">
      <h1>Employee Management</h1>
      <p>Manage employees, track performance, and oversee organizational structure</p>
    </div> -->

    <!-- Overview Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <div class="stat-content">
          <h3>{{ stats.total_employees }}</h3>
          <p>Total Employees</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="stat-content">
          <h3>{{ stats.active_employees }}</h3>
          <p>Active Employees</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div class="stat-content">
          <h3>{{ stats.total_managers }}</h3>
          <p>Managers</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
        </div>
        <div class="stat-content">
          <h3>{{ stats.new_joins_this_month }}</h3>
          <p>New This Month</p>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
      <div class="search-bar">
        <input
          v-model="filters.search"
          type="text"
          placeholder="Search employees..."
          class="search-input"
        >
        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <div class="filter-controls">
        <select v-model="filters.status" class="filter-select">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="suspended">Suspended</option>
          <option value="on_leave">On Leave</option>
        </select>

        <select v-model="filters.performance_rating" class="filter-select">
          <option value="">All Performance</option>
          <option value="excellent">Excellent</option>
          <option value="good">Good</option>
          <option value="average">Average</option>
          <option value="weak">Weak</option>
        </select>

        <select v-model="filters.branch_id" class="filter-select">
          <option value="">All Branches</option>
          <option v-for="branch in branches" :key="branch.id" :value="branch.id">
            {{ branch.name }}
          </option>
        </select>

        <button @click="resetFilters" class="btn btn-secondary">Reset</button>
      </div>
    </div>

    <!-- Employees Table -->
    <div class="employees-section">
      <div class="section-header">
        <h2>All Employees</h2>
        <button @click="router.push('/admin/employees/create')" class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add Employee
        </button>
      </div>

      <div class="employees-table-container">
        <table class="employees-table">
          <thead>
            <tr>
              <th @click="sortBy('name')" class="sortable">
                Name
                <svg v-if="sortField === 'name'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th @click="sortBy('employee_id')" class="sortable">
                Employee ID
                <svg v-if="sortField === 'employee_id'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th @click="sortBy('designation')" class="sortable">
                Designation
                <svg v-if="sortField === 'designation'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th @click="sortBy('performance_rating')" class="sortable">
                Performance
                <svg v-if="sortField === 'performance_rating'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th>Branch</th>
              <th @click="sortBy('join_date')" class="sortable">
                Join Date
                <svg v-if="sortField === 'join_date'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th>Manager</th>
              <th @click="sortBy('status')" class="sortable">
                Status
                <svg v-if="sortField === 'status'" class="sort-icon" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
              </th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="employee in paginatedEmployees" :key="employee.id">
              <td>{{ employee.name }}</td>
              <td>{{ employee.employee_id }}</td>
              <td>{{ employee.designation || 'N/A' }}</td>
              <td>
                <span :class="`performance-badge performance-${employee.performance_rating}`">
                  {{ employee.performance_rating }}
                </span>
              </td>
              <td>{{ employee.branch?.name || 'N/A' }}</td>
              <td>{{ employee.join_date ? formatDate(employee.join_date) : 'N/A' }}</td>
              <td>{{ employee.manager?.name || 'N/A' }}</td>
              <td>
                <span :class="`status-badge status-${employee.status}`">
                  {{ employee.status.replace('_', ' ') }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button @click="viewEmployee(employee)" class="btn btn-sm btn-info">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                  </button>
                  <button @click="editEmployee(employee)" class="btn btn-sm btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button @click="deleteEmployee(employee)" class="btn btn-sm btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="btn btn-secondary"
        >
          Previous
        </button>
        <span class="page-info">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="btn btn-secondary"
        >
          Next
        </button>
      </div>
    </div>

    <!-- No modals needed, using router navigation -->
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useNotificationStore } from '@/stores/notification.js'
import { api } from '@/services/api'

const router = useRouter()
const notificationStore = useNotificationStore()

// Reactive data
const employees = ref([])
const branches = ref([])
const stats = ref({
  total_employees: 0,
  active_employees: 0,
  total_managers: 0,
  new_joins_this_month: 0
})

// Initialize stats to prevent undefined errors
stats.value = {
  total_employees: 0,
  active_employees: 0,
  total_managers: 0,
  new_joins_this_month: 0
}

const filters = ref({
  search: '',
  status: '',
  performance_rating: '',
  branch_id: ''
})

const sortField = ref('created_at')
const sortDirection = ref('desc')
const currentPage = ref(1)
const itemsPerPage = ref(15)

// Computed properties
const filteredEmployees = computed(() => {
  let filtered = [...employees.value]

  // Apply filters
  if (filters.value.search) {
    const searchTerm = filters.value.search.toLowerCase()
    filtered = filtered.filter(employee =>
      employee.name.toLowerCase().includes(searchTerm) ||
      employee.email.toLowerCase().includes(searchTerm) ||
      employee.employee_id.toLowerCase().includes(searchTerm)
    )
  }

  if (filters.value.status) {
    filtered = filtered.filter(employee => employee.status === filters.value.status)
  }

  if (filters.value.performance_rating) {
    filtered = filtered.filter(employee => employee.performance_rating === filters.value.performance_rating)
  }

  if (filters.value.branch_id) {
    filtered = filtered.filter(employee => employee.branch_id == filters.value.branch_id)
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let aVal = a[sortField.value]
    let bVal = b[sortField.value]

    if (sortField.value === 'join_date' && aVal && bVal) {
      aVal = new Date(aVal)
      bVal = new Date(bVal)
    }

    if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1
    if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1
    return 0
  })

  return filtered
})

const paginatedEmployees = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredEmployees.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredEmployees.value.length / itemsPerPage.value)
})

// Methods
const loadEmployees = async () => {
  try {
    const response = await api.get('/users', {
      params: {
        ...filters.value,
        sort_by: sortField.value,
        sort_direction: sortDirection.value
      }
    })
    employees.value = response.data.data
  } catch (error) {
    console.error('Error loading employees:', error)
    notificationStore.addNotification({
      type: 'error',
      message: 'Failed to load employees'
    })
  }
}

const loadStats = async () => {
  try {
    const response = await api.get('/employees/stats')
    stats.value = response.data.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const loadBranches = async () => {
  try {
    const response = await api.get('/branches')
    branches.value = response.data.data
  } catch (error) {
    console.error('Error loading branches:', error)
  }
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  currentPage.value = 1
}

const resetFilters = () => {
  filters.value = {
    search: '',
    status: '',
    performance_rating: '',
    branch_id: ''
  }
  currentPage.value = 1
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const viewEmployee = (employee) => {
  router.push(`/admin/employees/${employee.id}`)
}

const editEmployee = (employee) => {
  router.push(`/admin/employees/${employee.id}/edit`)
}

const deleteEmployee = async (employee) => {
  if (!confirm(`Are you sure you want to delete ${employee.name}?`)) return

  try {
    await api.delete(`/users/${employee.id}`)
    await loadEmployees()
    await loadStats()
    notificationStore.addNotification({
      type: 'success',
      message: 'Employee deleted successfully'
    })
  } catch (error) {
    console.error('Error deleting employee:', error)
    notificationStore.addNotification({
      type: 'error',
      message: 'Failed to delete employee'
    })
  }
}



// Watchers
watch(filters, () => {
  currentPage.value = 1
  loadEmployees()
}, { deep: true })

watch(sortField, loadEmployees)
watch(sortDirection, loadEmployees)

// Lifecycle
onMounted(async () => {
  await Promise.all([
    loadEmployees(),
    loadStats(),
    loadBranches()
  ])
})
</script>

<style scoped>
.admin-employees {
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-icon {
  color: #384e74;
}

.stat-content h3 {
  color: #384e74;
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
}

.stat-content p {
  color: #9CA3AF;
  font-size: 0.875rem;
  margin: 0.25rem 0 0 0;
}

.filters-section {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.search-bar {
  position: relative;
  flex: 1;
  min-width: 300px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 3rem;
  border: 2px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 1rem;
  background: white;
}

.search-input:focus {
  outline: none;
  border-color: #384e74;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9CA3AF;
  width: 1.25rem;
  height: 1.25rem;
}

.filter-controls {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.filter-select {
  padding: 0.75rem 1rem;
  border: 2px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 0.875rem;
  background: white;
  min-width: 150px;
}

.filter-select:focus {
  outline: none;
  border-color: #384e74;
}

.employees-section {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
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

.employees-table-container {
  overflow-x: auto;
  margin-bottom: 1.5rem;
}

.employees-table {
  width: 100%;
  border-collapse: collapse;
}

.employees-table th,
.employees-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
}

.employees-table th {
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  cursor: pointer;
  user-select: none;
}

.employees-table th.sortable:hover {
  background: rgba(56, 78, 116, 0.05);
}

.sort-icon {
  display: inline-block;
  margin-left: 0.5rem;
  width: 0.75rem;
  height: 0.75rem;
  transition: transform 0.2s;
}

.rotate-180 {
  transform: rotate(180deg);
}

.employees-table td {
  color: #636a75;
}

.performance-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.performance-excellent {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.performance-good {
  background-color: rgba(59, 130, 246, 0.1);
  color: #3B82F6;
}

.performance-average {
  background-color: rgba(245, 158, 11, 0.1);
  color: #F59E0B;
}

.performance-weak {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-active {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-inactive {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.status-suspended {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.status-on_leave {
  background-color: rgba(245, 158, 11, 0.1);
  color: #F59E0B;
}

.action-buttons {
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

.btn-info {
  background: rgba(59, 130, 246, 0.1);
  border: 2px solid rgba(59, 130, 246, 0.3);
  color: #3B82F6;
}

.btn-info:hover {
  background: rgba(59, 130, 246, 0.2);
  border-color: #3B82F6;
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
}

.btn-danger {
  background: rgba(239, 68, 68, 0.1);
  border: 2px solid rgba(239, 68, 68, 0.3);
  color: #EF4444;
}

.btn-danger:hover {
  background: rgba(239, 68, 68, 0.2);
  border-color: #EF4444;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 1.5rem;
}

.page-info {
  color: #9CA3AF;
  font-size: 0.875rem;
}

@media (max-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }

  .filters-section {
    flex-direction: column;
    align-items: stretch;
  }

  .search-bar {
    min-width: auto;
  }

  .filter-controls {
    justify-content: center;
  }
}

@media (max-width: 768px) {
  .admin-employees {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .stat-card {
    padding: 1rem;
  }

  .stat-content h3 {
    font-size: 1.5rem;
  }

  .section-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .employees-table-container {
    padding: 1rem;
  }

  .employees-table th,
  .employees-table td {
    padding: 0.5rem;
  }

  .action-buttons {
    flex-direction: column;
  }

  .pagination {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>
