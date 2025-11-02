<template>
  <section class="hero-section">
    <!-- Animated Background -->
    <div class="hero-bg">
      <div class="hero-bg-blob hero-bg-blob-1"></div>
      <div class="hero-bg-blob hero-bg-blob-2"></div>
      <div class="hero-bg-blob hero-bg-blob-3"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 px-4">
      <div class="text-center">
        <!-- Badge -->
        <div class="hero-badge animate-fade-in">
          <span class="text-sm font-semibold">🚀 New: Real-time Branch Analytics Available</span>
        </div>
        
        <!-- Heading -->
        <h1 class="hero-heading animate-slide-up">
          Streamline Your Branch
          <br />
          <span class="hero-heading-gradient">
            Management System
          </span>
        </h1>

        <!-- Subheading -->
        <p class="hero-subheading animate-slide-up" style="animation-delay: 0.1s;">
          The comprehensive platform designed specifically for managing multiple branches efficiently. 
          Centralize operations, track performance, and optimize your business network.
        </p>

        <!-- CTA Buttons -->
        <div class="hero-cta-buttons animate-slide-up" style="animation-delay: 0.2s;">
          <router-link to="/login" class="hero-btn-primary group">
            Access Dashboard
            <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
          </router-link>
          <button class="hero-btn-secondary">
            <Play class="w-5 h-5" />
            Watch Demo
          </button>
        </div>

        <!-- Trust Badges -->
        <div class="hero-trust-badges animate-fade-in" style="animation-delay: 0.3s;">
          <div class="trust-badge">
            <CheckCircle class="w-5 h-5" />
            Centralized control
          </div>
          <div class="trust-badge">
            <CheckCircle class="w-5 h-5" />
            Real-time insights
          </div>
          <div class="trust-badge">
            <CheckCircle class="w-5 h-5" />
            Free 14-day trial
          </div>
        </div>
      </div>

      <!-- Dashboard Preview -->
      <div class="hero-dashboard-preview animate-slide-up" style="animation-delay: 0.4s;">
        <div class="dashboard-card">
          <div class="dashboard-inner">
            <!-- Mock Dashboard Elements -->
            <div class="dashboard-header">
              <div class="dashboard-header-left">
                <div class="skeleton-line w-32 h-6"></div>
                <div class="skeleton-line w-48 h-4 mt-2"></div>
              </div>
              <div class="dashboard-header-right">
                <div class="skeleton-circle"></div>
                <div class="skeleton-circle"></div>
              </div>
            </div>

            <div class="dashboard-content">
              <div class="dashboard-grid">
                <div v-for="i in 4" :key="i" class="stat-card" :style="{ animationDelay: `${i * 0.1}s` }">
                  <div class="stat-icon" :class="`icon-color-${i}`">
                    <component :is="getIcon(i)" class="w-6 h-6" />
                  </div>
                  <div class="stat-info">
                    <div class="skeleton-line w-20 h-4"></div>
                    <div class="skeleton-line w-16 h-6 mt-2"></div>
                  </div>
                </div>
              </div>

              <div class="chart-area">
                <div class="chart-placeholder">
                  <BarChart3 class="w-16 h-16 text-blue-300 animate-float" />
                  <p class="text-blue-600 font-medium mt-3">Beautiful Analytics Dashboard</p>
                  <p class="text-slate-500 text-sm mt-1">Real-time insights at your fingertips</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="hero-scroll-indicator">
      <ChevronDown class="w-8 h-8" />
    </div>
  </section>
</template>

<script setup>
import { ArrowRight, CheckCircle, BarChart3, ChevronDown, Play, Building2, Users, Package, TrendingUp } from 'lucide-vue-next'

const getIcon = (index) => {
  const icons = [Building2, Users, Package, TrendingUp]
  return icons[index - 1]
}
</script>

<style scoped>
.hero-section {
  position: relative;
  padding-top: 8rem;
  padding-bottom: 6rem;
  overflow: hidden;
  background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 50%, #EFF6FF 100%);
}

.hero-bg {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.hero-bg-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.4;
  animation: float 20s ease-in-out infinite;
}

