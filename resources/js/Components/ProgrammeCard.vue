<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  programme: { type: Object, required: true },
})

const imageUrl = computed(() =>
  props.programme.image_path ? `/storage/${props.programme.image_path}` : null
)

const typeLabels = {
  individual: 'Individuel',
  group: 'Groupe',
  corporate: 'Entreprise',
  adolescents: 'Adolescents',
}

const formattedPrice = computed(() =>
  Number(props.programme.price) > 0
    ? `${Number(props.programme.price).toLocaleString('fr-FR')} FCFA`
    : 'Gratuit'
)
</script>

<template>
  <article class="card card-hover group flex h-full flex-col !p-0 overflow-hidden">
    <Link :href="route('programmes.show', programme.slug)" class="block" :aria-label="programme.title">
      <div class="relative aspect-[16/10] bg-primary-gradient">
        <img v-if="imageUrl" :src="imageUrl" :alt="programme.title"
          class="h-full w-full object-cover transition-transform duration-250 ease-smooth group-hover:scale-105" />
        <span v-else class="grid h-full place-items-center font-sans text-5xl font-black text-white/60">
          {{ programme.title.charAt(0) }}
        </span>
        <span v-if="typeLabels[programme.type]" class="badge badge-solid absolute left-3 top-3 backdrop-blur-sm">
          {{ typeLabels[programme.type] }}
        </span>
        <span v-if="programme.level" class="badge absolute right-3 top-3 !bg-white/90 !text-primary backdrop-blur-sm">
          Niveau {{ programme.level }}
        </span>
      </div>
    </Link>

    <div class="flex flex-1 flex-col p-6">
      <h3 class="font-sans text-lg font-semibold text-ink">
        <Link :href="route('programmes.show', programme.slug)" class="transition-colors hover:text-accent">
          {{ programme.title }}
        </Link>
      </h3>
      <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-ink-secondary">
        {{ programme.short_description || programme.description }}
      </p>

      <dl class="mt-4 space-y-1.5 text-xs text-ink-muted">
        <div class="flex justify-between">
          <dt>Âges</dt>
          <dd class="font-medium text-ink-secondary">{{ programme.age_min }} - {{ programme.age_max }} ans</dd>
        </div>
        <div class="flex justify-between">
          <dt>Début</dt>
          <dd class="font-medium text-ink-secondary">{{ new Date(programme.start_date).toLocaleDateString('fr-FR') }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt>Places restantes</dt>
          <dd class="flex items-center gap-1.5 font-medium">
            <span class="relative flex h-2 w-2">
              <span :class="programme.available_seats > 5 ? 'bg-success' : 'bg-warning'" class="absolute inline-flex h-full w-full rounded-full opacity-75 animate-pulse" />
              <span :class="programme.available_seats > 5 ? 'bg-success' : 'bg-warning'" class="relative inline-flex h-2 w-2 rounded-full" />
            </span>
            <span :class="programme.available_seats > 5 ? 'text-success' : 'text-warning'">{{ programme.available_seats }}</span>
          </dd>
        </div>
      </dl>

      <div class="mt-5 flex items-center justify-between border-t border-line/50 pt-4">
        <span class="font-sans text-base font-bold text-primary">{{ formattedPrice }}</span>
        <Link :href="route('reserver-une-session')" class="btn-primary !rounded-md !px-4 !py-2 !text-xs">
          S'inscrire
        </Link>
      </div>
    </div>
  </article>
</template>
