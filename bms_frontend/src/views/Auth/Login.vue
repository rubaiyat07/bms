<template>
  <div class="login-page">
    <div class="login-bg">
      <div class="login-bg-blur login-bg-blur-1"></div>
      <div class="login-bg-blur login-bg-blur-2"></div>
    </div>

    <div class="login-container">
      <div class="login-card">
        <div class="login-header">
          <h2 class="login-title">
            Sign in to your account
          </h2>
          <p class="login-subtitle">Welcome back! Please enter your details</p>
        </div>

        <form class="login-form" @submit.prevent="handleLogin">
          <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input
              id="email"
              name="email"
              type="email"
              required
              class="form-input"
              placeholder="john@company.com"
              v-model="form.email"
            />
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              required
              class="form-input"
              placeholder="••••••••"
              v-model="form.password"
            />
          </div>

          <button
            type="submit"
            class="submit-button"
            :disabled="loading"
          >
            <span v-if="loading" class="loading-spinner">
              <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            <span v-else>Sign In</span>
          </button>

          <p class="form-footer">
            Registration is available through manual admin setup only.
          </p>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth.js'

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(false)
const form = ref({
  email: '',
  password: ''
})

const handleLogin = async () => {
  loading.value = true
  try {
    // Call auth store login with form credentials
    const result = await authStore.login(form.value)
    if (result.success) {
      // Redirect to admin dashboard after successful login
      router.push('/admin/dashboard')
    } else {
      console.error('Login failed:', result.message)
      // TODO: Show error message to user
    }
  } catch (error) {
    console.error('Login failed:', error)
    // TODO: Show error message to user
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(180deg, #51709c 0%, #d4e0f1 100%);
  padding: 3rem 1rem;
  position: relative;
  overflow: hidden;
}

.login-bg {
  position: absolute;
  inset: 0;
  opacity: 0.1;
}

.login-bg-blur {
  position: absolute;
  border-radius: 9999px;
  filter: blur(100px);
}

.login-bg-blur-1 {
  top: -5rem;
  left: -5rem;
  width: 30rem;
  height: 30rem;
  background: linear-gradient(135deg, #BDD4FF 0%, #8FB3FF 100%);
}

.login-bg-blur-2 {
  bottom: -5rem;
  right: -5rem;
  width: 30rem;
  height: 30rem;
  background: linear-gradient(135deg, #6B9AFF 0%, #BDD4FF 100%);
}

.login-container {
  max-width: 28rem;
  width: 100%;
  position: relative;
  z-index: 10;
}

.login-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.08) 0%, rgba(189, 212, 255, 0.03) 100%);
  margin-top: 50px;
  border-radius: 1.5rem;
  padding: 3rem;
  backdrop-filter: blur(12px);
  border: 1px solid rgba(189, 212, 255, 0.15);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-title {
  font-size: 2rem;
  font-weight: 700;
  color: white;
  margin-bottom: 0.5rem;
}

.login-subtitle {
  color: #cadeff;
  font-size: 0.875rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #dce7f7;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  background: #353b42;
  border: 1px solid rgba(189, 212, 255, 0.2);
  border-radius: 0.5rem;
  color: rgb(214, 214, 214);
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #BDD4FF;
  box-shadow: 0 0 0 3px rgba(189, 212, 255, 0.1);
}

.form-input::placeholder {
  color: #6B7280;
}

.submit-button {
  width: 100%;
  padding: 0.875rem;
  background: linear-gradient(135deg, #8c9bec 0%, #BDD4FF 100%);
  border: none;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
  color: #0F1419;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(189, 212, 255, 0.3);
}

.submit-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.loading-spinner {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  color: #0F1419;
}

.form-footer {
  text-align: center;
  color: #646870;
  font-size: 0.875rem;
}

.form-link {
  color: #BDD4FF;
  font-weight: 600;
  transition: color 0.3s ease;
}

.form-link:hover {
  color: #D9E7FF;
}
</style>
