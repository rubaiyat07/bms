<template>
  <div class="admin-settings">
    <div class="page-header">
      <h1>System Settings</h1>
      <p>Configure system-wide settings and preferences</p>
    </div>

    <div class="settings-content">
      <!-- General Settings -->
      <div class="settings-section">
        <h2>General Settings</h2>
        <div class="settings-grid">
          <div class="setting-item">
            <label>System Name</label>
            <input v-model="settings.systemName" type="text" class="setting-input">
          </div>
          <div class="setting-item">
            <label>Default Language</label>
            <select v-model="settings.defaultLanguage" class="setting-select">
              <option value="en">English</option>
              <option value="es">Spanish</option>
              <option value="fr">French</option>
              <option value="de">German</option>
            </select>
          </div>
          <div class="setting-item">
            <label>Timezone</label>
            <select v-model="settings.timezone" class="setting-select">
              <option value="UTC">UTC</option>
              <option value="EST">Eastern Time</option>
              <option value="PST">Pacific Time</option>
              <option value="GMT">Greenwich Mean Time</option>
            </select>
          </div>
          <div class="setting-item">
            <label>Date Format</label>
            <select v-model="settings.dateFormat" class="setting-select">
              <option value="MM/DD/YYYY">MM/DD/YYYY</option>
              <option value="DD/MM/YYYY">DD/MM/YYYY</option>
              <option value="YYYY-MM-DD">YYYY-MM-DD</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Security Settings -->
      <div class="settings-section">
        <h2>Security Settings</h2>
        <div class="settings-grid">
          <div class="setting-item">
            <label>Session Timeout (minutes)</label>
            <input v-model="settings.sessionTimeout" type="number" class="setting-input">
          </div>
          <div class="setting-item">
            <label>Password Minimum Length</label>
            <input v-model="settings.passwordMinLength" type="number" class="setting-input">
          </div>
          <div class="setting-item">
            <label>Enable Two-Factor Authentication</label>
            <div class="toggle-switch">
              <input v-model="settings.twoFactorEnabled" type="checkbox" id="twoFactor">
              <label for="twoFactor" class="toggle-slider"></label>
            </div>
          </div>
          <div class="setting-item">
            <label>Login Attempts Before Lockout</label>
            <input v-model="settings.loginAttempts" type="number" class="setting-input">
          </div>
        </div>
      </div>

      <!-- Email Settings -->
      <div class="settings-section">
        <h2>Email Settings</h2>
        <div class="settings-grid">
          <div class="setting-item">
            <label>SMTP Host</label>
            <input v-model="settings.smtpHost" type="text" class="setting-input">
          </div>
          <div class="setting-item">
            <label>SMTP Port</label>
            <input v-model="settings.smtpPort" type="number" class="setting-input">
          </div>
          <div class="setting-item">
            <label>SMTP Username</label>
            <input v-model="settings.smtpUsername" type="text" class="setting-input">
          </div>
          <div class="setting-item">
            <label>SMTP Password</label>
            <input v-model="settings.smtpPassword" type="password" class="setting-input">
          </div>
          <div class="setting-item full-width">
            <label>From Email Address</label>
            <input v-model="settings.fromEmail" type="email" class="setting-input">
          </div>
          <div class="setting-item full-width">
            <label>Email Signature</label>
            <textarea v-model="settings.emailSignature" class="setting-textarea" rows="3"></textarea>
          </div>
        </div>
      </div>

      <!-- Notification Settings -->
      <div class="settings-section">
        <h2>Notification Settings</h2>
        <div class="notification-settings">
          <div class="notification-item">
            <div class="notification-info">
              <h4>Email Notifications</h4>
              <p>Send email notifications for important events</p>
            </div>
            <div class="toggle-switch">
              <input v-model="settings.emailNotifications" type="checkbox" id="emailNotif">
              <label for="emailNotif" class="toggle-slider"></label>
            </div>
          </div>
          <div class="notification-item">
            <div class="notification-info">
              <h4>Push Notifications</h4>
              <p>Send browser push notifications</p>
            </div>
            <div class="toggle-switch">
              <input v-model="settings.pushNotifications" type="checkbox" id="pushNotif">
              <label for="pushNotif" class="toggle-slider"></label>
            </div>
          </div>
          <div class="notification-item">
            <div class="notification-info">
              <h4>SMS Notifications</h4>
              <p>Send SMS notifications for critical alerts</p>
            </div>
            <div class="toggle-switch">
              <input v-model="settings.smsNotifications" type="checkbox" id="smsNotif">
              <label for="smsNotif" class="toggle-slider"></label>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="settings-actions">
        <button @click="saveSettings" class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Save Settings
        </button>
        <button @click="resetSettings" class="btn btn-secondary">Reset to Defaults</button>
        <button @click="exportSettings" class="btn btn-secondary">Export Settings</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Mock settings data - replace with API calls
