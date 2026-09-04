<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminPagination from '@/Components/AdminPagination.vue'

const props = defineProps({
  programmes: Object,
  filters: Object,
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')

watch([search, status], () => {
  router.get(route('admin.programmes.index'), {
    search: search.value || undefined,
    status: status.value || undefined,
  }, { preserveState: true })
})

function destroy(programme) {
  if (confirm(`Supprimer le programme « ${programme.title} » ?`)) {
    router.delete(route('admin.programmes.destroy', programme.id))
  }
}

const statusBadges = {
  draft: 'bg-surface-hover text-ink-secondary',
  published: 'badge-soft-success',
  archived: 'badge-soft-warning',
}

const inputClass = 'rounded-md border border-line bg-surface px-4 py-2.5 text-sm text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <Head title="Programmes — Admin" />
  <AdminLayout>
    <div class="flex flex-wrap items-center justify-between gap-4 animate-slide-up">
      <div>
        <span class="eyebrow">Gestion</span>
        <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Programmes</h1>
      </div>
      <a :href="route('admin.programmes.create')" class="btn-primary !py-2.5">
        + Nouveau programme
      </a>
    </div>

    <!-- Filtres -->
    <div class="glass mt-6 flex flex-col gap-4 rounded-xl p-4 sm:flex-row sm:items-center">
      <input v-model="search" type="search" placeholder="Rechercher…" :class="[inputClass, 'sm:max-w-xs']" aria-label="Rechercher un programme" />
      <select v-model="status" :class="inputClass" aria-label="Filtrer par statut">
        <option value="">Tous les statuts</option>
        <option value="draft">Brouillon</option>
        <option value="published">Publié</option>
        <option value="archived">Archivé</option>
      </select>
    </div>

    <div class="card mt-6 overflow-x-auto !p-0 animate-slide-up [animation-delay:100ms]">
      <table class="min-w-full divide-y divide-line/50 text-sm">
        <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
          <tr>
            <th class="px-4 py-3.5">Titre</th>
            <th class="px-4 py-3.5">Dates</th>
            <th class="px-4 py-3.5">Prix</th>
            <th class="px-4 py-3.5">Places</th>
            <th class="px-4 py-3.5">Statut</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-line/40">
          <tr v-for="programme in programmes.data" :key="programme.id" class="transition-colors hover:bg-surface-hover/60">
            <td class="px-4 py-3.5">
              <p class="font-semibold text-ink">{{ programme.title }}</p>
              <p class="text-xs capitalize text-ink-muted">{{ programme.type }}</p>
            </td>
            <td class="px-4 py-3.5 text-ink-secondary">
              {{ new Date(programme.start_date).toLocaleDateString('fr-FR') }} → {{ new Date(programme.end_date).toLocaleDateString('fr-FR') }}
            </td>
            <td class="px-4 py-3.5 font-medium text-ink-secondary">
              {{ Number(programme.price) > 0 ? Number(programme.price).toLocaleString('fr-FR') + ' FCFA' : 'Gratuit' }}
            </td>
            <td class="px-4 py-3.5 text-center font-semibold text-ink">{{ programme.max_participants }}</td>
            <td class="px-4 py-3.5">
              <span :class="statusBadges[programme.status]" class="badge capitalize">
                {{ { draft: 'Brouillon', published: 'Publié', archived: 'Archivé' }[programme.status] }}
              </span>
            </td>
            <td class="px-4 py-3.5 text-right">
              <a :href="route('admin.programmes.edit', programme.id)"
                class="rounded-md px-2 py-1 font-medium text-info transition-colors hover:bg-info/10">Modifier</a>
              <button @click="destroy(programme)"
                class="ml-1 rounded-md px-2 py-1 font-medium text-danger transition-colors hover:bg-danger/10">Supprimer</button>
            </td>
          </tr>
          <tr v-if="!programmes.data.length">
            <td colspan="6" class="px-4 py-12 text-center text-ink-muted">Aucun programme trouvé.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <AdminPagination :links="programmes.links" />
  </AdminLayout>
</template>
