<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PlaceholderMedia from '@/Components/PlaceholderMedia.vue'
import { AUDIENCE_LABELS, TYPE_LABELS, audienceHue, audienceSoft, formatDate } from '@/constants'

const props = defineProps({
  programme: Object,
  testimonials: Array,
  gallery: Array,
})

const page = usePage()

const bar = computed(() => audienceHue(props.programme.audience))
const tagBg = computed(() => audienceSoft(props.programme.audience))

const forWhom = computed(() => {
  const p = props.programme
  if (p.audience === 'entreprises') return 'Équipes, managers et dirigeants qui veulent aligner performance et sens.'
  if (p.audience === 'adolescents') return `Adolescents de ${p.ages_label} en quête de confiance, d'identité et de repères.`
  return 'Adultes et parents à un tournant personnel ou professionnel.'
})

const benefits = ['Confiance et clarté sur ses propres ressources', 'Outils pratiques applicables dès la première semaine', 'Progression mesurée à chaque étape du parcours', 'Communauté de pairs et suivi post-programme']
const modules = [
  { n: '01', title: 'Diagnostic & objectifs', sub: 'Où en êtes-vous, où voulez-vous aller' },
  { n: '02', title: 'Fondations', sub: 'Confiance, posture, communication' },
  { n: '03', title: 'Mise en pratique', sub: 'Mises en situation et théâtre éducatif' },
  { n: '04', title: 'Ancrage', sub: 'Débrief coaché et plan d’action' },
]

const facts = computed(() => [
  { l: 'Public', v: props.programme.ages_label },
  { l: 'Début', v: formatDate(props.programme.start_date) },
  { l: 'Fin', v: formatDate(props.programme.end_date) },
  { l: 'Clôture', v: props.programme.registration_deadline ? formatDate(props.programme.registration_deadline) : 'Sans clôture' },
  { l: 'Participants max', v: String(props.programme.max_participants) },
])
</script>

