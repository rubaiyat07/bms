<template>
  <div v-if="show" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h3>Delete Department</h3>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the department "{{ department.name }}"?</p>
        <p class="warning">This action cannot be undone. Please download the department data first.</p>

        <div class="download-section">
          <button @click="downloadCSV" class="btn btn-secondary" :disabled="downloaded">
            {{ downloaded ? 'Data Downloaded' : 'Download Department Data (CSV)' }}
          </button>
        </div>

        <div v-if="downloaded" class="reason-section">
          <label for="reason">Reason for deletion (required):</label>
          <textarea
            id="reason"
            v-model="reason"
            placeholder="Please provide a reason for deleting this department..."
            rows="4"
            required
          ></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button @click="closeModal" class="btn btn-secondary">Cancel</button>
        <button
          @click="confirmDelete"
          class="btn btn-danger"
          :disabled="!downloaded || !reason.trim()"
        >
          Delete Department
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Department {
  id: number
  name: string
  code: string
  description?: string
  status: string
  head?: { name: string; designation?: string }
  created_at?: string
  performance_level: string
  users?: Array<{ name: string; email: string; role: string }>
  projects?: Array<{ name: string; budget?: number; status: string; start_date?: string; end_date?: string; progress?: number }>
}

interface Props {
  show: boolean
  department: Department
}

interface Emits {
  (e: 'close'): void
  (e: 'confirm', reason: string): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const downloaded = ref(false)
const reason = ref('')

const closeModal = () => {
  emit('close')
  downloaded.value = false
  reason.value = ''
}

const downloadCSV = () => {
  const csvContent = generateCSV(props.department)
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  const url = URL.createObjectURL(blob)
  link.setAttribute('href', url)
  link.setAttribute('download', `department_${props.department.code}_data.csv`)
  link.style.visibility = 'hidden'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  downloaded.value = true
}

const generateCSV = (department: Department) => {
  let csv = 'Section,Field,Value\n'

  // Department Info
  csv += `Department Info,Name,${department.name}\n`
  csv += `Department Info,Code,${department.code}\n`
  csv += `Department Info,Description,${department.description || 'N/A'}\n`
  csv += `Department Info,Status,${department.status}\n`
  csv += `Department Info,Department Head,${department.head?.name || 'N/A'}\n`
  csv += `Department Info,Created Date,${department.created_at || 'N/A'}\n`
  csv += `Department Info,Performance Level,${department.performance_level}\n`

  // Users
  if (department.users && department.users.length > 0) {
    department.users.forEach((user, index: number) => {
      csv += `Users,User ${index + 1} Name,${user.name}\n`
      csv += `Users,User ${index + 1} Email,${user.email}\n`
      csv += `Users,User ${index + 1} Role,${user.role}\n`
    })
  } else {
    csv += `Users,No users,N/A\n`
  }

  // Projects
  if (department.projects && department.projects.length > 0) {
    department.projects.forEach((project, index: number) => {
      csv += `Projects,Project ${index + 1} Name,${project.name}\n`
      csv += `Projects,Project ${index + 1} Budget,${project.budget || 'N/A'}\n`
      csv += `Projects,Project ${index + 1} Status,${project.status}\n`
      csv += `Projects,Project ${index + 1} Start Date,${project.start_date || 'N/A'}\n`
      csv += `Projects,Project ${index + 1} End Date,${project.end_date || 'N/A'}\n`
      csv += `Projects,Project ${index + 1} Progress,${project.progress || 0}%\n`
    })
  } else {
    csv += `Projects,No projects,N/A\n`
  }

  return csv
}

const confirmDelete = () => {
  if (downloaded.value && reason.value.trim()) {
    emit('confirm', reason.value.trim())
    closeModal()
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 1rem;
  padding: 0;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
  margin: 0;
  color: #384e74;
  font-size: 1.25rem;
  font-weight: 600;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6b7280;
}

.modal-body {
  padding: 1.5rem;
}

.warning {
  color: #ef4444;
  font-weight: 500;
  margin: 1rem 0;
}

.download-section {
  margin: 1rem 0;
}

.reason-section {
  margin-top: 1.5rem;
}

.reason-section label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #384e74;
}

.reason-section textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-family: inherit;
  resize: vertical;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
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

.btn-danger {
  background: #ef4444;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background: #dc2626;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
