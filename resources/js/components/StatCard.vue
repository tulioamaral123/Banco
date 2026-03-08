<script setup>
import { MoreVertical } from 'lucide-vue-next'

defineProps({
  title: { type: String, required: true },
  value: { type: String, required: true },
  trend: { type: String, required: true },
  icon:  { required: true },
  color: { type: String, required: true },
})
</script>

<template>
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-4">
      <div :class="['p-3 rounded-xl', color]">
        <component :is="icon" class="text-white" :size="24" />
      </div>
      <button class="text-slate-300 hover:text-slate-500 transition-colors">
        <MoreVertical :size="20" />
      </button>
    </div>
    <p class="text-sm font-medium text-slate-500 mb-1">{{ title }}</p>
    <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ value }}</h3>
    <p :class="['text-xs font-semibold', trend.includes('-') ? 'text-rose-500' : 'text-emerald-500']">
      {{ trend }}
    </p>
  </div>
</template>