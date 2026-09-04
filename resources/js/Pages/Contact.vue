<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Testimonials from '@/Components/Testimonials.vue'

const page = usePage()

const email = computed(() => page.props.settings?.contact_email ?? 'contact@stephane-ouattara.com')
const phone = computed(() => page.props.settings?.contact_phone ?? '+225 07 48 78 81 33')

defineProps({
  testimonials: Array,
})
</script>

<template>
  <Head title="Contact — Ensemble, créons votre chemin vers l'épanouissement">
    <meta name="description" content="Contactez Stéphane Ouattara, coach en développement personnel à Abidjan : consultation découverte gratuite de 30 minutes. Cabinet OMSY EDUC, Cocody." />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-20 sm:py-24">
      <div class="pointer-events-none absolute -right-12 top-0 h-56 w-56 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative max-w-4xl">
        <span class="eyebrow !text-accent-light">Contact</span>
        <h1 class="font-sans text-3xl font-bold tracking-tight text-white sm:text-4xl">
          Ensemble, créons votre chemin vers l'épanouissement
        </h1>
        <p class="mt-5 leading-relaxed text-white/85">
          Que vous soyez un jeune en quête d'identité, un parent souhaitant mieux communiquer avec votre
          adolescent, ou un dirigeant cherchant à développer un leadership authentique, chaque parcours est
          unique et mérite une attention personnalisée. Fort de plus de 10 ans d'expérience en accompagnement,
          je m'engage à être à vos côtés pour transformer vos défis en opportunités de croissance.
        </p>
      </div>
    </section>

    <section class="container-site grid gap-10 py-14 lg:grid-cols-5">
      <!-- Colonne infos -->
      <div v-reveal="{ from: 'left' }" class="space-y-6 lg:col-span-2">
        <!-- Réserver -->
        <div class="card !bg-primary-gradient !border-0 text-white shadow-elevated">
          <h2 class="font-sans text-xl font-bold">Réserver une session</h2>
          <p class="mt-3 text-sm leading-relaxed text-white/85">
            Votre transformation personnelle commence par une conversation authentique. En tant que Coach certifié
            FranklinCovey et Alumni IVLP, je vous propose une <strong>consultation découverte gratuite de 30
            minutes</strong> pour explorer ensemble vos objectifs et définir le meilleur parcours d'accompagnement.
          </p>
          <ul class="mt-4 space-y-2 text-sm text-white/90">
            <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-accent-light" />Club des Champions pour les adolescents</li>
            <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-accent-light" />Coaching individuel pour adultes et parents</li>
            <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-accent-light" />Ateliers de groupe et théâtre éducatif</li>
            <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-accent-light" />Accompagnement en entreprise (teambuilding, leadership)</li>
          </ul>
          <Link :href="route('reserver-une-session')" class="btn-accent mt-6 w-full">
            Réserver votre consultation gratuite
          </Link>
        </div>

        <!-- Coordonnées -->
        <div class="card animate-slide-up [animation-delay:150ms]">
          <h2 class="font-sans font-bold text-ink">Contact direct</h2>
          <ul class="mt-4 space-y-4 text-sm">
            <li class="flex items-center gap-4">
              <span class="grid h-11 w-11 shrink-0 place-items-center rounded-md bg-accent/10 text-accent">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
              </span>
              <div><p class="text-xs font-medium uppercase tracking-wide text-ink-muted">Email</p><p class="font-semibold text-ink">{{ email }}</p></div>
            </li>
            <li class="flex items-center gap-4">
              <span class="grid h-11 w-11 shrink-0 place-items-center rounded-md bg-accent/10 text-accent">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
              </span>
              <div><p class="text-xs font-medium uppercase tracking-wide text-ink-muted">Téléphone</p><p class="font-semibold text-ink">{{ phone }}</p></div>
            </li>
          </ul>

          <hr class="my-5 border-line/50" />

          <h3 class="font-sans text-sm font-semibold uppercase tracking-wide text-ink">Horaires de disponibilité</h3>
          <dl class="mt-3 space-y-1.5 text-sm">
            <div class="flex justify-between"><dt class="text-ink-secondary">Lundi - Vendredi</dt><dd class="font-medium text-ink">9h00 - 18h00</dd></div>
            <div class="flex justify-between"><dt class="text-ink-secondary">Samedi</dt><dd class="font-medium text-ink">Sur rendez-vous</dd></div>
          </dl>

          <hr class="my-5 border-line/50" />

          <h3 class="font-sans text-sm font-semibold uppercase tracking-wide text-ink">Localisation & Interventions</h3>
          <p class="mt-3 text-sm leading-relaxed text-ink-secondary">
            <strong class="font-semibold text-ink">Cabinet OMSY EDUC</strong><br />
            Cocody, Abidjan, Côte d'Ivoire<br />
            Accompagnement en présentiel et en ligne · Interventions dans toute la Côte d'Ivoire et à l'international.
          </p>

          <div class="mt-5 flex items-center gap-3">
            <a href="#" aria-label="Facebook"
              class="grid h-10 w-10 place-items-center rounded-full border border-line/60 text-ink-secondary transition-all duration-150 hover:-translate-y-0.5 hover:border-primary hover:text-primary hover:shadow-glow">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
            </a>
            <a href="#" aria-label="Instagram"
              class="grid h-10 w-10 place-items-center rounded-full border border-line/60 text-ink-secondary transition-all duration-150 hover:-translate-y-0.5 hover:border-accent hover:text-accent hover:shadow-glow">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Colonne témoignages -->
      <div v-reveal="{ from: 'right' }" class="lg:col-span-3">
        <Testimonials :testimonials="testimonials ?? []" />

        <div v-if="!testimonials?.length" class="flex flex-col items-center rounded-xl border border-dashed border-line p-12 text-center">
          <svg class="h-16 w-16 text-primary-bright" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" /></svg>
          <p class="mt-3 text-sm text-ink-muted">Les témoignages arrivent bientôt.</p>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>
