<template>
  <div class="min-h-screen flex flex-col bg-slate-50">
    <!-- Header/Navbar -->
    <AppHeader v-if="showHeader" />

    <!-- Main Content Area with Router Outlet -->
    <main class="flex-1">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <AppFooter v-if="showFooter" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppHeader from './AppHeader.vue'
import AppFooter from './AppFooter.vue'

const route = useRoute()

// Hide header/footer on certain pages like login
const showHeader = computed(() => !route.meta.hideHeader)
const showFooter = computed(() => !route.meta.hideFooter)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>