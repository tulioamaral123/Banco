<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { TrendingUp, Layers, DollarSign, Plus, Pencil, Trash2, Sparkles } from 'lucide-vue-next'
import axios from 'axios'

const router        = useRouter()
const investimentos = ref([])
const loading       = ref(true)

// --- Avaliação IA ---
const avaliacao        = ref('')
const avaliacaoLoading = ref(false)
const avaliacaoErro    = ref('')

async function avaliarCarteira() {
  avaliacaoLoading.value = true
  avaliacaoErro.value    = ''
  avaliacao.value        = ''
  try {
    const { data } = await axios.post('/api/v1/avaliacao/investimentos')
    avaliacao.value = data.avaliacao
  } catch (e) {
    avaliacaoErro.value = e.response?.data?.error ?? 'Erro ao obter avaliação.'
  } finally {
    avaliacaoLoading.value = false
  }
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/investimentos')
    investimentos.value = data
  } finally {
    loading.value = false
  }
})

// --- Cards de resumo ---
const totalBRL = computed(() =>
  investimentos.value.filter(i => i.moeda === 'BRL').reduce((s, i) => s + Number(i.valor), 0)
)
const totalUSD = computed(() =>
  investimentos.value.filter(i => i.moeda === 'USD').reduce((s, i) => s + Number(i.valor), 0)
)
const totalAtivos = computed(() => investimentos.value.length)
const totalTipos  = computed(() => new Set(investimentos.value.map(i => i.tipo)).size)

