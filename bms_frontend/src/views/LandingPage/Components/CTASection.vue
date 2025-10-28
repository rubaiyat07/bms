<template>
  <section class="cta-section">
    <div class="max-w-4xl mx-auto text-center">
      <div class="cta-card">
        <div class="cta-bg">
          <div class="cta-bg-blur cta-bg-blur-1"></div>
          <div class="cta-bg-blur cta-bg-blur-2"></div>
        </div>
        
        <div class="cta-content">
          <h2 class="cta-heading">
            Ready to Streamline Your Branch Network?
          </h2>
          <p class="cta-subheading">
            Start managing all your branches efficiently. Free 14-day trial with full features. No credit card required.
          </p>
          <router-link to="/register" class="cta-button">
            Start Your Free Trial
            <ArrowRight class="w-6 h-6 group-hover:translate-x-2 transition-transform" />
          </router-link>
          <p class="cta-note">
            ✓ Setup in minutes  ✓ No technical knowledge required  ✓ Cancel anytime
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ArrowRight } from 'lucide-vue-next'
</script>

<style scoped>
.cta-section {
  padding: 5rem 1rem;
}

.cta-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.1) 0%, rgba(189, 212, 255, 0.05) 100%);
  border-radius: 1.5rem;
  padding: 3rem;
  backdrop-filter: blur(12px);
  border: 1px solid rgba(189, 212, 255, 0.15);
  position: relative;
  overflow: hidden;
}

.cta-bg {
  position: absolute;
  inset: 0;
  opacity: 0.1;
}

.cta-bg-blur {
  position: absolute;
  border-radius: 9999px;
  filter: blur(80px);
}

.cta-bg-blur-1 {
  top: 0;
  right: 0;
  width: 16rem;
  height: 16rem;
  background: linear-gradient(135deg, #BDD4FF 0%, #8FB3FF 100%);
}

.cta-bg-blur-2 {
  bottom: 0;
  left: 0;
  width: 16rem;
  height: 16rem;
  background: linear-gradient(135deg, #6B9AFF 0%, #BDD4FF 100%);
}

.cta-content {
  position: relative;
  z-index: 10;
}

.cta-heading {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.2;
}

@media (min-width: 768px) {
  .cta-heading {
    font-size: 3rem;
  }
}

.cta-subheading {
  font-size: 1.25rem;
  color: #D1D5DB;
  margin-bottom: 2rem;
  max-width: 36rem;
  margin-left: auto;
  margin-right: auto;
}

.cta-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1.25rem 2.5rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 9999px;
  font-weight: 700;
  font-size: 1.25rem;
  color: #0F1419;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);
  text-decoration: none;
}

.cta-button:hover {
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(189, 212, 255, 0.5);
}

.cta-note {
  margin-top: 1.5rem;
  color: #9CA3AF;
  font-size: 0.875rem;
}
</style>