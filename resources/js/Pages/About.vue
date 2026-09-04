<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Testimonials from '@/Components/Testimonials.vue'

const props = defineProps({
  testimonials: Array,
})

const credentials = [
  { label: 'Master en management des organisations' },
  { label: 'Coach certifié FranklinCovey' },
  { label: 'Alumni du programme IVLP du gouvernement américain' },
  { label: 'Diplômé du programme ASPI de Stanford' },
]

const engagements = [
  {
    title: 'Manager Business Developer — Trand Advertising (Groupe Édit Africa)',
    description: "Conception de stratégies de communication 360° pour des marques, des institutions et des leaders.",
  },
  {
    title: 'Fondateur de Bénévoles du Monde',
    description: "Une organisation au service de l'engagement citoyen et de la solidarité.",
  },
  {
    title: 'Initiateur de Compétence 360, La Connexion & Club des Champions',
    description: "Des programmes signature dédiés à la connaissance de soi et au leadership.",
  },
  {
    title: 'Créateur du cabinet OMSY EDUC',
    description: "Chaque année, des milliers d'élèves, de parents et de professionnels formés à la connaissance de soi, au leadership et à la citoyenneté active.",
  },
]

const values = [
  {
    name: 'Authenticité',
    icon: 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z',
    text: "Je refuse de jouer un rôle. Ce que je vis, je le partage. Mes forces comme mes failles m'ont construit. Être vrai, aligné, sans masque : c'est ainsi que j'inspire. Mon authenticité est ma force, et c'est elle qui donne du sens à mon engagement.",
  },
  {
    name: "Foi en l'humain",
    icon: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
    text: "Je crois profondément en l'humain. Chaque personne porte un potentiel, même s'il est enfoui. Je vois des étincelles où d'autres voient des limites. Mon rôle, c'est de raviver cette flamme. Quand l'autre croit en lui, il devient capable de créer, d'élever et de transformer le monde.",
  },
  {
    name: 'Excellence',
    icon: 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0',
    text: "L'excellence est un choix quotidien. Je me forme, je progresse, je ne me contente jamais du minimum. Pas pour paraître, mais pour servir. Être exigeant, c'est honorer ceux que j'accompagne. Ils méritent le meilleur. L'excellence, c'est mon standard. Mon engagement, c'est de toujours viser plus haut.",
  },
  {
    name: 'Transmission',
    icon: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
    text: "Tout ce que j'apprends, je le transmets. Ce que je vis devient enseignement. La transmission est pour moi un devoir, pas une option. Je veux que chaque idée, chaque valeur semée, devienne un arbre. Car une vie qui ne se partage pas finit par s'éteindre en silence.",
  },
]
</script>

