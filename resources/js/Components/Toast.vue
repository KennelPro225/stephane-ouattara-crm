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
  // Warnings carry details the coach has to act on — give them longer to read.
  timer = setTimeout(() => (visible.value = false), kind === 'warning' ? 10000 : 5000)
}

watch(() => page.props.flash?.success, (value) => show(value, 'success'), { immediate: true })
watch(() => page.props.flash?.warning, (value) => show(value, 'warning'), { immediate: true })
watch(() => page.props.flash?.error, (value) => show(value, 'error'), { immediate: true })
</script>

<template>
  <Transition name="toast">
    <div v-if="visible && message" class="toast" :class="`toast-${variant}`" role="status" aria-live="polite">
      {{ message }}
    </div>
  </Transition>
</template>
