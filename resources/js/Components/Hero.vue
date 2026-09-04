<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  title: {
    type: String,
    default: "J'aide les ados, les leaders et les entreprises à se reconnecter, à croire en eux et oser.",
  },
})
</script>

<template>
  <section class="relative overflow-hidden bg-primary-gradient">
    <!-- Halos décoratifs animés -->
    <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
    <div class="pointer-events-none absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-accent-light/20 blur-3xl animate-float [animation-delay:-1.5s]" aria-hidden="true" />
    <div class="absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at 25% 25%, rgba(230,126,34,.35) 0%, transparent 45%), radial-gradient(circle at 75% 75%, rgba(245,167,107,.25) 0%, transparent 40%)" aria-hidden="true" />

    <div class="container-site relative grid items-center gap-12 py-20 sm:py-24 lg:grid-cols-2 lg:gap-8 lg:py-28">
      <!-- Texte -->
      <div class="animate-slide-up lg:pr-8">
        <p class="badge badge-outline mb-6 !border-white/40 !bg-white/10 !text-white/90 backdrop-blur-sm">
          Coach en développement personnel · Côte d'Ivoire
        </p>
        <h1 class="font-sans text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl xl:text-[2.9rem] xl:leading-[1.15]" style="letter-spacing: -0.03em">
          {{ title }}
        </h1>
        <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/85">
          Plus de 10 ans d'expérience au service du potentiel humain — coaching certifié FranklinCovey,
          alumni IVLP et Stanford ASPI.
        </p>
        <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
          <Link :href="route('reserver-une-session')" class="btn-accent w-full sm:w-auto">
            Réserver une session
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" /></svg>
          </Link>
          <Link :href="route('qui-suis-je')"
            class="inline-flex w-full items-center justify-center gap-2 rounded-md border-2 border-white/50 px-8 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-150 ease-standard hover:scale-[1.01] hover:bg-white/10 sm:w-auto">
            Qui suis-je ?
          </Link>
        </div>

        <!-- Indicateur scroll -->
        <a href="#histoire" class="mt-12 hidden items-center gap-2 text-xs font-medium uppercase tracking-widest text-white/60 transition-colors hover:text-white lg:inline-flex">
          Scroll for more ↓
        </a>
      </div>

      <!-- Composition d'images -->
      <div class="relative mx-auto w-full max-w-md animate-fade-in [animation-delay:200ms] lg:max-w-none">
        <!-- Portrait principal -->
        <div class="relative overflow-hidden rounded-xl border border-white/30 shadow-elevated backdrop-blur-sm transition-transform duration-250 ease-smooth hover:scale-[1.02]">
          <img src="/images/portrait-stephane.svg" alt="Stéphane Ouattara, coach en développement personnel"
            class="aspect-[6/7] w-full object-cover" />
          <figcaption class="absolute bottom-3 left-3 right-3 rounded-md glass px-4 py-3">
            <p class="font-sans text-sm font-bold text-white drop-shadow">Stéphane OUATTARA</p>
            <p class="text-xs text-white/85">Coach certifié · Entrepreneur social</p>
          </figcaption>
        </div>

        <!-- Carte flottante : coaching -->
        <img src="/images/coaching-session.svg" alt="Session de coaching individuel"
          class="absolute -left-6 top-6 hidden w-40 rounded-lg border border-line/50 shadow-elevated animate-float sm:block lg:-left-12 lg:w-48"
          loading="lazy" />

        <!-- Carte flottante : teambuilding -->
        <img src="/images/team-building.svg" alt="Teambuilding d'équipe"
          class="absolute -bottom-8 -right-4 hidden w-44 rounded-lg border border-line/50 shadow-elevated animate-float [animation-delay:-1.5s] sm:block lg:-right-10 lg:w-52"
          loading="lazy" />

        <!-- Badge années d'expérience -->
        <div class="glass absolute -top-4 right-4 rounded-full px-4 py-2 shadow-glow animate-pulse">
          <span class="font-sans text-xs font-bold text-accent dark:text-accent-light">+10 ans d'expérience</span>
        </div>
      </div>
    </div>
  </section>
</template>
