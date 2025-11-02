<template>
  <div class="pricing-page">
    <main class="main-content">
      <section class="pricing-section">
        <div class="max-w-7xl mx-auto">
          <!-- Section Header -->
          <div class="pricing-header">
            <h1 class="pricing-title">
              Choose Your Plan
            </h1>
            <p class="pricing-subtitle">
              Select the perfect plan for your branch management needs
            </p>
          </div>

          <!-- Pricing Cards -->
          <div class="pricing-grid">
            <div class="pricing-card">
              <div class="pricing-header">
                <h3 class="pricing-plan-name">Starter</h3>
                <div class="pricing-price">
                  <span class="price-amount">$29</span>
                  <span class="price-period">/month</span>
                </div>
                <p class="pricing-description">Perfect for small businesses with up to 5 branches</p>
              </div>
              <ul class="pricing-features">
                <li>Up to 5 branches</li>
                <li>Basic inventory tracking</li>
                <li>Staff management</li>
                <li>Email support</li>
                <li>Basic reporting</li>
              </ul>
              <button class="pricing-button">Get Started</button>
            </div>

            <div class="pricing-card pricing-card-popular">
              <div class="popular-badge">Most Popular</div>
              <h3 class="pricing-plan-name">Professional</h3>
              <div class="pricing-price">
                <span class="price-amount">$79</span>
                <span class="price-period">/month</span>
              </div>
              <p class="pricing-description">Ideal for growing businesses with multiple locations</p>
              <ul class="pricing-features">
                <li>Up to 25 branches</li>
                <li>Advanced inventory control</li>
                <li>Analytics & reporting</li>
                <li>Priority support</li>
                <li>API access</li>
                <li>Custom integrations</li>
              </ul>
              <button class="pricing-button pricing-button-primary">Get Started</button>
            </div>

            <div class="pricing-card">
              <div class="pricing-header">
                <h3 class="pricing-plan-name">Enterprise</h3>
                <div class="pricing-price">
                  <span class="price-amount">Custom</span>
                </div>
                <p class="pricing-description">Tailored solutions for large organizations</p>
              </div>
              <ul class="pricing-features">
                <li>Unlimited branches</li>
                <li>All features included</li>
                <li>Dedicated account manager</li>
                <li>24/7 phone support</li>
                <li>Custom development</li>
                <li>On-premise deployment</li>
              </ul>
              <router-link to="/contact" class="pricing-button">Contact Sales</router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section for Pricing Page -->
      <section class="pricing-cta">
        <div class="max-w-4xl mx-auto text-center">
          <h2>Need a Custom Solution?</h2>
          <p>Have specific requirements or need enterprise features? Our team is here to help you find the perfect solution.</p>
          <router-link to="/contact" class="pricing-cta-button">
            Contact Our Team
          </router-link>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
// No imports needed since header and footer are handled by AppLayout
</script>

<style scoped>
.pricing-page {
  min-height: 100vh;
  background: #f8fafc;
}

.main-content {
  padding-top: 5rem; /* Account for fixed header */
}

.pricing-section {
  padding: 5rem 1rem;
}

.pricing-header {
  text-align: center;
  margin-bottom: 4rem;
}

.pricing-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #2a3547 0%, #2c4264 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.pricing-subtitle {
  font-size: 1.125rem;
  color: #9CA3AF;
}

.pricing-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

@media (min-width: 768px) {
  .pricing-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.pricing-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 2rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
  transition: all 0.3s ease;
  position: relative;
}

.pricing-card:hover {
  transform: translateY(-5px);
  border-color: rgba(189, 212, 255, 0.3);
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.295) 0%, rgba(189, 212, 255, 0.04) 100%);
}

.pricing-card-popular {
  border-color: rgba(189, 212, 255, 0.3);
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
}

.popular-badge {
  position: absolute;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  color: #0F1419;
  padding: 0.5rem 1rem;
  border-radius: 1rem;
  font-size: 0.875rem;
  font-weight: 600;
}

.pricing-card .pricing-header {
  margin-bottom: 2rem;
  text-align: center;
}

.pricing-plan-name {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1F2937;
  margin-bottom: 1rem;
}

.pricing-price {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 0.25rem;
  margin-bottom: 0.5rem;
}

.price-amount {
  font-size: 3rem;
  font-weight: 700;
  background: linear-gradient(135deg, #1244a1 0%, #9ca0a7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.price-period {
  font-size: 1.125rem;
  color: #484c53;
}

.pricing-description {
  color: #323946;
  font-size: 0.875rem;
}

.pricing-features {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.pricing-features li {
  color: #86888d;
  font-size: 0.875rem;
  padding-left: 1.5rem;
  position: relative;
}

.pricing-features li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #5981ca;
  font-weight: 700;
}

.pricing-button {
  width: 100%;
  padding: 0.75rem 1rem;
  background: transparent;
  border: 2px solid rgba(189, 212, 255, 0.3);
  border-radius: 0.5rem;
  color: #3677f1;
  font-weight: 600;
  transition: all 0.3s ease;
  cursor: pointer;
}

.pricing-button:hover {
  background: rgba(135, 143, 158, 0.438);
  border-color: rgba(189, 212, 255, 0.5);
}

.pricing-button-primary {
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-color: transparent;
  color: #0F1419;
}

.pricing-button-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(189, 212, 255, 0.3);
}

.pricing-cta {
  padding: 5rem 1rem;
  text-align: center;
}

.pricing-cta h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1d345f 0%, #62a1ff 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.pricing-cta p {
  color: #374151;
  margin-bottom: 2rem;
  max-width: 36rem;
  margin-left: auto;
  margin-right: auto;
}

.pricing-cta-button {
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

.pricing-cta-button:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);
}
</style>
