<script setup>
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const props = defineProps({
  monthlySessions: Array,
  programmePopularity: Array,
  sessionsByType: Array,
  customersBySource: Array,
  conversion: Object,
})

const range = ref(12)
const ranges = [6, 12, 24]

function applyRange() {
  window.location = route('admin.analytics', { range: range.value })
}

const monthlyData = computed(() => ({
  labels: props.monthlySessions.map((m) => m.month),
  datasets: [{
    label: 'Réservations',
    data: props.monthlySessions.map((m) => m.count),
    backgroundColor: '#7C5A2F',
    hoverBackgroundColor: '#E67E22',
    borderRadius: 8,
    maxBarThickness: 32,
  }],
}))

const popularityData = computed(() => ({
  labels: props.programmePopularity.map((p) => p.title),
  datasets: [{
    label: 'Inscriptions',
    data: props.programmePopularity.map((p) => p.sessions_count),
    backgroundColor: '#D4845C',
    hoverBackgroundColor: '#B8740F',
    borderRadius: 8,
    maxBarThickness: 24,
  }],
}))

const typeLabels = {
  individual: 'Individuel',
  club_des_champions: 'Club des Champions',
  group: 'Groupe',
  corporate_wellness: 'Bien-être entreprise',
  teambuilding: 'Teambuilding',
  other: 'Autre',
}
const sourceLabels = { website: 'Site web', referral: 'Recommandation', direct: 'Direct', social_media: 'Réseaux sociaux' }

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: {
    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(232, 221, 208, 0.4)' } },
    x: { grid: { display: false } },
  },
}

// Tunnel de conversion
const funnelSteps = computed(() => [
  { label: 'En attente', value: props.conversion.pending, color: 'bg-warning' },
  { label: 'Confirmées', value: props.conversion.confirmed, color: 'bg-success' },
  { label: 'Terminées', value: props.conversion.completed, color: 'bg-info' },
])

const totalForFunnel = computed(() =>
  Math.max(1, funnelSteps.value.reduce((sum, s) => sum + s.value, 0))
)
</script>

<template>
  <Head title="Analytics — Admin" />
  <AdminLayout>
    <div class="flex flex-wrap items-center justify-between gap-4 animate-slide-up">
      <div>
        <span class="eyebrow">Analyse</span>
        <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Analytics</h1>
      </div>
      <div class="flex items-center gap-2">
        <select v-model.number="range" aria-label="Période"
          class="rounded-md border border-line bg-surface px-4 py-2.5 text-sm text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0">
          <option v-for="r in ranges" :key="r" :value="r">{{ r }} derniers mois</option>
        </select>
        <button @click="applyRange" class="btn-primary !py-2.5">Appliquer</button>
      </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
      <!-- Réservations mensuelles -->
      <section class="card animate-slide-up [animation-delay:100ms]">
        <h2 class="font-sans font-bold text-ink">Réservations mensuelles</h2>
        <div class="mt-4 h-72">
          <Bar :data="monthlyData" :options="chartOptions" />
        </div>
      </section>

      <!-- Popularité -->
      <section class="card animate-slide-up [animation-delay:200ms]">
        <h2 class="font-sans font-bold text-ink">Programmes les plus populaires</h2>
        <div class="mt-4 h-72">
          <Bar :data="popularityData" :options="{ ...chartOptions, indexAxis: 'y' }" />
        </div>
      </section>

      <!-- Tunnel de conversion -->
      <section class="card animate-slide-up [animation-delay:300ms]">
        <div class="flex items-center justify-between gap-3">
          <h2 class="font-sans font-bold text-ink">Tunnel de conversion</h2>
          <span class="badge badge-solid">{{ conversion.rate }}%</span>
        </div>
        <div class="mt-6 space-y-5">
          <div v-for="(step, i) in funnelSteps" :key="step.label">
            <div class="mb-1.5 flex items-center justify-between text-sm">
              <span class="flex items-center gap-2 font-medium text-ink-secondary">
                <span class="grid h-6 w-6 place-items-center rounded-full bg-primary-gradient text-[10px] font-bold text-white">{{ i + 1 }}</span>
                {{ step.label }}
              </span>
              <span class="font-sans font-extrabold text-ink">{{ step.value }}</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-line/50">
              <div :class="step.color" class="h-full rounded-full transition-all duration-350 ease-smooth"
                :style="{ width: `${(step.value / totalForFunnel) * 100}%` }" />
            </div>
          </div>
          <p class="flex items-center gap-2 pt-1 text-xs text-ink-muted">
            <span class="h-2 w-2 rounded-full bg-danger" aria-hidden="true"></span>
            Annulées : {{ conversion.cancelled }}
          </p>
        </div>
      </section>

      <!-- Répartitions -->
      <section class="grid gap-6 sm:grid-cols-2 animate-slide-up [animation-delay:400ms]">
        <div class="card">
          <h2 class="font-sans text-sm font-bold uppercase tracking-wide text-ink-secondary">Par type</h2>
          <ul class="mt-4 space-y-3 text-sm">
            <li v-for="item in sessionsByType" :key="item.type" class="flex items-center justify-between rounded-md bg-surface-hover px-3 py-2 transition-colors hover:bg-line/30">
              <span class="text-ink-secondary">{{ typeLabels[item.type] ?? item.type }}</span>
              <span class="font-bold text-ink">{{ item.count }}</span>
            </li>
            <li v-if="!sessionsByType.length" class="text-ink-muted">Aucune donnée.</li>
          </ul>
        </div>
        <div class="card">
          <h2 class="font-sans text-sm font-bold uppercase tracking-wide text-ink-secondary">Par source</h2>
          <ul class="mt-4 space-y-3 text-sm">
            <li v-for="item in customersBySource" :key="item.source" class="flex items-center justify-between rounded-md bg-surface-hover px-3 py-2 transition-colors hover:bg-line/30">
              <span class="text-ink-secondary">{{ sourceLabels[item.source] ?? item.source }}</span>
              <span class="font-bold text-ink">{{ item.count }}</span>
            </li>
            <li v-if="!customersBySource.length" class="text-ink-muted">Aucune donnée.</li>
          </ul>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
