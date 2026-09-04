<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  programme: Object,
  testimonials: Array,
})

const imageUrl = computed(() =>
  props.programme.image_path ? `/storage/${props.programme.image_path}` : null
)

const formattedPrice = computed(() =>
  Number(props.programme.price) > 0
    ? `${Number(props.programme.price).toLocaleString('fr-FR')} FCFA`
    : 'Gratuit'
)

const typeLabels = { individual: 'Individuel', group: 'Groupe', corporate: 'Entreprise', adolescents: 'Adolescents' }

const coachBio = "Stéphane OUATTARA est coach certifié en développement personnel (FranklinCovey), entrepreneur social et expert en autonomisation des jeunes. Diplômé du programme ASPI de Stanford et alumni IVLP, il accompagne depuis plus de 10 ans adolescents, professionnels et dirigeants vers l'impact durable."
</script>

<template>
  <Head :title="`${programme.title} — Stéphane Ouattara`">
    <meta name="description" :content="(programme.short_description || programme.description).slice(0, 160)" />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-24 sm:py-28">
      <img v-if="imageUrl" :src="imageUrl"
        class="absolute inset-0 h-full w-full object-cover opacity-20" aria-hidden="true" alt="" />
      <div class="pointer-events-none absolute -left-16 bottom-0 h-64 w-64 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative animate-slide-up">
        <nav class="mb-4 text-xs text-white/70" aria-label="Fil d'ariane">
          <Link :href="route('programmes.index')" class="hover:text-accent-light">Programmes</Link>
          <span class="mx-1.5">/</span>
          <span class="text-white">{{ programme.title }}</span>
        </nav>
        <div class="flex flex-wrap items-center gap-2">
          <span v-if="typeLabels[programme.type]" class="badge badge-solid !bg-white/15 backdrop-blur-sm">
            {{ typeLabels[programme.type] }}
          </span>
          <span v-if="programme.level" class="badge !bg-accent/90 text-white backdrop-blur-sm">Niveau {{ programme.level }}</span>
        </div>
        <h1 class="mt-4 max-w-3xl font-sans text-3xl font-extrabold tracking-tight text-white sm:text-5xl">{{ programme.title }}</h1>
        <p v-if="programme.short_description" class="mt-4 max-w-2xl text-lg text-white/85">
          {{ programme.short_description }}
        </p>
      </div>
    </section>

    <section class="container-site grid gap-12 py-14 lg:grid-cols-3">
      <!-- Contenu -->
      <div class="space-y-10 lg:col-span-2 animate-slide-up">
        <div>
          <h2 class="font-sans text-xl font-bold text-ink">Description & objectifs</h2>
          <p class="mt-4 whitespace-pre-line leading-relaxed text-ink-secondary">{{ programme.description }}</p>
        </div>

        <!-- À qui s'adresse ce programme -->
        <div class="card !bg-surface-hover/60">
          <h2 class="font-sans text-lg font-bold text-ink">À qui s'adresse ce programme ?</h2>
          <ul class="mt-3 space-y-1.5 text-sm leading-relaxed text-ink-secondary">
            <li>• Toute personne âgée de {{ programme.age_min }} à {{ programme.age_max }} ans</li>
            <li>• Ceux qui souhaitent se reconnecter, croire en eux et oser</li>
            <li v-if="programme.type === 'corporate'">• Équipes, cadres et dirigeants en quête de performance alignée sur le sens</li>
            <li v-else>• Toute personne prête à s'engager dans une transformation personnelle authentique</li>
          </ul>
        </div>

        <!-- Coach -->
        <div class="card flex flex-col items-start gap-5 sm:flex-row sm:items-center">
          <img src="/storage/content/hero-portrait.svg" alt="Portrait de Stéphane Ouattara"
            class="h-20 w-20 shrink-0 rounded-full border-[3px] border-accent object-cover shadow-glow" />
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-accent">Votre coach</p>
            <h2 class="font-sans text-lg font-bold text-ink">Stéphane Ouattara</h2>
            <p class="mt-1.5 text-sm leading-relaxed text-ink-secondary">{{ coachBio }}</p>
          </div>
        </div>

        <!-- Témoignages -->
        <div v-if="testimonials.length">
          <h2 class="font-sans text-xl font-bold text-ink">Ce qu'ils disent de moi</h2>
          <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <blockquote v-for="(testimonial, i) in testimonials" :key="testimonial.id"
              class="card card-hover animate-slide-up" :style="{ animationDelay: `${i * 80}ms` }">
              <div class="flex gap-0.5 text-accent" :aria-label="`Note : ${testimonial.rating}/5`">
                <svg v-for="s in testimonial.rating" :key="s" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path d="M9.05 2.9a1 1 0 0 1 1.9 0l1.4 4.3h4.5a1 1 0 0 1 .6 1.8l-3.7 2.6 1.4 4.3a1 1 0 0 1-1.5 1.1L10 14.4l-3.8 2.6a1 1 0 0 1-1.5-1.1l1.4-4.3L2.4 9a1 1 0 0 1 .6-1.8h4.5l1.4-4.3Z" />
                </svg>
              </div>
              <p class="mt-3 italic leading-relaxed text-ink-secondary">« {{ testimonial.message }} »</p>
              <footer class="mt-3 text-sm">
                <span class="font-semibold text-ink">{{ testimonial.author_name }}</span>
                <span v-if="testimonial.author_title" class="block text-xs text-ink-muted">{{ testimonial.author_title }}</span>
              </footer>
            </blockquote>
          </div>
        </div>
      </div>

      <!-- Carte d'inscription -->
      <aside class="lg:col-span-1">
        <div class="glass sticky top-24 rounded-xl p-7 shadow-elevated animate-scale-in [animation-delay:150ms]">
          <p class="font-sans text-3xl font-extrabold text-gradient">{{ formattedPrice }}</p>

          <div class="mt-5">
            <div class="mb-1.5 flex justify-between text-xs font-medium text-ink-secondary">
              <span>Places restantes</span>
              <span>{{ programme.available_seats }} / {{ programme.max_participants }}</span>
            </div>
            <div class="h-1.5 overflow-hidden rounded-full bg-line/50" role="progressbar"
              :aria-valuenow="programme.available_seats" :aria-valuemin="0" :aria-valuemax="programme.max_participants">
              <div class="h-full rounded-full bg-accent-gradient transition-all duration-350 ease-smooth"
                :style="{ width: `${(programme.available_seats / programme.max_participants) * 100}%` }" />
            </div>
          </div>

          <dl class="mt-6 space-y-4 text-sm">
            <div class="flex justify-between gap-3">
              <dt class="text-ink-muted">Dates</dt>
              <dd class="text-right font-semibold text-ink">
                {{ new Date(programme.start_date).toLocaleDateString('fr-FR') }} → {{ new Date(programme.end_date).toLocaleDateString('fr-FR') }}
              </dd>
            </div>
            <div v-if="programme.registration_deadline" class="flex justify-between gap-3">
              <dt class="text-ink-muted">Inscriptions avant</dt>
              <dd><span class="badge badge-soft-warning">{{ new Date(programme.registration_deadline).toLocaleDateString('fr-FR') }}</span></dd>
            </div>
            <div class="flex justify-between gap-3">
              <dt class="text-ink-muted">Âges</dt>
              <dd class="font-semibold text-ink">{{ programme.age_min }} - {{ programme.age_max }} ans</dd>
            </div>
            <div class="flex justify-between gap-3">
              <dt class="text-ink-muted">Durée des séances</dt>
              <dd class="font-semibold text-ink">{{ programme.duration_hours }}h{{ programme.session_time ? ` · ${programme.session_time}` : '' }}</dd>
            </div>
            <div class="flex justify-between gap-3">
              <dt class="text-ink-muted">Lieu</dt>
              <dd class="text-right font-semibold text-ink">{{ programme.location }}</dd>
            </div>
          </dl>

          <Link :href="route('reserver-une-session', { programme: programme.id })" class="btn-accent mt-7 w-full">
            Réserver cette session
          </Link>
          <p class="mt-3 text-center text-xs text-ink-muted">Consultation découverte gratuite de 30 minutes.</p>
        </div>
      </aside>
    </section>
  </PublicLayout>
</template>
