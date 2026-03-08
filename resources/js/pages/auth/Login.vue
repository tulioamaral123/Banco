<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Wallet } from 'lucide-vue-next'
import axios from 'axios'

const router   = useRouter()
const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)

async function handleLogin() {
  loading.value = true
  error.value   = ''
  try {
    await axios.post('/login', { email: email.value, password: password.value })
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Credenciais inválidas.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center font-sans">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

      <div class="flex items-center gap-2 text-indigo-600 font-bold text-2xl mb-8">
        <Wallet :size="32" />
        <span>FinanzP</span>
      </div>

      <h2 class="text-xl font-semibold text-slate-800 mb-1">Bem-vindo de volta</h2>
      <p class="text-sm text-slate-500 mb-6">Inicie sessão na sua conta</p>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="nome@exemplo.com"
            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Palavra-passe</label>
          <input
            v-model="password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
          />
        </div>

        <p v-if="error" class="text-rose-600 text-sm">{{ error }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-60"
        >
          {{ loading ? 'A entrar...' : 'Entrar' }}
        </button>
      </form>

    </div>
  </div>
</template>