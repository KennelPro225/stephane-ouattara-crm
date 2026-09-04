<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { BOOKING_STATUS_LABELS, BOOKING_STATUS_TAGS, HUES, formatDate } from '@/constants'

const props = defineProps({
  stats: Object,
  recentBookings: Array,
  upcomingProgrammes: Array,
  sources: Array,
})

function confirm (booking) {
  router.patch(route('admin.bookings.update', booking.id), { status: 'confirmed' }, { preserveScroll: true })
}
</script>

<template>
  <Head title="Tableau de bord — Admin" />
  <AdminLayout>
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:baseline;justify-content:space-between;margin-bottom:24px">
      <h1 style="margin:0;font-size:clamp(24px,4vw,36px)">Tableau de bord</h1>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:2px;background:var(--color-bg);margin-bottom:32px">
      <div v-for="(s, i) in [
        { label: 'Réservations', value: stats.bookings },
        { label: 'Clients', value: stats.customers },
        { label: 'Programmes actifs', value: stats.activeProgrammes },
        { label: 'Taux de conversion', value: `${stats.conversionRate} %` },
      ]" :key="s.label" :style="{ background: 'var(--color-bg)', boxShadow: '0 0 0 1px var(--color-divider)', borderTop: `5px solid ${HUES[i % 4]}`, padding: '18px' }">
        <p class="text-muted" style="margin:0 0 6px;font-size:10px;letter-spacing:0.1em;text-transform:uppercase">{{ s.label }}</p>
        <p style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:30px">{{ s.value }}</p>
      </div>
    </div>

    <h2 style="font-size:20px;margin:0 0 12px">Demandes de réservation récentes</h2>
    <div style="overflow-x:auto">
      <table class="table" style="min-width:640px">
        <thead>
          <tr><th>Client</th><th>Programme</th><th>Date souhaitée</th><th>Source</th><th>Statut</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="r in recentBookings" :key="r.id">
            <td><strong>{{ r.customer.first_name }} {{ r.customer.last_name }}</strong><br><span class="text-muted" style="font-size:11px">{{ r.customer.email }}</span></td>
            <td>{{ r.programme?.title ?? 'Session personnalisée' }}</td>
            <td>{{ formatDate(r.preferred_date) }}</td>
            <td class="text-muted">{{ r.customer.source ?? '—' }}</td>
            <td><span class="tag" :class="BOOKING_STATUS_TAGS[r.status]">{{ BOOKING_STATUS_LABELS[r.status] }}</span></td>
            <td style="text-align:right;white-space:nowrap">
              <button v-if="r.status === 'pending'" type="button" class="btn btn-ghost" @click="confirm(r)">Confirmer</button>
              <Link :href="route('admin.bookings.index')" class="btn btn-ghost">Voir</Link>
            </td>
          </tr>
          <tr v-if="!recentBookings.length"><td colspan="6" class="text-muted" style="text-align:center;padding:24px">Aucune réservation.</td></tr>
        </tbody>
      </table>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;margin-top:40px;border-top:2px solid var(--color-divider);padding-top:24px">
      <div>
        <h2 style="font-size:20px;margin:0 0 12px">Programmes à venir</h2>
        <div style="border-top:1px solid var(--color-divider)">
          <div v-for="u in upcomingProgrammes" :key="u.id" style="display:flex;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid var(--color-divider);font-size:13px">
            <span><strong>{{ u.title }}</strong><br><span class="text-muted" style="font-size:11px">Début {{ formatDate(u.start_date) }}</span></span>
            <span style="white-space:nowrap">{{ u.max_participants - u.available_seats }} / {{ u.max_participants }}</span>
          </div>
          <p v-if="!upcomingProgrammes.length" class="text-muted" style="padding:12px 0">Aucun programme à venir.</p>
        </div>
      </div>
      <div>
        <h2 style="font-size:20px;margin:0 0 12px">Sources des leads</h2>
        <div style="display:flex;flex-direction:column;gap:12px">
          <div v-for="(s, i) in sources" :key="s.label">
            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span>{{ s.label }}</span><span class="text-muted">{{ s.pct }}</span></div>
            <div style="height:10px;background:var(--color-neutral-300)"><div :style="{ height: '10px', background: HUES[i % 4], width: s.pct }"></div></div>
          </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:20px">
          <Link :href="route('admin.customers.export')" class="btn btn-secondary">Exporter CSV</Link>
          <Link :href="route('admin.programmes.create')" class="btn btn-primary">Nouveau programme</Link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
