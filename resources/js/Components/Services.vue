<script setup>
import { Link } from '@inertiajs/vue3'

const services = [
  {
    title: 'Coaching individuel',
    description:
      'Ados (Club des Champions) : identité solide et leadership affirmé. Adultes & parents : équilibre, clarté et épanouissement.',
    icon: 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 2c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4Z',
    href: 'ce-que-je-fais',
  },
  {
    title: 'Coaching de groupe',
    description:
      'Ateliers jeunes, théâtre éducatif et coaching collectif : apprendre ensemble, se soutenir et se propulser.',
    icon: 'M5 3l4 6 3-8 3 8 4-6v15H5V3Zm0 17h14v2H5v-2Z',
    href: 'ce-que-je-fais',
  },
  {
    title: 'Bien-être en entreprise',
    description:
      'Gestion du stress & des émotions, méditation pour dirigeants, intelligence émotionnelle au travail.',
    icon: 'M3 21V7l7-4v4h11v14H3Zm9-9h2v-2h-2v2Zm0 6h2v-2h-2v2Zm4-6h2v-2h-2v2Zm0 6h2v-2h-2v2Zm-8-6h2v-2H8v2Zm0 6h2v-2H8v2Z',
    href: 'ce-que-je-fais',
  },
  {
    title: 'Teambuilding & Leadership',
    description:
      'Expériences immersives pour souder les équipes, stimuler la coopération et faire émerger un leadership authentique.',
    icon: 'M17 20h4v-2a3 3 0 00-3-3M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z',
    href: 'entreprises',
  },
]
</script>

<template>
  <section id="services" class="bg-surface/50 py-20 sm:py-24">
    <div class="container-site">
      <div class="mx-auto max-w-2xl text-center animate-slide-up">
        <span class="eyebrow">Ce que je fais</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">
          Des services à fort impact humain
        </h2>
        <p class="mt-4 leading-relaxed text-ink-secondary">
          J'accompagne les personnes, les équipes et les organisations dans leur transformation
          intérieure et structurelle.
        </p>
      </div>

      <div class="mt-14 grid gap-6 sm:grid-cols-2">
        <article
          v-for="(service, i) in services"
          :key="service.title"
          class="card card-hover group animate-slide-up"
          :style="{ animationDelay: `${i * 100}ms` }"
        >
          <div class="flex items-start gap-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-md bg-accent-gradient text-white shadow-glow transition-transform duration-150 ease-standard group-hover:scale-110">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path :d="service.icon" /></svg>
            </span>
            <div>
              <h3 class="font-sans text-lg font-semibold text-ink">{{ service.title }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-ink-secondary">{{ service.description }}</p>
              <Link :href="route(service.href)" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary transition-colors duration-150 hover:text-accent">
                En savoir plus →
              </Link>
            </div>
          </div>
        </article>
      </div>

      <div class="mt-10 text-center animate-slide-up [animation-delay:400ms]">
        <Link :href="route('ce-que-je-fais')" class="btn-secondary">
          Voir tous les services
        </Link>
      </div>
    </div>
  </section>
</template>
