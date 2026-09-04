<script setup>
import { ref } from 'vue'

const isDark = ref(document.documentElement.classList.contains('dark'))

function toggle() {
    isDark.value = !isDark.value
    document.documentElement.classList.toggle('dark', isDark.value)
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}
</script>

<template>
    <button
        type="button"
        class="grid h-10 w-10 place-items-center rounded-md border border-line/60 text-ink-secondary transition-all duration-150 hover:bg-surface-hover hover:text-accent"
        :aria-label="isDark ? 'Activer le mode clair' : 'Activer le mode sombre'"
        @click="toggle"
    >
        <!-- Soleil -->
        <svg v-if="isDark" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36 6.36l-1.42-1.42M7.05 7.05L5.64 5.64m12.72 0l-1.42 1.41M7.05 16.95l-1.41 1.41M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <!-- Lune -->
        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
        </svg>
    </button>
</template>
