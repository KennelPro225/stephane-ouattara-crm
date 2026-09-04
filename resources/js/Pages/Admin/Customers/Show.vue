<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
  customer: Object,
  sessions: Array,
})

const statusBadges = {
  pending: 'badge-soft-warning',
  confirmed: 'badge-soft-success',
  completed: 'badge-soft-info',
  cancelled: 'badge-soft-danger',
}
const statusLabels = { pending: 'En attente', confirmed: 'Confirmée', completed: 'Terminée', cancelled: 'Annulée' }
const sourceLabels = { website: 'Site web', referral: 'Recommandation', direct: 'Direct', social_media: 'Réseaux sociaux' }
</script>

<template>
  <Head :title="`${customer.first_name} ${customer.last_name} — Admin`" />
  <AdminLayout>
    <a :href="route('admin.customers.index')" class="btn-ghost !pl-0 animate-slide-up">← Retour aux clients</a>

    <div class="mt-2 grid gap-8 xl:grid-cols-3">
      <!-- Fiche client -->
      <section class="card h-fit animate-slide-up [animation-delay:100ms]">
        <div class="flex items-center gap-4">
          <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full border-[3px] border-accent bg-primary-gradient font-sans text-base font-bold text-white">
            {{ customer.first_name.charAt(0) }}{{ customer.last_name.charAt(0) }}
          </span>
          <div>
            <h1 class="font-sans text-xl font-bold tracking-tight text-ink">{{ customer.first_name }} {{ customer.last_name }}</h1>
            <p class="text-sm text-ink-muted">{{ sourceLabels[customer.source] }} · Inscrit le {{ new Date(customer.created_at).toLocaleDateString('fr-FR') }}</p>
          </div>
        </div>

        <dl class="mt-6 space-y-3 text-sm">
          <div class="flex justify-between gap-3"><dt class="text-ink-muted">Email</dt><dd class="font-medium text-ink">{{ customer.email }}</dd></div>
          <div class="flex justify-between gap-3"><dt class="text-ink-muted">Téléphone</dt><dd class="font-medium text-ink">{{ customer.phone }}</dd></div>
          <div class="flex justify-between gap-3"><dt class="text-ink-muted">Entreprise</dt><dd class="font-medium text-ink">{{ customer.company || '—' }}</dd></div>
          <div class="flex justify-between gap-3"><dt class="text-ink-muted">Ville</dt><dd class="font-medium text-ink">{{ customer.city || '—' }}</dd></div>
          <div class="flex items-center justify-between gap-3">
            <dt class="text-ink-muted">Statut</dt>
            <dd><span class="badge badge-soft-success capitalize">{{ customer.status }}</span></dd>
          </div>
        </dl>

        <div v-if="customer.notes" class="mt-6 rounded-md bg-surface-hover p-4 text-sm leading-relaxed text-ink-secondary">
          {{ customer.notes }}
        </div>
      </section>

      <!-- Historique des sessions -->
      <section class="xl:col-span-2 animate-slide-up [animation-delay:200ms]">
        <h2 class="font-sans text-lg font-bold text-ink">
          Historique des sessions
          <span class="badge badge-outline ml-2 align-middle">{{ sessions.length }}</span>
        </h2>
        <div class="card mt-4 overflow-hidden !p-0">
          <table class="min-w-full divide-y divide-line/50 text-sm">
            <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
              <tr>
                <th class="px-4 py-3">Programme</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Date souhaitée</th>
                <th class="px-4 py-3">Statut</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-line/40">
              <tr v-for="session in sessions" :key="session.id" class="transition-colors hover:bg-surface-hover/60">
                <td class="px-4 py-3.5 font-semibold text-ink">{{ session.programme?.title || 'Session personnalisée' }}</td>
                <td class="px-4 py-3.5 capitalize text-ink-secondary">{{ session.type }}</td>
                <td class="px-4 py-3.5 text-ink-secondary">{{ new Date(session.preferred_date).toLocaleDateString('fr-FR') }}</td>
                <td class="px-4 py-3.5">
                  <span :class="statusBadges[session.status]" class="badge">{{ statusLabels[session.status] }}</span>
                </td>
              </tr>
              <tr v-if="!sessions.length">
                <td colspan="4" class="px-4 py-12 text-center text-ink-muted">Aucune session enregistrée.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
