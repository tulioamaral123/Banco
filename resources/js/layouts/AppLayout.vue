<script setup>
import AppSidebar from '../components/AppSidebar.vue'
import AppHeader  from '../components/AppHeader.vue'
</script>

<template>
  <div class="flex h-screen bg-slate-50 font-sans text-slate-900 overflow-hidden">
    <AppSidebar />
    <main class="flex-1 overflow-y-auto">
      <AppHeader />
      <div class="p-8 max-w-7xl mx-auto space-y-8">
        <RouterView />
      </div>
    </main>
  </div>
</template>