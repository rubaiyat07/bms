<template>
  <div id="app">
    <!-- Use different layouts based on route meta -->
    <component :is="layout" v-if="layout">
      <router-view />
    </component>
    <router-view v-else />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from './components/layout/AppLayout.vue'
import DashboardLayout from './views/Admin/Dashboard/DashboardLayout.vue'

const route = useRoute()

const layout = computed(() => {
  if (route.meta.layout === 'dashboard') {
    return DashboardLayout
  }
  return AppLayout
})
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  min-height: 100vh;
  background: #f8fafc;
}
</style>
