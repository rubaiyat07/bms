<template>
  <div class="branch-performance-chart">
    <h3>Branch Performance Statistics</h3>
    <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p>Loading performance data...</p>
    </div>
    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
      <button @click="fetchData" class="retry-btn">Retry</button>
    </div>
    <div v-else class="chart-content">
      <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
      </div>
      <div class="stats-summary">
        <div class="stat-item">
          <span class="stat-label">Total Branches:</span>
          <span class="stat-value">{{ performanceData.totalBranches }}</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Active Branches:</span>
          <span class="stat-value">{{ performanceData.activeBranches }}</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Avg Performance:</span>
          <span class="stat-value">{{ performanceData.averagePerformance }}%</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'

const chartCanvas = ref(null)
const loading = ref(true)
const error = ref(null)

const performanceData = ref({
  totalBranches: 0,
  activeBranches: 0,
  averagePerformance: 0,
  branches: []
})

const fetchData = async () => {
  try {
    loading.value = true
    error.value = null

    // Mock data for now - replace with actual API call
    // const response = await api.get('/api/branches/performance')
    // if (response.data.success) {
    //   performanceData.value = response.data.data
    // }

    // Mock performance data
    performanceData.value = {
      totalBranches: 5,
      activeBranches: 4,
      averagePerformance: 78,
      branches: [
        { name: 'Main Branch', performance_score: 85 },
        { name: 'North Branch', performance_score: 72 },
        { name: 'South Branch', performance_score: 90 },
        { name: 'East Branch', performance_score: 65 },
        { name: 'West Branch', performance_score: 80 }
      ]
    }

    await nextTick()
    renderChart()
  } catch (err) {
    error.value = err.message || 'Failed to load performance data'
    console.error('Error fetching branch performance:', err)
  } finally {
    loading.value = false
  }
}

const renderChart = () => {
  if (!chartCanvas.value || !performanceData.value.branches.length) return

  // Simple bar chart implementation without Chart.js for now
  const canvas = chartCanvas.value
  const ctx = canvas.getContext('2d')

  // Clear canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height)

  const branches = performanceData.value.branches
  const maxScore = 100
  const barWidth = (canvas.width - 60) / branches.length
  const barSpacing = 10

  // Draw bars
  branches.forEach((branch, index) => {
    const barHeight = (branch.performance_score / maxScore) * (canvas.height - 60)
    const x = 30 + index * (barWidth + barSpacing)
    const y = canvas.height - 30 - barHeight

    // Bar
    ctx.fillStyle = 'rgba(143, 168, 255, 0.6)'
    ctx.fillRect(x, y, barWidth, barHeight)

    // Bar border
    ctx.strokeStyle = '#8FB3FF'
    ctx.lineWidth = 1
    ctx.strokeRect(x, y, barWidth, barHeight)

    // Label
    ctx.fillStyle = '#9CA3AF'
    ctx.font = '12px Arial'
    ctx.textAlign = 'center'
    ctx.fillText(branch.name, x + barWidth / 2, canvas.height - 10)

    // Value
    ctx.fillStyle = '#BDD4FF'
    ctx.font = '14px Arial'
    ctx.fillText(branch.performance_score + '%', x + barWidth / 2, y - 5)
  })

  // Y-axis labels
  ctx.fillStyle = '#9CA3AF'
  ctx.font = '12px Arial'
  ctx.textAlign = 'right'
  for (let i = 0; i <= 100; i += 20) {
    const y = canvas.height - 30 - (i / 100) * (canvas.height - 60)
    ctx.fillText(i + '%', 25, y + 4)
  }
}

onMounted(() => {
  fetchData()
})
</script>

<style scoped>
.branch-performance-chart {
  background: linear-gradient(135deg, rgba(87, 109, 151, 0.267) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 1rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
}

.branch-performance-chart h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.loading-state, .error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  color: #636a75;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(189, 212, 255, 0.1);
  border-top: 3px solid #8FB3FF;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-state p {
  margin-bottom: 1rem;
}

.retry-btn {
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  color: #0F1419;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.retry-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(189, 212, 255, 0.3);
}

.chart-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.chart-container {
  height: 300px;
  position: relative;
}

.stats-summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  padding: 1rem;
  background: rgba(189, 212, 255, 0.05);
  border-radius: 0.5rem;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.stat-label {
  color: #636a75;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.25rem;
}

.stat-value {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 700;
}

@media (max-width: 768px) {
  .chart-container {
    height: 250px;
  }

  .stats-summary {
    grid-template-columns: 1fr;
  }
}
</style>
