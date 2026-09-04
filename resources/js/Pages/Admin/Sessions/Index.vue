<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminPagination from '@/Components/AdminPagination.vue'

const props = defineProps({
  sessions: Object,
  filters: Object,
})

const status = ref(props.filters.status ?? '')

watch(status, () => {
  router.get(route('admin.sessions.index'), {
    status: status.value || undefined,
  }, { preserveState: true })
})

const statusBadges = {
  pending: 'badge-soft-warning',
  confirmed: 'badge-soft-success',
  completed: 'badge-soft-info',
  cancelled: 'badge-soft-danger',
}
const statusLabels = { pending: 'En attente', confirmed: 'Confirmée', completed: 'Terminée', cancelled: 'Annulée' }

const nextActions = {
  pending: [{ status: 'confirmed', label: 'Confirmer' }, { status: 'cancelled', label: 'Annuler' }],
  confirmed: [{ status: 'completed', label: 'Marquer terminée' }, { status: 'cancelled', label: 'Annuler' }],
  completed: [],
  cancelled: [],
}

function transition(session, newStatus) {
  router.patch(route('admin.sessions.update', session.id), { status: newStatus }, { preserveScroll: true })
}

const inputClass = 'rounded-md border border-line bg-surface px-4 py-2.5 text-sm text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <Head title="Réservations — Admin" />
  <AdminLayout>
    <div class="flex flex-wrap items-center justify-between gap-4 animate-slide-up">
      <div>
        <span class="eyebrow">CRM</span>
        <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Réservations</h1>
      </div>
    </div>

    <div class="glass mt-6 flex flex-col gap-4 rounded-xl p-4 sm:flex-row sm:items-center">
      <select v-model="status" :class="inputClass" aria-label="Filtrer par statut">
        <option value="">Tous les statuts</option>
        <option v-for="(label, value) in statusLabels" :key="value" :value="value">{{ label }}</option>
      </select>
    </div>

    <div class="card mt-6 overflow-x-auto !p-0 animate-slide-up [animation-delay:100ms]">
      <table class="min-w-full divide-y divide-line/50 text-sm">
        <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
          <tr>
            <th class="px-4 py-3.5">Client</th>
            <th class="px-4 py-3.5">Programme</th>
            <th class="px-4 py-3.5 font-mono text-[11px] normal-case tracking-normal">Date souhaitée</th>
            <th class="px-4 py-3.5">Statut</th>
            <th class="px-4 py-3.5">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-line/40">
          <tr v-for="session in sessions.data" :key="session.id" class="transition-colors hover:bg-surface-hover/60">
            <td class="px-4 py-3.5">
              <p class="font-semibold text-ink">{{ session.customer?.first_name }} {{ session.customer?.last_name }}</p>
              <p class="text-xs text-ink-muted">{{ session.customer?.email }}</p>
            </td>
            <td class="px-4 py-3.5 text-ink-secondary">{{ session.programme?.title || 'Session personnalisée' }}</td>
            <td class="px-4 py-3.5 font-mono text-xs text-ink-secondary">{{ new Date(session.preferred_date).toLocaleDateString('fr-FR') }}</td>
            <td class="px-4 py-3.5">
              <span :class="statusBadges[session.status]" class="badge">{{ statusLabels[session.status] }}</span>
            </td>
            <td class="px-4 py-3.5">
              <div class="flex flex-wrap gap-2">
                <button v-for="action in nextActions[session.status]" :key="action.status" type="button"
                  class="text-xs font-semibold text-primary hover:underline"
                  @click="transition(session, action.status)">
                  {{ action.label }}
                </button>
                <span v-if="!nextActions[session.status].length" class="text-xs text-ink-muted">—</span>
              </div>
            </td>
          </tr>
          <tr v-if="!sessions.data.length">
            <td colspan="5" class="px-4 py-12 text-center text-ink-muted">Aucune réservation trouvée.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <AdminPagination :links="sessions.links" />
  </AdminLayout>
</template>
