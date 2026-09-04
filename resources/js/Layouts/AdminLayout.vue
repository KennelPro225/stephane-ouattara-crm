<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ThemeToggle from '@/Components/ThemeToggle.vue'
import Toast from '@/Components/Toast.vue'
import ScrollProgress from '@/Components/ScrollProgress.vue'

const sidebarOpen = ref(false)
const page = usePage()
const currentPath = () => new URL(page.url, window.location.origin).pathname

const links = [
  { label: 'Tableau de bord', href: route('admin.dashboard'), icon: 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75' },
  { label: 'Programmes', href: route('admin.programmes.index'), icon: 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5' },
  { label: 'Réservations', href: route('admin.sessions.index'), icon: 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5' },
  { label: 'Clients', href: route('admin.customers.index'), icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' },
  { label: 'Analytics', href: route('admin.analytics'), icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z' },
  { label: 'Contenu', href: route('admin.content'), icon: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' },
  { label: 'Comptes', href: route('admin.users.index'), icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z' },
]

function isActive(href) {
  const path = new URL(href, window.location.origin).pathname
  return currentPath() === path || (path !== '/admin/dashboard' && currentPath().startsWith(path))
}
</script>

<template>
  <div class="min-h-screen bg-cream">
    <ScrollProgress />
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[70] focus:rounded-md focus:bg-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
      Aller au contenu
    </a>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed inset-y-0 left-0 z-40 flex w-[300px] transform flex-col border-r border-line/60 bg-surface transition-transform duration-250 ease-smooth lg:translate-x-0" aria-label="Navigation admin">
      <div class="flex h-16 items-center gap-2.5 border-b border-line/60 px-6">
        <span class="grid h-9 w-9 place-items-center rounded-md bg-primary-gradient font-sans text-xs font-bold text-white">SO</span>
        <span class="font-sans font-bold tracking-tight text-ink">Admin CRM</span>
      </div>
      <nav class="flex-1 space-y-1 overflow-y-auto p-4">
        <Link v-for="link in links" :key="link.href" :href="link.href"
          class="group relative flex items-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium transition-all duration-150 ease-standard"
          :class="isActive(link.href)
            ? 'bg-primary-gradient text-white shadow-md'
            : 'text-ink-secondary hover:bg-surface-hover hover:text-ink'">
          <svg class="h-5 w-5 shrink-0 transition-transform duration-150 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" :d="link.icon" />
          </svg>
          {{ link.label }}
          <!-- Point de statut animé sur le lien actif -->
          <span v-if="isActive(link.href)" class="ml-auto h-2 w-2 rounded-full bg-success animate-pulse" aria-hidden="true" />
        </Link>
      </nav>
      <div class="border-t border-line/60 p-4">
        <Link :href="route('home')" class="btn-ghost w-full justify-start !text-accent">
          ← Retour au site
        </Link>
      </div>
    </aside>

    <!-- Overlay mobile -->
    <Transition enter-active-class="transition duration-200" enter-from-class="opacity-0" leave-active-class="transition duration-200" leave-to-class="opacity-0">
      <div v-if="sidebarOpen" class="fixed inset-0 z-30 bg-ink/40 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" />
    </Transition>

    <div class="lg:pl-[300px]">
      <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-line/60 bg-surface/80 px-4 backdrop-blur-md sm:px-6 lg:px-8">
        <button class="grid h-10 w-10 place-items-center rounded-md text-ink-secondary transition hover:bg-surface-hover lg:hidden" aria-label="Menu admin" @click="sidebarOpen = !sidebarOpen">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
          </svg>
        </button>
        <div />
        <div class="flex items-center gap-3">
          <ThemeToggle />
          <span v-if="page.props.auth?.user" class="hidden text-sm font-medium text-ink-secondary sm:block">{{ page.props.auth.user.name }}</span>
          <Link :href="route('logout')" method="post" as="button" class="btn-secondary !px-4 !py-2 !text-xs">
            Déconnexion
          </Link>
        </div>
      </header>

      <main id="main" class="animate-fade-in p-4 sm:p-6 lg:p-8">
        <slot />
      </main>
    </div>

    <Toast />
  </div>
</template>
