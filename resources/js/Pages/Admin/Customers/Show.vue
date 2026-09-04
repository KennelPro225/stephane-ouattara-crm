<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
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

const customerStatusBadges = {
  lead: 'bg-surface-hover text-ink-secondary',
  prospect: 'badge-soft-info',
  active: 'badge-soft-success',
  inactive: 'badge-soft-warning',
}

function updateStatus(event) {
  router.patch(route('admin.customers.update', props.customer.id), { status: event.target.value }, { preserveScroll: true })
}

const noteForm = useForm({ note: '' })

function submitNote() {
  noteForm.post(route('admin.customers.notes.store', props.customer.id), {
    preserveScroll: true,
    onSuccess: () => noteForm.reset('note'),
  })
}
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
            <dd>
              <select :value="customer.status" @change="updateStatus"
                class="rounded-md border border-line bg-surface px-2.5 py-1.5 text-xs font-semibold shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0"
                :class="customerStatusBadges[customer.status]">
                <option v-for="s in ['lead', 'prospect', 'active', 'inactive']" :key="s" :value="s">{{ s }}</option>
              </select>
            </dd>
          </div>
        </dl>

        <!-- Notes -->
        <div class="mt-6">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Notes</h3>
          <div v-if="customer.notes" class="mt-2 max-h-64 overflow-y-auto whitespace-pre-line rounded-md bg-surface-hover p-4 text-sm leading-relaxed text-ink-secondary">
            {{ customer.notes }}
          </div>
          <p v-else class="mt-2 text-sm text-ink-muted">Aucune note pour le moment.</p>

          <form class="mt-3 space-y-2" @submit.prevent="submitNote">
            <textarea v-model="noteForm.note" rows="3" placeholder="Ajouter une note…"
              class="block w-full resize-y rounded-md border border-line bg-surface px-3 py-2 text-sm text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="noteForm.errors.note" />
            <button type="submit" class="btn-secondary !py-2 !text-xs" :disabled="noteForm.processing || !noteForm.note.trim()">
              Ajouter la note
            </button>
          </form>
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
