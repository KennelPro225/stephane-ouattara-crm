<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { AUDIENCE_LABELS, TYPE_LABELS, audienceHue, audienceSoft, formatDate } from '@/constants'

const props = defineProps({
  programmes: Object,
  filters: Object,
})

const search = ref(props.filters.search ?? '')
watch(search, () => {
  router.get(route('admin.programmes.index'), { search: search.value || undefined }, { preserveState: true, replace: true })
})

function destroy (programme) {
  if (confirm(`Archiver « ${programme.title} » ?`)) {
    router.delete(route('admin.programmes.destroy', programme.id), { preserveScroll: true })
  }
}
</script>

<template>
  <Head title="Programmes — Admin" />
  <AdminLayout>
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:baseline;justify-content:space-between;margin-bottom:24px">
      <h1 style="margin:0;font-size:clamp(24px,4vw,36px)">Programmes</h1>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;align-items:center">
      <input v-model="search" class="input" type="search" placeholder="Rechercher un programme…" style="max-width:280px">
      <Link :href="route('admin.programmes.create')" class="btn btn-primary">Nouveau programme</Link>
    </div>

    <div style="overflow-x:auto">
      <table class="table" style="min-width:760px">
        <thead>
          <tr><th>Programme</th><th>Public</th><th>Type</th><th>Début</th><th>Prix</th><th>Inscrits</th><th>Statut</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="p in programmes.data" :key="p.id">
            <td><strong>{{ p.title }}</strong></td>
            <td><span class="tag" :style="{ background: audienceSoft(p.audience), color: audienceHue(p.audience) }">{{ AUDIENCE_LABELS[p.audience] }}</span></td>
            <td class="text-muted">{{ TYPE_LABELS[p.type] }}</td>
            <td>{{ formatDate(p.start_date) }}</td>
            <td>{{ p.price_label }}</td>
            <td>{{ p.confirmed_count }} / {{ p.max_participants }}</td>
            <td class="text-muted">{{ p.status === 'published' ? 'Publié' : 'Brouillon' }}</td>
            <td style="text-align:right;white-space:nowrap">
              <Link :href="route('admin.programmes.edit', p.id)" class="btn btn-ghost">Modifier</Link>
              <button type="button" class="btn btn-ghost" style="color:var(--color-neutral-700)" @click="destroy(p)">Archiver</button>
            </td>
          </tr>
          <tr v-if="!programmes.data.length"><td colspan="8" class="text-muted" style="text-align:center;padding:24px">Aucun programme.</td></tr>
        </tbody>
      </table>
    </div>

    <div v-if="programmes.links.length > 3" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px">
      <template v-for="link in programmes.links" :key="link.label">
        <button v-if="link.url" type="button" class="btn"
          :style="{ minWidth: '40px', border: '1px solid var(--color-divider)', background: link.active ? 'var(--color-accent)' : 'transparent', color: link.active ? 'var(--color-bg)' : 'var(--color-text)' }"
          @click="router.get(link.url, {}, { preserveState: true, preserveScroll: true })" v-html="link.label" />
      </template>
    </div>
  </AdminLayout>
</template>