.hero-bg-blob-1 {
  top: 10%;
  left: 10%;
  width: 500px;
  height: 500px;
  background: linear-gradient(135deg, #4A90E2 0%, #5DADE2 100%);
}

.hero-bg-blob-2 {
  top: 40%;
  right: 10%;
  width: 400px;
  height: 400px;
  background: linear-gradient(135deg, #8B5CF6 0%, #AF7AC5 100%);
  animation-delay: 2s;
}

.hero-bg-blob-3 {
  bottom: 10%;
  left: 50%;
  width: 350px;
  height: 350px;
  background: linear-gradient(135deg, #EC4899 0%, #F97316 100%);
  animation-delay: 4s;
}

.hero-badge {
  display: inline-block;
  margin-bottom: 1.5rem;
  padding: 0.625rem 1.25rem;
  background: linear-gradient(135deg, rgba(74, 144, 226, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
  border-radius: 50px;
  border: 2px solid rgba(74, 144, 226, 0.2);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(74, 144, 226, 0.15);
  color: #2A6BB8;
}

.hero-heading {
  font-size: 3.5rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  line-height: 1.1;
  color: #1E293B;
  letter-spacing: -0.02em;
}

@media (min-width: 768px) {
  .hero-heading {
    font-size: 5rem;
  }
}

.hero-heading-gradient {
  background: linear-gradient(135deg, #4A90E2 0%, #8B5CF6 50%, #EC4899 100%);
  background-size: 200% auto;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.hero-subheading {
  font-size: 1.25rem;
  color: #475569;
  margin-bottom: 2.5rem;
  max-width: 50rem;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.7;
}

@media (min-width: 768px) {
  .hero-subheading {
    font-size: 1.375rem;
  }
}

.hero-cta-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: center;
  align-items: center;
  margin-bottom: 3rem;
}

@media (min-width: 640px) {
  .hero-cta-buttons {
    flex-direction: row;
    gap: 1.25rem;
  }
}

.hero-btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
  padding: 1.125rem 2.25rem;
  background: linear-gradient(135deg, #4A90E2 0%, #2A6BB8 100%);
  border-radius: 50px;
  font-weight: 700;
  font-size: 1.125rem;
  color: white;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(74, 144, 226, 0.4);
  text-decoration: none;
}

.hero-btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(74, 144, 226, 0.5);
}

.hero-btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
  padding: 1.125rem 2.25rem;
  background: white;
  border: 2px solid #E2E8F0;
  border-radius: 50px;
  font-weight: 700;
  font-size: 1.125rem;
  color: #4A90E2;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.hero-btn-secondary:hover {
  background: #F8FAFC;
  border-color: #4A90E2;
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.hero-trust-badges {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 2rem;
  font-size: 0.9375rem;
}

.trust-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748B;
  font-weight: 500;
}

.trust-badge svg {
  color: #6BCF7F;
}

.hero-dashboard-preview {
  margin-top: 5rem;
  position: relative;
}

.dashboard-card {
  background: white;
  border-radius: 1.5rem;
  padding: 0.75rem;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  border: 1px solid #E2E8F0;
  transition: transform 0.5s ease;
}

.dashboard-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 35px 60px rgba(0, 0, 0, 0.2);
}

.dashboard-inner {
  background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
  border-radius: 1rem;
  padding: 2rem;
  min-height: 500px;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid #E2E8F0;
}

.dashboard-header-right {
  display: flex;
  gap: 1rem;
}

.skeleton-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(90deg, #E2E8F0 0%, #F1F5F9 50%, #E2E8F0 100%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.skeleton-line {
  background: linear-gradient(90deg, #E2E8F0 0%, #F1F5F9 50%, #E2E8F0 100%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
  border-radius: 0.5rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  border: 1px solid #E2E8F0;
  animation: fade-in 0.5s ease-out;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.icon-color-1 {
  background: linear-gradient(135deg, #4A90E2 0%, #5DADE2 100%);
  box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
}

.icon-color-2 {
  background: linear-gradient(135deg, #8B5CF6 0%, #AF7AC5 100%);
  box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.icon-color-3 {
  background: linear-gradient(135deg, #6BCF7F 0%, #51BB6A 100%);
  box-shadow: 0 4px 15px rgba(107, 207, 127, 0.3);
}

.icon-color-4 {
  background: linear-gradient(135deg, #FFA756 0%, #FF9142 100%);
  box-shadow: 0 4px 15px rgba(255, 167, 86, 0.3);
}

.stat-info {
  flex: 1;
}

.chart-area {
  background: white;
  border-radius: 1rem;
  padding: 3rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  border: 1px solid #E2E8F0;
}

.chart-placeholder {
  text-align: center;
  padding: 3rem 1rem;
}

.hero-scroll-indicator {
  position: absolute;
  bottom: 3rem;
  left: 50%;
  transform: translateX(-50%);
  animation: bounce 2s ease-in-out infinite;
  color: #4A90E2;
  opacity: 0.7;
}
</style>