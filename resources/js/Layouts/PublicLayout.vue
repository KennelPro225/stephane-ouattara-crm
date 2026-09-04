<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Navigation from '@/Components/Navigation.vue'
import Footer from '@/Components/Footer.vue'
import Toast from '@/Components/Toast.vue'
import ScrollProgress from '@/Components/ScrollProgress.vue'

const page = usePage()
</script>

<template>
  <div class="flex min-h-screen flex-col">
    <!-- Barre de progression de lecture -->
    <ScrollProgress />

    <!-- Lien d'évitement (accessibilité clavier) -->
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[70] focus:rounded-md focus:bg-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
      Aller au contenu
    </a>

    <Navigation />

    <!-- :key force la rejouabilité de l'animation à chaque navigation -->
    <main id="main" :key="page.url" class="page-enter flex-1">
      <slot />
    </main>

    <Footer />
    <Toast />
  </div>
</template>

<style scoped>
.page-enter {
  animation: pageIn 400ms cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes pageIn {
  from {
    opacity: 0;
    transform: translateY(14px);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
</style>