<template>
  <Head :title="programme.title">
    <meta name="description" :content="programme.description.slice(0, 160)" />
  </Head>
  <PublicLayout>
    <main>
      <img v-if="programme.image_url" :src="programme.image_url" alt="" :style="{ width: '100%', height: 'clamp(200px,38vw,380px)', objectFit: 'cover', borderBottom: `8px solid ${bar}`, display: 'block' }">
      <div v-else class="grayscale placeholder-media" :style="{ height: 'clamp(200px,38vw,380px)', borderBottom: `8px solid ${bar}` }">
        <span>bannière programme</span>
      </div>
      <div class="container-site" style="padding-top:28px">
        <Link :href="route('programmes.index')" class="btn btn-ghost">← Tous les programmes</Link>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin:16px 0 12px">
          <span class="tag" :style="{ background: tagBg, color: bar }">{{ AUDIENCE_LABELS[programme.audience] }}</span>
          <span class="tag tag-neutral">{{ TYPE_LABELS[programme.type] }}</span>
          <span v-if="programme.level" class="tag tag-outline">{{ programme.level }}</span>
        </div>
        <h1 style="font-size:clamp(28px,5.4vw,48px);margin:0 0 24px;max-width:24ch">{{ programme.title }}</h1>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:0;border-top:2px solid var(--color-divider);border-bottom:2px solid var(--color-divider)">
          <div v-for="f in facts" :key="f.l" style="padding:16px 16px 16px 0;border-right:1px solid var(--color-divider)">
            <p class="text-muted" style="margin:0 0 4px;font-size:10px;letter-spacing:0.1em;text-transform:uppercase">{{ f.l }}</p>
            <p style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:16px">{{ f.v }}</p>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:40px;padding:32px 0">
          <div>
            <h2 style="font-size:26px;margin:0 0 12px">Le programme</h2>
            <p style="font-size:14px;line-height:1.75">{{ programme.description }} Le parcours combine séances collectives, mises en situation et suivi individuel. Chaque étape est pensée pour ancrer les apprentissages dans le quotidien des participants.</p>
            <h3 style="font-size:20px;margin:28px 0 12px">Objectifs &amp; bénéfices</h3>
            <div style="display:flex;flex-direction:column;gap:10px">
              <p v-for="b in benefits" :key="b" style="margin:0;font-size:13px;padding-left:14px;border-left:2px solid var(--color-accent)">{{ b }}</p>
            </div>
            <h3 style="font-size:20px;margin:28px 0 12px">À qui s'adresse ce programme</h3>
            <p style="font-size:14px;line-height:1.7">{{ forWhom }}</p>
            <h3 style="font-size:20px;margin:28px 0 12px">Modules</h3>
            <div style="border-top:1px solid var(--color-divider)">
              <div v-for="m in modules" :key="m.n" style="display:flex;gap:16px;padding:14px 0;border-bottom:1px solid var(--color-divider)">
                <span style="font-family:var(--font-heading);font-weight:800;font-size:13px;color:var(--color-accent);min-width:28px">{{ m.n }}</span>
                <div>
                  <p style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:15px">{{ m.title }}</p>
                  <p class="text-muted" style="margin:2px 0 0;font-size:13px">{{ m.sub }}</p>
                </div>
              </div>
            </div>
          </div>
          <aside style="display:flex;flex-direction:column;gap:24px">
            <div style="border:2px solid var(--color-text);padding:20px">
              <p class="text-muted" style="margin:0 0 4px;font-size:10px;letter-spacing:0.1em;text-transform:uppercase">Tarif</p>
              <p style="margin:0 0 4px;font-family:var(--font-heading);font-weight:800;font-size:32px">{{ programme.price_label }}</p>
              <p class="text-muted" style="margin:0 0 16px;font-size:12px">{{ programme.registration_deadline ? `Clôture des inscriptions : ${formatDate(programme.registration_deadline)}` : 'Entrée continue' }}</p>
              <Link v-if="programme.available_seats > 0" :href="route('reserver-une-session', { programme: programme.id })" class="btn btn-primary btn-block">Réserver cette session</Link>
              <button v-else type="button" class="btn btn-block" disabled style="background:var(--color-neutral-300)">Complet</button>
              <p class="text-muted" style="margin:12px 0 0;font-size:11px">{{ programme.available_seats }} places restantes sur {{ programme.max_participants }}</p>
            </div>
            <div>
              <h6 style="margin:0 0 12px">Le coach</h6>
              <PlaceholderMedia :src="page.props.content.coachImage" aspect="1/1" />
              <p style="margin:12px 0 0;font-family:var(--font-heading);font-weight:800;font-size:15px">Stéphane Ouattara</p>
              <p class="text-muted" style="margin:2px 0 8px;font-size:12px">Coach certifié FranklinCovey · Alumni IVLP · ASPI Stanford</p>
              <p style="margin:0;font-size:13px;line-height:1.65">Plus de 10 ans d'accompagnement des jeunes, des professionnels et des organisations en Côte d'Ivoire et à l'international.</p>
            </div>
            <div v-if="testimonials.length">
              <h6 style="margin:0 0 12px">Témoignages du programme</h6>
              <div style="display:flex;flex-direction:column;gap:2px;background:var(--color-bg)">
                <div v-for="t in testimonials" :key="t.id" style="background:var(--color-bg);box-shadow:0 0 0 1px var(--color-divider);padding:14px">
                  <p style="margin:0 0 8px;font-size:13px;line-height:1.6">“{{ t.quote }}”</p>
                  <p class="text-muted" style="margin:0;font-size:11px">{{ t.name }} — {{ t.role }}</p>
                </div>
              </div>
            </div>
          </aside>
        </div>

        <template v-if="gallery.length">
          <h3 style="font-size:20px;margin:0 0 12px">Galerie du programme</h3>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:2px;background:var(--color-bg);margin-bottom:40px">
            <PlaceholderMedia v-for="g in gallery" :key="g.id" :src="g.image_url" aspect="4/3" :label="g.slot_label" />
          </div>
        </template>
      </div>
    </main>
  </PublicLayout>
</template>
