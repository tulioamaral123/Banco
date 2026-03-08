<script setup>
import { Pencil, Trash2 } from 'lucide-vue-next'

defineProps({
  columns: { type: Array,   required: true },
  rows:    { type: Array,   default: () => [] },
  loading: { type: Boolean, default: false },
})

const emit = defineEmits(['edit', 'delete'])

function formatCell(value, type) {
  if (value == null) return '—'
  if (type === 'currency') return Number(value).toLocaleString('pt-PT', { style: 'currency', currency: 'brl' })
  if (type === 'date')     return new Date(value).toLocaleDateString('pt-PT')
  return value
}
</script>

<template>
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div v-if="loading" class="p-12 text-center text-slate-400 text-sm">
      A carregar...
    </div>

    <div v-else-if="rows.length === 0" class="p-12 text-center text-slate-400 text-sm">
      Nenhum registo encontrado.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
            <th v-for="col in columns" :key="col.key" class="px-6 py-4 font-semibold">
              {{ col.label }}
            </th>
            <th class="px-6 py-4 font-semibold text-center">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="row in rows"
            :key="row.id"
            class="hover:bg-slate-50 transition-colors"
          >
            <td v-for="col in columns" :key="col.key" class="px-6 py-4 text-sm">
              <span
                v-if="col.type === 'badge'"
                class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase rounded-full"
              >
                {{ row[col.key] }}
              </span>
              <span
                v-else-if="col.type === 'currency'"
                :class="Number(row[col.key]) >= 0 ? 'text-emerald-600 font-bold' : 'text-slate-800 font-bold'"
              >
                {{ formatCell(row[col.key], 'currency') }}
              </span>
              <span v-else class="text-slate-700">
                {{ formatCell(row[col.key], col.type) }}
              </span>
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center justify-center gap-2">
                <button
                  class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                  title="Editar"
                  @click="emit('edit', row)"
                >
                  <Pencil :size="16" />
                </button>
                <button
                  class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                  title="Eliminar"
                  @click="emit('delete', row)"
                >
                  <Trash2 :size="16" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>