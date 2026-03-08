<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowUpRight, ArrowDownRight, Filter, MoreVertical } from 'lucide-vue-next'

const router = useRouter()
const transacoes = ref([])
const loading = ref(false)

const fetchTransacoes = async () => {
  loading.value = true
  try {
    const response = await fetch('/api/v1/transacoes')
    const data = await response.json()
    transacoes.value = data
  } catch (error) {
    console.error('Erro ao carregar transações:', error)
  } finally {
    loading.value = false
  }
}

const deleteTransacao = async (id) => {
  if (confirm('Tem certeza que deseja excluir esta transação?')) {
    try {
      await fetch(`/api/v1/transacoes/${id}`, { method: 'DELETE' })
      fetchTransacoes()
    } catch (error) {
      console.error('Erro ao excluir transação:', error)
    }
  }
}

function formatCurrency(value) {
  return value.toLocaleString('pt-PT', { style: 'currency', currency: 'EUR' })
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-PT')
}

onMounted(() => {
  fetchTransacoes()
})
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Transações</h1>
      <button
        @click="router.push({ name: 'transacoes.create' })"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
      >
        Nova Transação
      </button>
    </div>

    <div v-if="loading" class="text-center py-8">
      <p class="text-slate-500">Carregando...</p>
    </div>

    <section v-else class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h2 class="font-bold text-slate-800 text-lg">Transações Recentes</h2>
        <button class="flex items-center gap-2 text-indigo-600 text-sm font-semibold hover:underline">
          <Filter :size="16" />
          Filtrar
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
              <th class="px-6 py-4 font-semibold">Transação</th>
              <th class="px-6 py-4 font-semibold">Categoria</th>
              <th class="px-6 py-4 font-semibold">Data</th>
              <th class="px-6 py-4 font-semibold">Valor</th>
              <th class="px-6 py-4 font-semibold text-center">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="t in transacoes"
              :key="t.id"
              class="hover:bg-slate-50 transition-colors group"
            >
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div :class="['w-10 h-10 rounded-lg flex items-center justify-center', t.amount > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600']">
                    <ArrowUpRight v-if="t.amount > 0" :size="20" />
                    <ArrowDownRight v-else :size="20" />
                  </div>
                  <span class="font-medium text-slate-800">{{ t.title }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-slate-500 text-sm">{{ t.category }}</td>
              <td class="px-6 py-4 text-slate-500 text-sm">{{ formatDate(t.date) }}</td>
              <td :class="['px-6 py-4 font-bold', t.amount > 0 ? 'text-emerald-600' : 'text-slate-800']">
                {{ t.amount > 0 ? '+' : '' }}{{ formatCurrency(t.amount) }}
              </td>
              <td class="px-6 py-4 text-center">
                <button
                  @click="deleteTransacao(t.id)"
                  class="text-slate-400 hover:text-rose-600 transition-colors"
                >
                  <MoreVertical :size="18" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>
