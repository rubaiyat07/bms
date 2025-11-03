<template>
  <div v-if="show" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h3>Delete Employee</h3>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong>{{ employee?.name }}</strong>? This action cannot be undone.</p>
        <div class="form-group">
          <label for="delete-reason">Reason for deletion (optional):</label>
          <textarea
            id="delete-reason"
            v-model="reason"
            placeholder="Enter reason for deletion..."
            rows="3"
            class="form-control"
          ></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button @click="closeModal" class="btn btn-secondary">Cancel</button>
        <button @click="confirmDelete" class="btn btn-danger">Delete Employee</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  employee: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'confirm'])

const reason = ref('')

const closeModal = () => {
  reason.value = ''
  emit('close')
}

const confirmDelete = () => {
  emit('confirm', reason.value)
  closeModal()
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
  border-radius: 0.5rem;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
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
  color: #9ca3af;
  padding: 0;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.25rem;
  transition: all 0.2s;
}

.close-btn:hover {
  background: #f3f4f6;
  color: #6b7280;
}

.modal-body {
  padding: 1.5rem;
}

.modal-body p {
  margin-bottom: 1rem;
  color: #6b7280;
  line-height: 1.5;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #384e74;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  resize: vertical;
  transition: border-color 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #384e74;
  box-shadow: 0 0 0 3px rgba(56, 78, 116, 0.1);
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary {
  background: #f3f4f6;
  color: #6b7280;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-danger {
  background: #ef4444;
  color: white;
}

.btn-danger:hover {
  background: #dc2626;
}
</style>
