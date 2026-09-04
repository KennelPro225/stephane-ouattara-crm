<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Testimonials from '@/Components/Testimonials.vue'

const props = defineProps({
  programmes: Array,
})

const testimonials = [
  {
    author_name: 'Aminata K.',
    author_title: "Parent d'élève, Abidjan",
    rating: 5,
    message: 'Le programme Club des Champions a transformé mon fils. Il est maintenant plus confiant et responsable. Un vrai changement en seulement 3 mois.',
  },
  {
    author_name: 'Kouamé S.',
    author_title: 'Directeur RH, Groupe bancaire',
    rating: 5,
    message: "Les séances de teambuilding ont complètement transformé la dynamique de notre équipe. La communication est meilleure et l'engagement est au plus haut.",
  },
  {
    author_name: 'Fanta D.',
    author_title: 'Entrepreneure, Secteur Digital',
    rating: 5,
    message: "Le coaching m'a permis de trouver ma voie et de lancer mon entreprise avec confiance. L'approche de Stéphane est unique et transformatrice.",
  },
  {
    author_name: 'Souleymane B.',
    author_title: 'Directeur Commercial, Multinationale',
    rating: 5,
    message: "Les ateliers sur le leadership ont changé ma façon de diriger. Je gère maintenant mon équipe avec plus de sérénité et d'impact.",
  },
]

const services = [
  {
    title: 'Coaching individuel',
    tagline: 'Un espace sur mesure pour se découvrir, se redéfinir, s\'élever.',
    intro: "Chaque personne est unique, c'est pourquoi j'adapte l'accompagnement selon l'âge, les enjeux et le moment de vie.",
    icon: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
    items: [
      { label: 'Adolescents — Club des Champions (Lion, Meute, Aigle)', detail: 'confiance, identité et leadership.' },
      { label: 'Adultes', detail: 'équilibre personnel, prise de parole, reconversion ou affirmation professionnelle.' },
      { label: 'Parents', detail: 'mieux communiquer avec leurs ados, gérer les émotions, restaurer le lien.' },
    ],
    image: '/images/coaching-session.svg',
  },
  {
    title: 'Coaching de groupe',
    tagline: "Parce qu'on avance souvent plus vite ensemble.",
    intro: 'Ces espaces collectifs permettent de grandir à travers l\'interaction, la pratique et la dynamique de groupe.',
    icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
    items: [
      { label: 'Ateliers thématiques', detail: 'affirmation de soi, gestion des émotions, communication non-violente.' },
      { label: 'Théâtre éducatif pour ados', detail: 'exprimer, ressentir, apprendre autrement.' },
      { label: 'Groupes restreints', detail: 'partages puissants et progression collective.' },
    ],
    image: '/images/team-building.svg',
  },
  {
    title: 'Bien-être en entreprise',
    tagline: "Parce que performance et bien-être ne sont pas incompatibles.",
    intro: "J'interviens pour réintroduire l'humain, le calme et la clarté dans les environnements de travail.",
    icon: 'M3 21V7l7-4v4h11v14H3Zm9-9h2v-2h-2v2Zm0 6h2v-2h-2v2Zm4-6h2v-2h-2v2Zm0 6h2v-2h-2v2Zm-8-6h2v-2H8v2Zm0 6h2v-2H8v2Z',
    items: [
      { label: 'Séances de respiration et relaxation guidée' },
      { label: 'Méditation active pour dirigeants' },
      { label: 'Coaching des managers vers un leadership bienveillant' },
      { label: 'Gestion du stress et prévention du burn-out' },
    ],
    image: null,
  },
  {
    title: 'Teambuilding pour entreprises',
    tagline: "Des expériences originales pour souder, réveiller l'intelligence collective.",
    intro: 'Renforcer les liens. Créer du sens. Stimuler la coopération et faire émerger le leadership naturel.',
    icon: 'M17 20h4v-2a3 3 0 00-3-3M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z',
    items: [
      { label: 'Escape Game émotionnel', detail: 'collaboration & expression créative.' },
      { label: 'Jeux de rôle professionnels', detail: 'résolution de conflits et prise de décision.' },
      { label: 'Débriefs avec coaching ciblé', detail: 'ancrage des apprentissages.' },
      { label: "Recueils de témoignages d'impact", detail: 'pour pérenniser l\'expérience.' },
    ],
    image: '/images/team-building.svg',
  },
]
</script>

