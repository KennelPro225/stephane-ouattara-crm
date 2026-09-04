<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({
    /** Exemples : "200+", "1K+", "50" */
    value: { type: String, required: true },
})

const display = ref('0')
const el = ref(null)

// Parse "200+" -> { target: 200, prefix: "", suffix: "+" }
//         "1K+"  -> { target: 1,  prefix: "", suffix: "K+" }
const match = String(props.value).match(/^([^\d]*)([\d.,]+)(.*)$/)
const target = match ? parseFloat(match[2].replace(',', '.')) : 0
const prefix = match ? match[1] : ''
const suffix = match ? match[3] : ''
const decimals = match && match[2].includes('.') ? 1 : 0

onMounted(() => {
    if (!match) {
        display.value = props.value
        return
    }

    if (!('IntersectionObserver' in window)) {
        display.value = props.value
        return
    }

    const io = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) return
            io.disconnect()

            const duration = 1400
            const start = performance.now()

            const tick = (now) => {
                const t = Math.min((now - start) / duration, 1)
                // easeOutCubic pour un ralentissement naturel
                const eased = 1 - Math.pow(1 - t, 3)
                const current = target * eased
                display.value =
                    prefix +
                    current.toFixed(decimals).replace(/\.0$/, '') +
                    suffix
                if (t < 1) requestAnimationFrame(tick)
            }

            requestAnimationFrame(tick)
        },
        { threshold: 0.4 },
    )

    io.observe(el.value)
})
</script>

<template>
    <span ref="el">{{ display }}</span>
</template>
