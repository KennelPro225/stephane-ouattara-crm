<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  heading: { type: String, default: "Commence dès aujourd'hui" },
  subheading: {
    type: String,
    default: "Assez réfléchi. Il est temps d'agir. Réserve ta première séance dès maintenant et commence ta transformation.",
  },
})
</script>

<template>
  <section class="relative overflow-hidden bg-accent-gradient bg-[length:200%_200%] py-16 animate-gradient-flow">
    <div class="container-site flex max-w-5xl flex-col items-center gap-8 text-center lg:flex-row lg:justify-between lg:text-left">
      <div>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ heading }}</h2>
        <p class="mt-3 max-w-xl text-white/90">{{ subheading }}</p>
      </div>
      <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
        <Link :href="route('reserver-une-session')"
          class="rounded-md bg-white px-8 py-4 font-sans text-base font-bold text-primary shadow-elevated transition-all duration-150 ease-standard hover:scale-[1.02] hover:shadow-xl">
          Réserver une session
        </Link>
        <Link :href="route('programmes.index')"
          class="rounded-md border-2 border-white/60 px-8 py-4 text-center text-base font-semibold text-white backdrop-blur-sm transition-all duration-150 ease-standard hover:scale-[1.01] hover:bg-white/10">
          Voir les programmes
        </Link>
      </div>
    </div>
  </section>
</template>
