<template>
  <section class="welcome-carousel-section">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="carousel-header">
        <h2 class="carousel-title">
          Complete Branch Management Solution
        </h2>
        <p class="carousel-subtitle">
          Everything you need to manage multiple locations efficiently and profitably
        </p>
      </div>

      <!-- Carousel Container -->
      <div class="carousel-container">
        <div class="carousel-wrapper">
          <!-- Navigation Buttons -->
          <button 
            @click="prevSlide" 
            class="carousel-nav carousel-nav-prev"
            aria-label="Previous slide"
          >
            <ChevronLeft class="w-6 h-6" />
          </button>

          <!-- Slides -->
          <div class="carousel-track">
            <transition :name="slideDirection" mode="out-in">
              <div :key="currentSlide" class="carousel-slide">
                <div class="slide-content">
                  <!-- Icon -->
                  <div class="slide-icon" :class="slides[currentSlide].colorClass">
                    <component :is="slides[currentSlide].icon" class="w-16 h-16" />
                  </div>

                  <!-- Content -->
                  <div class="slide-text">
                    <h3 class="slide-title">{{ slides[currentSlide].title }}</h3>
                    <p class="slide-description">{{ slides[currentSlide].description }}</p>
                    
                    <!-- Features List -->
                    <ul class="slide-features">
                      <li v-for="feature in slides[currentSlide].features" :key="feature" class="feature-item">
                        <CheckCircle class="w-5 h-5 feature-icon" :class="slides[currentSlide].colorClass" />
                        <span>{{ feature }}</span>
                      </li>
                    </ul>

                    <!-- Action Button -->
                    <button 
                      class="slide-button"
                      :class="slides[currentSlide].buttonClass"
                    >
                      {{ slides[currentSlide].buttonText }}
                      <ArrowRight class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>
            </transition>
          </div>

          <!-- Navigation Buttons -->
          <button 
            @click="nextSlide" 
            class="carousel-nav carousel-nav-next"
            aria-label="Next slide"
          >
            <ChevronRight class="w-6 h-6" />
          </button>
        </div>

        <!-- Indicators -->
        <div class="carousel-indicators">
          <button
            v-for="(slide, index) in slides"
            :key="index"
            @click="goToSlide(index)"
            :class="['indicator', { 'indicator-active': currentSlide === index }]"
            :aria-label="`Go to slide ${index + 1}`"
          ></button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { ChevronLeft, ChevronRight, CheckCircle, ArrowRight, Building2, BarChart3, Package, Users } from 'lucide-vue-next'

const currentSlide = ref(0)
const slideDirection = ref('slide-left')
const autoplayInterval = ref(null)

const slides = [
  {
    icon: Building2,
    title: 'Multi-Branch Operations',
    description: 'Manage unlimited branches from a centralized dashboard with real-time synchronization and complete visibility.',
    features: [
      'Centralized branch control panel',
      'Real-time branch status monitoring',
      'Location-based access permissions',
      'Inter-branch communication tools'
    ],
    buttonText: 'Explore Branch Features',
    colorClass: 'icon-yellow',
    buttonClass: 'btn-pastel-yellow'
  },
  {
    icon: Package,
    title: 'Inventory Management',
    description: 'Track inventory across all locations with automated reordering, stock transfers, and comprehensive reporting.',
    features: [
      'Multi-location inventory tracking',
      'Automated stock level alerts',
      'Inter-branch transfer management',
      'Product performance analytics'
    ],
    buttonText: 'View Inventory Tools',
    colorClass: 'icon-orange',
    buttonClass: 'btn-pastel-orange'
  },
  {
    icon: BarChart3,
    title: 'Branch Performance Analytics',
    description: 'Compare branch performance, identify trends, and make data-driven decisions with powerful analytics.',
    features: [
      'Revenue tracking per branch',
      'Performance comparison reports',
      'Sales forecasting & trends',
      'Customizable KPI dashboards'
    ],
    buttonText: 'See Analytics',
    colorClass: 'icon-green',
    buttonClass: 'btn-pastel-green'
  },
  {
    icon: Users,
    title: 'Employee & Role Management',
    description: 'Manage staff across branches with role-based access, attendance tracking, and performance monitoring.',
    features: [
      'Multi-branch staff management',
      'Role-based access control',
      'Attendance & scheduling',
      'Performance tracking & reviews'
    ],
    buttonText: 'Manage Teams',
    colorClass: 'icon-red',
    buttonClass: 'btn-pastel-red'
  }
]

const nextSlide = () => {
  slideDirection.value = 'slide-left'
  currentSlide.value = (currentSlide.value + 1) % slides.length
  resetAutoplay()
}

const prevSlide = () => {
  slideDirection.value = 'slide-right'
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length
  resetAutoplay()
}

const goToSlide = (index) => {
  slideDirection.value = index > currentSlide.value ? 'slide-left' : 'slide-right'
  currentSlide.value = index
  resetAutoplay()
}

