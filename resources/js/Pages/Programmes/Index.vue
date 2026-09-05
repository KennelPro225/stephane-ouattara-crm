<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PlaceholderMedia from '@/Components/PlaceholderMedia.vue'
import { AUDIENCE_LABELS, TYPE_LABELS, audienceHue, audienceSoft, formatDate } from '@/constants'

const props = defineProps({
  programmes: Object,
  filters: Object,
})

const q = ref(props.filters.q ?? '')
const age = ref(props.filters.age ?? 'Tous')
const type = ref(props.filters.type ?? 'Tous')
const sort = ref(props.filters.sort ?? 'Date')

watch([q, age, type, sort], () => {
  router.get(route('programmes.index'), {
    q: q.value || undefined,
    age: age.value !== 'Tous' ? age.value : undefined,
    type: type.value !== 'Tous' ? type.value : undefined,
    sort: sort.value !== 'Date' ? sort.value : undefined,
  }, { preserveState: true, replace: true })
})
</script>

<template>
  <Head title="Programmes" />
  <PublicLayout>
    <main class="container-site" style="padding-top:40px">
      <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-accent-700);margin:0 0 16px">Programmes</p>
      <h1 style="font-size:clamp(30px,6vw,52px);margin:0 0 16px;max-width:26ch">Découvrez nos programmes transformationnels</h1>
      <p style="max-width:52ch;font-size:15px;margin:0 0 32px">Des parcours conçus pour les adolescents, les adultes et les organisations. Chaque programme mêle accompagnement, pratique et mesure d'impact.</p>

      <div style="border-top:2px solid var(--color-divider);border-bottom:2px solid var(--color-divider);padding:20px 0;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px">
        <div class="field">
          <label for="q">Recherche</label>
          <input id="q" v-model="q" class="input" type="search" placeholder="Nom du programme…">
        </div>
        <div class="field">
          <label>Public</label>
          <div class="seg" style="flex-wrap:wrap">
            <label v-for="opt in ['Tous', 'Adolescents', 'Adultes', 'Entreprises']" :key="opt" class="seg-opt">
              <input v-model="age" type="radio" name="age" :value="opt">{{ opt }}
            </label>
          </div>
        </div>
        <div class="field">
          <label>Type</label>
          <div class="seg" style="flex-wrap:wrap">
            <label v-for="opt in ['Tous', 'Individuel', 'Groupe', 'Entreprise']" :key="opt" class="seg-opt">
              <input v-model="type" type="radio" name="type" :value="opt">{{ opt }}
            </label>
          </div>
        </div>
        <div class="field">
          <label for="sort">Trier par</label>
          <select id="sort" v-model="sort" class="input">
            <option value="Date">Date de début</option>
            <option value="Prix">Prix</option>
          </select>
        </div>
      </div>

      <p class="text-muted" style="font-size:12px;margin:16px 0 20px;letter-spacing:0.06em;text-transform:uppercase">
        {{ programmes.total }} programme{{ programmes.total > 1 ? 's' : '' }} — page {{ programmes.current_page }} sur {{ programmes.last_page }}
      </p>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px">
        <article v-for="(p, i) in programmes.data" :key="p.id" v-reveal="{ delay: (i % 6) * 70 }" class="edge-card" :style="{ border: '2px solid var(--color-text)', borderTop: `8px solid ${audienceHue(p.audience)}`, display: 'flex', flexDirection: 'column' }">
          <PlaceholderMedia :src="p.image_url" aspect="16/9" border="2px solid var(--color-text)" label="photo programme" />

          <div style="padding:18px;display:flex;flex-direction:column;gap:10px;flex:1">
            <div style="display:flex;gap:6px;flex-wrap:wrap">
              <span class="tag" :style="{ background: audienceSoft(p.audience), color: audienceHue(p.audience) }">{{ AUDIENCE_LABELS[p.audience] }}</span>
              <span class="tag tag-neutral">{{ TYPE_LABELS[p.type] }}</span>
            </div>
            <h3 style="margin:0;font-size:20px">{{ p.title }}</h3>
            <p style="margin:0;font-size:13px;line-height:1.6;flex:1">{{ p.description }}</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;border-top:1px solid var(--color-divider);padding-top:10px">
              <span class="text-muted">Début</span><span>{{ formatDate(p.start_date) }}</span>
              <span class="text-muted">Fin</span><span>{{ formatDate(p.end_date) }}</span>
              <span class="text-muted">Durée</span><span>{{ p.duration_label }}</span>
              <span class="text-muted">Âge</span><span>{{ p.ages_label }}</span>
              <span class="text-muted">Prix</span><span style="font-family:var(--font-heading);font-weight:800">{{ p.price_label }}</span>
            </div>
            <Link :href="route('programmes.show', p.slug)" class="btn btn-primary btn-block">Voir les détails →</Link>
          </div>
        </article>
        <p v-if="!programmes.data.length" class="text-muted">Aucun programme ne correspond à votre recherche.</p>
      </div>

      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:32px;border-top:2px solid var(--color-divider);padding-top:20px">
        <template v-for="link in programmes.links" :key="link.label">
          <button v-if="link.url" type="button" class="btn"
            :style="{ minWidth: '44px', border: '1px solid var(--color-divider)', background: link.active ? 'var(--color-accent)' : 'transparent', color: link.active ? 'var(--color-bg)' : 'var(--color-text)' }"
            @click="router.get(link.url, {}, { preserveState: true, preserveScroll: true })" v-html="link.label" />
        </template>
        <Link :href="route('reserver-une-session')" class="btn btn-primary" style="margin-left:auto">Réserver une session</Link>
      </div>
    </main>
  </PublicLayout>
</template>
