<template>
  <section class="stats-section">
    <div class="max-w-7xl mx-auto">
      <div class="grid md:grid-cols-4 gap-8">
        <div v-for="stat in stats" :key="stat.label" class="stat-card">
          <div class="stat-value">
            {{ stat.value }}
          </div>
          <div class="stat-label">{{ stat.label }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const stats = [
  { value: '10K+', label: 'Active Branches' },
  { value: '50+', label: 'Countries' },
  { value: '99.9%', label: 'Uptime' },
  { value: '24/7', label: 'Support' }
]
</script>

<style scoped>
.stats-section {
  padding: 5rem 1rem;
  background: linear-gradient(180deg, #51709c 0%, #d4e0f1 100%);
}

.stat-card {
  text-align: center;
  padding: 2rem 1rem;
  border-radius: 1rem;
  background: linear-gradient(135deg, rgba(4, 18, 43, 0.05) 0%, rgba(235, 242, 255, 0) 100%);
  border: 1px solid rgba(251, 253, 255, 0.1);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  border-color: rgba(236, 252, 255, 0.3);
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.08) 0%, rgba(189, 212, 255, 0.04) 100%);
}

.stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #BDD4FF 0%, #EBF3FF 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
}

@media (min-width: 768px) {
  .stat-value {
    font-size: 3rem;
  }
}

.stat-label {
  color: #ffffff;
  font-size: 1rem;
}
</style>