<template>
  <div class="department-form">
    <div class="form-container">
      <form @submit.prevent="handleSubmit" class="department-form-content">
        <div class="form-grid">
          <div class="form-group">
            <label for="name">Department Name *</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              placeholder="Enter department name"
              :class="{ 'error': errors.name }"
            />
            <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
          </div>

          <div class="form-group">
            <label for="code">Department Code *</label>
            <input
              id="code"
              v-model="form.code"
              type="text"
              required
              placeholder="Enter department code"
              :class="{ 'error': errors.code }"
            />
            <span v-if="errors.code" class="error-message">{{ errors.code }}</span>
          </div>

          <div class="form-group full-width">
            <label for="description">Description</label>
            <textarea
              id="description"
              v-model="form.description"
              placeholder="Enter department description"
              rows="3"
              :class="{ 'error': errors.description }"
            ></textarea>
            <span v-if="errors.description" class="error-message">{{ errors.description }}</span>
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
            <label for="manager_id">Department Manager</label>
            <select
              id="manager_id"
              v-model="form.manager_id"
              :class="{ 'error': errors.manager_id }"
            >
              <option value="">Select Department Manager</option>
              <option v-for="user in managers" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
            <span v-if="errors.manager_id" class="error-message">{{ errors.manager_id }}</span>
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
            </select>
            <span v-if="errors.status" class="error-message">{{ errors.status }}</span>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" @click="$router.go(-1)" class="btn btn-secondary">Cancel</button>
          <button type="submit" :disabled="loading" class="btn btn-primary">
            <span v-if="loading">Saving...</span>
            <span v-else>{{ isEditing ? 'Update Department' : 'Create Department' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { api } from '@/services/api'

const route = useRoute()
const router = useRouter()

const isEditing = ref(false)
const loading = ref(false)
const managers = ref([])
const branches = ref([])
const errors = ref({})

const form = ref({
  name: '',
  code: '',
  description: '',
  branch_id: '',
  manager_id: '',
  status: 'active'
} as {
  name: string
  code: string
  description: string
  branch_id: string
  manager_id: string
  status: string
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

const fetchDepartment = async (id: string) => {
  try {
    loading.value = true
    const response = await api.get(`/departments/${id}`)
    if (response.success) {
      const department = response.data
      form.value = {
        name: department.name || '',
        code: department.code || '',
        description: department.description || '',
        branch_id: department.branch_id || '',
        manager_id: department.manager_id || '',
        status: department.status || 'active'
      }
    }
  } catch (error) {
    console.error('Error fetching department:', error)
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  try {
    loading.value = true
    errors.value = {}

    const payload = { ...form.value }
    if (!payload.description) delete payload.description
    if (!payload.manager_id) delete payload.manager_id

    let response
    if (isEditing.value) {
      response = await api.put(`/departments/${route.params.id}`, payload)
    } else {
      response = await api.post('/departments', payload)
    }

    if (response.success) {
      router.push('/admin/departments')
    } else {
      errors.value = response.errors || { general: 'An error occurred' }
    }
  } catch (error) {
    console.error('Error saving department:', error)
    errors.value = { general: 'Failed to save department' }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchBranches()
  fetchManagers()

  if (route.params.id) {
    isEditing.value = true
    fetchDepartment(route.params.id as string)
  }
})
</script>

<style scoped>
.department-form {
  padding: 1rem;
  max-width: 800px;
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
  gap: 1.5rem;
  margin-bottom: 2rem;
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
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 0.75rem;
  border: 2px solid rgba(56, 78, 116, 0.2);
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: border-color 0.3s ease;
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

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }
}
</style>
