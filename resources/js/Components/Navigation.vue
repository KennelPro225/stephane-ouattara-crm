<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ThemeToggle from '@/Components/ThemeToggle.vue'

const mobileOpen = ref(false)
const page = usePage()
const currentPath = () => new URL(page.url, window.location.origin).pathname

function scrollTo(id) {
  const el = document.getElementById(id)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
    mobileOpen.value = false
  } else {
    window.location = `/#${id}`
  }
}

const links = [
  { label: 'Accueil', href: route('home') },
  { label: 'Programmes', href: route('programmes.index') },
  { label: 'Contact', href: route('contact') },
]

function isActive(href) {
  return currentPath() === new URL(href, window.location.origin).pathname
}
</script>

<template>
  <header class="sticky top-0 z-40 border-b border-line/60 bg-surface/80 backdrop-blur-md">
    <div class="container-site flex h-16 items-center justify-between">
      <Link :href="route('home')" class="flex items-center gap-2.5" aria-label="Accueil — Stéphane Ouattara">
        <span class="grid h-10 w-10 place-items-center rounded-md bg-primary-gradient font-sans text-sm font-bold text-white shadow-sm">SO</span>
        <span class="hidden font-sans text-base font-bold tracking-tight text-ink sm:block">Stéphane Ouattara</span>
      </Link>

      <nav class="hidden items-center gap-1 md:flex" aria-label="Navigation principale">
        <Link
          v-for="link in links"
          :key="link.href"
          :href="link.href"
          class="relative px-3 py-2 text-sm font-medium transition-colors duration-150"
          :class="isActive(link.href) ? 'text-primary after:absolute after:inset-x-2 after:bottom-0 after:h-0.5 after:rounded-full after:bg-primary' : 'text-ink-secondary hover:text-ink'"
        >{{ link.label }}</Link>

        <!-- Ancres one-page -->
        <button v-if="isActive(route('home'))" type="button" @click="scrollTo('about')"
          class="px-3 py-2 text-sm font-medium text-ink-secondary transition-colors duration-150 hover:text-ink">À propos</button>
        <button v-if="isActive(route('home'))" type="button" @click="scrollTo('services')"
          class="px-3 py-2 text-sm font-medium text-ink-secondary transition-colors duration-150 hover:text-ink">Services</button>
        <button v-if="isActive(route('home'))" type="button" @click="scrollTo('realisations')"
          class="px-3 py-2 text-sm font-medium text-ink-secondary transition-colors duration-150 hover:text-ink">Réalisations</button>

        <div class="ml-4 flex items-center gap-2">
          <ThemeToggle />
          <Link v-if="page.props.auth?.user" :href="route('admin.dashboard')"
            class="btn-secondary !px-4 !py-2 !text-xs">Admin</Link>
          <Link :href="route('reserver-une-session')" class="btn-primary !px-5 !py-2.5 !text-xs">
            Réserver une session
          </Link>
        </div>
      </nav>

      <button class="grid h-10 w-10 place-items-center rounded-md text-ink-secondary transition hover:bg-surface-hover md:hidden" aria-label="Menu" @click="mobileOpen = !mobileOpen">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path v-if="!mobileOpen" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
          <path v-else stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <Transition enter-active-class="transition duration-250 ease-entrance" enter-from-class="-translate-y-2 opacity-0">
      <nav v-if="mobileOpen" class="border-t border-line/60 bg-surface px-4 py-4 md:hidden" aria-label="Navigation mobile">
        <Link v-for="link in links" :key="link.href" :href="link.href"
          class="block rounded-md px-3 py-3 text-sm font-medium transition-colors hover:bg-surface-hover"
          :class="isActive(link.href) ? 'text-primary' : 'text-ink-secondary'"
          @click="mobileOpen = false">{{ link.label }}</Link>
        <div class="mt-4 flex items-center gap-3">
          <ThemeToggle />
          <Link :href="route('reserver-une-session')" @click="mobileOpen = false" class="btn-primary flex-1 !py-3">
            Réserver une session
          </Link>
        </div>
      </nav>
    </Transition>
  </header>
</template>
