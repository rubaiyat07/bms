<template>
  <footer class="app-footer">
    <div class="footer-container">
      <div class="footer-grid">
        <!-- Company Info -->
        <div class="footer-column">
          <div class="footer-logo">
            <div class="logo-icon">BMS</div>
            <span class="logo-text">Branch Manager</span>
          </div>
          <p class="footer-description">
            Empowering businesses with intelligent branch management solutions.
          </p>
          <div class="footer-social">
            <a href="#" class="social-link">Twitter</a>
            <a href="#" class="social-link">LinkedIn</a>
            <a href="#" class="social-link">GitHub</a>
          </div>
        </div>

        <!-- Product -->
        <div class="footer-column">
          <h3 class="footer-heading">Product</h3>
          <ul class="footer-links">
            <li><a href="#" class="footer-link">Features</a></li>
            <li><a href="#" class="footer-link">Pricing</a></li>
            <li><a href="#" class="footer-link">Integrations</a></li>
            <li><a href="#" class="footer-link">Updates</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div class="footer-column">
          <h3 class="footer-heading">Company</h3>
          <ul class="footer-links">
            <li><a href="#" class="footer-link">About</a></li>
            <li><a href="#" class="footer-link">Blog</a></li>
            <li><a href="#" class="footer-link">Careers</a></li>
            <li><a href="#" class="footer-link">Contact</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div class="footer-column">
          <h3 class="footer-heading">Support</h3>
          <ul class="footer-links">
            <li><a href="#" class="footer-link">Help Center</a></li>
            <li><a href="#" class="footer-link">Documentation</a></li>
            <li><a href="#" class="footer-link">Community</a></li>
            <li><a href="#" class="footer-link">Status</a></li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="footer-bottom">
        <p class="footer-copyright">
          &copy; 2025 BMS. All rights reserved.
        </p>
        <div class="footer-legal">
          <a href="#" class="legal-link">Privacy Policy</a>
          <a href="#" class="legal-link">Terms of Service</a>
          <a href="#" class="legal-link">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter'
}
</script>

<style scoped>
.app-footer {
  background: linear-gradient(180deg, #0F1419 0%, #0A0E13 100%);
  border-top: 1px solid rgba(189, 212, 255, 0.1);
  padding: 4rem 1rem 2rem;
}

.footer-container {
  max-width: 80rem;
  margin: 0 auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 3rem;
  margin-bottom: 3rem;
}

@media (min-width: 640px) {
  .footer-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .footer-grid {
    grid-template-columns: 2fr 1fr 1fr 1fr;
  }
}

.footer-column {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}

.logo-icon {
  width: 2.5rem;
  height: 2.5rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.875rem;
  color: #0F1419;
}

.logo-text {
  font-size: 1.125rem;
  font-weight: 600;
  color: white;
}

.footer-description {
  color: #9CA3AF;
  font-size: 0.875rem;
  line-height: 1.6;
}

.footer-social {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}

.social-link {
  color: #D1D5DB;
  font-size: 0.875rem;
  transition: color 0.3s ease;
  text-decoration: none;
}

.social-link:hover {
  color: #BDD4FF;
}

.footer-heading {
  font-size: 0.875rem;
  font-weight: 600;
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.footer-links {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-link {
  color: #9CA3AF;
  font-size: 0.875rem;
  transition: color 0.3s ease;
  text-decoration: none;
}

.footer-link:hover {
  color: #BDD4FF;
}

.footer-bottom {
  padding-top: 2rem;
  border-top: 1px solid rgba(189, 212, 255, 0.1);
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
  justify-content: space-between;
}

@media (min-width: 768px) {
  .footer-bottom {
    flex-direction: row;
  }
}

.footer-copyright {
  color: #6B7280;
  font-size: 0.875rem;
}

.footer-legal {
  display: flex;
  gap: 1.5rem;
}

.legal-link {
  color: #9CA3AF;
  font-size: 0.875rem;
  transition: color 0.3s ease;
  text-decoration: none;
}

.legal-link:hover {
  color: #BDD4FF;
}
</style>