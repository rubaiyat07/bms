<template>
  <div class="features-page">
    <main class="main-content">
      <FeaturesSection />

      <!-- CTA Section for Features Page -->
      <section class="features-cta">
        <h2>Ready to Get Started?</h2>
        <p>Experience the power of centralized branch management. Join thousands of businesses already using our platform.</p>
        <div class="features-buttons">
          <router-link to="/pricing" class="features-button">
            View Pricing
          </router-link>
          <router-link to="/contact" class="features-button-secondary">
            Contact Sales
          </router-link>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import FeaturesSection from './LandingPage/components/FeaturesSection.vue'
</script>

<style scoped>
.features-page {
  min-height: 100vh;
  background: #f8fafc;
}

.main-content {
  padding-top: 5rem; /* Account for fixed header */
}

.features-cta {
  text-align: center;
  margin-top: 4rem;
  padding: 3rem 1rem;
}

.features-cta h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1d345f 0%, #62a1ff 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.features-cta p {
  color: #374151;
  margin-bottom: 2rem;
  max-width: 36rem;
  margin-left: auto;
  margin-right: auto;
}

.features-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: center;
}

@media (min-width: 640px) {
  .features-buttons {
    flex-direction: row;
    gap: 1.5rem;
  }
}

.features-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 9999px;
  font-weight: 600;
  color: #0F1419;
  transition: all 0.3s ease;
  text-decoration: none;
}

.features-button:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);
}

.features-button-secondary {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  background: transparent;
  border: 2px solid #8FB3FF;
  border-radius: 9999px;
  font-weight: 600;
  color: #8FB3FF;
  transition: all 0.3s ease;
  text-decoration: none;
}

.features-button-secondary:hover {
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  color: #0F1419;
  transform: scale(1.05);
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);

}
</style>