const startAutoplay = () => {
  autoplayInterval.value = setInterval(() => {
    nextSlide()
  }, 5000)
}

const resetAutoplay = () => {
  if (autoplayInterval.value) {
    clearInterval(autoplayInterval.value)
  }
  startAutoplay()
}

onMounted(() => {
  startAutoplay()
})

onUnmounted(() => {
  if (autoplayInterval.value) {
    clearInterval(autoplayInterval.value)
  }
})
</script>

<style scoped>
.welcome-carousel-section {
  padding: 5rem 1rem;
  background: linear-gradient(180deg, #0F1419 0%, #0A0E13 100%);
}

.carousel-header {
  text-align: center;
  margin-bottom: 4rem;
}

.carousel-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #BDD4FF 0%, #EBF3FF 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

@media (min-width: 768px) {
  .carousel-title {
    font-size: 3rem;
  }
}

.carousel-subtitle {
  font-size: 1.125rem;
  color: #9CA3AF;
}

.carousel-container {
  position: relative;
}

.carousel-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.carousel-track {
  width: 100%;
  overflow: hidden;
  min-height: 500px;
}

.carousel-slide {
  width: 100%;
}

.slide-content {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.08) 0%, rgba(189, 212, 255, 0.03) 100%);
  border-radius: 1.5rem;
  padding: 3rem;
  backdrop-filter: blur(12px);
  border: 1px solid rgba(189, 212, 255, 0.15);
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  align-items: center;
}

@media (min-width: 768px) {
  .slide-content {
    grid-template-columns: auto 1fr;
    gap: 3rem;
  }
}

.slide-icon {
  width: 8rem;
  height: 8rem;
  border-radius: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  transition: transform 0.3s ease;
}

.slide-content:hover .slide-icon {
  transform: scale(1.05) rotate(5deg);
}

.icon-yellow {
  background: linear-gradient(135deg, rgba(255, 250, 171, 0.2) 0%, rgba(255, 235, 122, 0.2) 100%);
  border: 2px solid rgba(255, 250, 171, 0.3);
  color: #FFFAAB;
}

.icon-red {
  background: linear-gradient(135deg, rgba(255, 179, 186, 0.2) 0%, rgba(255, 138, 147, 0.2) 100%);
  border: 2px solid rgba(255, 179, 186, 0.3);
  color: #FFB3BA;
}

.icon-green {
  background: linear-gradient(135deg, rgba(180, 248, 200, 0.2) 0%, rgba(143, 230, 168, 0.2) 100%);
  border: 2px solid rgba(180, 248, 200, 0.3);
  color: #B4F8C8;
}

.icon-orange {
  background: linear-gradient(135deg, rgba(255, 205, 163, 0.2) 0%, rgba(255, 179, 128, 0.2) 100%);
  border: 2px solid rgba(255, 205, 163, 0.3);
  color: #FFCDA3;
}

.slide-text {
  text-align: left;
}

.slide-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: white;
}

@media (min-width: 768px) {
  .slide-title {
    font-size: 2.5rem;
  }
}

.slide-description {
  font-size: 1.125rem;
  color: #D1D5DB;
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.slide-features {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1rem;
  color: #E5E7EB;
}

.feature-icon {
  flex-shrink: 0;
}

.slide-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.875rem 1.75rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}

.carousel-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(189, 212, 255, 0.1);
  border: 1px solid rgba(189, 212, 255, 0.2);
  border-radius: 50%;
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #BDD4FF;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
  backdrop-filter: blur(12px);
}

.carousel-nav:hover {
  background: rgba(189, 212, 255, 0.2);
  transform: translateY(-50%) scale(1.1);
}

.carousel-nav-prev {
  left: -1rem;
}

.carousel-nav-next {
  right: -1rem;
}

@media (min-width: 768px) {
  .carousel-nav-prev {
    left: -4rem;
  }
  
  .carousel-nav-next {
    right: -4rem;
  }
}

.carousel-indicators {
  display: flex;
  justify-content: center;
  gap: 0.75rem;
  margin-top: 2rem;
}

.indicator {
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 50%;
  background: rgba(189, 212, 255, 0.2);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator:hover {
  background: rgba(189, 212, 255, 0.4);
  transform: scale(1.2);
}

.indicator-active {
  background: #BDD4FF;
  width: 2rem;
  border-radius: 0.375rem;
}

/* Slide Transitions */
.slide-left-enter-active,
.slide-left-leave-active,
.slide-right-enter-active,
.slide-right-leave-active {
  transition: all 0.5s ease;
}

.slide-left-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.slide-left-leave-to {
  opacity: 0;
  transform: translateX(-100px);
}

.slide-right-enter-from {
  opacity: 0;
  transform: translateX(-100px);
}

.slide-right-leave-to {
  opacity: 0;
  transform: translateX(100px);
}
</style>