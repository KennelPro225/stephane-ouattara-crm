<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' },
  month: { type: String, required: true },
  availableDates: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'update:month'])

const MONTHS = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
const WEEKDAY_INITIALS = ['L', 'M', 'M', 'J', 'V', 'S', 'D']

const availableSet = computed(() => new Set(props.availableDates))

function pad (n) {
  return String(n).padStart(2, '0')
}

function toKey (year, monthIndex, day) {
  return `${year}-${pad(monthIndex + 1)}-${pad(day)}`
}

const cursor = computed(() => {
  const [year, month] = props.month.split('-').map(Number)
  return { year, monthIndex: month - 1 }
})

const label = computed(() => `${MONTHS[cursor.value.monthIndex]} ${cursor.value.year}`)

const currentMonthKey = computed(() => {
  const now = new Date()
  return `${now.getFullYear()}-${pad(now.getMonth() + 1)}`
})

// No booking in the past, so there is nothing to see before the current month.
const canGoBack = computed(() => props.month > currentMonthKey.value)

/** Days of the displayed month, padded so the grid starts on a Monday. */
const cells = computed(() => {
  const { year, monthIndex } = cursor.value
  const firstOfMonth = new Date(year, monthIndex, 1)
  // getDay() is 0=Sunday; shift so Monday is the first column.
  const leading = (firstOfMonth.getDay() + 6) % 7
  const daysInMonth = new Date(year, monthIndex + 1, 0).getDate()

  const list = []
  for (let i = 0; i < leading; i += 1) {
    list.push({ key: `pad-${i}`, blank: true })
  }
  for (let day = 1; day <= daysInMonth; day += 1) {
    const key = toKey(year, monthIndex, day)
    list.push({ key, blank: false, day, available: availableSet.value.has(key) })
  }
  return list
})

function shiftMonth (offset) {
  const { year, monthIndex } = cursor.value
  const shifted = new Date(year, monthIndex + offset, 1)
  emit('update:month', `${shifted.getFullYear()}-${pad(shifted.getMonth() + 1)}`)
}

function select (cell) {
  if (!cell.available) return
  emit('update:modelValue', cell.key)
}
</script>

<template>
  <div class="calendar" :class="{ 'calendar-loading': loading }">
    <div class="calendar-head">
      <button type="button" class="calendar-nav" :disabled="!canGoBack" aria-label="Mois précédent" @click="shiftMonth(-1)">‹</button>
      <span class="calendar-label" aria-live="polite">{{ label }}</span>
      <button type="button" class="calendar-nav" aria-label="Mois suivant" @click="shiftMonth(1)">›</button>
    </div>

    <div class="calendar-grid" role="grid">
      <span v-for="(initial, i) in WEEKDAY_INITIALS" :key="`h-${i}`" class="calendar-weekday" aria-hidden="true">{{ initial }}</span>

      <template v-for="cell in cells" :key="cell.key">
        <span v-if="cell.blank" class="calendar-blank"></span>
        <button
          v-else
          type="button"
          class="calendar-day"
          :class="{ 'is-selected': cell.key === modelValue, 'is-unavailable': !cell.available }"
          :disabled="!cell.available"
          :aria-pressed="cell.key === modelValue"
          :aria-label="cell.available ? `${cell.day} ${label}` : `${cell.day} ${label} — indisponible`"
          @click="select(cell)"
        >{{ cell.day }}</button>
      </template>
    </div>

    <p class="calendar-hint">
      <span v-if="loading">Chargement des disponibilités…</span>
      <span v-else-if="availableDates.length === 0">Aucune date disponible ce mois-ci — essayez le mois suivant.</span>
      <span v-else>Les jours grisés sont fermés ou déjà complets.</span>
    </p>
  </div>
</template>

<style scoped>
.calendar {
  border: 2px solid var(--color-text);
  padding: 12px;
  max-width: 340px;
  transition: opacity 0.15s ease;
}
.calendar-loading { opacity: 0.55; }

.calendar-head {
  display: flex; align-items: center; justify-content: space-between;
  gap: 8px; margin-bottom: 10px;
  border-bottom: 2px solid var(--color-divider); padding-bottom: 8px;
}
.calendar-label {
  font-family: var(--font-heading); font-weight: 700; font-size: 14px;
}
.calendar-nav {
  min-width: 32px; min-height: 32px; padding: 0;
  border: 1px solid var(--color-divider); background: transparent;
  font-size: 18px; line-height: 1; cursor: pointer; color: var(--color-text);
  transition: background-color 0.15s ease, color 0.15s ease;
}
.calendar-nav:hover:not(:disabled) { background: var(--color-text); color: var(--color-bg); }
.calendar-nav:disabled { opacity: 0.3; cursor: not-allowed; }

.calendar-grid {
  display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px;
}
.calendar-weekday {
  text-align: center; font-size: 10px; letter-spacing: 0.08em;
  text-transform: uppercase; color: var(--color-neutral-600); padding-bottom: 4px;
}
.calendar-blank { aspect-ratio: 1; }

.calendar-day {
  aspect-ratio: 1; display: grid; place-items: center;
  border: 1px solid var(--color-divider); background: transparent;
  font-family: var(--font-body); font-size: 13px; color: var(--color-text);
  cursor: pointer;
  transition: background-color 0.15s ease, color 0.15s ease;
}
.calendar-day:hover:not(:disabled):not(.is-selected) { background: var(--color-accent-200); }
.calendar-day.is-selected {
  background: var(--color-accent); color: var(--color-bg);
  border-color: var(--color-accent); font-weight: 700;
}
.calendar-day.is-unavailable {
  color: var(--color-neutral-400); border-color: transparent;
  background: transparent; cursor: not-allowed; text-decoration: line-through;
}

.calendar-hint {
  margin: 10px 0 0; font-size: 11px; line-height: 1.5;
  color: var(--color-neutral-600);
}

@media (prefers-reduced-motion: reduce) {
  .calendar, .calendar-nav, .calendar-day { transition: none; }
}
</style>
