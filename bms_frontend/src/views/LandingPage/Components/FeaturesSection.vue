<template>
  <section class="features-section">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="features-header">
        <h2 class="features-title">
          Powerful Features for Branch Management
        </h2>
        <p class="features-subtitle">
          Everything you need to manage, monitor, and grow your branch network
        </p>
      </div>

      <!-- Features Grid -->
      <div class="features-grid">
        <div 
          v-for="(feature, index) in features" 
          :key="index" 
          class="feature-card"
        >
          <div class="feature-icon-wrapper" :class="feature.colorClass">
            <component :is="feature.icon" class="w-8 h-8" />
          </div>
          <h3 class="feature-title">{{ feature.title }}</h3>
          <p class="feature-description">{{ feature.description }}</p>
          <ul class="feature-list">
            <li v-for="item in feature.items" :key="item" class="feature-list-item">
              {{ item }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { 
  Building2, 
  Package, 
  BarChart3, 
  Users, 
  Settings, 
  Shield, 
  Clock, 
  Globe 
} from 'lucide-vue-next'

const features = [
  {
    icon: Building2,
    title: 'Branch Management',
    description: 'Centralized control over all your branch locations',
    colorClass: 'icon-wrapper-blue',
    items: [
      'Unlimited branch locations',
      'Branch hierarchy structure',
      'Location-based permissions',
      'Branch configuration management'
    ]
  },
  {
    icon: Package,
    title: 'Inventory Control',
    description: 'Track and manage inventory across all branches',
    colorClass: 'icon-wrapper-orange',
    items: [
      'Real-time stock levels',
      'Inter-branch transfers',
      'Automated reordering',
      'Product catalog management'
    ]
  },
  {
    icon: BarChart3,
    title: 'Analytics & Reports',
    description: 'Comprehensive reporting and data insights',
    colorClass: 'icon-wrapper-green',
    items: [
      'Branch performance metrics',
      'Sales analytics',
      'Custom report builder',
      'Export & scheduling'
    ]
  },
  {
    icon: Users,
    title: 'Staff Management',
    description: 'Manage employees across all locations',
    colorClass: 'icon-wrapper-red',
    items: [
      'Employee profiles',
      'Role-based access',
      'Attendance tracking',
      'Performance reviews'
    ]
  },
  {
    icon: Settings,
    title: 'Workflow Automation',
    description: 'Automate repetitive tasks and processes',
    colorClass: 'icon-wrapper-blue',
    items: [
      'Custom workflows',
      'Automated notifications',
      'Task assignment',
      'Approval processes'
    ]
  },
  {
    icon: Shield,
    title: 'Security & Compliance',
    description: 'Enterprise-grade security for your data',
    colorClass: 'icon-wrapper-red',
    items: [
      'End-to-end encryption',
      '2FA authentication',
      'Audit logs',
      'Data backup & recovery'
    ]
  },
  {
    icon: Clock,
    title: 'Real-Time Sync',
    description: 'Instant synchronization across all branches',
    colorClass: 'icon-wrapper-yellow',
    items: [
      'Live data updates',
      'Conflict resolution',
      'Offline mode support',
      'Change tracking'
    ]
  },
  {
    icon: Globe,
    title: 'Multi-Currency & Language',
    description: 'Support for global operations',
    colorClass: 'icon-wrapper-green',
    items: [
      'Multiple currencies',
      'Multi-language interface',
      'Tax configuration',
      'Regional compliance'
    ]
  }
]
</script>

<style scoped>
.features-section {
  padding: 5rem 1rem;
  background: #f8fafc;
}

.features-header {
  text-align: center;
  margin-bottom: 4rem;
}

.features-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #2a3547 0%, #2c4264 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

@media (min-width: 768px) {
  .features-title {
    font-size: 3rem;
  }
}

.features-subtitle {
  font-size: 1.125rem;
  color: #374151;
}

.features-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

@media (min-width: 640px) {
  .features-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .features-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.feature-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.411) 0%, rgba(189, 212, 255, 0.02) 100%);
  border-radius: 1rem;
  padding: 2rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
  transition: all 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-5px);
  border-color: rgba(189, 212, 255, 0.3);
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.08) 0%, rgba(189, 212, 255, 0.04) 100%);
}

.feature-icon-wrapper {
  width: 4rem;
  height: 4rem;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon-wrapper {
  transform: scale(1.1) rotate(5deg);
}

.icon-wrapper-blue {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.2) 0%, rgba(143, 179, 255, 0.2) 100%);
  border: 2px solid rgba(189, 212, 255, 0.3);
  color: #8ab2fc;
}

.icon-wrapper-orange {
  background: linear-gradient(135deg, rgba(255, 205, 163, 0.2) 0%, rgba(255, 179, 128, 0.2) 100%);
  border: 2px solid rgba(255, 205, 163, 0.3);
  color: #ffbf8b;
}

.icon-wrapper-green {
  background: linear-gradient(135deg, rgba(180, 248, 200, 0.2) 0%, rgba(143, 230, 168, 0.2) 100%);
  border: 2px solid rgba(180, 248, 200, 0.3);
  color: #6ffa99;
}

.icon-wrapper-red {
  background: linear-gradient(135deg, rgba(255, 179, 186, 0.2) 0%, rgba(255, 138, 147, 0.2) 100%);
  border: 2px solid rgba(255, 179, 186, 0.3);
  color: #ff8692;
}

.icon-wrapper-yellow {
  background: linear-gradient(135deg, rgba(255, 249, 171, 0.616) 0%, rgba(255, 235, 122, 0.2) 100%);
  border: 2px solid rgba(255, 249, 171, 0.829);
  color: #fff564;
}

.feature-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: #1F2937;
}

.feature-description {
  color: #374151;
  font-size: 0.875rem;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.feature-list-item {
  color: #374151;
  font-size: 0.875rem;
  padding-left: 1.25rem;
  position: relative;
}

.feature-list-item::before {
  content: '•';
  position: absolute;
  left: 0;
  color: #BDD4FF;
  font-weight: 700;
}
</style>