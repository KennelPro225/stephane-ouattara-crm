<script setup>
import { Link, router, useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { BOOKING_STATUS_LABELS, BOOKING_STATUS_TAGS, CUSTOMER_STATUS_LABELS, formatDate } from '@/constants'

const props = defineProps({
  customer: Object,
  bookings: Array,
})

function updateStatus (event) {
  router.patch(route('admin.customers.update', props.customer.id), { status: event.target.value }, { preserveScroll: true })
}

const noteForm = useForm({ note: '' })
function submitNote () {
  noteForm.post(route('admin.customers.notes.store', props.customer.id), {
    preserveScroll: true,
    onSuccess: () => noteForm.reset('note'),
  })
}
</script>

<template>
  <Head :title="`${customer.first_name} ${customer.last_name} — Admin`" />
  <AdminLayout>
    <Link :href="route('admin.customers.index')" class="btn btn-ghost" style="padding-left:0">← Tous les clients</Link>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;margin-top:16px">
      <section style="border:2px solid var(--color-text);padding:24px;height:fit-content">
        <h1 style="margin:0 0 4px;font-size:26px">{{ customer.first_name }} {{ customer.last_name }}</h1>
        <p class="text-muted" style="margin:0 0 20px;font-size:13px">Client depuis le {{ formatDate(customer.created_at) }}</p>

        <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
          <div style="display:flex;justify-content:space-between;gap:8px"><span class="text-muted">Email</span><span>{{ customer.email }}</span></div>
          <div style="display:flex;justify-content:space-between;gap:8px"><span class="text-muted">Téléphone</span><span>{{ customer.phone || '—' }}</span></div>
          <div style="display:flex;justify-content:space-between;gap:8px"><span class="text-muted">Société</span><span>{{ customer.company || '—' }}</span></div>
          <div style="display:flex;justify-content:space-between;gap:8px"><span class="text-muted">Fonction</span><span>{{ customer.job_title || '—' }}</span></div>
          <div style="display:flex;justify-content:space-between;gap:8px"><span class="text-muted">Source</span><span>{{ customer.source || '—' }}</span></div>
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
            <span class="text-muted">Statut</span>
            <select :value="customer.status" class="input" style="width:auto;min-height:36px" @change="updateStatus">
              <option v-for="(label, key) in CUSTOMER_STATUS_LABELS" :key="key" :value="key">{{ label }}</option>
            </select>
          </div>
        </div>

        <h6 style="margin:24px 0 8px">Notes</h6>
        <div v-if="customer.notes" class="text-muted" style="white-space:pre-line;font-size:12px;max-height:220px;overflow-y:auto;background:var(--color-surface);padding:12px;margin-bottom:12px">{{ customer.notes }}</div>
        <p v-else class="text-muted" style="font-size:12px">Aucune note.</p>
        <form style="display:flex;flex-direction:column;gap:8px" @submit.prevent="submitNote">
          <textarea v-model="noteForm.note" class="input" placeholder="Ajouter une note…" style="min-height:70px"></textarea>
          <button type="submit" class="btn btn-secondary" :disabled="noteForm.processing || !noteForm.note.trim()">Ajouter la note</button>
        </form>
      </section>

      <section>
        <h2 style="margin:0 0 12px;font-size:20px">Historique des réservations</h2>
        <div style="overflow-x:auto">
          <table class="table">
            <thead><tr><th>Programme</th><th>Type</th><th>Date</th><th>Statut</th></tr></thead>
            <tbody>
              <tr v-for="b in bookings" :key="b.id">
                <td>{{ b.programme?.title ?? 'Session personnalisée' }}</td>
                <td class="text-muted">{{ b.service_type }}</td>
                <td>{{ formatDate(b.preferred_date) }}</td>
                <td><span class="tag" :class="BOOKING_STATUS_TAGS[b.status]">{{ BOOKING_STATUS_LABELS[b.status] }}</span></td>
              </tr>
              <tr v-if="!bookings.length"><td colspan="4" class="text-muted" style="text-align:center;padding:24px">Aucune réservation enregistrée.</td></tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
