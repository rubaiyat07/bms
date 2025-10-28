<template>
  <header class="app-header">
    <div class="header-container">
      <div class="header-logo">
        <router-link to="/" class="logo-link">
          <div class="logo-icon">BMS</div>
          <span class="logo-text">Business Manager</span>
        </router-link>
      </div>

      <nav class="header-nav">
        <router-link to="/" class="nav-link">Home</router-link>
        <router-link to="/features" class="nav-link">Features</router-link>
        <router-link to="/pricing" class="nav-link">Pricing</router-link>
        <router-link to="/about" class="nav-link">About</router-link>
      </nav>

      <div class="header-actions">
        <router-link to="/login" class="btn-login">Sign In</router-link>
        <router-link to="/register" class="btn-register">Get Started</router-link>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader'
}
</script>

<style scoped>
.app-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 50;
  background: rgba(15, 20, 25, 0.8);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
}

.header-container {
  max-width: 80rem;
  margin: 0 auto;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
}

.header-logo {
  flex-shrink: 0;
}

.logo-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
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

.header-nav {
  display: none;
  gap: 2rem;
}

@media (min-width: 768px) {
  .header-nav {
    display: flex;
  }
}

.nav-link {
  color: #D1D5DB;
  font-weight: 500;
  transition: color 0.3s ease;
  text-decoration: none;
}

.nav-link:hover {
  color: #BDD4FF;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.btn-login {
  padding: 0.5rem 1.25rem;
  color: #D1D5DB;
  font-weight: 500;
  transition: color 0.3s ease;
  text-decoration: none;
}

.btn-login:hover {
  color: #BDD4FF;
}

.btn-register {
  padding: 0.5rem 1.25rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border-radius: 0.5rem;
  font-weight: 600;
  color: #0F1419;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-register:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(189, 212, 255, 0.3);
}
</style>