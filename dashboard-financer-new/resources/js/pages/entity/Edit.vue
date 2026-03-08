<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import DynamicForm from '../../components/DynamicForm.vue'
import entities    from '../../entities'

const route  = useRoute()
const router = useRouter()

const entity  = computed(() => entities[route.params.entity])
const form    = ref({})
const loading = ref(false)
const error   = ref('')

onMounted(async () => {
  if (!entity.value) return
  try {
    const { data } = await axios.get(`${entity.value.apiBase}/${route.params.id}`)
    form.value = data
  } catch {
    error.value = 'Erro ao carregar registo.'
  }
})

async function handleSubmit() {
  loading.value = true
  error.value   = ''
  try {
    await axios.put(`${entity.value.apiBase}/${route.params.id}`, form.value)
    router.push(`/${route.params.entity}`)
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Erro ao guardar.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div v-if="!entity" class="text-slate-500 p-8">Entidade não encontrada.</div>

  <div v-else>
    <div class="flex items-center gap-3 mb-6">
      <RouterLink
        :to="`/${route.params.entity}`"
        class="text-slate-400 hover:text-indigo-600 text-sm transition-colors"
      >
        ← {{ entity.label }}
      </RouterLink>
      <span class="text-slate-300">/</span>
      <h1 class="text-xl font-bold text-slate-800">Editar registo</h1>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 max-w-2xl">
      <DynamicForm
        v-model="form"
        :fields="entity.fields"
        :loading="loading"
        :error="error"
        submit-label="Guardar alterações"
        @submit="handleSubmit"
        @cancel="router.push(`/${route.params.entity}`)"
      />
    </div>
  </div>
</template>
