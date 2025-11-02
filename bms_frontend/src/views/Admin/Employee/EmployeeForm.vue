<template>
  <div class="employee-form">
    <div class="form-container">
      <form @submit.prevent="handleSubmit" class="employee-form-content">
        <div class="form-grid">
          <!-- Personal Information -->
          <div class="form-section">
            <h3>Personal Information</h3>

            <div class="form-group">
              <label for="name">Full Name *</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                placeholder="Enter full name"
                :class="{ 'error': errors.name }"
              />
              <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
            </div>

            <div class="form-group">
              <label for="email">Email Address *</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                placeholder="Enter email address"
                :class="{ 'error': errors.email }"
              />
              <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                placeholder="Enter phone number"
                :class="{ 'error': errors.phone }"
              />
              <span v-if="errors.phone" class="error-message">{{ errors.phone }}</span>
            </div>

            <div class="form-group">
              <label for="date_of_birth">Date of Birth</label>
              <input
                id="date_of_birth"
                v-model="form.date_of_birth"
                type="date"
                :class="{ 'error': errors.date_of_birth }"
              />
              <span v-if="errors.date_of_birth" class="error-message">{{ errors.date_of_birth }}</span>
            </div>

            <div class="form-group">
              <label for="gender">Gender</label>
              <select
                id="gender"
                v-model="form.gender"
                :class="{ 'error': errors.gender }"
              >
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
              <span v-if="errors.gender" class="error-message">{{ errors.gender }}</span>
            </div>

            <div class="form-group full-width">
              <label for="address">Address</label>
              <textarea
                id="address"
                v-model="form.address"
                placeholder="Enter full address"
                rows="3"
                :class="{ 'error': errors.address }"
              ></textarea>
              <span v-if="errors.address" class="error-message">{{ errors.address }}</span>
            </div>
          </div>

          <!-- Employment Information -->
          <div class="form-section">
            <h3>Employment Information</h3>

            <div class="form-group">
              <label for="employee_id">Employee ID *</label>
              <input
                id="employee_id"
                v-model="form.employee_id"
                type="text"
                required
                placeholder="Enter employee ID"
                :class="{ 'error': errors.employee_id }"
              />
              <span v-if="errors.employee_id" class="error-message">{{ errors.employee_id }}</span>
            </div>

            <div class="form-group">
              <label for="designation">Designation *</label>
              <input
                id="designation"
                v-model="form.designation"
                type="text"
                required
                placeholder="Enter designation"
                :class="{ 'error': errors.designation }"
              />
              <span v-if="errors.designation" class="error-message">{{ errors.designation }}</span>
            </div>

            <div class="form-group">
              <label for="department">Department</label>
              <input
                id="department"
                v-model="form.department"
                type="text"
                placeholder="Enter department"
                :class="{ 'error': errors.department }"
              />
              <span v-if="errors.department" class="error-message">{{ errors.department }}</span>
            </div>

            <div class="form-group">
              <label for="branch_id">Branch *</label>
              <select
                id="branch_id"
                v-model="form.branch_id"
                required
                :class="{ 'error': errors.branch_id }"
              >
                <option value="">Select Branch</option>
                <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                  {{ branch.name }}
                </option>
              </select>
              <span v-if="errors.branch_id" class="error-message">{{ errors.branch_id }}</span>
            </div>

            <div class="form-group">
              <label for="manager_id">Manager</label>
              <select
                id="manager_id"
                v-model="form.manager_id"
                :class="{ 'error': errors.manager_id }"
              >
                <option value="">Select Manager</option>
                <option v-for="manager in managers" :key="manager.id" :value="manager.id">
                  {{ manager.name }}
                </option>
              </select>
              <span v-if="errors.manager_id" class="error-message">{{ errors.manager_id }}</span>
            </div>

            <div class="form-group">
              <label for="join_date">Join Date *</label>
              <input
                id="join_date"
                v-model="form.join_date"
                type="date"
                required
                :class="{ 'error': errors.join_date }"
              />
              <span v-if="errors.join_date" class="error-message">{{ errors.join_date }}</span>
            </div>

            <div class="form-group">
              <label for="salary">Salary</label>
              <input
                id="salary"
                v-model="form.salary"
                type="number"
                step="0.01"
                placeholder="Enter salary"
                :class="{ 'error': errors.salary }"
              />
              <span v-if="errors.salary" class="error-message">{{ errors.salary }}</span>
            </div>

            <div class="form-group">
              <label for="status">Status *</label>
              <select
                id="status"
                v-model="form.status"
                required
                :class="{ 'error': errors.status }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
                <option value="on_leave">On Leave</option>
              </select>
              <span v-if="errors.status" class="error-message">{{ errors.status }}</span>
            </div>

            <div class="form-group">
              <label for="performance_rating">Performance Rating</label>
              <select
                id="performance_rating"
                v-model="form.performance_rating"
                :class="{ 'error': errors.performance_rating }"
              >
                <option value="">Select Rating</option>
                <option value="excellent">Excellent</option>
                <option value="good">Good</option>
                <option value="average">Average</option>
                <option value="weak">Weak</option>
              </select>
              <span v-if="errors.performance_rating" class="error-message">{{ errors.performance_rating }}</span>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" @click="$router.go(-1)" class="btn btn-secondary">Cancel</button>
          <button type="submit" :disabled="loading" class="btn btn-primary">
            <span v-if="loading">Saving...</span>
            <span v-else>{{ isEditing ? 'Update Employee' : 'Create Employee' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { api } from '@/services/api'

const route = useRoute()
const router = useRouter()

const isEditing = ref(false)
const loading = ref(false)
const branches = ref([])
const managers = ref([])
const errors = ref({})

const form = ref({
  name: '',
  email: '',
  phone: '',
  date_of_birth: '',
  gender: '',
  address: '',
  employee_id: '',
  designation: '',
  department: '',
  branch_id: '',
  manager_id: '',
  join_date: '',
  salary: '',
  status: 'active',
  performance_rating: ''
})

const fetchBranches = async () => {
  try {
    const response = await api.get('/branches')
    if (response.success) {
      branches.value = response.data.data
    }
  } catch (error) {
    console.error('Error fetching branches:', error)
  }
}

const fetchManagers = async () => {
  try {
    const response = await api.get('/users?role=manager')
    if (response.success) {
      managers.value = response.data.data
    }
  } catch (error) {
    console.error('Error fetching managers:', error)
  }
}

const fetchEmployee = async (id) => {
  try {
    loading.value = true
    const response = await api.get(`/users/${id}`)
    if (response.success) {
      const employee = response.data
      form.value = {
        name: employee.name || '',
        email: employee.email || '',
        phone: employee.phone || '',
        date_of_birth: employee.date_of_birth ? employee.date_of_birth.split('T')[0] : '',
        gender: employee.gender || '',
        address: employee.address || '',
        employee_id: employee.employee_id || '',
        designation: employee.designation || '',
        department: employee.department || '',
        branch_id: employee.branch_id || '',
        manager_id: employee.manager_id || '',
        join_date: employee.join_date ? employee.join_date.split('T')[0] : '',
        salary: employee.salary || '',
        status: employee.status || 'active',
        performance_rating: employee.performance_rating || ''
      }
    }
  } catch (error) {
    console.error('Error fetching employee:', error)
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  try {
    loading.value = true
    errors.value = {}

    const payload = { ...form.value }
    // Clean up empty values
    Object.keys(payload).forEach(key => {
      if (!payload[key]) delete payload[key]
    })

    let response
    if (isEditing.value) {
      response = await api.put(`/users/${route.params.id}`, payload)
    } else {
      response = await api.post('/users', payload)
    }

    if (response.success) {
      router.push('/admin/employees')
    } else {
      errors.value = response.errors || { general: 'An error occurred' }
    }
  } catch (error) {
    console.error('Error saving employee:', error)
    errors.value = { general: 'Failed to save employee' }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchBranches()
  fetchManagers()

  if (route.params.id) {
    isEditing.value = true
    fetchEmployee(route.params.id)
  }
})
</script>

<style scoped>
.employee-form {
  padding: 1rem;
  max-width: 1200px;
  margin: 0 auto;
}

.form-container {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.form-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-section h3 {
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid rgba(56, 78, 116, 0.1);
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 600;
  color: #384e74;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 0.75rem;
  border: 2px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: border-color 0.3s ease;
  background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #384e74;
}

.form-group input.error,
.form-group select.error,
.form-group textarea.error {
  border-color: #EF4444;
}

.error-message {
  color: #EF4444;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
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
  text-decoration: none;
}

.btn-primary {
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  color: #0F1419;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 78, 116, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
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

@media (max-width: 1024px) {
  .form-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
}

@media (max-width: 768px) {
  .employee-form {
    padding: 1rem;
  }

  .form-container {
    padding: 1.5rem;
  }

  .form-actions {
    flex-direction: column;
  }
}
</style>
