<template>
  <div class="admin-inventory">
    <div class="page-header">
      <h1>Inventory Management</h1>
      <p>Manage inventory items across all branches</p>
    </div>

    <div class="inventory-content">
      <div class="inventory-filters">
        <div class="filter-group">
          <label>Category:</label>
          <select v-model="categoryFilter" class="filter-select">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div class="filter-group">
          <label>Branch:</label>
          <select v-model="branchFilter" class="filter-select">
            <option value="">All Branches</option>
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.name }}
            </option>
          </select>
        </div>
        <div class="filter-group">
          <label>Status:</label>
          <select v-model="statusFilter" class="filter-select">
            <option value="">All Status</option>
            <option value="in-stock">In Stock</option>
            <option value="low-stock">Low Stock</option>
            <option value="out-of-stock">Out of Stock</option>
          </select>
        </div>
        <button @click="clearFilters" class="btn btn-secondary">Clear Filters</button>
      </div>

      <div class="inventory-table-container">
        <table class="inventory-table">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Category</th>
              <th>Branch</th>
              <th>Quantity</th>
              <th>Min Stock</th>
              <th>Status</th>
              <th>Last Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredItems" :key="item.id">
              <td>{{ item.name }}</td>
              <td>{{ item.category }}</td>
              <td>{{ item.branch }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.min_stock }}</td>
              <td>
                <span :class="`status-badge status-${item.status}`">
                  {{ item.status.replace('-', ' ') }}
                </span>
              </td>
              <td>{{ formatDate(item.last_updated) }}</td>
              <td>
                <div class="action-buttons">
                  <button @click="editItem(item)" class="btn btn-sm btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button @click="viewHistory(item)" class="btn btn-sm btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="inventory-stats">
        <div class="stat-card">
          <h3>Total Items</h3>
          <div class="stat-number">{{ items.length }}</div>
        </div>
        <div class="stat-card">
          <h3>In Stock</h3>
          <div class="stat-number">{{ inStockCount }}</div>
        </div>
        <div class="stat-card">
          <h3>Low Stock</h3>
          <div class="stat-number">{{ lowStockCount }}</div>
        </div>
        <div class="stat-card">
          <h3>Out of Stock</h3>
          <div class="stat-number">{{ outOfStockCount }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Mock data - replace with API calls
const items = ref([
  {
    id: 1,
    name: 'Laptop Dell XPS 13',
    category: 'Electronics',
    branch: 'Main Branch',
    quantity: 15,
    min_stock: 5,
    status: 'in-stock',
    last_updated: '2024-01-15T10:30:00Z'
  },
  {
    id: 2,
    name: 'Office Chair',
    category: 'Furniture',
    branch: 'North Branch',
    quantity: 3,
    min_stock: 5,
    status: 'low-stock',
    last_updated: '2024-01-14T14:20:00Z'
  },
  {
    id: 3,
    name: 'Printer Paper A4',
    category: 'Stationery',
    branch: 'South Branch',
    quantity: 0,
    min_stock: 10,
    status: 'out-of-stock',
    last_updated: '2024-01-13T09:15:00Z'
  },
  {
    id: 4,
    name: 'Wireless Mouse',
    category: 'Electronics',
    branch: 'Main Branch',
    quantity: 25,
    min_stock: 8,
    status: 'in-stock',
    last_updated: '2024-01-15T11:45:00Z'
  }
])

const categories = ref([
  { id: 1, name: 'Electronics' },
  { id: 2, name: 'Furniture' },
  { id: 3, name: 'Stationery' },
  { id: 4, name: 'Software' }
])

const branches = ref([
  { id: 1, name: 'Main Branch' },
  { id: 2, name: 'North Branch' },
  { id: 3, name: 'South Branch' }
])

const categoryFilter = ref('')
const branchFilter = ref('')
const statusFilter = ref('')

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const categoryMatch = !categoryFilter.value || item.category === categories.value.find(c => c.id == categoryFilter.value)?.name
    const branchMatch = !branchFilter.value || item.branch === branches.value.find(b => b.id == branchFilter.value)?.name
    const statusMatch = !statusFilter.value || item.status === statusFilter.value
    return categoryMatch && branchMatch && statusMatch
  })
})

const inStockCount = computed(() => {
  return items.value.filter(item => item.status === 'in-stock').length
})

const lowStockCount = computed(() => {
  return items.value.filter(item => item.status === 'low-stock').length
})

const outOfStockCount = computed(() => {
  return items.value.filter(item => item.status === 'out-of-stock').length
})

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

const clearFilters = () => {
  categoryFilter.value = ''
  branchFilter.value = ''
  statusFilter.value = ''
}

const editItem = (item) => {
  console.log('Edit item:', item)
  // Implement edit item logic
}

const viewHistory = (item) => {
  console.log('View history:', item)
  // Implement view history logic
}
</script>

<style scoped>
.admin-inventory {
  padding: 2rem;
  max-width: 1400px;
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

.inventory-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.inventory-filters {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-group label {
  color: #384e74;
  font-weight: 600;
}

.filter-select {
  background: rgba(56, 78, 116, 0.05);
  border: 1px solid rgba(56, 78, 116, 0.1);
  border-radius: 0.5rem;
  padding: 0.5rem;
  color: #636a75;
  font-size: 0.875rem;
}

.filter-select:focus {
  outline: none;
  border-color: rgba(56, 78, 116, 0.3);
}

.inventory-table-container {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  overflow-x: auto;
}

.inventory-table {
  width: 100%;
  border-collapse: collapse;
}

.inventory-table th,
.inventory-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid rgba(56, 78, 116, 0.1);
}

.inventory-table th {
  color: #384e74;
  font-weight: 600;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.inventory-table td {
  color: #636a75;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-in-stock {
  background-color: rgba(34, 197, 94, 0.1);
  color: #22C55E;
}

.status-low-stock {
  background-color: rgba(245, 158, 11, 0.1);
  color: #F59E0B;
}

.status-out-of-stock {
  background-color: rgba(239, 68, 68, 0.1);
  color: #EF4444;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.inventory-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(56, 78, 116, 0.05) 0%, rgba(56, 78, 116, 0.02) 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(56, 78, 116, 0.1);
  text-align: center;
}

.stat-card h3 {
  color: #9CA3AF;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.stat-number {
  color: #384e74;
  font-size: 2rem;
  font-weight: 700;
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

@media (max-width: 768px) {
  .admin-inventory {
    padding: 1rem;
  }

  .page-header h1 {
    font-size: 2rem;
  }

  .inventory-filters {
    flex-direction: column;
    align-items: flex-start;
  }

  .inventory-table-container {
    padding: 1rem;
  }

  .inventory-table th,
  .inventory-table td {
    padding: 0.5rem;
  }

  .inventory-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .action-buttons {
    flex-direction: column;
  }
}
</style>