<template>
  <Head title="Ce que je fais - Stéphane OUATTARA | Services de Coaching Développement Personnel Côte d'Ivoire">
    <meta name="description" content="Services de coaching en développement personnel : coaching individuel adolescents, teambuilding entreprise, leadership, confiance en soi. Coach certifié Stéphane OUATTARA." />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-16 sm:py-24">
      <div class="pointer-events-none absolute -left-16 top-10 h-64 w-64 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative mx-auto max-w-[800px] text-center animate-slide-up">
        <span class="badge badge-outline mb-5 !border-white/40 !bg-white/10 !text-white/90">Services</span>
        <h1 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-5xl" style="letter-spacing:-0.03em">
          Des services à fort impact humain
        </h1>
        <p class="mx-auto mt-4 max-w-2xl text-white/85">
          J'accompagne les personnes, les équipes et les organisations dans leur transformation
          intérieure et structurelle. Mon approche allie écoute profonde, expériences concrètes
          et outils puissants pour aider chacun à se (re)connecter à ce qui l'anime.
        </p>
      </div>
    </section>

    <!-- Services détaillés -->
    <section class="container-site space-y-16 py-16">
      <article v-for="(service, i) in services" :key="service.title"
        :id="`service-${i + 1}`"
        class="grid items-center gap-10 lg:grid-cols-2"
        :class="i % 2 === 1 ? 'lg:[&>*:first-child]:order-2' : ''">
        <div class="animate-slide-up">
          <div class="flex items-center gap-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-md bg-accent-gradient text-white shadow-glow">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="service.icon" /></svg>
            </span>
            <span class="badge badge-outline">0{{ i + 1 }}</span>
          </div>
          <h2 class="mt-5 font-sans text-2xl font-bold tracking-tight text-ink sm:text-3xl">{{ service.title }}</h2>
          <p class="mt-2 font-sans text-base italic text-accent">{{ service.tagline }}</p>
          <p class="mt-3 leading-relaxed text-ink-secondary">{{ service.intro }}</p>

          <ul class="mt-6 space-y-3">
            <li v-for="item in service.items" :key="item.label" class="flex items-start gap-3">
              <span class="mt-1 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-success/10 text-success">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
              </span>
              <p class="text-sm text-ink-secondary">
                <strong class="font-semibold text-ink">{{ item.label }}</strong>
                <template v-if="item.detail"> : {{ item.detail }}</template>
              </p>
            </li>
          </ul>

          <a :href="route('reserver-une-session')" class="btn-primary mt-8 inline-flex">Réserver une session</a>
        </div>

        <Transition appear enter-active-class="transition duration-350 ease-entrance" enter-from-class="scale-95 opacity-0">
          <img v-if="service.image" :src="service.image" :alt="service.title"
            class="w-full rounded-xl border border-line/50 shadow-elevated transition-transform duration-250 ease-smooth hover:scale-[1.02]"
            loading="lazy" />
          <div v-else class="card flex aspect-[4/3] items-center justify-center bg-primary-gradient !border-0 shadow-elevated transition-transform duration-250 ease-smooth hover:scale-[1.02]">
            <blockquote class="max-w-xs px-6 text-center font-sans text-lg italic leading-relaxed text-white">
              « Quand l'humain se trouve, il se dépasse. »
            </blockquote>
          </div>
        </Transition>
      </article>
    </section>

    <!-- CTA -->
    <section class="relative overflow-hidden bg-accent-gradient bg-[length:200%_200%] py-14 animate-gradient-flow">
      <div class="container-site max-w-4xl text-center">
        <h2 class="font-sans text-2xl font-bold tracking-tight text-white sm:text-3xl">Prêt à transformer votre vie ou votre équipe ?</h2>
        <p class="mx-auto mt-3 max-w-2xl text-white/90">
          Chaque session de coaching est une opportunité de se redécouvrir, de se dépasser et de créer
          l'impact que vous souhaitez avoir dans le monde.
        </p>
        <a :href="route('reserver-une-session')"
          class="mt-6 inline-flex rounded-md bg-white px-8 py-3.5 font-sans text-sm font-bold text-primary shadow-elevated transition-all duration-150 ease-standard hover:scale-[1.02]">
          Réserver maintenant
        </a>
      </div>
    </section>

    <Testimonials :testimonials="testimonials" />
  </PublicLayout>
</template>
