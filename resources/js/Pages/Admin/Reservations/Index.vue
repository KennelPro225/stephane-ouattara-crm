<script setup>
import { ref, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { BOOKING_STATUS_LABELS, BOOKING_STATUS_TAGS, SERVICE_TYPE_LABELS, formatDate } from '@/constants'

const props = defineProps({
  bookings: Object,
  filters: Object,
})

const status = ref(props.filters.status ?? '')
watch(status, () => {
  router.get(route('admin.bookings.index'), { status: status.value || undefined }, { preserveState: true, replace: true })
})

const nextActions = {
  pending: [{ status: 'confirmed', label: 'Confirmer' }, { status: 'cancelled', label: 'Refuser' }],
  confirmed: [{ status: 'completed', label: 'Terminer' }, { status: 'cancelled', label: 'Annuler' }],
  completed: [],
  cancelled: [],
}

function transition (booking, newStatus) {
  router.patch(route('admin.bookings.update', booking.id), { status: newStatus }, { preserveScroll: true })
}
</script>

<template>
  <Head title="Réservations — Admin" />
  <AdminLayout>
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:baseline;justify-content:space-between;margin-bottom:24px">
      <h1 style="margin:0;font-size:clamp(24px,4vw,36px)">Réservations</h1>
    </div>

    <div style="margin-bottom:16px">
      <select v-model="status" class="input" style="max-width:220px">
        <option value="">Tous les statuts</option>
        <option v-for="(label, key) in BOOKING_STATUS_LABELS" :key="key" :value="key">{{ label }}</option>
      </select>
    </div>

    <div style="overflow-x:auto">
      <table class="table" style="min-width:680px">
        <thead><tr><th>Client</th><th>Programme</th><th>Date souhaitée</th><th>Type</th><th>Statut</th><th></th></tr></thead>
        <tbody>
          <tr v-for="r in bookings.data" :key="r.id">
            <td><strong>{{ r.customer.first_name }} {{ r.customer.last_name }}</strong><br><span class="text-muted" style="font-size:11px">{{ r.customer.email }}</span></td>
            <td>{{ r.programme?.title ?? 'Session personnalisée' }}</td>
            <td>{{ formatDate(r.preferred_date) }}<span v-if="r.preferred_time" class="text-muted"> · {{ r.preferred_time }}</span></td>
            <td class="text-muted">{{ SERVICE_TYPE_LABELS[r.service_type] }}</td>
            <td><span class="tag" :class="BOOKING_STATUS_TAGS[r.status]">{{ BOOKING_STATUS_LABELS[r.status] }}</span></td>
            <td style="text-align:right;white-space:nowrap">
              <button v-for="a in nextActions[r.status]" :key="a.status" type="button" class="btn btn-ghost" @click="transition(r, a.status)">{{ a.label }}</button>
              <span v-if="!nextActions[r.status].length" class="text-muted">—</span>
            </td>
          </tr>
          <tr v-if="!bookings.data.length"><td colspan="6" class="text-muted" style="text-align:center;padding:24px">Aucune réservation.</td></tr>
        </tbody>
      </table>
    </div>

    <div v-if="bookings.links.length > 3" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px">
      <template v-for="link in bookings.links" :key="link.label">
        <button v-if="link.url" type="button" class="btn"
          :style="{ minWidth: '40px', border: '1px solid var(--color-divider)', background: link.active ? 'var(--color-accent)' : 'transparent', color: link.active ? 'var(--color-bg)' : 'var(--color-text)' }"
          @click="router.get(link.url, {}, { preserveState: true, preserveScroll: true })" v-html="link.label" />
      </template>
    </div>
  </AdminLayout>
</template>
