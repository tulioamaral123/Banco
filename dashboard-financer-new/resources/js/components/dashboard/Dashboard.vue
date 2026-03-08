<script setup>
import { ref, computed, onMounted } from 'vue'
import { ArrowUpRight, ArrowDownRight, Filter, Wallet, TrendingUp, TrendingDown } from 'lucide-vue-next'
import axios from 'axios'
import StatCard from '../StatCard.vue'

const transactions = ref([])
const loading      = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/transacoes')
    transactions.value = data
  } finally {
    loading.value = false
  }
})

// --- Filtro de categorias ---
const MONTHS = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']

const filterMode    = ref('month')
const selectedYear  = ref(new Date().getFullYear())
const selectedMonth = ref(new Date().getMonth() + 1)

const availableYears = computed(() => {
  const years = [...new Set(transactions.value.map(t => new Date(t.data).getFullYear()))]
  return years.sort((a, b) => b - a)
})

const filteredForCategories = computed(() => {
  return transactions.value.filter(t => {
    const d = new Date(t.data)
    if (filterMode.value === 'year') return d.getFullYear() === selectedYear.value
    return d.getFullYear() === selectedYear.value && d.getMonth() + 1 === selectedMonth.value
  })
})

const categories = computed(() => {
  const expenses = filteredForCategories.value.filter(t => t.valor < 0)
  const total    = expenses.reduce((s, t) => s + Math.abs(Number(t.valor)), 0)
  const map      = {}
  expenses.forEach(t => { map[t.categoria] = (map[t.categoria] ?? 0) + Math.abs(Number(t.valor)) })
  return Object.entries(map)
    .map(([name, amount], i) => ({
      name,
      amount,
      percentage: total ? Math.round((amount / total) * 100) : 0,
      color: ['bg-blue-500', 'bg-indigo-600', 'bg-purple-500', 'bg-pink-500', 'bg-rose-500'][i % 5],
    }))
    .sort((a, b) => b.percentage - a.percentage)
})

// --- Stats gerais (todos os dados) ---
const income   = computed(() => transactions.value.filter(t => t.valor > 0).reduce((s, t) => s + Number(t.valor), 0))
const expenses = computed(() => transactions.value.filter(t => t.valor < 0).reduce((s, t) => s + Number(t.valor), 0))
const balance  = computed(() => income.value + expenses.value)

const statCards = computed(() => [
  { title: 'Saldo Total', value: formatCurrency(balance.value),  trend: 'Saldo atual',       icon: Wallet,       color: 'bg-indigo-600'  },
  { title: 'Receitas',    value: formatCurrency(income.value),   trend: 'Total de entradas',  icon: TrendingUp,   color: 'bg-emerald-500' },
  { title: 'Despesas',    value: formatCurrency(expenses.value), trend: 'Total de saídas',    icon: TrendingDown, color: 'bg-rose-500'    },
])

const recent = computed(() => [...transactions.value].slice(-5).reverse())

function formatCurrency(value) {
  return Number(value).toLocaleString('pt-PT', { style: 'currency', currency: 'BRL' })
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-PT')
}
</script>

<template>
  <div v-if="loading" class="text-center text-slate-400 py-20 text-sm">A carregar...</div>

  <div v-else class="space-y-8">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <StatCard v-for="card in statCards" :key="card.title" v-bind="card" />
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Gastos por Categoria -->
      <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <!-- Cabeçalho com filtros -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
          <h2 class="font-bold text-slate-800 text-lg">Gastos por Categoria</h2>

          <div class="flex items-center gap-2 flex-wrap">
            <!-- Toggle Ano / Mês -->
            <div class="flex rounded-lg border border-slate-200 overflow-hidden text-sm">
              <button
                :class="filterMode === 'month' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50'"
                class="px-3 py-1.5 font-medium transition-colors"
                @click="filterMode = 'month'"
              >Mês</button>
              <button
                :class="filterMode === 'year' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50'"
                class="px-3 py-1.5 font-medium transition-colors border-l border-slate-200"
                @click="filterMode = 'year'"
              >Ano</button>
            </div>

            <!-- Selector de mês (só em modo mês) -->
            <select
              v-if="filterMode === 'month'"
              v-model="selectedMonth"
              class="text-sm border border-slate-200 rounded-lg px-2 py-1.5 bg-white outline-none focus:ring-2 focus:ring-indigo-500"
            >
              <option v-for="(m, i) in MONTHS" :key="i" :value="i + 1">{{ m }}</option>
            </select>

            <!-- Selector de ano -->
            <select
              v-model="selectedYear"
              class="text-sm border border-slate-200 rounded-lg px-2 py-1.5 bg-white outline-none focus:ring-2 focus:ring-indigo-500"
            >
              <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>
        </div>

        <!-- Lista de categorias -->
        <div v-if="categories.length === 0" class="text-slate-400 text-sm">
          Sem gastos no período selecionado.
        </div>
        <div v-else class="space-y-5">
          <div v-for="cat in categories" :key="cat.name">
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 font-medium">{{ cat.name }}</span>
              <div class="flex gap-3">
                <span class="text-slate-400">{{ formatCurrency(cat.amount) }}</span>
                <span class="text-slate-900 font-bold w-10 text-right">{{ cat.percentage }}%</span>
              </div>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
              <div :class="['h-full rounded-full transition-all', cat.color]" :style="{ width: `${cat.percentage}%` }" />
            </div>
          </div>
        </div>
      </div>

      <!-- Resumo -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="font-bold text-slate-800 text-lg mb-4">Resumo</h2>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between">
            <span class="text-slate-500">Total de transações</span>
            <span class="font-semibold">{{ transactions.length }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Entradas</span>
            <span class="font-semibold text-emerald-600">{{ transactions.filter(t => t.valor > 0).length }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Saídas</span>
            <span class="font-semibold text-rose-600">{{ transactions.filter(t => t.valor < 0).length }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Transações Recentes -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h2 class="font-bold text-slate-800 text-lg">Transações Recentes</h2>
        <RouterLink to="/transacoes" class="flex items-center gap-2 text-indigo-600 text-sm font-semibold hover:underline">
          <Filter :size="16" />
          Ver todas
        </RouterLink>
      </div>

      <div v-if="recent.length === 0" class="p-8 text-center text-slate-400 text-sm">
        Nenhuma transação encontrada.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
              <th class="px-6 py-4 font-semibold">Transação</th>
              <th class="px-6 py-4 font-semibold">Categoria</th>
              <th class="px-6 py-4 font-semibold">Data</th>
              <th class="px-6 py-4 font-semibold">Valor</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="t in recent" :key="t.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div :class="['w-10 h-10 rounded-lg flex items-center justify-center', t.valor > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600']">
                    <ArrowUpRight v-if="t.valor > 0" :size="20" />
                    <ArrowDownRight v-else :size="20" />
                  </div>
                  <span class="font-medium text-slate-800">{{ t.nome }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-slate-500 text-sm">{{ t.categoria }}</td>
              <td class="px-6 py-4 text-slate-500 text-sm">{{ formatDate(t.data) }}</td>
              <td :class="['px-6 py-4 font-bold', t.valor > 0 ? 'text-emerald-600' : 'text-slate-800']">
                {{ t.valor > 0 ? '+' : '' }}{{ formatCurrency(t.valor) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>
