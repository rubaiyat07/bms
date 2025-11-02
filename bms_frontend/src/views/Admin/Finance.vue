<template>
  <div class="finance-page">
    <div class="page-header">
      <h1>Finance Management</h1>
      <p>Manage transactions, expense categories, and financial reports</p>
    </div>

    <div class="tabs-container">
      <div class="tabs">
        <button
          :class="['tab-button', { active: activeTab === 'transactions' }]"
          @click="activeTab = 'transactions'"
        >
          Transactions
        </button>
        <button
          :class="['tab-button', { active: activeTab === 'categories' }]"
          @click="activeTab = 'categories'"
        >
          Categories
        </button>
        <button
          :class="['tab-button', { active: activeTab === 'reports' }]"
          @click="activeTab = 'reports'"
        >
          Reports
        </button>
      </div>
    </div>

    <!-- Transactions Tab -->
    <div v-if="activeTab === 'transactions'" class="tab-content">
      <div class="content-header">
        <h2>Transactions</h2>
        <button class="btn btn-primary" @click="showTransactionModal = true">
          <Plus class="w-4 h-4 mr-2" />
          Add Transaction
        </button>
      </div>

      <div class="filters">
        <div class="filter-group">
          <select v-model="transactionFilters.type" class="form-select">
            <option value="">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
          </select>
          <select v-model="transactionFilters.status" class="form-select">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
          <input
            v-model="transactionFilters.start_date"
            type="date"
            class="form-input"
            placeholder="Start Date"
          />
          <input
            v-model="transactionFilters.end_date"
            type="date"
            class="form-input"
            placeholder="End Date"
          />
          <button class="btn btn-secondary" @click="loadTransactions">Filter</button>
        </div>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Category</th>
              <th>Description</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="transaction in transactions" :key="transaction.id">
              <td>{{ formatDate(transaction.date) }}</td>
              <td>
                <span :class="['badge', transaction.type === 'income' ? 'badge-success' : 'badge-danger']">
                  {{ transaction.type }}
                </span>
              </td>
              <td>${{ transaction.amount.toFixed(2) }}</td>
              <td>{{ transaction.category?.name }}</td>
              <td>{{ transaction.description }}</td>
              <td>
                <span :class="['badge', getStatusBadgeClass(transaction.status)]">
                  {{ transaction.status }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="btn btn-sm btn-info" @click="viewTransaction(transaction)">
                    <Eye class="w-4 h-4" />
                  </button>
                  <button
                    v-if="transaction.status === 'pending'"
                    class="btn btn-sm btn-success"
                    @click="approveTransaction(transaction)"
                  >
                    <Check class="w-4 h-4" />
                  </button>
                  <button
                    v-if="transaction.status === 'pending'"
                    class="btn btn-sm btn-danger"
                    @click="rejectTransaction(transaction)"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Categories Tab -->
    <div v-if="activeTab === 'categories'" class="tab-content">
      <div class="content-header">
        <h2>Expense Categories</h2>
        <button class="btn btn-primary" @click="showCategoryModal = true">
          <Plus class="w-4 h-4 mr-2" />
          Add Category
        </button>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Code</th>
              <th>Type</th>
              <th>Description</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="category in categories" :key="category.id">
              <td>{{ category.name }}</td>
              <td>{{ category.code }}</td>
              <td>
                <span :class="['badge', category.type === 'income' ? 'badge-success' : 'badge-warning']">
                  {{ category.type }}
                </span>
              </td>
              <td>{{ category.description }}</td>
              <td>
                <span :class="['badge', category.is_active ? 'badge-success' : 'badge-secondary']">
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="btn btn-sm btn-info" @click="editCategory(category)">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button class="btn btn-sm btn-danger" @click="deleteCategory(category)">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reports Tab -->
    <div v-if="activeTab === 'reports'" class="tab-content">
      <div class="content-header">
        <h2>Financial Reports</h2>
      </div>

      <div class="report-filters">
        <div class="filter-group">
          <input
            v-model="reportFilters.start_date"
            type="date"
            class="form-input"
            placeholder="Start Date"
          />
          <input
            v-model="reportFilters.end_date"
            type="date"
            class="form-input"
            placeholder="End Date"
          />
          <button class="btn btn-primary" @click="generateReport">Generate Report</button>
        </div>
      </div>

      <div v-if="reportData" class="report-cards">
        <div class="report-card">
          <h3>Total Income</h3>
          <p class="amount income">${{ reportData.income.total.toFixed(2) }}</p>
          <p class="count">{{ reportData.income.count }} transactions</p>
        </div>
        <div class="report-card">
          <h3>Total Expenses</h3>
          <p class="amount expense">${{ reportData.expense.total.toFixed(2) }}</p>
          <p class="count">{{ reportData.expense.count }} transactions</p>
        </div>
        <div class="report-card">
          <h3>Net Profit/Loss</h3>
          <p :class="['amount', reportData.net >= 0 ? 'profit' : 'loss']">
            ${{ reportData.net.toFixed(2) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Transaction Modal -->
    <div v-if="showTransactionModal" class="modal-overlay" @click="closeTransactionModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>{{ editingTransaction ? 'Edit Transaction' : 'Add Transaction' }}</h3>
          <button class="modal-close" @click="closeTransactionModal">
            <X class="w-6 h-6" />
          </button>
        </div>
        <form @submit.prevent="saveTransaction" class="modal-form">
          <div class="form-group">
            <label class="form-label">Type</label>
            <select v-model="transactionForm.type" class="form-select" required>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Amount</label>
            <input
              v-model="transactionForm.amount"
              type="number"
              step="0.01"
              class="form-input"
              required
            />
          </div>
          <div class="form-group">
            <label class="form-label">Category</label>
            <select v-model="transactionForm.category_id" class="form-select" required>
              <option value="">Select Category</option>
              <option
                v-for="category in filteredCategories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Date</label>
            <input v-model="transactionForm.date" type="date" class="form-input" required />
          </div>
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea v-model="transactionForm.description" class="form-textarea"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Reference No</label>
            <input v-model="transactionForm.reference_no" type="text" class="form-input" />
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" @click="closeTransactionModal">Cancel</button>
            <button type="submit" class="btn btn-primary" :disabled="saving">
              {{ saving ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Category Modal -->
    <div v-if="showCategoryModal" class="modal-overlay" @click="closeCategoryModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>{{ editingCategory ? 'Edit Category' : 'Add Category' }}</h3>
          <button class="modal-close" @click="closeCategoryModal">
            <X class="w-6 h-6" />
          </button>
        </div>
        <form @submit.prevent="saveCategory" class="modal-form">
          <div class="form-group">
            <label class="form-label">Name</label>
            <input v-model="categoryForm.name" type="text" class="form-input" required />
          </div>
          <div class="form-group">
            <label class="form-label">Code</label>
            <input v-model="categoryForm.code" type="text" class="form-input" required />
          </div>
          <div class="form-group">
            <label class="form-label">Type</label>
            <select v-model="categoryForm.type" class="form-select" required>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea v-model="categoryForm.description" class="form-textarea"></textarea>
          </div>
          <div class="form-group">
            <label class="checkbox-group">
              <input v-model="categoryForm.is_active" type="checkbox" />
              <label>Active</label>
            </label>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" @click="closeCategoryModal">Cancel</button>
            <button type="submit" class="btn btn-primary" :disabled="saving">
              {{ saving ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { Plus, Eye, Edit, Trash2, Check, X } from 'lucide-vue-next'
import api from '@/services/api'

const activeTab = ref('transactions')
const transactions = ref([])
const categories = ref([])
const reportData = ref(null)

// Modals
const showTransactionModal = ref(false)
const showCategoryModal = ref(false)
const editingTransaction = ref(null)
const editingCategory = ref(null)
const saving = ref(false)

// Filters
const transactionFilters = ref({
  type: '',
  status: '',
  start_date: '',
  end_date: ''
})

const reportFilters = ref({
  start_date: '',
  end_date: ''
})

// Forms
const transactionForm = ref({
  type: 'expense',
  amount: '',
  category_id: '',
  date: new Date().toISOString().split('T')[0],
  description: '',
  reference_no: ''
})

const categoryForm = ref({
  name: '',
  code: '',
  type: 'expense',
  description: '',
  is_active: true
})

const filteredCategories = computed(() => {
  return categories.value.filter(cat => cat.type === transactionForm.value.type && cat.is_active)
})

const loadTransactions = async () => {
  try {
    const params = {}
    if (transactionFilters.value.type) params.type = transactionFilters.value.type
    if (transactionFilters.value.status) params.status = transactionFilters.value.status
    if (transactionFilters.value.start_date) params.start_date = transactionFilters.value.start_date
    if (transactionFilters.value.end_date) params.end_date = transactionFilters.value.end_date

    const response = await api.get('/transactions', { params })
    transactions.value = response.data.data
  } catch (error) {
    console.error('Error loading transactions:', error)
  }
}

const loadCategories = async () => {
  try {
    const response = await api.get('/expense-categories')
    categories.value = response.data
  } catch (error) {
    console.error('Error loading categories:', error)
  }
}

const saveTransaction = async () => {
  saving.value = true
  try {
    if (editingTransaction.value) {
      await api.put(`/transactions/${editingTransaction.value.id}`, transactionForm.value)
    } else {
      await api.post('/transactions', transactionForm.value)
    }
    await loadTransactions()
    closeTransactionModal()
  } catch (error) {
    console.error('Error saving transaction:', error)
  } finally {
    saving.value = false
  }
}

const approveTransaction = async (transaction) => {
  try {
    await api.post(`/transactions/${transaction.id}/approve`)
    await loadTransactions()
  } catch (error) {
    console.error('Error approving transaction:', error)
  }
}

const rejectTransaction = async (transaction) => {
  try {
    await api.post(`/transactions/${transaction.id}/reject`)
    await loadTransactions()
  } catch (error) {
    console.error('Error rejecting transaction:', error)
  }
}

const saveCategory = async () => {
  saving.value = true
  try {
    if (editingCategory.value) {
      await api.put(`/expense-categories/${editingCategory.value.id}`, categoryForm.value)
    } else {
      await api.post('/expense-categories', categoryForm.value)
    }
    await loadCategories()
    closeCategoryModal()
  } catch (error) {
    console.error('Error saving category:', error)
  } finally {
    saving.value = false
  }
}

const deleteCategory = async (category) => {
  if (!confirm('Are you sure you want to delete this category?')) return

  try {
    await api.delete(`/expense-categories/${category.id}`)
    await loadCategories()
  } catch (error) {
    console.error('Error deleting category:', error)
  }
}

const generateReport = async () => {
  try {
    const params = {}
    if (reportFilters.value.start_date) params.start_date = reportFilters.value.start_date
    if (reportFilters.value.end_date) params.end_date = reportFilters.value.end_date

    const response = await api.get('/reports/income-expense', { params })
    reportData.value = response.data
  } catch (error) {
    console.error('Error generating report:', error)
  }
}

const viewTransaction = (transaction) => {
  editingTransaction.value = transaction
  transactionForm.value = {
    type: transaction.type,
    amount: transaction.amount,
    category_id: transaction.category_id,
    date: transaction.date,
    description: transaction.description || '',
    reference_no: transaction.reference_no || ''
  }
  showTransactionModal.value = true
}

const editCategory = (category) => {
  editingCategory.value = category
  categoryForm.value = {
    name: category.name,
    code: category.code,
    type: category.type,
    description: category.description || '',
    is_active: category.is_active
  }
  showCategoryModal.value = true
}

const closeTransactionModal = () => {
  showTransactionModal.value = false
  editingTransaction.value = null
  transactionForm.value = {
    type: 'expense',
    amount: '',
    category_id: '',
    date: new Date().toISOString().split('T')[0],
    description: '',
    reference_no: ''
  }
}

const closeCategoryModal = () => {
  showCategoryModal.value = false
  editingCategory.value = null
  categoryForm.value = {
    name: '',
    code: '',
    type: 'expense',
    description: '',
    is_active: true
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'approved': return 'badge-success'
    case 'rejected': return 'badge-danger'
    case 'pending': return 'badge-warning'
    default: return 'badge-secondary'
  }
}

onMounted(() => {
  loadTransactions()
  loadCategories()
})
</script>

<style scoped>
.finance-page {
  padding: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: bold;
  color: #384e74;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #6b7280;
}

.tabs-container {
  margin-bottom: 2rem;
}

.tabs {
  display: flex;
  border-bottom: 1px solid #e5e7eb;
}

.tab-button {
  padding: 0.75rem 1.5rem;
  border: none;
  background: none;
  color: #6b7280;
  font-weight: 500;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}

.tab-button.active {
  color: #3b82f6;
  border-bottom-color: #3b82f6;
}

.tab-button:hover {
  color: #3b82f6;
}

.tab-content {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
}

.content-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.content-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.filters {
  margin-bottom: 1.5rem;
}

.filter-group {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.form-select,
.form-input {
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.table-container {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.data-table th {
  background: #f9fafb;
  font-weight: 600;
  color: #374151;
}

.badge {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.badge-success {
  background: #dcfce7;
  color: #166534;
}

.badge-danger {
  background: #fef2f2;
  color: #991b1b;
}

.badge-warning {
  background: #fef3c7;
  color: #92400e;
}

.badge-secondary {
  background: #f3f4f6;
  color: #374151;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
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

.btn-info {
  background: #06b6d4;
  color: white;
}

.btn-info:hover {
  background: #0891b2;
}

.btn-success {
  background: #10b981;
  color: white;
}

.btn-success:hover {
  background: #059669;
}

.btn-danger {
  background: #ef4444;
  color: white;
}

.btn-danger:hover {
  background: #dc2626;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.report-filters {
  margin-bottom: 2rem;
}

.report-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.report-card {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.report-card h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 1rem;
}

.amount {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.amount.income {
  color: #10b981;
}

.amount.expense {
  color: #ef4444;
}

.amount.profit {
  color: #10b981;
}

.amount.loss {
  color: #ef4444;
}

.count {
  color: #6b7280;
  font-size: 0.875rem;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 0.5rem;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
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
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
}

.modal-form {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}
</style>
