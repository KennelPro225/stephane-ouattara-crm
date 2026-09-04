<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { HUES } from '@/constants'

defineProps({
  testimonials: Array,
})

const page = usePage()
const content = computed(() => page.props.content)

const contactPrograms = ['Club des Champions pour les adolescents', 'Coaching individuel pour adultes et parents', 'Ateliers de groupe et théâtre éducatif', 'Accompagnement en entreprise (teambuilding, leadership)']
const blocks = computed(() => [
  { title: 'Contact', l1: content.value.email, l2: content.value.phone },
  { title: 'Horaires de disponibilité', l1: content.value.hours, l2: content.value.hours2 },
  { title: 'Localisation & interventions', l1: content.value.address, l2: content.value.coverage },
])
</script>

<template>
  <Head title="Contact" />
  <PublicLayout>
    <main class="container-site" style="padding-top:40px">
      <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-accent-700);margin:0 0 16px">Contact</p>
      <h1 style="font-size:clamp(30px,6vw,52px);margin:0 0 20px;max-width:24ch">Ensemble, créons votre chemin vers l'épanouissement</h1>
      <p style="max-width:64ch;font-size:15px;line-height:1.75;margin:0 0 32px">Que vous soyez un jeune en quête d'identité, un parent souhaitant mieux communiquer avec votre adolescent, ou un dirigeant cherchant à développer un leadership authentique, chaque parcours est unique et mérite une attention personnalisée.</p>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:40px;border-top:2px solid var(--color-divider);padding-top:32px">
        <div>
          <h2 style="margin:0 0 12px;font-size:28px">Réserver une session</h2>
          <p style="font-size:14px;line-height:1.75">Votre transformation personnelle commence par une conversation authentique. En tant que Coach certifié FranklinCovey et Alumni IVLP, je vous propose une consultation découverte gratuite de 30 minutes.</p>
          <div style="display:flex;flex-direction:column;gap:10px;margin:20px 0 24px">
            <p v-for="c in contactPrograms" :key="c" style="margin:0;font-size:13px;padding-left:14px;border-left:2px solid var(--color-accent)">{{ c }}</p>
          </div>
          <Link :href="route('reserver-une-session')" class="btn btn-primary">Réserver votre consultation gratuite</Link>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:2px;background:var(--color-bg);align-content:start">
          <div v-for="b in blocks" :key="b.title" :style="{ background: 'var(--color-bg)', boxShadow: '0 0 0 1px var(--color-divider)', borderTop: `5px solid ${HUES[blocks.indexOf(b) % 4]}`, padding: '20px' }">
            <h6 style="margin:0 0 10px">{{ b.title }}</h6>
            <p style="margin:0;font-size:13px;line-height:1.8">{{ b.l1 }}<br>{{ b.l2 }}</p>
          </div>
          <div style="background:var(--color-bg);box-shadow:0 0 0 1px var(--color-divider);padding:20px">
            <h6 style="margin:0 0 10px">Réseaux sociaux</h6>
            <div style="display:flex;gap:8px">
              <a href="#" class="btn btn-secondary" style="padding:0 14px">Facebook</a>
              <a href="#" class="btn btn-secondary" style="padding:0 14px">Instagram</a>
            </div>
          </div>
        </div>
      </div>

      <h2 style="margin:48px 0 24px;font-size:clamp(24px,4vw,36px)">Témoignages de clients</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2px;background:var(--color-bg)">
        <div v-for="(t, i) in testimonials" :key="t.id" :style="{ background: 'var(--color-bg)', boxShadow: '0 0 0 1px var(--color-divider)', borderTop: `4px solid ${HUES[i % 4]}`, padding: '20px', display: 'flex', flexDirection: 'column', gap: '12px' }">
          <p style="margin:0;font-size:13px;line-height:1.7;flex:1">“{{ t.quote }}”</p>
          <div style="border-top:1px solid var(--color-divider);padding-top:10px">
            <p style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:13px">{{ t.name }}</p>
            <p class="text-muted" style="margin:0;font-size:11px">{{ t.role }}</p>
          </div>
        </div>
      </div>
    </main>
  </PublicLayout>
</template>
