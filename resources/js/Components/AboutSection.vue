<script setup>
import { Link } from '@inertiajs/vue3'

const credentials = [
  { label: 'Master en management des organisations', icon: 'M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 7.48c1.944 0 3.861.254 5.66.759' },
  { label: 'Coach certifié FranklinCovey', icon: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
  { label: 'Alumni IVLP — Gouvernement américain', icon: 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418' },
  { label: 'Diplômé du programme ASPI de Stanford', icon: 'M4.26 13.75a59.938 59.938 0 013.215-1.103M4.26 13.75L3 20.25l4.476-1.235m-3.216-5.265a59.67 59.67 0 019.69 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
]

const engagements = [
  { role: 'Manager Business Developer', org: 'Trand Advertising – Groupe Édit Africa', detail: "Stratégies de communication 360° pour marques, institutions et leaders." },
  { role: 'Fondateur', org: 'Bénévoles du Monde', detail: "Organisation d'engagement citoyen et solidaire." },
  { role: 'Initiateur des programmes', org: 'Compétence 360 · La Connexion · Club des Champions', detail: "Des parcours transformationnels multi-audiences." },
  { role: 'Créateur du cabinet', org: 'OMSY EDUC', detail: "Des milliers d'élèves, parents et professionnels formés chaque année à la connaissance de soi, au leadership et à la citoyenneté active." },
]
</script>

<template>
  <section id="about" class="scroll-mt-20 py-20 sm:py-24">
    <div class="container-site grid items-start gap-12 lg:grid-cols-2">
      <!-- Texte -->
      <div v-reveal>
        <span class="eyebrow">Qui suis-je ?</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">
          L'histoire d'un appel, le parcours d'un bâtisseur
        </h2>
        <p class="mt-3 text-lg font-medium text-primary">Quelle est l'histoire derrière mon parcours ?</p>

        <div class="mt-6 space-y-4 leading-relaxed text-ink-secondary">
          <p>
            Je m'appelle <strong class="font-semibold text-accent">Stéphane OUATTARA</strong>. Je suis coach en
            développement personnel, entrepreneur social, formateur, bâtisseur d'écosystèmes et rêveur lucide.
            Depuis plus de 10 ans, j'ai choisi de mettre ma vie au service d'une conviction simple :
            <em>chaque personne porte en elle un potentiel à révéler</em>, et ce potentiel, lorsqu'il est activé,
            devient une richesse durable pour l'Afrique et pour le monde.
          </p>
          <p>
            Fils unique, né en Côte d'Ivoire, j'ai très tôt compris que notre histoire personnelle — aussi belle,
            dure ou imparfaite soit-elle — peut devenir un levier puissant de transformation. J'ai choisi d'en faire
            une force. Depuis 2014, je chemine aux côtés de jeunes en quête d'identité, de professionnels en
            transition, et de dirigeants en quête de sens.
          </p>
        </div>

        <!-- Credentials -->
        <h3 class="mt-10 font-sans text-sm font-semibold uppercase tracking-wide text-ink">Parcours & certifications</h3>
        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
          <li v-for="(cred, i) in credentials" :key="cred.label" v-reveal="{ delay: i * 80 }"
            class="flex items-start gap-3 rounded-md border border-line/60 bg-surface p-3.5 text-sm text-ink-secondary shadow-xs transition-all duration-150 hover:-translate-y-0.5 hover:shadow-md">
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-primary-gradient text-white">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="cred.icon" /></svg>
            </span>
            {{ cred.label }}
          </li>
        </ul>

        <Link :href="route('contact')" class="btn-primary mt-8">
          Travaillons ensemble
        </Link>
      </div>

      <!-- Engagements -->
      <div v-reveal="{ from: 'right' }" class="space-y-4 lg:sticky lg:top-24">
        <div class="glass rounded-xl p-7 shadow-elevated">
          <svg class="float h-9 w-9 text-accent" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z" />
          </svg>
          <blockquote class="mt-4 font-sans text-xl italic leading-relaxed text-ink">
            « Je crois que quand l'humain se trouve, il se dépasse. »
          </blockquote>
        </div>

        <h3 class="pt-2 font-sans text-sm font-semibold uppercase tracking-wide text-ink">Engagements</h3>
        <article v-for="(item, i) in engagements" :key="item.org" v-reveal="{ delay: i * 90 }"
          class="card card-hover !p-5">
          <p class="text-xs font-semibold uppercase tracking-wide text-accent">{{ item.role }}</p>
          <p class="mt-1 font-sans font-bold text-ink">{{ item.org }}</p>
          <p class="mt-1.5 text-sm leading-relaxed text-ink-secondary">{{ item.detail }}</p>
        </article>
      </div>
    </div>
  </section>
</template>
