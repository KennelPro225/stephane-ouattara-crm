<template>
  <section id="about" class="py-20 sm:py-24">
    <div class="container-site grid items-center gap-12 lg:grid-cols-2">
      <div class="animate-slide-up">
        <span class="eyebrow">À propos</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">
          Stéphane Ouattara
        </h2>
        <p class="mt-6 text-lg leading-relaxed text-ink-secondary">
          Coach en développement personnel et entrepreneur social basé à Abidjan, Stéphane consacre
          sa carrière à révéler le potentiel des jeunes et des professionnels de Côte d'Ivoire.
        </p>
        <p class="mt-4 leading-relaxed text-ink-secondary">
          À travers ses programmes « Club des Champions », ses formations en entreprise et son
          mentorat individuel, il transmet les clés du leadership, de la confiance en soi et de
          l'impact social positif.
        </p>

        <dl class="mt-10 grid grid-cols-3 gap-4 text-center">
          <div v-for="stat in [
            { value: '500+', label: 'Personnes coachées' },
            { value: '15+', label: 'Années d\'expérience' },
            { value: '30+', label: 'Entreprises accompagnées' },
          ]" :key="stat.label" class="card card-hover !p-5">
            <dt class="font-sans text-2xl font-extrabold text-gradient">{{ stat.value }}</dt>
            <dd class="mt-1 text-xs font-medium uppercase tracking-wide text-ink-muted">{{ stat.label }}</dd>
          </div>
        </dl>
      </div>

      <div class="relative animate-fade-in [animation-delay:150ms]">
        <div class="glass rounded-xl p-8 shadow-elevated transition-transform duration-250 ease-smooth hover:scale-[1.02]">
          <svg class="float h-10 w-10 text-accent" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z" />
          </svg>
          <blockquote class="mt-6 font-sans text-xl italic leading-relaxed text-ink sm:text-2xl">
            « Chaque personne porte en elle un champion. Mon rôle est de l'aider à le réveiller. »
          </blockquote>
          <p class="mt-6 flex items-center gap-3">
            <span class="grid h-12 w-12 place-items-center rounded-full border-[3px] border-accent bg-primary-gradient font-sans text-sm font-bold text-white">SO</span>
            <span class="text-sm font-semibold text-ink-secondary">Stéphane Ouattara</span>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>
