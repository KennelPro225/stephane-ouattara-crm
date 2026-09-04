<script setup>
import { ref } from 'vue'
import { Head, usePage, useForm } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'

const page = usePage()

const props = defineProps({
  programmes: Array,
  types: Array,
  selectedType: { type: String, default: null },
  selectedProgramme: { type: [String, Number], default: null },
})

const serviceLabels = {
  individual: { label: 'Coaching individuel', icon: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z' },
  club_des_champions: { label: 'Club des Champions', icon: 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z' },
  group: { label: 'Coaching de groupe', icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z' },
  corporate_wellness: { label: 'Bien-être entreprise', icon: 'M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75z' },
  teambuilding: { label: 'Teambuilding', icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21' },
  other: { label: 'Autre demande', icon: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.02M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z' },
}

// Multi-étapes
const step = ref(1)

function goToStep(n) {
  step.value = n
  window.scrollTo({ top: 180, behavior: 'smooth' })
}

function validateStep1() {
  goToStep(2)
}

function validateStep2() {
  const required = ['first_name', 'last_name', 'email', 'phone']
  for (const field of required) {
    if (!String(form[field]).trim()) return
  }
  goToStep(3)
}

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  company: '',
  role: '',
  city: '',
  type: props.selectedType ?? 'individual',
  programme_id: props.selectedProgramme ?? '',
  preferred_date: '',
  preferred_time: '',
  message: '',
})

function submit() {
  form.post(route('sessions.store'), {
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <Head title="Réserver une Session — Consultation découverte gratuite">
    <meta name="description" content="Réservez votre consultation découverte gratuite de 30 minutes avec Stéphane Ouattara : coaching individuel, Club des Champions, coaching de groupe, bien-être entreprise, teambuilding." />
  </Head>
  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary-gradient py-16">
      <div class="pointer-events-none absolute -right-16 top-0 h-56 w-56 rounded-full bg-accent/30 blur-3xl animate-float" aria-hidden="true" />
      <div class="container-site relative text-center animate-slide-up">
        <span class="badge badge-outline mb-4 !border-white/40 !bg-white/10 !text-white/90">Réservation</span>
        <h1 class="font-sans text-3xl font-bold tracking-tight text-white sm:text-4xl">Réserver une session</h1>
        <p class="mx-auto mt-3 max-w-xl text-white/85">
          Votre transformation personnelle commence par une conversation authentique.
          Consultation découverte gratuite de 30 minutes — nous confirmons sous 48h.
        </p>
      </div>
    </section>

    <section class="container-site max-w-[600px] py-12">
      <!-- Indicateur multi-étapes -->
      <nav aria-label="Étapes de réservation" class="mb-10 flex items-center justify-center gap-0">
        <template v-for="(label, i) in ['Service & infos', 'Disponibilités', 'Confirmation']" :key="i">
          <div v-if="i > 0" class="mx-2 h-px flex-1" :class="step > i ? 'bg-success' : 'bg-line'" />
          <button type="button" @click="step > i + 1 && goToStep(i + 1)"
            :aria-current="step === i + 1 ? 'step' : undefined"
            :disabled="step <= i + 1" class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full text-sm font-semibold transition-all duration-250 ease-bounce"
              :class="step > i + 1 ? 'bg-success text-white'
                : step === i + 1 ? 'bg-primary text-white shadow-md scale-110'
                : 'bg-line text-ink-muted'">
              <svg v-if="step > i + 1" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
              <template v-else>{{ i + 1 }}</template>
            </span>
            <span class="hidden text-xs font-medium sm:block" :class="step >= i + 1 ? 'text-ink' : 'text-ink-muted'">{{ label }}</span>
          </button>
        </template>
      </nav>

      <form class="card !p-8 shadow-lg animate-scale-in" @submit.prevent="submit">
        <!-- ÉTAPE 1 -->
        <Transition enter-active-class="transition duration-250 ease-entrance" enter-from-class="translate-x-4 opacity-0" mode="out-in">
          <fieldset v-if="step === 1" key="s1" class="space-y-6">
            <legend class="eyebrow">Type de session</legend>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
              <label v-for="(config, t) in serviceLabels" :key="t"
                v-show="types.includes(t)"
                class="cursor-pointer rounded-md border-2 p-4 text-center transition-all duration-150 ease-standard"
                :class="form.type === t ? 'border-primary bg-primary/5 shadow-focus-ring scale-[1.02]' : 'border-line hover:border-primary/40 hover:scale-[1.01]'">
                <input v-model="form.type" type="radio" name="type" :value="t" class="sr-only" />
                <svg class="mx-auto h-6 w-6 transition-colors" :class="form.type === t ? 'text-accent' : 'text-ink-muted'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="config.icon" /></svg>
                <span class="mt-2 block text-xs font-semibold leading-snug" :class="form.type === t ? 'text-primary' : 'text-ink-secondary'">{{ config.label }}</span>
              </label>
            </div>
            <InputError :message="form.errors.type" />

            <div class="grid gap-4 sm:grid-cols-2">
              <div>
                <InputLabel for="first_name" value="Prénom *" />
                <TextInput id="first_name" v-model="form.first_name" type="text" required autocomplete="given-name" />
                <InputError :message="form.errors.first_name" class="mt-2" />
              </div>
              <div>
                <InputLabel for="last_name" value="Nom *" />
                <TextInput id="last_name" v-model="form.last_name" type="text" required autocomplete="family-name" />
                <InputError :message="form.errors.last_name" class="mt-2" />
              </div>
              <div>
                <InputLabel for="email" value="Email *" />
                <TextInput id="email" v-model="form.email" type="email" required autocomplete="email" />
                <InputError :message="form.errors.email" class="mt-2" />
              </div>
              <div>
                <InputLabel for="phone" value="Téléphone *" />
                <TextInput id="phone" v-model="form.phone" type="tel" placeholder="+225 ..." required autocomplete="tel" />
                <InputError :message="form.errors.phone" class="mt-2" />
              </div>
              <div>
                <InputLabel for="company" value="Entreprise (si applicable)" />
                <TextInput id="company" v-model="form.company" type="text" autocomplete="organization" />
                <InputError :message="form.errors.company" class="mt-2" />
              </div>
              <div>
                <InputLabel for="role" value="Fonction / Poste (si applicable)" />
                <TextInput id="role" v-model="form.role" type="text" autocomplete="organization-title" />
                <InputError :message="form.errors.role" class="mt-2" />
              </div>
            </div>

            <div class="flex justify-end border-t border-line/50 pt-5">
              <button type="button" class="btn-primary" @click="validateStep1">Continuer →</button>
            </div>
          </fieldset>

          <!-- ÉTAPE 2 -->
          <fieldset v-else-if="step === 2" key="s2" class="space-y-6">
            <legend class="eyebrow">Programme & disponibilités</legend>
            <div>
              <InputLabel for="programme_id" value="Programme souhaité (optionnel)" />
              <select id="programme_id" v-model="form.programme_id"
                class="w-full rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0">
                <option value="">— Session personnalisée —</option>
                <option v-for="programme in programmes" :key="programme.id" :value="programme.id">
                  {{ programme.title }} (dès le {{ new Date(programme.start_date).toLocaleDateString('fr-FR') }})
                </option>
              </select>
              <InputError :message="form.errors.programme_id" class="mt-2" />
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
              <div>
                <InputLabel for="preferred_date" value="Date souhaitée *" />
                <TextInput id="preferred_date" v-model="form.preferred_date" type="date"
                  :min="new Date().toISOString().slice(0, 10)" required />
                <InputError :message="form.errors.preferred_date" class="mt-2" />
              </div>
              <div>
                <InputLabel for="preferred_time" value="Heure préférée" />
                <select id="preferred_time" v-model="form.preferred_time"
                  class="w-full rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0">
                  <option value="">— Indifférent —</option>
                  <option v-for="slot in ['09:00','10:00','11:00','14:00','15:00','16:00','17:00']" :key="slot" :value="slot">{{ slot }}</option>
                </select>
                <InputError :message="form.errors.preferred_time" class="mt-2" />
              </div>
            </div>
            <div>
              <InputLabel for="message" value="Vos objectifs / message" />
              <textarea id="message" v-model="form.message" rows="4"
                placeholder="Décrivez brièvement votre besoin ou vos attentes…"
                class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
              <InputError :message="form.errors.message" class="mt-2" />
            </div>

            <div class="flex justify-between border-t border-line/50 pt-5">
              <button type="button" class="btn-secondary" @click="goToStep(1)">← Retour</button>
              <button type="button" class="btn-primary" @click="validateStep2">Voir le récapitulatif →</button>
            </div>
          </fieldset>

          <!-- ÉTAPE 3 -->
          <fieldset v-else key="s3" class="space-y-6">
            <legend class="eyebrow">Récapitulatif</legend>
            <dl class="space-y-3 rounded-md bg-surface-hover p-5 text-sm">
              <div class="flex justify-between gap-4">
                <dt class="text-ink-muted">Type</dt><dd class="font-semibold text-ink">{{ serviceLabels[form.type]?.label }}</dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-ink-muted">Participant</dt><dd class="font-semibold text-ink">{{ form.first_name }} {{ form.last_name }}</dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-ink-muted">Contact</dt><dd class="font-semibold text-ink">{{ form.email }} · {{ form.phone }}</dd>
              </div>
              <div v-if="form.company || form.role" class="flex justify-between gap-4">
                <dt class="text-ink-muted">Entreprise</dt>
                <dd class="text-right font-semibold text-ink">{{ [form.company, form.role].filter(Boolean).join(' — ') }}</dd>
              </div>
              <div v-if="form.programme_id" class="flex justify-between gap-4">
                <dt class="text-ink-muted">Programme</dt>
                <dd class="font-semibold text-ink">{{ programmes.find(p => p.id == form.programme_id)?.title }}</dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-ink-muted">Date</dt>
                <dd class="font-semibold text-ink">
                  {{ new Date(form.preferred_date).toLocaleDateString('fr-FR') }} {{ form.preferred_time ? `à ${form.preferred_time}` : '' }}
                </dd>
              </div>
            </dl>

            <p class="flex items-start gap-2 rounded-md bg-info/5 p-3 text-xs leading-relaxed text-ink-secondary">
              <svg class="mt-0.5 h-4 w-4 shrink-0 text-info" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.02M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
              Un email de confirmation vous sera envoyé immédiatement. En tant que Coach certifié FranklinCovey,
              je vous propose d'abord une consultation découverte gratuite de 30 minutes pour définir ensemble
              le meilleur parcours d'accompagnement.
            </p>

            <div class="flex justify-between border-t border-line/50 pt-5">
              <button type="button" class="btn-secondary" @click="goToStep(2)">← Modifier</button>
              <button type="submit" :disabled="form.processing" class="btn-accent min-w-[180px]">
                <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
                <span>{{ form.processing ? 'Envoi…' : 'Confirmer la réservation' }}</span>
              </button>
            </div>
          </fieldset>
        </Transition>
      </form>
    </section>
  </PublicLayout>
</template>
