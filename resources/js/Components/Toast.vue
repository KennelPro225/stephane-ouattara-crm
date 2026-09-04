<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const visible = ref(false)
const message = ref('')
const variant = ref('success')
let timer

function show (value, kind) {
  if (!value) return
  message.value = value
  variant.value = kind
  visible.value = true
  clearTimeout(timer)
  timer = setTimeout(() => (visible.value = false), 5000)
}

watch(() => page.props.flash?.success, (value) => show(value, 'success'), { immediate: true })
watch(() => page.props.flash?.error, (value) => show(value, 'error'), { immediate: true })
</script>

<template>
  <div v-if="visible && message" class="toast" :class="{ 'toast-error': variant === 'error' }" role="status" aria-live="polite">
    {{ message }}
  </div>
</template>
