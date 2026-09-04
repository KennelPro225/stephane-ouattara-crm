<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  portrait: { type: String, default: '/storage/content/hero-portrait.png' },
})

// Parallaxe légère au mouvement de la souris
const orbX = ref(0)
const orbY = ref(0)
const imgX = ref(0)

function onMouseMove(event) {
  const { innerWidth, innerHeight } = window
  const x = (event.clientX / innerWidth - 0.5) * 2
  const y = (event.clientY / innerHeight - 0.5) * 2
  orbX.value = x * 18
  orbY.value = y * 12
  imgX.value = x * -8
}

function scrollToAbout() {
  document.getElementById('about')?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
  <section class="relative flex min-h-[92vh] items-center overflow-hidden" @mousemove="onMouseMove">
    <!-- Portrait en arrière-plan (parallaxe horizontale subtile) -->
    <img
      :src="portrait"
      alt="Portrait de Stéphane Ouattara, coach en développement personnel"
      class="absolute inset-0 h-full w-full scale-105 object-cover object-center transition-transform duration-500 ease-out"
      :style="{ transform: `translateX(${imgX}px) scale(1.05)` }"
    />
    <!-- Voiles dégradés pour la lisibilité -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#1A1410]/90 via-[#1A1410]/70 to-transparent" aria-hidden="true" />
    <div class="absolute inset-0 bg-gradient-to-t from-[#1A1410]/80 via-transparent to-[#1A1410]/40" aria-hidden="true" />

    <!-- Orbites flottantes en parallaxe -->
    <div
      class="pointer-events-none absolute -right-24 top-1/3 h-96 w-96 rounded-full bg-accent/20 blur-3xl animate-float will-change-transform"
      :style="{ transform: `translate(${orbX}px, ${orbY}px)` }"
      aria-hidden="true"
    />
    <div
      class="pointer-events-none absolute left-1/3 bottom-10 h-40 w-40 rounded-full bg-accent-light/15 blur-2xl animate-float [animation-delay:-1.8s] will-change-transform"
      :style="{ transform: `translate(${-orbX * 1.4}px, ${-orbY}px)` }"
      aria-hidden="true"
    />

    <div class="container-site relative py-32">
      <div class="max-w-2xl">
        <p class="badge badge-outline mb-6 !border-white/30 !bg-white/10 !text-white/90 backdrop-blur-sm animate-slide-up">
          Coach certifié · FranklinCovey · Stanford ASPI · IVLP
        </p>

        <h1
          class="font-sans text-4xl font-extrabold leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-6xl animate-slide-up [animation-delay:120ms]"
          style="letter-spacing: -0.04em"
        >
          J'aide les ados, les leaders et les entreprises à
          <span class="relative inline-block text-accent-light">se reconnecter<svg class="absolute -bottom-1.5 left-0 w-full text-accent/70" height="6" preserveAspectRatio="none" viewBox="0 0 200 6" aria-hidden="true"><path d="M0 3 Q50 6 100 3 T200 3" stroke="currentColor" stroke-width="2.5" fill="none"/></svg></span>, à
          <span class="shimmer-text !text-white [background-image:linear-gradient(110deg,#fff_40%,#F5A76B_50%,#fff_60%)]">croire en eux</span> et
          <span class="text-accent-light">oser</span>.
        </h1>

        <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/80 animate-slide-up [animation-delay:240ms]">
          Un potentiel pour chaque personne, une valeur sûre pour l'Afrique.
        </p>

        <div class="mt-10 flex flex-col gap-4 sm:flex-row animate-slide-up [animation-delay:360ms]">
          <button type="button" @click="scrollToAbout"
            class="btn-secondary !border-white/60 !bg-white/10 !text-white backdrop-blur-sm hover:!bg-white/20">
            Qui suis-je ?
          </button>
          <Link :href="route('reserver-une-session')" class="btn-accent group">
            Réserver une session
            <svg class="h-4 w-4 transition-transform duration-150 ease-standard group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" /></svg>
          </Link>
        </div>
      </div>
    </div>

    <!-- Scroll for more -->
    <button type="button" @click="scrollToAbout"
      class="group absolute bottom-8 left-1/2 -translate-x-1/2 text-white/70 transition-colors hover:text-accent-light"
      aria-label="Faire défiler vers la section À propos">
      <span class="mb-2 block text-xs font-medium uppercase tracking-widest">Scroll for more</span>
      <svg class="mx-auto h-6 w-6 animate-bounce" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
      </svg>
    </button>
  </section>
</template>
