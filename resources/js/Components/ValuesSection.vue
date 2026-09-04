<script setup>
const values = [
  {
    name: 'Authenticité',
    icon: 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0',
    text: "Je refuse de jouer un rôle. Ce que je vis, je le partage. Mes forces comme mes failles m'ont construit. Être vrai, aligné, sans masque : c'est ainsi que j'inspire. Mon authenticité est ma force, et c'est elle qui donne du sens à mon engagement.",
  },
  {
    name: 'Foi en l\'humain',
    icon: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
    text: "Je crois profondément en l'humain. Chaque personne porte un potentiel, même s'il est enfoui. Je vois des étincelles où d'autres voient des limites. Mon rôle, c'est de raviver cette flamme. Quand l'autre croit en lui, il devient capable de créer, d'élever et de transformer le monde.",
  },
  {
    name: 'Excellence',
    icon: 'M16.5 18.75l-5.25 2.25-5.25-2.25m10.5-12L11.25 2.25 6 6.75m10.5 12V9.75L11.25 6 6 9.75m10.5 12H6a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 016 4.5h12a2.25 2.25 0 012.25 2.25v11.25A2.25 2.25 0 0118 20.25z',
    text: "L'excellence est un choix quotidien. Je me forme, je progresse, je ne me contente jamais du minimum. Pas pour paraître, mais pour servir. Être exigeant, c'est honorer ceux que j'accompagne. Ils méritent le meilleur. L'excellence, c'est mon standard. Mon engagement, c'est de toujours viser plus haut.",
  },
  {
    name: 'Transmission',
    icon: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
    text: "Tout ce que j'apprends, je le transmets. Ce que je vis devient enseignement. La transmission est pour moi un devoir, pas une option. Je veux que chaque idée, chaque valeur semée, devienne un arbre. Car une vie qui ne se partage pas finit par s'éteindre en silence.",
  },
]

const whyMe = "Parce que je ne me contente pas d'accompagner, je m'implique. Depuis plus de 10 ans, je marche aux côtés de celles et ceux qui veulent se révéler, se repositionner, se dépasser. Je crois en la puissance de l'humain, en la force des récits personnels et en la transformation possible à chaque étape de vie. Travailler avec moi, c'est vivre une expérience humaine forte, exigeante et profondément authentique. Je n'apporte pas que des outils ou des méthodes — j'apporte une présence, une écoute, une vision. Je ne suis pas là pour impressionner, je suis là pour élever."
</script>

<template>
  <section id="valeurs" class="scroll-mt-20 bg-surface/50 py-20 sm:py-24">
    <div class="container-site">
      <div v-reveal class="mx-auto max-w-2xl text-center">
        <span class="eyebrow">Mes valeurs</span>
        <h2 class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">
          Ce qui guide chacun de mes accompagnements
        </h2>
      </div>

      <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <article v-for="(value, i) in values" :key="value.name" v-reveal="{ delay: i * 110 }"
          class="card card-hover group">
          <span class="grid h-12 w-12 place-items-center rounded-full border-[3px] border-accent bg-primary-gradient text-white shadow-glow transition-transform duration-150 ease-bounce group-hover:scale-110">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" :d="value.icon" /></svg>
          </span>
          <h3 class="mt-5 font-sans text-lg font-bold text-primary">{{ value.name }}</h3>
          <p class="mt-3 text-sm leading-relaxed text-ink-secondary">{{ value.text }}</p>
        </article>
      </div>

      <!-- Pourquoi travailler avec moi -->
      <div v-reveal="{ delay: 150 }" class="card mt-12 !bg-primary-gradient !border-0 p-10 text-white shadow-elevated transition-transform duration-250 ease-smooth hover:scale-[1.01]">
        <div class="grid items-center gap-8 lg:grid-cols-[auto_1fr]">
          <div class="text-center lg:w-56">
            <p class="font-sans text-5xl font-extrabold tracking-tight">10+</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-white/80">années à élever les autres</p>
            <div class="mx-auto mt-4 h-px w-16 bg-white/30" />
            <p class="mt-4 font-sans text-lg italic">« Je ne suis pas là pour impressionner, je suis là pour élever. »</p>
          </div>
          <p class="leading-relaxed text-white/90">{{ whyMe }} Que vous soyez un jeune en quête d'identité, un professionnel à un tournant de sa carrière ou un leader qui veut aligner performance et sens, je vous aide à reconnecter avec ce qui vous anime vraiment. Je construis des ponts, je crée des cadres d'élévation, je transmets ce que j'ai appris — de la formation à l'incubation, de la stratégie à la communication d'influence. Et toujours avec un seul objectif : faire émerger la meilleure version de vous-même.</p>
        </div>
      </div>
    </div>
  </section>
</template>
