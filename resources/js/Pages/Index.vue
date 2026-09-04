<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import HeroSection from '@/Components/HeroSection.vue'
import AboutSection from '@/Components/AboutSection.vue'
import ValuesSection from '@/Components/ValuesSection.vue'
import AchievementsSection from '@/Components/AchievementsSection.vue'
import ServicesSection from '@/Components/ServicesSection.vue'
import Testimonials from '@/Components/Testimonials.vue'
import CtaBanner from '@/Components/CtaBanner.vue'
import ProgrammeCard from '@/Components/ProgrammeCard.vue'
import MarqueeBand from '@/Components/MarqueeBand.vue'
import ScrollProgress from '@/Components/ScrollProgress.vue'

defineProps({
  featuredProgrammes: Array,
  testimonials: Array,
  galleries: Array,
})
</script>

<template>
  <Head title="Coach Stéphane OUATTARA | Expert Développement Personnel & Leadership Côte d'Ivoire">
    <meta name="description" content="Coach certifié en développement personnel avec +10 ans d'expérience. J'aide les ados, les leaders et les entreprises en Côte d'Ivoire à se reconnecter, à croire en eux et oser." />
  </Head>
  <PublicLayout>
    <!-- SECTION 1 : Hero -->
    <HeroSection />

    <!-- Bandeau défilant des programmes -->
    <MarqueeBand />

    <!-- SECTION 2 : Qui suis-je -->
    <AboutSection />

    <!-- SECTION 3 : Valeurs -->
    <ValuesSection />

    <!-- SECTION 5 : Services -->
    <ServicesSection />

        <!-- Programmes à la une depuis la base -->
    <section v-if="featuredProgrammes.length" class="bg-surface/50 py-16">
      <div class="container-site">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <span class="eyebrow">Agenda</span>
            <h2 class="font-sans text-2xl font-bold tracking-tight text-ink sm:text-3xl">Prochaines sessions ouvertes</h2>
          </div>
          <a :href="route('programmes.index')" class="hidden text-sm font-semibold text-primary transition-colors hover:text-accent sm:block">
            Voir tous les programmes →
          </a>
        </div>
        <div class="mt-8 grid grid-cols-3 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <ProgrammeCard v-for="programme in featuredProgrammes" :key="programme.id"
            :programme="programme" class="mt-6 max-w-md animate-slide-up" />
        </div>
      </div>
    </section>

    
    <!-- SECTION 6 : Témoignages -->
    <section id="temoignages" class="py-20 sm:py-24">
      <Testimonials :testimonials="testimonials" />
    </section>

    <!-- SECTION 7 : CTA final -->
    <CtaBanner />
  </PublicLayout>
</template>