// --- Distribuição por tipo ---
const porTipo = computed(() => {
  const total = investimentos.value.filter(i => i.moeda === 'BRL').reduce((s, i) => s + Number(i.valor), 0)
  const map   = {}
  investimentos.value.forEach(i => {
    if (!map[i.tipo]) map[i.tipo] = { valor: 0, quantidade: 0 }
    map[i.tipo].valor     += Number(i.valor)
    map[i.tipo].quantidade += 1
  })
  const colors = ['bg-indigo-500', 'bg-emerald-500', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500']
  return Object.entries(map)
    .map(([tipo, d], i) => ({
      tipo,
      valor: d.valor,
      quantidade: d.quantidade,
      percentage: total ? Math.round((d.valor / total) * 100) : 0,
      color: colors[i % colors.length],
    }))
    .sort((a, b) => b.valor - a.valor)
})

// --- Distribuição por moeda ---
const porMoeda = computed(() => {
  const map = {}
  investimentos.value.forEach(i => {
    if (!map[i.moeda]) map[i.moeda] = { valor: 0, quantidade: 0 }
    map[i.moeda].valor     += Number(i.valor)
    map[i.moeda].quantidade += 1
  })
  const colors = { BRL: 'bg-emerald-500', USD: 'bg-blue-500', EUR: 'bg-indigo-500' }
  return Object.entries(map).map(([moeda, d]) => ({
    moeda,
    valor: d.valor,
    quantidade: d.quantidade,
    color: colors[moeda] ?? 'bg-slate-400',
  }))
})

// --- Tabela paginada ---
const PER_PAGE   = 8
const page       = ref(1)
const totalPages = computed(() => Math.ceil(investimentos.value.length / PER_PAGE))
const visiblePages = computed(() => {
  const left  = Math.max(1, page.value - 2)
  const right = Math.min(totalPages.value, page.value + 2)
  return Array.from({ length: right - left + 1 }, (_, i) => left + i)
})
const pageRows = computed(() => {
  const start = (page.value - 1) * PER_PAGE
  return investimentos.value.slice(start, start + PER_PAGE)
})

async function onDelete(id) {
  if (!confirm('Eliminar este investimento?')) return
  await axios.delete(`/api/v1/investimentos/${id}`)
  investimentos.value = investimentos.value.filter(i => i.id !== id)
  if (page.value > totalPages.value) page.value = totalPages.value || 1
}

function formatCurrency(value, moeda = 'BRL') {
  const locale   = moeda === 'EUR' ? 'pt-PT' : 'pt-BR'
  return Number(value).toLocaleString(locale, { style: 'currency', currency: moeda })
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-BR')
}
</script>

<template>
  <div v-if="loading" class="text-center text-slate-400 py-20 text-sm">A carregar...</div>

  <div v-else class="space-y-8">

    <!-- Cabeçalho -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-slate-800">Investimentos</h1>
      <RouterLink
        to="/investimentos/create"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors"
      >
        <Plus :size="16" />
        Novo
      </RouterLink>
    </div>

    <!-- Cards de resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-3">
          <div class="p-2.5 bg-emerald-500 rounded-xl"><TrendingUp class="text-white" :size="20" /></div>
          <span class="text-sm font-medium text-slate-500">Total em BRL</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ formatCurrency(totalBRL, 'BRL') }}</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-3">
          <div class="p-2.5 bg-blue-500 rounded-xl"><DollarSign class="text-white" :size="20" /></div>
          <span class="text-sm font-medium text-slate-500">Total em USD</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ formatCurrency(totalUSD, 'USD') }}</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-3">
          <div class="p-2.5 bg-indigo-500 rounded-xl"><Layers class="text-white" :size="20" /></div>
          <span class="text-sm font-medium text-slate-500">Ativos</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ totalAtivos }}</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-3">
          <div class="p-2.5 bg-purple-500 rounded-xl"><TrendingUp class="text-white" :size="20" /></div>
          <span class="text-sm font-medium text-slate-500">Tipos</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ totalTipos }}</p>
      </div>
    </div>

    <!-- Distribuição -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Por tipo -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 text-lg mb-6">Distribuição por Tipo</h2>
        <div v-if="porTipo.length === 0" class="text-slate-400 text-sm">Sem dados.</div>
        <div v-else class="space-y-5">
          <div v-for="item in porTipo" :key="item.tipo">
            <div class="flex justify-between text-sm mb-2">
              <div class="flex items-center gap-2">
                <div :class="['w-2.5 h-2.5 rounded-full', item.color]" />
                <span class="text-slate-700 font-medium">{{ item.tipo }}</span>
                <span class="text-slate-400 text-xs">({{ item.quantidade }})</span>
              </div>
              <div class="flex gap-3 items-center">
                <span class="text-slate-400 text-xs">{{ formatCurrency(item.valor, 'BRL') }}</span>
                <span class="font-bold text-slate-800 w-10 text-right">{{ item.percentage }}%</span>
              </div>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
              <div :class="['h-full rounded-full transition-all', item.color]" :style="{ width: `${item.percentage}%` }" />
            </div>
          </div>
        </div>
      </div>

      <!-- Por moeda -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 text-lg mb-6">Por Moeda</h2>
        <div class="space-y-4">
          <div v-for="item in porMoeda" :key="item.moeda" class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
            <div class="flex items-center gap-3">
              <div :class="['w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold', item.color]">
                {{ item.moeda }}
              </div>
              <div>
                <p class="text-sm font-semibold text-slate-800">{{ formatCurrency(item.valor, item.moeda) }}</p>
                <p class="text-xs text-slate-400">{{ item.quantidade }} ativo{{ item.quantidade !== 1 ? 's' : '' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Avaliação IA -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <div class="p-2 bg-indigo-600 rounded-xl"><Sparkles class="text-white" :size="18" /></div>
          <h2 class="font-bold text-slate-800 text-lg">Avaliação por IA</h2>
        </div>
        <button
          :disabled="avaliacaoLoading || investimentos.length === 0"
          class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          @click="avaliarCarteira"
        >
          <Sparkles :size="15" />
          {{ avaliacaoLoading ? 'A analisar...' : 'Analisar carteira' }}
        </button>
      </div>

      <div v-if="avaliacaoLoading" class="flex items-center gap-3 text-slate-400 text-sm py-6 justify-center">
        <div class="w-4 h-4 border-2 border-indigo-300 border-t-indigo-600 rounded-full animate-spin" />
        A processar a sua carteira...
      </div>

      <p v-else-if="avaliacaoErro" class="text-rose-600 text-sm">{{ avaliacaoErro }}</p>

      <div v-else-if="avaliacao" class="prose prose-sm max-w-none text-slate-700 whitespace-pre-wrap leading-relaxed bg-slate-50 rounded-xl p-5 text-sm">{{ avaliacao }}</div>

      <p v-else class="text-slate-400 text-sm">
        Clique em "Analisar carteira" para obter uma avaliação da sua carteira de investimentos.
      </p>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-slate-100">
        <h2 class="font-bold text-slate-800 text-lg">Todos os Investimentos</h2>
      </div>

      <div v-if="investimentos.length === 0" class="p-10 text-center text-slate-400 text-sm">
        Nenhum investimento encontrado.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
              <th class="px-6 py-4 font-semibold">Nome</th>
              <th class="px-6 py-4 font-semibold">Tipo</th>
              <th class="px-6 py-4 font-semibold">Moeda</th>
              <th class="px-6 py-4 font-semibold">Valor</th>
              <th class="px-6 py-4 font-semibold">Data</th>
              <th class="px-6 py-4 font-semibold text-center">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="inv in pageRows" :key="inv.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-4 font-medium text-slate-800 text-sm">{{ inv.nome }}</td>
              <td class="px-6 py-4 text-sm">
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">
                  {{ inv.tipo }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm">
                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                  {{ inv.moeda }}
                </span>
              </td>
              <td class="px-6 py-4 font-bold text-slate-800 text-sm">{{ formatCurrency(inv.valor, inv.moeda) }}</td>
              <td class="px-6 py-4 text-slate-500 text-sm">{{ formatDate(inv.data) }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                  <button
                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                    @click="router.push(`/investimentos/${inv.id}/edit`)"
                  >
                    <Pencil :size="16" />
                  </button>
                  <button
                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                    @click="onDelete(inv.id)"
                  >
                    <Trash2 :size="16" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginação -->
      <div v-if="totalPages > 1" class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50">
        <span class="text-sm text-slate-500">
          {{ (page - 1) * PER_PAGE + 1 }}–{{ Math.min(page * PER_PAGE, investimentos.length) }}
          de {{ investimentos.length }} registos
        </span>
        <div class="flex items-center gap-1">
          <button
            :disabled="page === 1"
            class="px-2.5 py-1.5 rounded-lg text-slate-500 hover:bg-white disabled:opacity-30 disabled:cursor-not-allowed transition-colors text-sm"
            @click="page--"
          >‹</button>
          <button
            v-for="p in visiblePages"
            :key="p"
            :class="['w-8 h-8 rounded-lg text-sm font-medium transition-colors', p === page ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-white']"
            @click="page = p"
          >{{ p }}</button>
          <button
            :disabled="page === totalPages"
            class="px-2.5 py-1.5 rounded-lg text-slate-500 hover:bg-white disabled:opacity-30 disabled:cursor-not-allowed transition-colors text-sm"
            @click="page++"
          >›</button>
        </div>
      </div>
    </div>

  </div>
</template>
