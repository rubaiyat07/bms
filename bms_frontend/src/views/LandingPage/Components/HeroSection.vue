<template>
  <section class="hero-section">
    <!-- Animated Background -->
    <div class="hero-bg">
      <div class="hero-bg-blur hero-bg-blur-1"></div>
      <div class="hero-bg-blur hero-bg-blur-2"></div>
      <div class="hero-bg-grid"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
      <div class="text-center">
        
        <!-- Heading -->
        <h1 class="hero-heading">
          Transform Your Business
          <br />
          <span class="hero-heading-gradient">
            Management Today
          </span>
        </h1>
        
        <!-- Subheading -->
        <p class="hero-subheading">
          The all-in-one platform that empowers teams to work smarter, faster, and more efficiently than ever before.
        </p>

        <!-- CTA Buttons -->
        <div class="hero-cta-buttons">
          <router-link to="/register" class="hero-btn-primary">
            Start Free Trial
            <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
          </router-link>
          <button class="hero-btn-secondary">
            Watch Demo
          </button>
        </div>

        <!-- Trust Badges -->
        <div class="hero-trust-badges">
          <div class="flex items-center gap-2">
            <CheckCircle class="w-5 h-5 text-[#BDD4FF]" />
            No credit card required
          </div>
          <div class="flex items-center gap-2">
            <CheckCircle class="w-5 h-5 text-[#BDD4FF]" />
            14-day free trial
          </div>
        </div>
      </div>

      <!-- Dashboard Preview -->
      <div class="hero-dashboard-preview">
        <div class="hero-dashboard-gradient"></div>
        <div class="hero-dashboard-card">
          <div class="hero-dashboard-inner">
            <div class="hero-dashboard-bg">
              <div class="hero-dashboard-grid">
                <div 
                  v-for="i in 9" 
                  :key="i" 
                  class="hero-dashboard-grid-item"
                  :style="{ animationDelay: `${i * 0.1}s` }"
                ></div>
              </div>
            </div>
            <div class="hero-dashboard-content">
              <BarChart3 class="w-24 h-24 mx-auto text-[#BDD4FF] mb-4" />
              <h3 class="text-2xl font-semibold">Beautiful Dashboard Interface</h3>
              <p class="text-gray-400 mt-2">Intuitive design that your team will love</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="hero-scroll-indicator">
      <ChevronDown class="w-8 h-8 text-[#BDD4FF]" />
    </div>
  </section>
</template>

<script setup>
import { ArrowRight, CheckCircle, BarChart3, ChevronDown } from 'lucide-vue-next'
</script>

<style scoped>
.hero-section {
  position: relative;
  padding-top: 8rem;
  padding-bottom: 5rem;
  padding-left: 1rem;
  padding-right: 1rem;
  overflow: hidden;
}

.hero-bg {
  position: absolute;
  inset: 0;
  opacity: 0.15;
}

.hero-bg-blur {
  position: absolute;
  border-radius: 9999px;
  filter: blur(80px);
  animation: pulse 4s ease-in-out infinite;
}

.hero-bg-blur-1 {
  top: 5rem;
  left: 2.5rem;
  width: 18rem;
  height: 18rem;
  background: linear-gradient(135deg, #BDD4FF 0%, #8FB3FF 100%);
}

.hero-bg-blur-2 {
  bottom: 5rem;
  right: 2.5rem;
  width: 24rem;
  height: 24rem;
  background: linear-gradient(135deg, #6B9AFF 0%, #BDD4FF 100%);
  animation-delay: 1s;
}

.hero-bg-grid {
  position: absolute;
  inset: 0;
  background-image: 
    linear-gradient(rgba(189, 212, 255, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(189, 212, 255, 0.05) 1px, transparent 1px);
  background-size: 50px 50px;
}

.hero-badge {
  display: inline-block;
  margin-bottom: 1rem;
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.1) 0%, rgba(189, 212, 255, 0.05) 100%);
  border-radius: 9999px;
  border: 1px solid rgba(189, 212, 255, 0.3);
  backdrop-filter: blur(12px);
  animation: fade-in 1s ease-out;
}

.hero-heading {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.2;
  animation: slide-up 0.8s ease-out;
}

.hero-heading-gradient {
  background: linear-gradient(135deg, #BDD4FF 0%, #EBF3FF 50%, #BDD4FF 100%);
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

@media (min-width: 768px) {
  .hero-heading {
    font-size: 4.5rem;
  }
}

.hero-subheading {
  font-size: 1.25rem;
  color: #D1D5DB;
  margin-bottom: 2rem;
  max-width: 48rem;
  margin-left: auto;
  margin-right: auto;
  animation: slide-up 0.8s ease-out;
  animation-delay: 0.1s;
  animation-fill-mode: both;
}

@media (min-width: 768px) {
  .hero-subheading {
    font-size: 1.5rem;
  }
}

.hero-cta-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: center;
  align-items: center;
  margin-bottom: 3rem;
  animation: slide-up 0.8s ease-out;
  animation-delay: 0.2s;
  animation-fill-mode: both;
}

@media (min-width: 640px) {
  .hero-cta-buttons {
    flex-direction: row;
  }
}

.hero-btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 9999px;
  font-weight: 600;
  font-size: 1.125rem;
  color: #0F1419;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);
}

.hero-btn-primary:hover {
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(189, 212, 255, 0.4);
}

.hero-btn-secondary {
  padding: 1rem 2rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(12px);
  border-radius: 9999px;
  font-weight: 600;
  font-size: 1.125rem;
  color: white;
  transition: all 0.3s ease;
  border: 1px solid rgba(189, 212, 255, 0.2);
}

.hero-btn-secondary:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(189, 212, 255, 0.4);
}

.hero-trust-badges {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
  font-size: 0.875rem;
  color: #9CA3AF;
  animation: fade-in 1s ease-out;
  animation-delay: 0.3s;
  animation-fill-mode: both;
}

.hero-dashboard-preview {
  margin-top: 5rem;
  position: relative;
  animation: slide-up 0.8s ease-out;
  animation-delay: 0.4s;
  animation-fill-mode: both;
}

.hero-dashboard-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, #0F1419 0%, transparent 50%, transparent 100%);
  z-index: 10;
  pointer-events: none;
}

.hero-dashboard-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.1) 0%, rgba(189, 212, 255, 0.05) 100%);
  border-radius: 1rem;
  padding: 0.5rem;
  backdrop-filter: blur(12px);
  border: 1px solid rgba(189, 212, 255, 0.15);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
  transition: transform 0.5s ease;
}

.hero-dashboard-card:hover {
  transform: scale(1.02);
}

.hero-dashboard-inner {
  background: #1A1F26;
  border-radius: 0.75rem;
  padding: 2rem;
  min-height: 400px;
  position: relative;
  overflow: hidden;
}

.hero-dashboard-bg {
  position: absolute;
  inset: 0;
  opacity: 0.2;
}

.hero-dashboard-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  padding: 1rem;
}

.hero-dashboard-grid-item {
  height: 6rem;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.2) 0%, rgba(143, 179, 255, 0.2) 100%);
  border-radius: 0.5rem;
  animation: pulse 2s ease-in-out infinite;
}

.hero-dashboard-content {
  position: relative;
  z-index: 10;
  text-align: center;
  padding: 5rem 0;
}

.hero-scroll-indicator {
  position: absolute;
  bottom: 2.5rem;
  left: 50%;
  transform: translateX(-50%);
  animation: bounce 2s ease-in-out infinite;
}
</style>