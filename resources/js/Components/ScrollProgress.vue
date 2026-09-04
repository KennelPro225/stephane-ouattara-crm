<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const progress = ref(0)

function update() {
    const total = document.documentElement.scrollHeight - window.innerHeight
    progress.value = total > 0 ? (window.scrollY / total) * 100 : 0
}

onMounted(() => {
    update()
    window.addEventListener('scroll', update, { passive: true })
    window.addEventListener('resize', update)
})

onUnmounted(() => {
    window.removeEventListener('scroll', update)
    window.removeEventListener('resize', update)
})
</script>

<template>
    <div class="pointer-events-none fixed inset-x-0 top-0 z-[70] h-0.5" aria-hidden="true">
        <div
            class="h-full bg-accent-gradient shadow-glow transition-[width] duration-150 ease-standard"
            :style="{ width: `${progress}%` }"
        />
    </div>
</template>
