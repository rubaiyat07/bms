<template>
  <div class="admin-reports">
    <div class="page-header">
      <h1>Reports & Analytics</h1>
      <p>Generate and view system reports and analytics</p>
    </div>

    <div class="reports-content">
      <!-- Report Filters -->
      <div class="report-filters">
        <div class="filter-group">
          <label>Report Type:</label>
          <select v-model="reportType" class="filter-select">
            <option value="user-activity">User Activity</option>
            <option value="branch-performance">Branch Performance</option>
            <option value="project-status">Project Status</option>
            <option value="inventory-summary">Inventory Summary</option>
            <option value="financial-report">Financial Report</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Date Range:</label>
          <select v-model="dateRange" class="filter-select">
            <option value="last-7-days">Last 7 Days</option>
            <option value="last-30-days">Last 30 Days</option>
            <option value="last-3-months">Last 3 Months</option>
            <option value="last-6-months">Last 6 Months</option>
            <option value="last-year">Last Year</option>
            <option value="custom">Custom Range</option>
          </select>
        </div>
        <button @click="generateReport" class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Generate Report
        </button>
      </div>

      <!-- Quick Stats -->
      <div class="quick-stats">
        <div class="stat-card">
          <h3>Total Employees</h3>
          <div class="stat-number">{{ employeeStats.total_employees }}</div>
          <div class="stat-change positive">+{{ employeeStats.new_joins_this_month }} this month</div>
        </div>
        <div class="stat-card">
          <h3>Active Employees</h3>
          <div class="stat-number">{{ employeeStats.active_employees }}</div>
          <div class="stat-change positive">{{ Math.round((employeeStats.active_employees / employeeStats.total_employees) * 100) }}% active rate</div>
        </div>
        <div class="stat-card">
          <h3>Managers</h3>
          <div class="stat-number">{{ employeeStats.total_managers }}</div>
          <div class="stat-change neutral">{{ Math.round((employeeStats.total_managers / employeeStats.total_employees) * 100) }}% management ratio</div>
        </div>
        <div class="stat-card">
          <h3>Top Performers</h3>
          <div class="stat-number">{{ topPerformersCount }}</div>
          <div class="stat-change positive">Excellent rating</div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="charts-section">
        <div class="chart-container">
          <h3>Employee Performance Distribution</h3>
          <div class="chart-placeholder">
            <div class="pie-chart">
              <div class="pie-segment excellent" style="transform: rotate(0deg); clip-path: polygon(50% 50%, 50% 0%, 75% 0%)">
                <span>Excellent: {{ performanceStats.excellent }}</span>
              </div>
              <div class="pie-segment good" style="transform: rotate(90deg); clip-path: polygon(50% 50%, 75% 0%, 100% 25%)">
                <span>Good: {{ performanceStats.good }}</span>
              </div>
              <div class="pie-segment average" style="transform: rotate(180deg); clip-path: polygon(50% 50%, 100% 25%, 100% 75%)">
                <span>Average: {{ performanceStats.average }}</span>
              </div>
              <div class="pie-segment weak" style="transform: rotate(270deg); clip-path: polygon(50% 50%, 100% 75%, 75% 100%)">
                <span>Weak: {{ performanceStats.weak }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="chart-container">
          <h3>Branch-wise Employee Distribution</h3>
          <div class="chart-placeholder">
            <div class="bar-chart">
              <div v-for="branch in branchStats" :key="branch.name" class="bar" :style="{ height: branch.percentage + '%' }">
                <span>{{ branch.name }} ({{ branch.count }})</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Reports -->
      <div class="recent-reports">
        <div class="section-header">
          <h2>Recent Reports</h2>
          <button @click="exportAllReports" class="btn btn-secondary">Export All</button>
        </div>

        <div class="reports-list">
          <div v-for="report in recentReports" :key="report.id" class="report-item">
            <div class="report-info">
              <h4>{{ report.name }}</h4>
              <p>{{ report.description }}</p>
              <span class="report-date">{{ formatDate(report.generated_at) }}</span>
            </div>
            <div class="report-actions">
              <button @click="viewReport(report)" class="btn btn-sm btn-primary">View</button>
              <button @click="downloadReport(report)" class="btn btn-sm btn-secondary">Download</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'

// Employee stats data
const employeeStats = ref({
  total_employees: 0,
  active_employees: 0,
  total_managers: 0,
  new_joins_this_month: 0
})

const topPerformersCount = ref(0)
const performanceStats = ref({
  excellent: 0,
  good: 0,
  average: 0,
  weak: 0
})

const branchStats = ref([])

const reportType = ref('user-activity')
const dateRange = ref('last-30-days')

// Load employee stats
const loadEmployeeStats = async () => {
  try {
    const response = await api.get('/users/stats')
    employeeStats.value = response.data
  } catch (error) {
    console.error('Error loading employee stats:', error)
  }
}

// Load performance stats
const loadPerformanceStats = async () => {
  try {
    const response = await api.get('/users/performance-stats')
    performanceStats.value = response.data
    topPerformersCount.value = response.data.excellent
  } catch (error) {
    console.error('Error loading performance stats:', error)
  }
}

// Load branch stats
const loadBranchStats = async () => {
  try {
    const response = await api.get('/branches/employee-distribution')
    branchStats.value = response.data
  } catch (error) {
    console.error('Error loading branch stats:', error)
  }
}

// Load all stats on mount
onMounted(async () => {
  await Promise.all([
    loadEmployeeStats(),
    loadPerformanceStats(),
    loadBranchStats()
  ])
})

const recentReports = ref([
  {
    id: 1,
    name: 'Monthly User Activity Report',
    description: 'Comprehensive analysis of user engagement and activity patterns',
    generated_at: '2024-01-15T10:30:00Z'
  },
  {
    id: 2,
    name: 'Branch Performance Summary',
    description: 'Performance metrics and KPIs for all branches',
    generated_at: '2024-01-14T14:20:00Z'
  },
  {
    id: 3,
    name: 'Inventory Status Report',
    description: 'Current inventory levels and stock movement analysis',
    generated_at: '2024-01-13T09:15:00Z'
  },
  {
    id: 4,
    name: 'Financial Overview Q4',
    description: 'Quarterly financial performance and budget analysis',
    generated_at: '2024-01-12T16:45:00Z'
  }
])

const generateReport = () => {
  console.log('Generating report:', { type: reportType.value, range: dateRange.value })
  // Implement report generation logic
}

const viewReport = (report) => {
  console.log('View report:', report)
  // Implement view report logic
}

const downloadReport = (report) => {
  console.log('Download report:', report)
  // Implement download report logic
}

const exportAllReports = () => {
  console.log('Export all reports')
  // Implement export all reports logic
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}
</script>

<style scoped>
.admin-reports {
  padding: 2rem;
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

.reports-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.report-filters {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
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

.quick-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
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
  margin-bottom: 0.5rem;
}

.stat-change {
  font-size: 0.75rem;
  font-weight: 600;
}

.stat-change.positive {
  color: #22C55E;
}

.stat-change.negative {
  color: #EF4444;
}

.charts-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
}

.chart-container {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.chart-container h3 {
  color: #384e74;
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.chart-placeholder {
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9CA3AF;
}

.chart-svg {
  width: 100%;
  height: 100%;
}

.bar-chart {
  display: flex;
  align-items: end;
  justify-content: space-around;
  height: 150px;
  gap: 1rem;
}

.bar {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: end;
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  border-radius: 4px 4px 0 0;
  min-width: 60px;
  position: relative;
}

.bar span {
  position: absolute;
  bottom: -25px;
  color: #9CA3AF;
  font-size: 0.75rem;
  text-align: center;
  width: 100%;
}

.recent-reports {
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

.reports-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.report-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: rgba(56, 78, 116, 0.05);
  border-radius: 0.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.report-info h4 {
  color: #384e74;
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.report-info p {
  color: #9CA3AF;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.report-date {
  color: #6B7280;
  font-size: 0.75rem;
}

.report-actions {
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

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
}

@media (max-width: 768px) {
  .admin-reports {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .report-filters {
    flex-direction: column;
    align-items: flex-start;
  }

  .quick-stats {
    grid-template-columns: 1fr;
  }

  .charts-section {
    grid-template-columns: 1fr;
  }

  .report-item {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .report-actions {
    width: 100%;
    justify-content: flex-end;
  }
}
</style>
