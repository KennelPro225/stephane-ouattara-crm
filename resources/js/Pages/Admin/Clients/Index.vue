<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { CUSTOMER_STATUS_LABELS } from '@/constants'

const props = defineProps({
  customers: Object,
  filters: Object,
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
watch([search, status], () => {
  router.get(route('admin.customers.index'), {
    search: search.value || undefined,
    status: status.value || undefined,
  }, { preserveState: true, replace: true })
})
</script>

<template>
  <Head title="Clients — Admin" />
  <AdminLayout>
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:baseline;justify-content:space-between;margin-bottom:24px">
      <h1 style="margin:0;font-size:clamp(24px,4vw,36px)">Clients</h1>
      <Link :href="route('admin.customers.export')" class="btn btn-secondary">Exporter CSV</Link>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
      <input v-model="search" class="input" type="search" placeholder="Nom, email…" style="max-width:240px">
      <select v-model="status" class="input" style="max-width:200px">
        <option value="">Tous les statuts</option>
        <option v-for="(label, key) in CUSTOMER_STATUS_LABELS" :key="key" :value="key">{{ label }}</option>
      </select>
    </div>

    <div style="overflow-x:auto">
      <table class="table" style="min-width:720px">
        <thead><tr><th>Client</th><th>Téléphone</th><th>Société</th><th>Historique</th><th>Statut</th><th></th></tr></thead>
        <tbody>
          <tr v-for="c in customers.data" :key="c.id">
            <td><strong>{{ c.first_name }} {{ c.last_name }}</strong><br><span class="text-muted" style="font-size:11px">{{ c.email }}</span></td>
            <td>{{ c.phone || '—' }}</td>
            <td class="text-muted">{{ c.company || '—' }}</td>
            <td>{{ c.bookings_count }} demande{{ c.bookings_count > 1 ? 's' : '' }}</td>
            <td><span class="tag tag-neutral">{{ CUSTOMER_STATUS_LABELS[c.status] }}</span></td>
            <td style="text-align:right;white-space:nowrap"><Link :href="route('admin.customers.show', c.id)" class="btn btn-ghost">Voir la fiche</Link></td>
          </tr>
          <tr v-if="!customers.data.length"><td colspan="6" class="text-muted" style="text-align:center;padding:24px">Aucun client.</td></tr>
        </tbody>
      </table>
    </div>

    <div v-if="customers.links.length > 3" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px">
      <template v-for="link in customers.links" :key="link.label">
        <button v-if="link.url" type="button" class="btn"
          :style="{ minWidth: '40px', border: '1px solid var(--color-divider)', background: link.active ? 'var(--color-accent)' : 'transparent', color: link.active ? 'var(--color-bg)' : 'var(--color-text)' }"
          @click="router.get(link.url, {}, { preserveState: true, preserveScroll: true })" v-html="link.label" />
      </template>
    </div>
  </AdminLayout>
</template>
