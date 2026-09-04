<script setup>
import { ref } from 'vue'
import StatCounter from '@/Components/StatCounter.vue'

defineProps({
  galleries: { type: Array, default: () => [] },
})

const programmes = [
  {
    title: 'Club des Champions',
    description: "Programme d'excellence pour adolescents : développement personnel, leadership et confiance en soi.",
    stats: [{ value: '200+', label: 'Jeunes accompagnés' }],
    duration: 'Plusieurs semaines / mois',
    icon: 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z',
  },
  {
    title: "Leadership d'Entreprise",
    description: "Formation intensive en leadership, gestion d'équipe et intelligence émotionnelle pour cadres et dirigeants.",
    stats: [{ value: '50+', label: 'Entreprises' }],
    duration: 'Modules multiples',
    icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
  },
  {
    title: 'Teambuilding Impact',
    description: "Sessions immersives de cohésion d'équipe : renforcement des liens, communication et performance collective.",
    stats: [
      { value: '100+', label: 'Sessions' },
      { value: '1K+', label: 'Participants' },
    ],
    duration: 'Formats sur mesure',
    icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
  },
]

const videos = [
  { name: 'Marie', caption: "Comment j'ai retrouvé confiance en moi grâce au coaching de Stéphane" },
  { name: 'Pierre', caption: 'Transformation de mon leadership en entreprise' },
]

const activeVideo = ref(null)
</script>

<template>
  <section id="realisations" class="scroll-mt-20 py-20 sm:py-24">
    <div class="container-site">
      <div v-reveal class="mx-auto max-w-2xl text-center">
        <span class="eyebrow">Mes réalisations</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">Nos Programmes Phares</h2>
        <p class="mt-3 text-ink-secondary">Découvrez nos programmes transformationnels et leurs impacts</p>
      </div>

      <!-- Cartes programmes phares -->
      <div class="mt-14 grid gap-6 md:grid-cols-3">
        <article v-for="(programme, i) in programmes" :key="programme.title" v-reveal="{ delay: i * 110 }"
          class="card card-hover group relative overflow-hidden">
          <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-accent/10 blur-2xl transition-opacity group-hover:opacity-100" aria-hidden="true" />
          <span class="grid h-12 w-12 place-items-center rounded-md bg-accent-gradient text-white shadow-glow">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" :d="programme.icon" /></svg>
          </span>
          <h3 class="mt-5 font-sans text-lg font-bold text-ink">{{ programme.title }}</h3>
          <p class="mt-3 text-sm leading-relaxed text-ink-secondary">{{ programme.description }}</p>

          <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-line/50 pt-4">
            <div v-for="stat in programme.stats" :key="stat.label">
              <p class="font-sans text-2xl font-extrabold text-gradient leading-none"><StatCounter :value="stat.value" /></p>
              <p class="mt-1 text-xs font-medium uppercase tracking-wide text-ink-muted">{{ stat.label }}</p>
            </div>
            <span class="badge badge-outline ml-auto">{{ programme.duration }}</span>
          </div>
        </article>
      </div>

      <!-- Témoignages vidéo -->
      <div v-reveal class="mt-20">
        <h3 class="text-center font-sans text-2xl font-bold tracking-tight text-ink">Témoignages en Vidéo</h3>
        <p class="mt-2 text-center text-sm text-ink-secondary">Découvrez les témoignages de mes clients et leurs transformations</p>

        <div class="mx-auto mt-8 grid max-w-3xl gap-6 sm:grid-cols-2">
          <button v-for="video in videos" :key="video.name" type="button"
            class="group relative overflow-hidden rounded-xl border border-line/60 bg-primary-gradient text-left shadow-card transition-all duration-250 ease-smooth hover:-translate-y-1 hover:shadow-elevated"
            @click="activeVideo = video.name">
            <img src="/storage/galleries/conferences.svg" alt="" aria-hidden="true"
              class="aspect-video h-full w-full object-cover opacity-60 transition-transform duration-250 group-hover:scale-105" />
            <span class="absolute inset-0 grid place-items-center">
              <span class="grid h-14 w-14 place-items-center rounded-full bg-white/90 text-primary shadow-elevated transition-transform duration-150 ease-bounce group-hover:scale-110">
                <svg v-if="activeVideo !== video.name" class="ml-1 h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                <svg v-else class="h-5 w-5 text-ink-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" /></svg>
              </span>
            </span>
            <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#1A1410]/90 to-transparent p-4 pt-10">
              <span class="block font-sans font-bold text-white">Témoignage de {{ video.name }}</span>
              <span class="mt-0.5 block text-xs text-white/80">{{ video.caption }}</span>
            </span>
          </button>
        </div>
        <p class="mt-4 text-center text-xs text-ink-muted">Les vidéos complètes seront bientôt disponibles.</p>
      </div>

      <!-- Galerie -->
      <div v-reveal class="mt-20">
        <h3 class="text-center font-sans text-2xl font-bold tracking-tight text-ink">Galerie de Réalisations</h3>
        <p class="mt-2 text-center text-sm text-ink-secondary">Quelques moments forts de mes accompagnements</p>

        <div v-if="galleries.length" class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-3">
          <figure v-for="(item, i) in galleries" :key="item.id" v-reveal="{ delay: i * 70 }"
            class="group relative overflow-hidden rounded-xl border border-line/60 shadow-card transition-all duration-250 ease-smooth hover:-translate-y-1 hover:shadow-elevated">
            <img :src="`/storage/${item.media_path}`" :alt="item.title"
              class="aspect-[4/3] w-full object-cover transition-transform duration-250 group-hover:scale-105" />
            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#1A1410]/90 via-[#1A1410]/40 to-transparent p-3 pt-8">
              <p class="font-sans text-sm font-bold text-white">{{ item.title }}</p>
              <p v-if="item.caption" class="text-xs text-white/75">{{ item.caption }}</p>
            </figcaption>
          </figure>
        </div>
      </div>
    </div>
  </section>
</template>
