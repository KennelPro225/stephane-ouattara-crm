<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue'
import RouteProgress from '@/Components/RouteProgress.vue'

const page = usePage()
const drawer = ref(false)

const navLinks = [
  { label: 'Accueil', href: () => route('home') },
  { label: 'Programmes', href: () => route('programmes.index') },
  { label: 'Contact', href: () => route('contact') },
]

function closeDrawer () {
  drawer.value = false
}
</script>

<template>
  <div style="min-height:100vh;font-family:var(--font-body)">
    <RouteProgress />
    <header style="position:sticky;top:0;z-index:30;background:var(--color-bg);border-bottom:2px solid var(--color-divider)">
      <div class="container-site" style="display:flex;align-items:center;gap:20px;padding:14px 20px">
        <Link :href="route('home')" style="text-decoration:none;color:inherit;font-family:var(--font-heading);font-weight:800;font-size:15px;letter-spacing:0.06em;text-transform:uppercase;margin-right:auto;line-height:1.1">
          Stéphane<br>Ouattara
        </Link>

        <nav class="nav-wide" style="display:flex;align-items:center;gap:26px">
          <Link v-for="l in navLinks" :key="l.label" :href="l.href()"
            style="text-decoration:none;color:inherit;font-size:13px;letter-spacing:0.06em;text-transform:uppercase;font-family:var(--font-heading);font-weight:600">
            {{ l.label }}
          </Link>
          <Link v-if="page.props.auth.user" :href="route('admin.dashboard')"
            style="text-decoration:none;color:inherit;font-size:13px;letter-spacing:0.06em;text-transform:uppercase;font-family:var(--font-heading);font-weight:600">
            CRM admin
          </Link>
          <!-- Aucun lien de connexion pour les visiteurs : le back-office ne
               s'annonce pas publiquement, il reste joignable via /login. -->
          <Link :href="route('reserver-une-session')" class="btn btn-primary">Réserver une session</Link>
        </nav>

        <button class="nav-narrow btn-icon" style="display:none;flex-direction:column;justify-content:center;gap:5px;background:transparent;border:1px solid var(--color-divider);cursor:pointer" aria-label="Menu" @click="drawer = true">
          <span style="display:block;height:2px;background:var(--color-text)"></span>
          <span style="display:block;height:2px;background:var(--color-text)"></span>
          <span style="display:block;height:2px;background:var(--color-text)"></span>
        </button>
      </div>
    </header>

    <Transition name="fade">
    <div v-if="drawer" style="position:fixed;inset:0;z-index:40;display:flex;justify-content:flex-end;background:color-mix(in srgb, var(--color-neutral-900) 55%, transparent)" @click.self="closeDrawer">
      <Transition name="drawer" appear>
      <aside style="width:min(340px,86vw);background:var(--color-bg);border-left:2px solid var(--color-text);padding:20px;display:flex;flex-direction:column;gap:4px">
        <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:16px;border-bottom:2px solid var(--color-divider)">
          <span class="text-muted" style="font-size:11px;letter-spacing:0.12em;text-transform:uppercase">Menu</span>
          <button class="btn-icon" style="background:transparent;border:1px solid var(--color-divider);cursor:pointer;font-size:18px;font-family:var(--font-heading)" aria-label="Fermer" @click="closeDrawer">×</button>
        </div>
        <Link v-for="(l, i) in navLinks" :key="l.label" :href="l.href()" @click="closeDrawer"
          style="text-decoration:none;color:inherit;font-family:var(--font-heading);font-weight:800;font-size:24px;padding:14px 0;border-bottom:1px solid var(--color-divider);display:flex;align-items:baseline;gap:12px;min-height:44px">
          <span style="font-size:11px;font-weight:400;color:var(--color-accent)">0{{ i + 1 }}</span>{{ l.label }}
        </Link>
        <Link v-if="page.props.auth.user" :href="route('admin.dashboard')" @click="closeDrawer"
          style="text-decoration:none;color:inherit;font-family:var(--font-heading);font-weight:800;font-size:24px;padding:14px 0;border-bottom:1px solid var(--color-divider);display:flex;align-items:baseline;gap:12px;min-height:44px">
          <span style="font-size:11px;font-weight:400;color:var(--color-accent)">0{{ navLinks.length + 1 }}</span>CRM admin
        </Link>
        <Link :href="route('reserver-une-session')" class="btn btn-primary btn-block" style="margin-top:12px" @click="closeDrawer">Réserver une session</Link>
        <p class="text-muted" style="margin-top:auto;font-size:11px;line-height:1.6">Cabinet OMSY EDUC — Cocody, Abidjan<br>{{ page.props.content.phone }}</p>
      </aside>
      </Transition>
    </div>
    </Transition>

    <slot />

    <footer style="margin-top:64px;border-top:2px solid var(--color-text);background:var(--color-bg)">
      <div class="container-site" style="padding:40px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:32px">
        <div>
          <p style="font-family:var(--font-heading);font-weight:800;font-size:15px;letter-spacing:0.06em;text-transform:uppercase;margin:0 0 16px">Stéphane Ouattara</p>
          <p style="margin:0;font-size:13px;line-height:1.7;max-width:44ch">{{ page.props.content.bio }}</p>
        </div>
        <div>
          <h6 style="margin:0 0 12px">Le site</h6>
          <div style="display:flex;flex-direction:column">
            <Link v-for="l in navLinks" :key="l.label" :href="l.href()"
              style="text-decoration:none;color:inherit;font-size:13px;padding:10px 0;border-bottom:1px solid var(--color-divider);min-height:44px;display:flex;align-items:center">
              {{ l.label }}
            </Link>
          </div>
        </div>
        <div>
          <h6 style="margin:0 0 12px">Session</h6>
          <Link :href="route('reserver-une-session')" class="btn btn-primary" style="padding:0 18px">Réserver une session</Link>
          <h6 style="margin:28px 0 12px">Suivre</h6>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="#" class="btn btn-secondary" style="padding:0 14px">Facebook</a>
            <a href="#" class="btn btn-secondary" style="padding:0 14px">Instagram</a>
          </div>
        </div>
      </div>
      <div style="border-top:1px solid var(--color-divider)">
        <div class="container-site" style="padding:16px 20px;display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between">
          <span class="text-muted" style="font-size:11px">© Copyright Stephane Ouattara</span>
          <span class="text-muted" style="font-size:11px">{{ page.props.content.address }}</span>
        </div>
      </div>
    </footer>

    <Toast />
  </div>
</template>

<style scoped>
@media (max-width: 899px) {
  .nav-wide { display: none !important; }
  .nav-narrow { display: flex !important; }
}
</style>
