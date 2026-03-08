<script setup>
import { Search, PlusCircle } from 'lucide-vue-next'
</script>

<template>
  <header class="bg-white border-b border-slate-200 px-8 py-4 sticky top-0 z-10 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-slate-800">Bem-vindo, utilizador!</h1>

    <div class="flex items-center gap-4">
      <div class="relative hidden sm:block">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
        <input
          type="text"
          placeholder="Pesquisar transações..."
          class="pl-10 pr-4 py-2 bg-slate-100 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
        />
      </div>
      <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-full">
        <PlusCircle :size="24" class="text-indigo-600" />
      </button>
      <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-indigo-200 flex items-center justify-center font-bold text-indigo-700">
        JD
      </div>
    </div>
  </header>
</template>