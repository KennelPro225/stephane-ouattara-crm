<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  message: '',
})

function submit() {
  form.post(route('contact.store'), { onSuccess: () => form.reset('message') })
}
</script>

<template>
  <form class="space-y-5" @submit.prevent="submit">
    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <InputLabel for="cf-first" value="Prénom *" />
        <TextInput id="cf-first" v-model="form.first_name" type="text" required autocomplete="given-name" />
        <InputError :message="form.errors.first_name" class="mt-2" />
      </div>
      <div>
        <InputLabel for="cf-last" value="Nom *" />
        <TextInput id="cf-last" v-model="form.last_name" type="text" required autocomplete="family-name" />
        <InputError :message="form.errors.last_name" class="mt-2" />
      </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <InputLabel for="cf-email" value="Email *" />
        <TextInput id="cf-email" v-model="form.email" type="email" required autocomplete="email" />
        <InputError :message="form.errors.email" class="mt-2" />
      </div>
      <div>
        <InputLabel for="cf-phone" value="Téléphone *" />
        <TextInput id="cf-phone" v-model="form.phone" type="tel" placeholder="+225 ..." required autocomplete="tel" />
        <InputError :message="form.errors.phone" class="mt-2" />
      </div>
    </div>
    <div>
      <InputLabel for="cf-message" value="Votre message *" />
      <textarea id="cf-message" v-model="form.message" rows="4"
        class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 ease-standard placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"
        required></textarea>
      <InputError :message="form.errors.message" class="mt-2" />
    </div>
    <button type="submit" :disabled="form.processing" class="btn-primary w-full sm:w-auto">
      <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
      </svg>
      <span>{{ form.processing ? 'Envoi en cours…' : 'Envoyer le message' }}</span>
    </button>
  </form>
</template>
