<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import ProgrammeCard from '@/Components/ProgrammeCard.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  programmes: Object,
  filters: Object,
})

const search = ref(props.filters.search ?? '')
const audience = ref(props.filters.audience ?? '')
const type = ref(props.filters.type ?? '')
const sort = ref(props.filters.sort ?? 'date_asc')

let timeout
watch([search, audience, type], () => {
  clearTimeout(timeout)
  timeout = setTimeout(() => fetchProgrammes(), 300)
})
watch(sort, () => fetchProgrammes())

function fetchProgrammes() {
  router.get(route('programmes.index'), {
    search: search.value || undefined,
    audience: audience.value || undefined,
    type: type.value || undefined,
    sort: sort.value !== 'date_asc' ? sort.value : undefined,
  }, { preserveState: true })
}

const inputClass = 'w-full rounded-md border border-line bg-surface px-4 py-3 text-sm text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <Head title="Nos Programmes — Découvrez nos Offres de Coaching">
    <meta name="description" content="Découvrez nos programmes transformationnels : Club des Champions (Lion, Meute, Aigle), Compétence 360, La Connexion, coaching individuel, teambuilding et bien-être en entreprise." />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-16 sm:py-20 animate-fade-in">
      <div class="pointer-events-none absolute -right-20 top-0 h-64 w-64 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative">
        <span class="badge badge-outline mb-4 !border-white/40 !bg-white/10 !text-white/90">Catalogue</span>
        <h1 class="font-sans text-3xl font-bold tracking-tight text-white sm:text-5xl">Découvrez nos programmes transformationnels</h1>
        <p class="mt-3 max-w-2xl text-white/85">
          Chaque programme est une opportunité de se redécouvrir, de se dépasser et de créer l'impact
          que vous souhaitez avoir dans le monde.
        </p>
      </div>
    </section>

    <section class="container-site py-12">
      <!-- Filtres -->
      <div class="glass grid gap-4 rounded-xl p-5 shadow-card sm:grid-cols-2 lg:grid-cols-4">
        <input v-model="search" type="search" placeholder="Rechercher un programme…" :class="inputClass" aria-label="Rechercher un programme" />

        <select v-model="audience" :class="inputClass" aria-label="Filtrer par public">
          <option value="">Tous les publics</option>
          <option value="adolescents">Adolescents & jeunes</option>
          <option value="adultes">Adultes & parents</option>
          <option value="entreprises">Entreprises</option>
        </select>

        <select v-model="type" :class="inputClass" aria-label="Filtrer par format">
          <option value="">Tous les formats</option>
          <option value="individual">Individuel</option>
          <option value="group">Groupe</option>
          <option value="corporate">Entreprise</option>
        </select>

        <select v-model="sort" :class="inputClass" aria-label="Trier">
          <option value="date_asc">Date : plus tôt d'abord</option>
          <option value="date_desc">Date : plus tard d'abord</option>
          <option value="price_asc">Prix croissant</option>
          <option value="price_desc">Prix décroissant</option>
        </select>
      </div>

      <div v-if="programmes.data.length" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <ProgrammeCard v-for="(programme, i) in programmes.data" :key="programme.id" v-reveal="{ delay: Math.min(i, 8) * 70 }"
          :programme="programme" />
      </div>

      <!-- Empty state -->
      <div v-else class="mt-16 flex flex-col items-center rounded-xl border border-dashed border-line p-12 text-center">
        <svg class="h-16 w-16 text-primary-bright animate-float" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.969L12 13.787l-3.182 3.182a4.5 4.5 0 01-6.364-6.364L8.969 4.09a1.5 1.5 0 012.121 0l6.364 6.364a4.5 4.5 0 01-2.272 7.515zM12 13.787l3.182 3.182" />
        </svg>
        <h3 class="mt-4 font-sans text-xl font-semibold text-ink">Aucun programme trouvé</h3>
        <p class="mt-2 max-w-md text-sm text-ink-secondary">Essayez d'élargir vos critères ou contactez-nous pour un accompagnement sur mesure.</p>
      </div>

      <Pagination :links="programmes.links" />

      <div class="mt-14 text-center">
        <a :href="route('reserver-une-session')" class="btn-accent">Réserver une session</a>
      </div>
    </section>
  </PublicLayout>
</template>
