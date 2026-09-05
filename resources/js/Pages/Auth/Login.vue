<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({ email: '', password: '', remember: false })

function submit () {
  form.post(route('login'), { onFinish: () => form.reset('password') })
}
</script>

<template>
  <Head title="Connexion" />
  <div style="min-height:100vh;display:grid;place-items:center;background:var(--color-bg);padding:20px">
    <form style="width:min(400px,100%);border:2px solid var(--color-text);padding:32px" @submit.prevent="submit">
      <p style="font-family:var(--font-heading);font-weight:800;font-size:15px;letter-spacing:0.06em;text-transform:uppercase;margin:0 0 4px">Stéphane Ouattara</p>
      <h1 style="font-size:26px;margin:0 0 24px">Connexion CRM</h1>

      <div class="field" style="margin-bottom:16px">
        <label for="email">Email</label>
        <input id="email" v-model="form.email" type="email" class="input" required autofocus autocomplete="username">
        <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
      </div>
      <div class="field" style="margin-bottom:20px">
        <label for="password">Mot de passe</label>
        <input id="password" v-model="form.password" type="password" class="input" required autocomplete="current-password">
        <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
      </div>
      <label class="radio" style="margin-bottom:20px">
        <input v-model="form.remember" type="checkbox"><span class="dot" style="border-radius:2px"></span>Se souvenir de moi
      </label>
      <button type="submit" class="btn btn-primary btn-block" :class="{ 'btn-loading': form.processing }" :disabled="form.processing">Se connecter</button>
      <a href="/" style="display:block;margin-top:20px;font-size:13px;text-align:center">← Retour au site</a>
    </form>
  </div>
</template>
