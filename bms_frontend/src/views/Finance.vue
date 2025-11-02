<template>
  <div class="finance-page">
    <div class="page-header">
      <h1>Finance Management</h1>
      <p>Manage transactions, categories, and financial reports</p>
    </div>

    <div class="finance-content">
      <!-- Navigation Tabs -->
      <div class="nav-tabs">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          :class="['tab-button', { active: activeTab === tab.key }]"
          @click="activeTab = tab.key"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Transactions Tab -->
      <div v-if="activeTab === 'transactions'" class="tab-content">
        <div class="content-header">
          <h2>Transactions</h2>
          <button class="btn btn-primary" @click="showTransactionModal = true">
            <Plus class="w-4 h-4 mr-2" />
            New Transaction
          </button>
        </div>

        <!-- Filters -->
        <div class="filters">
          <div class="filter-group">
            <label>Type:</label>
            <select v-model="filters.type" @change="fetchTransactions">
              <option value="">All Types</option>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Status:</label>
            <select v-model="filters.status" @change="fetchTransactions">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Start Date:</label>
            <input type="date" v-model="filters.start_date" @change="fetchTransactions" />
          </div>
          <div class="filter-group">
            <label>End Date:</label>
            <input type="date" v-model="filters.end_date" @change="fetchTransactions" />
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
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
                <td>{{ transaction.category?.name }}</td>
                <td>{{ transaction.description }}</td>
                <td class="amount">{{ formatCurrency(transaction.amount) }}</td>
                <td>
                  <span :class="['badge', getStatusBadgeClass(transaction.status)]">
                    {{ transaction.status }}
                  </span>
                </td>
                <td>
