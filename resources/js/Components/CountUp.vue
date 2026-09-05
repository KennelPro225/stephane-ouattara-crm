<script setup>
import { onBeforeUnmount, ref, watch } from 'vue'

const props = defineProps({
  /** Any string like "200+", "1K+", "150 000 FCFA", "42.9 %" — the leading number animates, everything else is preserved. */
  value: { type: [String, Number], required: true },
  duration: { type: Number, default: 1000 },
})

const el = ref(null)
const display = ref(String(props.value))

const prefersReducedMotion = typeof window !== 'undefined' && window.matchMedia?.('(prefers-reduced-motion: reduce)').matches

function parse (value) {
  const match = String(value).match(/^(\D*)(\d[\d\s.,]*)(.*)$/)
  if (!match) return null
  const prefix = match[1]
  let numberPart = match[2]
  let suffix = match[3]
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
  return { prefix, suffix, target, decimals, groupSpaces }
}

function formatNumber (n, decimals, groupSpaces) {
  let s = n.toFixed(decimals)
  if (groupSpaces) {
    const [int, dec] = s.split('.')
    s = int.replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + (dec ? ',' + dec : '')
  }
  return s
}

let rafId = null
let lastNumeric = 0
let hasAppeared = false

function renderTo (rawValue, from) {
  const parsed = parse(rawValue)
  if (!parsed || parsed.target === null || prefersReducedMotion) {
    display.value = String(rawValue)
    lastNumeric = parsed?.target ?? 0
    return
  }
  const { prefix, suffix, target, decimals, groupSpaces } = parsed
  cancelAnimationFrame(rafId)
  const start = performance.now()
  const tick = (now) => {
    const t = Math.min((now - start) / props.duration, 1)
    const eased = 1 - Math.pow(1 - t, 3)
    const n = from + (target - from) * eased
    display.value = prefix + formatNumber(n, decimals, groupSpaces) + suffix
    if (t < 1) {
      rafId = requestAnimationFrame(tick)
    } else {
      lastNumeric = target
    }
  }
  rafId = requestAnimationFrame(tick)
}

let observer
function setEl (node) {
  el.value = node
  if (!node) return

  if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') {
    hasAppeared = true
    renderTo(props.value, 0)
    return
  }

  const parsed = parse(props.value)
  if (parsed && parsed.target !== null) {
    display.value = parsed.prefix + formatNumber(0, parsed.decimals, parsed.groupSpaces) + parsed.suffix
  }

  observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && ! hasAppeared) {
      hasAppeared = true
      renderTo(props.value, 0)
      observer.disconnect()
    }
  }, { threshold: 0.4 })
  observer.observe(node)
}

// Re-animate whenever the underlying value changes after it has already
// appeared once (e.g. an admin dashboard stat updating after an action) —
// previously this component computed its target once at mount and never
// looked at prop changes again, so numbers went stale/"stuck" after the
// first render.
watch(() => props.value, (newVal) => {
  if (! hasAppeared) return
  renderTo(newVal, lastNumeric)
})

onBeforeUnmount(() => {
  observer?.disconnect()
  cancelAnimationFrame(rafId)
})
</script>

<template>
  <span :ref="setEl">{{ display }}</span>
</template>
