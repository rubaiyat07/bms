<template>
  <section class="benefits-section">
    <div class="max-w-7xl mx-auto">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="benefits-heading">
            Why Multi-Branch Businesses Choose Our BMS
          </h2>
          <p class="benefits-subheading">
            Join over 10,000 businesses managing their branch networks more efficiently with our platform.
          </p>
          <div class="benefits-list">
            <div v-for="benefit in benefits" :key="benefit" class="benefit-item">
              <div class="benefit-icon">
                <CheckCircle class="w-4 h-4" />
              </div>
              <span class="benefit-text">{{ benefit }}</span>
            </div>
          </div>
        </div>

        <div class="relative">
          <div class="metrics-card">
            <div class="space-y-4">
              <div v-for="metric in metrics" :key="metric.label" class="metric-item">
                <div class="metric-header">
                  <span class="metric-label">{{ metric.label }}</span>
                  <span class="metric-change">{{ metric.change }}</span>
                </div>
                <div class="metric-progress-bg">
                  <div class="metric-progress-fill" :style="{ width: metric.percentage }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { CheckCircle } from 'lucide-vue-next'

const benefits = [
  'Manage unlimited branches from one dashboard',
  'Real-time inventory synchronization',
  'Reduce operational costs by 35%',
  'Automated inter-branch transfers',
  'Branch-level performance insights',
  '24/7 Multi-location support'
]

const metrics = [
  { label: 'Branch Efficiency', change: '+45%', percentage: '75%' },
  { label: 'Inventory Accuracy', change: '+60%', percentage: '85%' },
  { label: 'Cost Reduction', change: '-35%', percentage: '70%' }
]
</script>

<style scoped>
.benefits-section {
  padding: 5rem 1rem;
}

.benefits-heading {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.2;
}

@media (min-width: 768px) {
  .benefits-heading {
    font-size: 3rem;
  }
}

.benefits-subheading {
  font-size: 1.25rem;
  color: #9CA3AF;
  margin-bottom: 2rem;
}

.benefits-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: all 0.3s ease;
}

.benefit-item:hover {
  transform: translateX(8px);
}

.benefit-icon {
  width: 1.5rem;
  height: 1.5rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 9999px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.benefit-item:hover .benefit-icon {
  transform: scale(1.15);
}

.benefit-text {
  font-size: 1.125rem;
  transition: color 0.3s ease;
}

.benefit-item:hover .benefit-text {
  color: #BDD4FF;
}

.metrics-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.616) 0%, rgba(189, 212, 255, 0.03) 100%);
  border-radius: 1rem;
  padding: 2rem;
  backdrop-filter: blur(12px);
  border: 1px solid rgba(189, 212, 255, 0.15);
}

.metric-item {
  background: #1a1f2644;
  border-radius: 0.5rem;
  padding: 1rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.metric-item:hover {
  background: #242930bd;
  transform: translateY(-2px);
}

.metric-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.metric-label {
  font-weight: 600;
}

.metric-change {
  color: #3a3f47;
  font-weight: 700;
}

.metric-change:hover {
  color: #a6c6fd;
  font-weight: 700;
}

.metric-progress-bg {
  height: 0.5rem;
  background: #3741518f;
  border-radius: 9999px;
  overflow: hidden;
}

.metric-progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #8fb3ff 0%, #BDD4FF 100%);
  border-radius: 9999px;
  transition: width 1s ease;
}
</style>