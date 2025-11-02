<template>
  <div class="admin-users">
    <div class="page-header">
      <h1>User Management</h1>
      <p>Manage system users, roles, and permissions</p>
    </div>

    <div class="content-grid">
      <!-- Users List -->
      <div class="users-section">
        <div class="section-header">
          <h2>All Users</h2>
          <button @click="showCreateUserModal = true" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add User
          </button>
        </div>

        <div class="users-table-container">
          <table class="users-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                  <span class="role-badge">{{ user.role }}</span>
                </td>
                <td>{{ user.branch || 'N/A' }}</td>
                <td>
                  <span :class="`status-badge status-${user.status}`">
                    {{ user.status }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button @click="editUser(user)" class="btn btn-sm btn-secondary">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button @click="deleteUser(user)" class="btn btn-sm btn-danger">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Roles & Permissions -->
      <div class="roles-section">
        <div class="section-header">
          <h2>Roles & Permissions</h2>
          <button @click="showCreateRoleModal = true" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Role
          </button>
        </div>

        <div class="roles-list">
          <div v-for="role in roles" :key="role.id" class="role-card">
            <div class="role-header">
              <h3>{{ role.name }}</h3>
              <span class="user-count">{{ role.user_count }} users</span>
            </div>
            <div class="role-permissions">
              <span v-for="permission in role.permissions" :key="permission.id" class="permission-tag">
                {{ permission.name }}
              </span>
            </div>
            <div class="role-actions">
              <button @click="editRole(role)" class="btn btn-sm btn-secondary">Edit</button>
              <button @click="deleteRole(role)" class="btn btn-sm btn-danger">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Mock data - replace with API calls
const users = ref([
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    role: 'Admin',
    branch: 'Main Branch',
    status: 'active'
  },
  {
    id: 2,
    name: 'Jane Smith',
    email: 'jane@example.com',
    role: 'Manager',
    branch: 'Branch A',
    status: 'active'
  },
  {
    id: 3,
    name: 'Bob Johnson',
    email: 'bob@example.com',
    role: 'Staff',
    branch: 'Branch B',
    status: 'inactive'
  }
])

const roles = ref([
  {
    id: 1,
    name: 'Admin',
    user_count: 1,
    permissions: [
      { id: 1, name: 'manage_users' },
      { id: 2, name: 'manage_branches' },
      { id: 3, name: 'view_reports' }
    ]
  },
  {
    id: 2,
    name: 'Manager',
    user_count: 5,
    permissions: [
      { id: 4, name: 'manage_team' },
      { id: 5, name: 'view_projects' }
    ]
  },
  {
    id: 3,
    name: 'Staff',
    user_count: 20,
    permissions: [
      { id: 6, name: 'view_tasks' },
      { id: 7, name: 'update_profile' }
    ]
  }
])

const showCreateUserModal = ref(false)
const showCreateRoleModal = ref(false)

const editUser = (user) => {
  console.log('Edit user:', user)
  // Implement edit user logic
}

const deleteUser = (user) => {
  if (confirm(`Are you sure you want to delete ${user.name}?`)) {
    console.log('Delete user:', user)
    // Implement delete user logic
  }
}

const editRole = (role) => {
  console.log('Edit role:', role)
  // Implement edit role logic
}

const deleteRole = (role) => {
  if (confirm(`Are you sure you want to delete the ${role.name} role?`)) {
    console.log('Delete role:', role)
    // Implement delete role logic
  }
}

onMounted(() => {
  // Load users and roles from API
  console.log('Loading users and roles...')
})
</script>

<style scoped>
.admin-users {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 1rem;
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

.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.section-header h2 {
  color: #384e74;
  font-size: 1.5rem;
  font-weight: 600;
}

.users-table-container {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  overflow-x: auto;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
}

.users-table th,
.users-table td {
  padding: 0.5rem;
  text-align: left;
  border-bottom: 1px solid rgba(189, 212, 255, 0.1);
}

.users-table th {
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.users-table td {
  color: #636a75;
}

.role-badge {
  background: linear-gradient(135deg, #8FB3FF 0%, #384e74 100%);
  color: #0F1419;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-active {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-inactive {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
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

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
}

.btn-danger {
  background: rgba(239, 68, 68, 0.1);
  border: 2px solid rgba(239, 68, 68, 0.3);
  color: #EF4444;
}

.btn-danger:hover {
  background: rgba(239, 68, 68, 0.2);
  border-color: #EF4444;
}

.roles-section {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.roles-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.role-card {
  background: rgba(56, 78, 116, 0.05);
  border-radius: 0.75rem;
  padding: 1rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
}

.role-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.role-header h3 {
  color: #384e74;
  font-size: 1.125rem;
  font-weight: 600;
}

.user-count {
  color: #9CA3AF;
  font-size: 0.875rem;
}

.role-permissions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.permission-tag {
  background: rgba(56, 78, 116, 0.1);
  color: #384e74;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.role-actions {
  display: flex;
  gap: 0.5rem;
}

@media (max-width: 1024px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .admin-users {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .section-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .users-table-container {
    padding: 1rem;
  }

  .users-table th,
  .users-table td {
    padding: 0.5rem;
  }

  .action-buttons {
    flex-direction: column;
  }
}
</style>