const settings = ref({
  systemName: 'BMS System',
  defaultLanguage: 'en',
  timezone: 'UTC',
  dateFormat: 'MM/DD/YYYY',
  sessionTimeout: 30,
  passwordMinLength: 8,
  twoFactorEnabled: false,
  loginAttempts: 5,
  smtpHost: 'smtp.example.com',
  smtpPort: 587,
  smtpUsername: 'noreply@example.com',
  smtpPassword: '',
  fromEmail: 'noreply@example.com',
  emailSignature: 'Best regards,\nBMS System',
  emailNotifications: true,
  pushNotifications: true,
  smsNotifications: false
})

const saveSettings = () => {
  console.log('Saving settings:', settings.value)
  // Implement save settings logic
  // Show success message
}

const resetSettings = () => {
  if (confirm('Are you sure you want to reset all settings to defaults?')) {
    console.log('Resetting settings to defaults')
    // Implement reset logic
  }
}

const exportSettings = () => {
  console.log('Exporting settings')
  // Implement export logic
}

onMounted(() => {
  // Load settings from API
  console.log('Loading settings...')
})
</script>

<style scoped>
.admin-settings {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  color: #384e74;
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #9CA3AF;
  font-size: 1.125rem;
}

.settings-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.settings-section {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.settings-section h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.setting-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.setting-item.full-width {
  grid-column: 1 / -1;
}

.setting-item label {
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
}

.setting-input,
.setting-select,
.setting-textarea {
  background: rgba(56, 78, 116, 0.05);
  border: 1px solid rgba(56, 78, 116, 0.1);
  border-radius: 0.5rem;
  padding: 0.75rem;
  color: #D1D5DB;
  font-size: 0.875rem;
  transition: border-color 0.3s ease;
}

.setting-input:focus,
.setting-select:focus,
.setting-textarea:focus {
  outline: none;
  border-color: rgba(56, 78, 116, 0.3);
}

.setting-textarea {
  resize: vertical;
  min-height: 80px;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(56, 78, 116, 0.2);
  transition: 0.4s;
  border-radius: 24px;
}

.toggle-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: #D1D5DB;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .toggle-slider {
  background-color: #8FB3FF;
}

input:checked + .toggle-slider:before {
  transform: translateX(26px);
  background-color: #0F1419;
}

.notification-settings {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.notification-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: rgba(56, 78, 116, 0.05);
  border-radius: 0.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.notification-info h4 {
  color: #384e74;
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.notification-info p {
  color: #9CA3AF;
  font-size: 0.875rem;
}

.settings-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 2rem;
  border-top: 1px solid rgba(56, 78, 116, 0.1);
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  color: #0F1419;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 78, 116, 0.3);
}

.btn-secondary {
  background: transparent;
  border: 2px solid rgba(56, 78, 116, 0.3);
  color: #384e74;
}

.btn-secondary:hover {
  background: rgba(56, 78, 116, 0.1);
  border-color: rgba(56, 78, 116, 0.5);
}

@media (max-width: 768px) {
  .admin-settings {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .settings-grid {
    grid-template-columns: 1fr;
  }

  .notification-item {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .settings-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
