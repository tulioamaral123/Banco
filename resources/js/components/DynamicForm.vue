<script setup>
const props = defineProps({
  modelValue:  { type: Object, default: () => ({}) },
  fields:      { type: Array,  required: true },
  loading:     { type: Boolean, default: false },
  error:       { type: String,  default: '' },
  submitLabel: { type: String,  default: 'Guardar' },
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

function update(key, value) {
  emit('update:modelValue', { ...props.modelValue, [key]: value })
}
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-5">

    <div v-for="field in fields" :key="field.key">
      <label class="block text-sm font-medium text-slate-700 mb-1">
        {{ field.label }}
        <span v-if="field.required" class="text-rose-500 ml-0.5">*</span>
      </label>

      <textarea
        v-if="field.type === 'textarea'"
        :value="modelValue[field.key]"
        :required="field.required"
        rows="3"
        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all resize-none"
        @input="update(field.key, $event.target.value)"
      />

      <select
        v-else-if="field.type === 'select'"
        :value="modelValue[field.key]"
        :required="field.required"
        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all bg-white"
        @change="update(field.key, $event.target.value)"
      >
        <option value="" disabled :selected="!modelValue[field.key]">Selecionar...</option>
        <option v-for="opt in field.options" :key="opt.value" :value="opt.value">
          {{ opt.label }}
        </option>
      </select>

      <input
        v-else
        :type="field.type ?? 'text'"
        :value="modelValue[field.key]"
        :required="field.required"
        :step="field.type === 'number' ? '0.01' : undefined"
        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
        @input="update(field.key, field.type === 'number' ? Number($event.target.value) : $event.target.value)"
      />
    </div>

    <p v-if="error" class="text-rose-600 text-sm">{{ error }}</p>

    <div class="flex gap-3 pt-2">
      <button
        type="submit"
        :disabled="loading"
        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-60"
      >
        {{ loading ? 'A guardar...' : submitLabel }}
      </button>
      <button
        type="button"
        class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors"
        @click="emit('cancel')"
      >
        Cancelar
      </button>
    </div>

  </form>
</template>