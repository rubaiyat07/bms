<template>
  <div class="about-page">
    <main class="main-content">
      <section class="about-section">
        <div class="max-w-7xl mx-auto">
          <!-- Section Header -->
          <div class="about-header">
            <h1 class="about-title">
              About Branch Manager System
            </h1>
            <p class="about-subtitle">
              Revolutionizing branch management with innovative technology and comprehensive solutions
            </p>
          </div>

          <!-- About Content -->
          <div class="about-content">
            <!-- Text Content -->
            <div class="about-text">
              <h2>Our Mission</h2>
              <p>
                At Branch Manager System, we believe that managing multiple branches shouldn't be a complex challenge.
                Our mission is to empower businesses with intuitive, powerful tools that streamline operations,
                enhance productivity, and drive growth across all locations.
              </p>

              <h2>What We Do</h2>
              <p>
                We provide a comprehensive branch management platform that centralizes all your business operations.
                From inventory tracking and staff management to financial reporting and analytics,
                our system ensures every branch operates efficiently and stays connected to your main office.
              </p>

              <h2>Why Choose Us</h2>
              <ul>
                <li><strong>Centralized Control:</strong> Manage all branches from a single, intuitive dashboard</li>
                <li><strong>Real-time Insights:</strong> Get instant updates and analytics on all operations</li>
                <li><strong>Scalable Solutions:</strong> Grow from 1 to 100+ branches seamlessly</li>
                <li><strong>Expert Support:</strong> Dedicated team ready to help you succeed</li>
                <li><strong>Security First:</strong> Enterprise-grade security for your sensitive data</li>
              </ul>

              <h2>Our Story</h2>
              <p>
                Founded in 2020, Branch Manager System was born from the frustration of business owners
                struggling to manage multiple locations effectively. We saw the need for a comprehensive solution
                that could adapt to businesses of all sizes and industries. Today, we're proud to serve
                thousands of businesses worldwide, helping them achieve operational excellence.
              </p>
            </div>

            <!-- Stats -->
            <div class="about-stats">
              <div class="stat-item">
                <div class="stat-number">10,000+</div>
                <div class="stat-label">Active Users</div>
              </div>
              <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Businesses Served</div>
              </div>
              <div class="stat-item">
                <div class="stat-number">50,000+</div>
                <div class="stat-label">Branches Managed</div>
              </div>
              <div class="stat-item">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Uptime</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section for About Page -->
      <section class="about-cta">
        <div class="max-w-4xl mx-auto text-center">
          <h2>Ready to Join Our Success Story?</h2>
          <p>Experience the power of centralized branch management. Start your journey with Branch Manager System today.</p>
          <div class="about-buttons">
            <router-link to="/pricing" class="about-button">
              View Pricing
            </router-link>
            <router-link to="/contact" class="about-button-secondary">
              Contact Us
            </router-link>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
// No imports needed since header and footer are handled by AppLayout
</script>

<style scoped>
.about-page {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
}

.main-content {
  padding-top: 5rem; /* Account for fixed header */
}

.about-section {
  padding: 5rem 1rem;
}

.about-header {
  text-align: center;
  margin-bottom: 4rem;
}

.about-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #2a3547 0%, #2c4264 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.about-subtitle {
  font-size: 1.125rem;
  color: #374151;
}

.about-content {
  display: grid;
  grid-template-columns: 1fr;
  gap: 4rem;
}

@media (min-width: 1024px) {
  .about-content {
    grid-template-columns: 2fr 1fr;
    gap: 4rem;
  }
}

.about-text h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1F2937;
  margin-bottom: 1rem;
  margin-top: 2rem;
}

.about-text h2:first-child {
  margin-top: 0;
}

.about-text p {
  color: #374151;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.about-text ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.about-text li {
  color: #374151;
  margin-bottom: 0.75rem;
  padding-left: 1.5rem;
  position: relative;
}

.about-text li::before {
  content: '•';
  position: absolute;
  left: 0;
  color: #BDD4FF;
  font-weight: 700;
}

.about-text strong {
  color: #2956a8;
}

.about-stats {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

@media (min-width: 640px) {
  .about-stats {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .about-stats {
    grid-template-columns: 1fr;
  }
}

.stat-item {
  text-align: center;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.836) 0%, rgba(189, 212, 255, 0.02) 100%);
  border-radius: 1rem;
  border: 1px solid rgba(189, 212, 255, 0.151);
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #1244a1 0%, #9ca0a7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #637088;
  font-size: 0.875rem;
  font-weight: 500;
}

.about-cta {
  padding: 5rem 1rem;
  text-align: center;
}

.about-cta h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1d345f 0%, #62a1ff 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.about-cta p {
  color: #374151;
  margin-bottom: 2rem;
  max-width: 36rem;
  margin-left: auto;
  margin-right: auto;
}

.about-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: center;
}

@media (min-width: 640px) {
  .about-buttons {
    flex-direction: row;
    gap: 1.5rem;
  }
}

.about-button {
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

.about-button:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 30px rgba(189, 212, 255, 0.3);
}

.about-button-secondary {
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

.about-button-secondary:hover {
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  color: #0F1419;  border-color: rgba(189, 212, 255, 0.8);
  transform: scale(1.05);
}
</style>
