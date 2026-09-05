<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue'
import RouteProgress from '@/Components/RouteProgress.vue'

const page = usePage()

const tabs = [
  { label: 'Tableau de bord', href: () => route('admin.dashboard') },
  { label: 'Programmes', href: () => route('admin.programmes.index') },
  { label: 'Clients', href: () => route('admin.customers.index') },
  { label: 'Réservations', href: () => route('admin.bookings.index') },
  { label: 'Contenus', href: () => route('admin.content.index') },
]

function isActive (href) {
  return page.url.startsWith(new URL(href, window.location.origin).pathname)
}
</script>

<template>
  <div style="min-height:100vh;font-family:var(--font-body)">
    <RouteProgress />
    <main class="crm-grid" style="display:grid;align-items:start">
      <aside style="border-right:2px solid var(--color-divider);padding:24px 20px">
        <Link :href="route('home')" style="text-decoration:none;color:inherit;font-family:var(--font-heading);font-weight:800;font-size:14px;letter-spacing:0.06em;text-transform:uppercase;display:block;margin-bottom:20px">
          Stéphane<br>Ouattara
        </Link>
        <p class="text-muted" style="margin:0 0 16px;font-size:10px;letter-spacing:0.12em;text-transform:uppercase">Administration</p>
        <div style="display:flex;flex-direction:column">
          <Link v-for="t in tabs" :key="t.label" :href="t.href()"
            style="text-align:left;min-height:44px;padding:10px 12px;border:0;border-bottom:1px solid var(--color-divider);cursor:pointer;font-family:var(--font-heading);font-weight:600;font-size:14px;text-decoration:none;display:flex;align-items:center;transition:background-color 0.15s ease,color 0.15s ease"
            :style="{ background: isActive(t.href()) ? 'var(--color-accent)' : 'transparent', color: isActive(t.href()) ? 'var(--color-bg)' : 'var(--color-text)' }">
            {{ t.label }}
          </Link>
        </div>
        <p class="text-muted" style="margin:24px 0 0;font-size:11px;line-height:1.6">Connecté comme<br><strong style="color:var(--color-text)">{{ page.props.auth.user.email }}</strong></p>
        <Link :href="route('logout')" method="post" as="button" class="btn btn-secondary btn-block" style="margin-top:16px">Déconnexion</Link>
      </aside>
      <div style="padding:24px 20px;min-width:0">
        <slot />
      </div>
    </main>
    <Toast />
  </div>
</template>

<style scoped>
.crm-grid { grid-template-columns: minmax(0, 1fr); }
@media (min-width: 900px) {
  .crm-grid { grid-template-columns: 260px minmax(0, 1fr); }
}
</style>
