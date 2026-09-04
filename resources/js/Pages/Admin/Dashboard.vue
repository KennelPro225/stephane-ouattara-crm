<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DashboardCard from '@/Components/DashboardCard.vue'

defineProps({
  stats: Object,
  recentBookings: Array,
  upcomingProgrammes: Array,
})

const statusBadges = {
  pending: 'badge-soft-warning',
  confirmed: 'badge-soft-success',
  completed: 'badge-soft-info',
  cancelled: 'badge-soft-danger',
}

const statusLabels = {
  pending: 'En attente', confirmed: 'Confirmée', completed: 'Terminée', cancelled: 'Annulée',
}
</script>

<template>
  <Head title="Tableau de bord — Admin" />
  <AdminLayout>
    <div class="animate-slide-up">
      <span class="eyebrow">Vue d'ensemble</span>
      <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Tableau de bord</h1>
    </div>

    <!-- Stats -->
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
      <DashboardCard title="Réservations" :value="stats.total_sessions" icon="calendar" />
      <DashboardCard title="En attente" :value="stats.pending_sessions" icon="clock" />
      <DashboardCard title="Clients" :value="stats.customers" icon="users" />
      <DashboardCard title="Programmes publiés" :value="stats.published_programmes" icon="book" />
      <DashboardCard title="Conversion" :value="`${stats.conversion_rate}%`" icon="trend" />
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-3">
      <!-- Dernières réservations -->
      <section class="xl:col-span-2 animate-slide-up [animation-delay:100ms]">
        <h2 class="font-sans text-lg font-bold text-ink">Dernières réservations</h2>
        <div class="card mt-4 !p-0 overflow-hidden">
          <table class="min-w-full divide-y divide-line/50 text-sm">
            <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
              <tr>
                <th class="px-4 py-3">Client</th>
                <th class="px-4 py-3">Programme</th>
                <th class="px-4 py-3">Date souhaitée</th>
                <th class="px-4 py-3">Statut</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-line/40">
              <tr v-for="booking in recentBookings" :key="booking.id" class="transition-colors hover:bg-surface-hover/60">
                <td class="px-4 py-3.5">
                  <p class="font-semibold text-ink">{{ booking.customer?.first_name }} {{ booking.customer?.last_name }}</p>
                  <p class="text-xs text-ink-muted">{{ booking.customer?.email }}</p>
                </td>
                <td class="px-4 py-3.5 text-ink-secondary">{{ booking.programme?.title || 'Session personnalisée' }}</td>
                <td class="px-4 py-3.5 text-ink-secondary">{{ new Date(booking.preferred_date).toLocaleDateString('fr-FR') }}</td>
                <td class="px-4 py-3.5">
                  <span :class="statusBadges[booking.status]" class="badge">
                    <span class="relative flex h-2 w-2">
                      <span v-if="booking.status === 'pending'" class="absolute inline-flex h-full w-full rounded-full bg-current opacity-75 animate-pulse" />
                      <span class="relative inline-flex h-2 w-2 rounded-full bg-current" />
                    </span>
                    {{ statusLabels[booking.status] }}
                  </span>
                </td>
              </tr>
              <tr v-if="!recentBookings.length">
                <td colspan="4" class="px-4 py-10 text-center text-ink-muted">Aucune réservation pour le moment.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Prochains programmes -->
      <section class="animate-slide-up [animation-delay:200ms]">
        <h2 class="font-sans text-lg font-bold text-ink">Prochains programmes</h2>
        <ul class="mt-4 space-y-3">
          <li v-for="programme in upcomingProgrammes" :key="programme.id"
            class="card card-hover flex items-center gap-4 !p-4">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-md bg-primary-gradient text-center leading-none shadow-sm">
              <span class="font-sans text-sm font-bold text-white">{{ new Date(programme.start_date).getDate() }}</span>
            </div>
            <div class="min-w-0">
              <p class="truncate font-semibold text-ink">{{ programme.title }}</p>
              <p class="text-xs text-ink-muted">Début : {{ new Date(programme.start_date).toLocaleDateString('fr-FR') }} · {{ programme.location }}</p>
            </div>
          </li>
          <li v-if="!upcomingProgrammes.length" class="rounded-lg border border-dashed border-line p-6 text-center text-sm text-ink-muted">
            Aucun programme à venir.
          </li>
        </ul>
      </section>
    </div>
  </AdminLayout>
</template>
