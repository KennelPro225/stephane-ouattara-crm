<script setup>
import { onBeforeUnmount, ref } from 'vue'

const props = defineProps({
  /** Any string like "200+", "1K+", "150 000 FCFA", "42.9 %" — the leading number animates, everything else is preserved. */
  value: { type: [String, Number], required: true },
  duration: { type: Number, default: 1200 },
})

const el = ref(null)
const display = ref(String(props.value))

const match = String(props.value).match(/^(\D*)(\d[\d\s.,]*)(.*)$/)
const prefix = match ? match[1] : ''
let numberPart = match ? match[2] : ''
let suffix = match ? match[3] : ''
// Keep exact original spacing: a thousands-grouping space is part of the
// number, but a trailing space before a unit (e.g. "42.9 %") belongs to the suffix.
const trailingSpace = numberPart.match(/\s+$/)
if (trailingSpace) {
  suffix = trailingSpace[0] + suffix
  numberPart = numberPart.slice(0, -trailingSpace[0].length)
}
const target = numberPart ? parseFloat(numberPart.replace(/\s/g, '').replace(',', '.')) : null
const decimals = numberPart.includes(',') || numberPart.includes('.') ? (numberPart.split(/[.,]/)[1] || '').length : 0
const groupSpaces = numberPart.includes(' ')

function formatNumber (n) {
  let s = n.toFixed(decimals)
  if (groupSpaces) {
    const [int, dec] = s.split('.')
    s = int.replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + (dec ? ',' + dec : '')
  }
  return s
}

const prefersReducedMotion = typeof window !== 'undefined' && window.matchMedia?.('(prefers-reduced-motion: reduce)').matches

let observer
function animate () {
  if (target === null || prefersReducedMotion) return
  const start = performance.now()
  const tick = (now) => {
    const t = Math.min((now - start) / props.duration, 1)
    const eased = 1 - Math.pow(1 - t, 3)
    display.value = prefix + formatNumber(target * eased) + suffix
    if (t < 1) requestAnimationFrame(tick)
  }
  requestAnimationFrame(tick)
}

function onMountedEl (node) {
  el.value = node
  if (!node || target === null) return
  if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') return
  observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      animate()
      observer.disconnect()
    }
  }, { threshold: 0.4 })
  observer.observe(node)
}
onBeforeUnmount(() => observer?.disconnect())
</script>

<template>
  <span :ref="onMountedEl">{{ display }}</span>
</template>
