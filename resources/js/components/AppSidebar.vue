<script setup>
import {
  Wallet,
  LayoutDashboard,
  ArrowLeftRight,
  TrendingUp,
  Settings,
} from 'lucide-vue-next'
import { useRoute } from 'vue-router'

const route = useRoute()

const navItems = [
  { icon: LayoutDashboard, label: 'Visão Geral',   to: '/dashboard'     },
  { icon: ArrowLeftRight,  label: 'Transações',    to: '/transacoes'    },
  { icon: TrendingUp,      label: 'Investimentos', to: '/investimentos' },
  { icon: Settings,        label: 'Definições',    to: '/settings'      },
]

function isActive(to) {
  return route.path === to || route.path.startsWith(to + '/')
}
</script>

<template>
  <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col">
    <div class="p-6">
      <div class="flex items-center gap-2 text-indigo-600 font-bold text-2xl">
        <Wallet :size="32" />
        <span>FinanzP</span>
      </div>
    </div>

    <nav class="flex-1 px-4 space-y-2">
      <RouterLink
        v-for="item in navItems"
        :key="item.label"
        :to="item.to"
        :class="[
          'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all',
          isActive(item.to)
            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200'
            : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800'
        ]"
      >
        <component :is="item.icon" :size="20" />
        <span>{{ item.label }}</span>
      </RouterLink>
    </nav>

    <div class="p-4 mt-auto border-t border-slate-100">
      <div class="bg-indigo-50 p-4 rounded-xl">
        <p class="text-xs text-indigo-600 font-semibold mb-1 uppercase tracking-wider">Plano Premium</p>
        <p class="text-sm text-slate-600 mb-3">Obtenha análises mais detalhadas.</p>
        <button class="w-full py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
          Atualizar
        </button>
      </div>
    </div>
  </aside>
</template>