<template>
  <Head title="Qui suis-je? - Stéphane OUATTARA | Coach en Développement Personnel Côte d'Ivoire">
    <meta name="description" content="Découvrez Stéphane OUATTARA, coach certifié en développement personnel avec +10 ans d'expérience. Diplômé de Stanford, certifié FranklinCovey, alumni IVLP." />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-20 sm:py-28">
      <div class="pointer-events-none absolute -right-16 top-0 h-72 w-72 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative mx-auto max-w-[800px] text-center animate-slide-up">
        <span class="badge badge-outline mb-5 !border-white/40 !bg-white/10 !text-white/90">Qui suis-je ?</span>
        <h1 class="font-sans text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl" style="letter-spacing:-0.03em">
          Un potentiel pour chaque personne,<br class="hidden sm:block" /> une valeur sûre pour l'Afrique
        </h1>
      </div>
    </section>

    <!-- Bio -->
    <section id="histoire" class="container-site grid gap-12 py-16 lg:grid-cols-3">
      <div class="lg:col-span-2 animate-slide-up">
        <img src="/images/portrait-stephane.svg" alt="Stéphane Ouattara"
          class="mb-8 w-full max-w-xs rounded-xl border border-line/50 shadow-elevated lg:hidden" loading="lazy" />
        <p class="text-lg leading-relaxed text-ink-secondary">
          Je m'appelle <strong class="font-semibold text-accent">Stéphane OUATTARA</strong>. Je suis coach en
          développement personnel, entrepreneur social, formateur, bâtisseur d'écosystèmes et rêveur lucide.
          Depuis plus de 10 ans, j'ai choisi de mettre ma vie au service d'une conviction simple :
          chaque personne porte en elle un potentiel à révéler, et ce potentiel, lorsqu'il est activé,
          devient une richesse durable pour l'Afrique et pour le monde.
        </p>

        <h2 class="mt-12 font-sans text-2xl font-bold tracking-tight text-ink">
          Quelle est l'histoire derrière mon parcours ?
        </h2>
        <p class="mt-4 leading-relaxed text-ink-secondary">
          Fils unique, né en Côte d'Ivoire, j'ai très tôt compris que notre histoire personnelle —
          aussi belle, dure ou imparfaite soit-elle — peut devenir un levier puissant de transformation.
          J'ai choisi d'en faire une force. Depuis 2014, je chemine aux côtés de jeunes en quête d'identité,
          de professionnels en transition, et de dirigeants en quête de sens.
        </p>
        <p class="mt-4 leading-relaxed text-ink-secondary">
          Ma mission : les aider à se révéler, à s'aligner, à construire une vie à la hauteur de leur vraie nature.
          Sur ce chemin, je me suis outillé.
        </p>

        <!-- Credentials -->
        <ul class="mt-6 space-y-3">
          <li v-for="credential in credentials" :key="credential.label" class="flex items-start gap-3 rounded-md bg-surface-hover px-4 py-3 transition-colors hover:bg-line/30">
            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-success/10 text-success">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </span>
            <span class="text-sm font-medium text-ink">{{ credential.label }}</span>
          </li>
        </ul>

        <!-- Engagements -->
        <h2 class="mt-12 font-sans text-2xl font-bold tracking-tight text-ink">Mon engagement aujourd'hui</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
          <article v-for="(engagement, i) in engagements" :key="engagement.title"
            class="card card-hover animate-slide-up" :style="{ animationDelay: `${i * 80}ms` }">
            <h3 class="font-sans text-sm font-bold text-primary">{{ engagement.title }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-ink-secondary">{{ engagement.description }}</p>
          </article>
        </div>
      </div>

      <!-- Portrait sticky desktop -->
      <aside class="hidden lg:block">
        <div class="sticky top-24">
          <img src="/images/portrait-stephane.svg" alt="Stéphane Ouattara, coach en développement personnel"
            class="w-full rounded-xl border border-line/50 shadow-elevated transition-transform duration-250 ease-smooth hover:scale-[1.02]" loading="lazy" />
          <blockquote class="glass mt-4 rounded-lg p-5 text-sm italic leading-relaxed text-ink shadow-card">
            « Notre vécu n'est pas une fatalité — c'est un tremplin. »
          </blockquote>
        </div>
      </aside>
    </section>

    <!-- Valeurs -->
    <section class="bg-surface/50 py-20">
      <div class="container-site">
        <div class="mx-auto max-w-2xl text-center animate-slide-up">
          <span class="eyebrow">Mes valeurs</span>
          <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">Ce qui guide chaque accompagnement</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2">
          <article v-for="(value, i) in values" :key="value.name"
            class="card card-hover group animate-slide-up" :style="{ animationDelay: `${i * 100}ms` }">
            <div class="flex items-start gap-4">
              <span class="grid h-11 w-11 shrink-0 place-items-center rounded-md bg-accent-gradient text-white shadow-glow transition-transform duration-150 group-hover:scale-110">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="value.icon" /></svg>
              </span>
              <div>
                <h3 class="font-sans text-lg font-semibold text-ink">{{ value.name }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-secondary">{{ value.text }}</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Pourquoi travailler avec moi -->
    <section class="container-site py-20">
      <div class="card relative overflow-hidden !border-0 !bg-primary-gradient p-8 text-white shadow-elevated sm:p-12 animate-scale-in">
        <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full bg-accent/30 blur-2xl animate-float" aria-hidden="true" />
        <h2 class="relative font-sans text-2xl font-bold tracking-tight">Pourquoi travailler avec moi ?</h2>
        <div class="relative mt-5 max-w-3xl space-y-4 text-sm leading-relaxed text-white/90 sm:text-base">
          <p>Parce que je ne me contente pas d'accompagner, je m'implique. Depuis plus de 10 ans, je marche aux côtés de celles et ceux qui veulent se révéler, se repositionner, se dépasser.</p>
          <p>Travailler avec moi, c'est vivre une expérience humaine forte, exigeante et profondément authentique. Je n'apporte pas que des outils ou des méthodes — j'apporte une présence, une écoute, une vision. Je ne suis pas là pour impressionner, je suis là pour élever.</p>
          <p>Que vous soyez un jeune en quête d'identité, un professionnel à un tournant de sa carrière ou un leader qui veut aligner performance et sens, je vous aide à reconnecter avec ce qui vous anime vraiment. Et toujours avec un seul objectif : faire émerger la meilleure version de vous-même.</p>
        </div>
        <a :href="route('reserver-une-session')" class="btn-accent relative mt-8 inline-flex">
          Commencer maintenant
        </a>
      </div>
    </section>

    <Testimonials v-if="testimonials.length" :testimonials="testimonials" />

    <!-- CTA final -->
    <section class="relative overflow-hidden bg-accent-gradient bg-[length:200%_200%] py-14 animate-gradient-flow">
      <div class="container-site max-w-4xl text-center">
        <h2 class="font-sans text-2xl font-bold tracking-tight text-white sm:text-3xl">Commencez dès aujourd'hui avec une session.</h2>
        <p class="mt-3 text-white/90">Assez réfléchi. Il est temps d'agir. Réserve ta première séance dès maintenant et commence ta transformation.</p>
        <a :href="route('reserver-une-session')"
          class="mt-6 inline-flex shrink-0 rounded-md bg-white px-8 py-3.5 font-sans text-sm font-bold text-primary shadow-elevated transition-all duration-150 ease-standard hover:scale-[1.02]">
          Réserver ma séance
        </a>
      </div>
    </section>
  </PublicLayout>
</template>
