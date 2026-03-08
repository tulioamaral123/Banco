<script setup>
import { ref, computed, watchEffect } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Plus, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'
import entities  from '../../entities'

const PER_PAGE = 11

const route  = useRoute()
const router = useRouter()

const entity  = computed(() => entities[route.params.entity])
const rows    = ref([])
const loading = ref(false)
const error   = ref('')
const page    = ref(1)

const totalPages = computed(() => Math.ceil(rows.value.length / PER_PAGE))

const visiblePages = computed(() => {
  const delta = 2
  const left  = Math.max(1, page.value - delta)
  const right = Math.min(totalPages.value, page.value + delta)
  return Array.from({ length: right - left + 1 }, (_, i) => left + i)
})

const pageRows = computed(() => {
  const start = (page.value - 1) * PER_PAGE
  return rows.value.slice(start, start + PER_PAGE)
})

watchEffect(async () => {
  if (!entity.value) return
  loading.value = true
  error.value   = ''
  page.value    = 1
  try {
    const { data } = await axios.get(entity.value.apiBase)
    rows.value = data
  } catch {
    error.value = 'Erro ao carregar dados.'
  } finally {
    loading.value = false
  }
})

function onEdit(row) {
  router.push(`/${route.params.entity}/${row.id}/edit`)
}

async function onDelete(row) {
  if (!confirm('Eliminar este registo?')) return
  try {
    await axios.delete(`${entity.value.apiBase}/${row.id}`)
    rows.value = rows.value.filter(r => r.id !== row.id)
    if (page.value > totalPages.value) page.value = totalPages.value || 1
  } catch {
    alert('Erro ao eliminar.')
  }
}
</script>

<template>
  <div v-if="!entity" class="text-slate-500 p-8">Entidade não encontrada.</div>

  <div v-else>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-slate-800">{{ entity.label }}</h1>
      <RouterLink
        :to="`/${route.params.entity}/create`"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors"
      >
        <Plus :size="16" />
        Novo
      </RouterLink>
    </div>

    <p v-if="error" class="text-rose-600 text-sm mb-4">{{ error }}</p>

    <DataTable
      :columns="entity.columns"
      :rows="pageRows"
      :loading="loading"
      @edit="onEdit"
      @delete="onDelete"
    />

    <div v-if="totalPages > 1" class="flex items-center justify-between mt-4 px-1">
      <span class="text-sm text-slate-500">
        {{ (page - 1) * PER_PAGE + 1 }}–{{ Math.min(page * PER_PAGE, rows.length) }}
        de {{ rows.length }} registos
      </span>

      <div class="flex items-center gap-1">
        <button
          :disabled="page === 1"
          class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
          @click="page--"
        >
          <ChevronLeft :size="18" />
        </button>

        <button
          v-for="p in visiblePages"
          :key="p"
          :class="[
            'w-8 h-8 rounded-lg text-sm font-medium transition-colors',
            p === page
              ? 'bg-indigo-600 text-white'
              : 'text-slate-600 hover:bg-slate-100'
          ]"
          @click="page = p"
        >
          {{ p }}
        </button>

        <button
          :disabled="page === totalPages"
          class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
          @click="page++"
        >
          <ChevronRight :size="18" />
        </button>
      </div>
    </div>
  </div>
</template>
