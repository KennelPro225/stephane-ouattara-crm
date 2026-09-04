<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'

const props = defineProps({
  testimonials: { type: Array, required: true },
})

const index = ref(0)
const paused = ref(false)
let timer

const count = computed(() => props.testimonials.length)
const current = computed(() => props.testimonials[index.value])

const next = () => { if (count.value) index.value = (index.value + 1) % count.value }
const prev = () => { if (count.value) index.value = (index.value - 1 + count.value) % count.value }

function startAutoplay() {
  stopAutoplay()
  if (count.value > 1 && !paused.value) {
    timer = setInterval(next, 5000)
  }
}

function stopAutoplay() {
  if (timer) clearInterval(timer)
}

function goTo(i) {
  index.value = i
  startAutoplay() // repart après interaction
}

onMounted(startAutoplay)
onUnmounted(stopAutoplay)

const stars = (n) => Array.from({ length: n }, (_, i) => i)
</script>

<template>
  <section class="relative overflow-hidden py-4">
    <div class="pointer-events-none absolute left-1/2 top-0 h-64 w-[600px] -translate-x-1/2 rounded-full bg-accent/10 blur-3xl" aria-hidden="true" />
    <div class="container-site relative mx-auto max-w-4xl text-center">
      <span v-reveal class="eyebrow">Témoignages</span>
      <h2 v-reveal="{ delay: 80 }" class="font-sans text-3xl font-bold tracking-tight text-ink sm:text-4xl">Ce qu'ils disent de moi</h2>

      <!-- Pile de cartes en arrière-plan pour la profondeur -->
      <div
        class="relative mx-auto mt-12 max-w-3xl"
        @mouseenter="paused = true; stopAutoplay()"
        @mouseleave="paused = false; startAutoplay()"
      >
        <div class="absolute inset-x-8 -top-3 h-full rounded-xl border border-line/40 bg-surface opacity-50" aria-hidden="true" />
        <div class="absolute inset-x-4 -top-1.5 h-full rounded-xl border border-line/60 bg-surface opacity-75" aria-hidden="true" />

        <Transition name="testimonial" mode="out-in">
          <figure v-if="current" :key="index" class="glass card relative !p-8 shadow-elevated sm:!p-12">
            <svg class="mx-auto h-10 w-10 text-accent/30 animate-float" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z" />
            </svg>
            <div class="mt-4 flex justify-center gap-1 text-accent" :aria-label="`Note : ${current.rating} sur 5`">
              <svg v-for="i in stars(current.rating)" :key="i" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.05 2.9a1 1 0 0 1 1.9 0l1.4 4.3h4.5a1 1 0 0 1 .6 1.8l-3.7 2.6 1.4 4.3a1 1 0 0 1-1.5 1.1L10 14.4l-3.8 2.6a1 1 0 0 1-1.5-1.1l1.4-4.3L2.4 9a1 1 0 0 1 .6-1.8h4.5l1.4-4.3Z" />
              </svg>
            </div>
            <blockquote class="mt-6 font-sans text-lg italic leading-relaxed text-ink-secondary sm:text-xl">
              « {{ current.message }} »
            </blockquote>
            <figcaption class="mt-6">
              <p class="flex items-center justify-center gap-2 font-semibold text-ink">
                <span class="grid h-9 w-9 place-items-center rounded-full border-2 border-accent bg-primary-gradient text-xs font-bold text-white">
                  {{ current.author_name.charAt(0) }}
                </span>
                {{ current.author_name }}
              </p>
              <p v-if="current.author_title || current.author_company" class="mt-1 text-sm text-ink-muted">
                {{ [current.author_title, current.author_company].filter(Boolean).join(', ') }}
              </p>
            </figcaption>
          </figure>
        </Transition>
      </div>

      <div v-if="count > 1" class="mt-8 flex items-center justify-center gap-4">
        <button @click="prev; goTo(index)" aria-label="Témoignage précédent"
          class="grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-ink-secondary shadow-xs transition-all duration-150 hover:-translate-y-0.5 hover:border-primary hover:text-primary">←</button>
        <div class="flex gap-2" role="tablist" aria-label="Choisir un témoignage">
          <button v-for="(t, i) in testimonials" :key="i" @click="goTo(i)"
            :class="i === index ? 'w-6 bg-primary' : 'bg-line hover:bg-ink-muted'"
            class="h-2 rounded-full transition-all duration-250 ease-smooth"
            :aria-label="`Témoignage ${i + 1}`" />
        </div>
        <button @click="next" aria-label="Témoignage suivant"
          class="grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-ink-secondary shadow-xs transition-all duration-150 hover:-translate-y-0.5 hover:border-primary hover:text-primary">→</button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.testimonial-enter-active { transition: opacity 300ms cubic-bezier(0.34, 1.56, 0.64, 1), transform 300ms cubic-bezier(0.34, 1.56, 0.64, 1); }
.testimonial-leave-active { transition: opacity 200ms cubic-bezier(0.34, 0.56, 0.64, 0), transform 200ms cubic-bezier(0.34, 0.56, 0.64, 0); }
.testimonial-enter-from { opacity: 0; transform: translateY(16px) scale(0.97); }
.testimonial-leave-to { opacity: 0; transform: translateY(-12px) scale(0.97); }
</style>
