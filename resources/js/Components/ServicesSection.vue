<script setup>
import { Link } from '@inertiajs/vue3'

const services = [
  {
    id: 'coaching-individuel',
    title: 'Coaching individuel',
    intro: "Un espace sur mesure pour se découvrir, se redéfinir, s'élever. Chaque personne est unique, c'est pourquoi j'adapte l'accompagnement selon l'âge, les enjeux, et le moment de vie.",
    icon: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
    items: [
      { label: 'Adolescents — Club des Champions', detail: 'Avec les niveaux Lion, Meute et Aigle : confiance, identité et leadership.' },
      { label: 'Parents / Adultes', detail: 'Un accompagnement sur mesure pour retrouver équilibre, clarté et épanouissement.' },
    ],
    type: 'individual',
  },
  {
    id: 'coaching-groupe',
    title: 'Coaching de groupe',
    intro: "Parce qu'on avance souvent plus vite ensemble. Ces espaces collectifs permettent de grandir à travers l'interaction, la pratique et la dynamique de groupe.",
    icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
    items: [
      { label: 'Ateliers thématiques', detail: 'Affirmation de soi, gestion des émotions, communication non-violente.' },
      { label: 'Théâtre éducatif pour ados', detail: 'Exprimer, ressentir, apprendre autrement.' },
      { label: 'Ateliers jeunes & groupes restreints', detail: 'Partages puissants et progression collective.' },
    ],
    type: 'group',
  },
  {
    id: 'bien-etre',
    title: 'Bien-être en entreprise',
    intro: "Parce que performance et bien-être ne sont pas incompatibles. J'interviens pour réintroduire l'humain, le calme et la clarté dans les environnements de travail.",
    objective: "Objectif : replacer l'humain au cœur de la performance.",
    icon: 'M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z',
    items: [
      { label: 'Respiration & relaxation guidée', detail: 'Séances pratiques pour retrouver calme et lucidité.' },
      { label: 'Méditation active pour dirigeants', detail: 'Prendre de la hauteur avant de décider.' },
      { label: 'Leadership bienveillant & gestion du stress', detail: 'Accompagnement des managers, prévention du burn-out.' },
    ],
    type: 'corporate_wellness',
  },
  {
    id: 'teambuilding',
    title: 'Teambuilding & Leadership',
    intro: 'Renforcer les liens. Créer du sens. Stimuler la coopération. Des expériences immersives pour souder les équipes, développer l\'intelligence collective et faire émerger un leadership authentique.',
    icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
    items: [
      { label: 'Escape Game émotionnel', detail: 'Collaboration & expression créative.' },
      { label: 'Jeux de rôle professionnels', detail: 'Résolution de conflits et prise de décision.' },
      { label: 'Débriefs avec coaching ciblé', detail: "Ancrage des apprentissages et recueils de témoignages d'impact." },
    ],
    type: 'teambuilding',
  },
]
</script>

<template>
  <section id="services" class="scroll-mt-20 bg-surface/50 py-20 sm:py-24">
    <div class="container-site">
      <div v-reveal class="mx-auto max-w-2xl text-center">
        <span class="eyebrow">Ce que je fais</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">Des services à fort impact humain</h2>
        <p class="mt-4 leading-relaxed text-ink-secondary">
          J'accompagne les personnes, les équipes et les organisations dans leur transformation intérieure et
          structurelle. Mon approche allie écoute profonde, expériences concrètes et outils puissants pour aider
          chacun à se (re)connecter à ce qui l'anime.
        </p>
      </div>

      <div class="mt-14 grid gap-6 md:grid-cols-2">
        <article v-for="(service, i) in services" :key="service.id" v-reveal="{ delay: (i % 2) * 120 }"
          class="card card-hover group flex flex-col">
          <div class="flex items-start gap-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-md bg-primary-gradient text-white shadow-sm transition-transform duration-150 ease-bounce group-hover:scale-110">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" :d="service.icon" /></svg>
            </span>
            <h3 class="font-sans text-xl font-bold tracking-tight text-ink">{{ service.title }}</h3>
          </div>
          <p v-if="service.objective" class="badge badge-soft-warning mt-4 h-fit w-fit">{{ service.objective }}</p>
          <p class="mt-4 text-sm leading-relaxed text-ink-secondary">{{ service.intro }}</p>

          <ul class="mt-5 flex-1 space-y-3 border-t border-line/50 pt-4">
            <li v-for="item in service.items" :key="item.label" class="flex items-start gap-3 text-sm">
              <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-success/10 text-success">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
              </span>
              <span><strong class="font-semibold text-ink">{{ item.label }}</strong> — <span class="text-ink-secondary">{{ item.detail }}</span></span>
            </li>
          </ul>

          <Link :href="route('reserver-une-session', { type: service.type })"
            class="btn-secondary mt-6 self-start !py-2 !text-xs">
            Réserver ce format
          </Link>
        </article>
      </div>

      <div class="mt-12 text-center">
        <Link :href="route('programmes.index')" class="btn-primary">
          Voir tous les programmes
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" /></svg>
        </Link>
      </div>
    </div>
  </section>
</template>
