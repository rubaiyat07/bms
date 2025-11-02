<template>
  <div class="contact-page">
    <main class="main-content">
      <section class="contact-section">
        <div class="max-w-7xl mx-auto">
          <!-- Section Header -->
          <div class="contact-header">
            <h1 class="contact-title">
              Get in Touch
            </h1>
            <p class="contact-subtitle">
              Have questions about Branch Manager System? We're here to help you succeed.
            </p>
          </div>

          <!-- Contact Content -->
          <div class="contact-content">
            <!-- Contact Form -->
            <div class="contact-form-container">
              <h2 class="form-title">Send us a Message</h2>
              <form @submit.prevent="submitForm" class="contact-form">
                <div class="form-group">
                  <label for="name" class="form-label">Full Name</label>
                  <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="form-input"
                    required
                  >
                </div>

                <div class="form-group">
                  <label for="email" class="form-label">Email Address</label>
                  <input
                    type="email"
                    id="email"
                    v-model="form.email"
                    class="form-input"
                    required
                  >
                </div>

                <div class="form-group">
                  <label for="company" class="form-label">Company (Optional)</label>
                  <input
                    type="text"
                    id="company"
                    v-model="form.company"
                    class="form-input"
                  >
                </div>

                <div class="form-group">
                  <label for="subject" class="form-label">Subject</label>
                  <select
                    id="subject"
                    v-model="form.subject"
                    class="form-select"
                    required
                  >
                    <option value="">Select a subject</option>
                    <option value="general">General Inquiry</option>
                    <option value="support">Technical Support</option>
                    <option value="sales">Sales & Pricing</option>
                    <option value="demo">Request Demo</option>
                    <option value="partnership">Partnership</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="message" class="form-label">Message</label>
                  <textarea
                    id="message"
                    v-model="form.message"
                    class="form-textarea"
                    rows="6"
                    required
                  ></textarea>
                </div>

                <button type="submit" class="form-button" :disabled="isSubmitting">
                  {{ isSubmitting ? 'Sending...' : 'Send Message' }}
                </button>
              </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info">
              <div class="info-card">
                <div class="info-icon">
                  <Mail class="w-6 h-6" />
                </div>
                <h3 class="info-title">Email Us</h3>
                <p class="info-text">support@branchmanager.com</p>
                <p class="info-text">sales@branchmanager.com</p>
              </div>

              <div class="info-card">
                <div class="info-icon">
                  <Phone class="w-6 h-6" />
                </div>
                <h3 class="info-title">Call Us</h3>
                <p class="info-text">+1 (555) 123-4567</p>
                <p class="info-subtext">Mon-Fri, 9AM-6PM EST</p>
              </div>

              <div class="info-card">
                <div class="info-icon">
                  <MapPin class="w-6 h-6" />
                </div>
                <h3 class="info-title">Visit Us</h3>
                <p class="info-text">
                  123 Business Avenue<br>
                  Suite 100<br>
                  New York, NY 10001
                </p>
              </div>

              <div class="info-card">
                <div class="info-icon">
                  <Clock class="w-6 h-6" />
                </div>
                <h3 class="info-title">Response Time</h3>
                <p class="info-text">Within 24 hours</p>
                <p class="info-subtext">For all inquiries</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Mail, Phone, MapPin, Clock } from 'lucide-vue-next'

const form = ref({
  name: '',
  email: '',
  company: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)

const submitForm = async () => {
  isSubmitting.value = true

  // Simulate form submission
  try {
    await new Promise(resolve => setTimeout(resolve, 2000))
    alert('Thank you for your message! We\'ll get back to you within 24 hours.')
    // Reset form
    form.value = {
      name: '',
      email: '',
      company: '',
      subject: '',
      message: ''
    }
  } catch (error) {
    alert('There was an error sending your message. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.contact-page {
  min-height: 100vh;
  background: #f8fafc;
}

.main-content {
  padding-top: 5rem; /* Account for fixed header */
}

.contact-section {
  padding: 5rem 1rem;
}

.contact-header {
  text-align: center;
  margin-bottom: 4rem;
}

.contact-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #2a3547 0%, #2c4264 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.contact-subtitle {
  font-size: 1.125rem;
  color: #6B7280;
}

.contact-content {
  display: grid;
  grid-template-columns: 1fr;
  gap: 4rem;
}

@media (min-width: 1024px) {
  .contact-content {
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
  }
}

.contact-form-container {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 2rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
}

.form-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1F2937;
  margin-bottom: 2rem;
}

.contact-form {
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
  font-weight: 500;
  color: #374151;
}

.form-input,
.form-select,
.form-textarea {
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.726);
  border: 1px solid rgba(189, 212, 255, 0.2);
  border-radius: 0.5rem;
  color: #1F2937;
  font-size: 0.875rem;
  transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: rgba(189, 212, 255, 0.5);
  box-shadow: 0 0 0 3px rgba(189, 212, 255, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 120px;
}

.form-button {
  padding: 0.75rem 2rem;
  background: linear-gradient(135deg, #8FB3FF 0%, #BDD4FF 100%);
  border: none;
  border-radius: 0.5rem;
  color: #0F1419;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.form-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(189, 212, 255, 0.3);
}

.form-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.contact-info {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

@media (min-width: 640px) {
  .contact-info {
    grid-template-columns: repeat(2, 1fr);
  }
}

.info-card {
  background: linear-gradient(135deg, rgba(189, 212, 255, 0.863) 0%, rgba(189, 212, 255, 0) 100%);
  border-radius: 1rem;
  padding: 2rem;
  border: 1px solid rgba(189, 212, 255, 0.1);
  text-align: center;
  transition: all 0.3s ease;
}

.info-card:hover {
  transform: translateY(-5px);
  border-color: rgba(189, 212, 255, 0.3);
}

.info-icon {
  width: 3rem;
  height: 3rem;
  background: linear-gradient(135deg, rgba(189, 212, 255, 0) 0%, rgba(143, 179, 255, 0.2) 100%);
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem auto;
  color: #576175;
}

.info-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1F2937;
  margin-bottom: 0.5rem;
}

.info-text {
  color: #374151;
  font-size: 0.875rem;
  line-height: 1.5;
  margin-bottom: 0.25rem;
}

.info-subtext {
  color: #9CA3AF;
  font-size: 0.75rem;
}
</style>
