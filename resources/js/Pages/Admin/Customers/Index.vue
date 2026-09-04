<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminPagination from '@/Components/AdminPagination.vue'

const props = defineProps({
  customers: Object,
  filters: Object,
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
const source = ref(props.filters.source ?? '')

watch([search, status, source], () => {
  router.get(route('admin.customers.index'), {
    search: search.value || undefined,
    status: status.value || undefined,
    source: source.value || undefined,
  }, { preserveState: true })
})

const statusBadges = {
  lead: 'bg-surface-hover text-ink-secondary',
  prospect: 'badge-soft-info',
  active: 'badge-soft-success',
  inactive: 'badge-soft-warning',
}

const sourceLabels = { website: 'Site web', referral: 'Recommandation', direct: 'Direct', social_media: 'Réseaux sociaux' }

const inputClass = 'rounded-md border border-line bg-surface px-4 py-2.5 text-sm text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <Head title="Clients — Admin" />
  <AdminLayout>
    <div class="flex flex-wrap items-center justify-between gap-4 animate-slide-up">
      <div>
        <span class="eyebrow">CRM</span>
        <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Clients</h1>
      </div>
      <a :href="route('admin.customers.export')" class="btn-secondary !py-2.5">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
        Exporter en CSV
      </a>
    </div>

    <!-- Filtres -->
    <div class="glass mt-6 flex flex-col gap-4 rounded-xl p-4 sm:flex-row sm:items-center">
      <input v-model="search" type="search" placeholder="Nom, email…" :class="[inputClass, 'sm:max-w-xs']" aria-label="Rechercher un client" />
      <select v-model="status" :class="inputClass" aria-label="Filtrer par statut">
        <option value="">Tous les statuts</option>
        <option value="lead">Lead</option>
        <option value="prospect">Prospect</option>
        <option value="active">Actif</option>
        <option value="inactive">Inactif</option>
      </select>
      <select v-model="source" :class="inputClass" aria-label="Filtrer par source">
        <option value="">Toutes les sources</option>
        <option v-for="(label, value) in sourceLabels" :key="value" :value="value">{{ label }}</option>
      </select>
    </div>

    <div class="card mt-6 overflow-x-auto !p-0 animate-slide-up [animation-delay:100ms]">
      <table class="min-w-full divide-y divide-line/50 text-sm">
        <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
          <tr>
            <th class="px-4 py-3.5">Client</th>
            <th class="px-4 py-3.5">Contact</th>
            <th class="px-4 py-3.5">Entreprise</th>
            <th class="px-4 py-3.5 text-center">Sessions</th>
            <th class="px-4 py-3.5">Source</th>
            <th class="px-4 py-3.5">Statut</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-line/40">
          <tr v-for="customer in customers.data" :key="customer.id"
            class="cursor-pointer transition-colors hover:bg-surface-hover/60"
            @click="router.visit(route('admin.customers.show', customer.id))">
            <td class="px-4 py-3.5">
              <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full border-2 border-accent bg-primary-gradient text-xs font-bold text-white">
                  {{ customer.first_name.charAt(0) }}{{ customer.last_name.charAt(0) }}
                </span>
                <div>
                  <p class="font-semibold text-ink">{{ customer.first_name }} {{ customer.last_name }}</p>
                  <p class="text-xs text-ink-muted">{{ customer.city }}</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-3.5">
              <p class="text-ink-secondary">{{ customer.email }}</p>
              <p class="text-xs text-ink-muted">{{ customer.phone }}</p>
            </td>
            <td class="px-4 py-3.5 text-ink-secondary">{{ customer.company || '—' }}</td>
            <td class="px-4 py-3.5 text-center font-semibold text-ink">{{ customer.sessions_count }}</td>
            <td class="px-4 py-3.5 text-ink-secondary">{{ sourceLabels[customer.source] }}</td>
            <td class="px-4 py-3.5">
              <span :class="statusBadges[customer.status]" class="badge capitalize">{{ customer.status }}</span>
            </td>
          </tr>
          <tr v-if="!customers.data.length">
            <td colspan="6" class="px-4 py-12 text-center text-ink-muted">Aucun client trouvé.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <AdminPagination :links="customers.links" />
  </AdminLayout>
</template>
