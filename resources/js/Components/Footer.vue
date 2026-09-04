<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const email = computed(() => page.props.settings?.contact_email ?? 'contact@stephane-ouattara.com')
const phone = computed(() => page.props.settings?.contact_phone ?? '+225 07 48 78 81 33')

const year = new Date().getFullYear()
</script>

<template>
  <footer class="border-t border-line/60 bg-surface">
    <div class="container-site grid gap-10 py-14 md:grid-cols-4">
      <!-- À propos -->
      <div class="md:col-span-2">
        <Link :href="route('home')" class="flex items-center gap-2.5">
          <span class="grid h-9 w-9 place-items-center rounded-md bg-primary-gradient font-sans text-xs font-bold text-white">SO</span>
          <span class="font-sans font-bold tracking-tight text-ink">Stéphane OUATTARA</span>
        </Link>
        <p class="mt-4 max-w-lg text-sm leading-relaxed text-ink-secondary">
          Stéphane OUATTARA est coach certifié en développement personnel, entrepreneur social et expert en
          autonomisation des jeunes. Fort de plus de 10 ans d'expérience, il accompagne jeunes, institutions et
          communautés vers l'impact durable. Diplômé de Stanford et du programme IVLP, il dirige aussi des
          stratégies 360° chez Trand Advertising. Fondateur des Bénévoles du Monde, il incarne un leadership au
          service de l'humain.
        </p>

        <!-- Réseaux sociaux -->
        <div class="mt-6 flex items-center gap-3">
          <a href="#" aria-label="Facebook"
            class="grid h-10 w-10 place-items-center rounded-full border border-line/60 text-ink-secondary transition-all duration-150 hover:-translate-y-0.5 hover:border-primary hover:text-primary hover:shadow-glow">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
          </a>
          <a href="#" aria-label="Instagram"
            class="grid h-10 w-10 place-items-center rounded-full border border-line/60 text-ink-secondary transition-all duration-150 hover:-translate-y-0.5 hover:border-accent hover:text-accent hover:shadow-glow">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
        </div>
      </div>

      <!-- Site links -->
      <nav aria-label="Liens de pied de page">
        <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-ink">Site</h3>
        <ul class="mt-4 space-y-2 text-sm">
          <li><Link :href="route('home')" class="text-ink-secondary transition-colors duration-150 hover:text-accent">Accueil</Link></li>
          <li><Link :href="route('programmes.index')" class="text-ink-secondary transition-colors duration-150 hover:text-accent">Programmes</Link></li>
          <li><Link :href="route('contact')" class="text-ink-secondary transition-colors duration-150 hover:text-accent">Contact</Link></li>
        </ul>
      </nav>

      <!-- Session + contact -->
      <div>
        <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-ink">Session</h3>
        <Link :href="route('reserver-une-session')" class="btn-primary mt-4 !px-5 !py-2.5 !text-xs">
          Réserver une session
        </Link>
        <ul class="mt-5 space-y-2 text-sm text-ink-secondary">
          <li>{{ email }}</li>
          <li>{{ phone }}</li>
          <li>Cabinet OMSY EDUC — Cocody, Abidjan</li>
        </ul>
      </div>
    </div>

    <div class="border-t border-line/40">
      <div class="container-site flex flex-col items-center justify-between gap-2 py-5 text-xs text-ink-muted sm:flex-row">
        <p>© Copyright {{ year }} Stephane Ouattara</p>
        <p>Développé par <span class="font-semibold text-ink-secondary">G. Kennel KASSI</span></p>
      </div>
    </div>
  </footer>
</template>
