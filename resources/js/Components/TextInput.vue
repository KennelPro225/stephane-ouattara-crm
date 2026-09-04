<script setup>
import { onMounted, ref } from 'vue'

defineProps({
    modelValue: [String, Number],
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <input
        ref="input"
        class="w-full rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 ease-standard placeholder:text-ink-muted focus:scale-[1.01] focus:border-primary focus:shadow-focus-ring focus:ring-0"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
