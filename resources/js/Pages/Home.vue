<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PlaceholderMedia from '@/Components/PlaceholderMedia.vue'
import CountUp from '@/Components/CountUp.vue'
import { HUES, HUES_SOFT, audienceHue, formatDate } from '@/constants'

const props = defineProps({
  flagship: Array,
  testimonials: Array,
  gallery: Array,
  heroStats: Array,
})

const page = usePage()
const content = computed(() => page.props.content)

const credentials = [
  'Master en management des organisations',
  'Coach certifié FranklinCovey',
  'Alumni du programme IVLP du gouvernement américain',
  'Diplômé du programme ASPI de Stanford',
]
const engagements = [
  'Manager Business Developer chez Trand Advertising — Groupe Édit Africa',
  "Fondateur de l'organisation Bénévoles du Monde",
  'Initiateur des programmes Compétence 360, La Connexion, Club des Champions',
  'Créateur du cabinet OMSY EDUC — formations annuelles pour des milliers d’élèves, parents et professionnels',
]
const values = [
  { num: '01', title: 'Authenticité', text: "Je refuse de jouer un rôle. Ce que je vis, je le partage. Mes forces comme mes failles m'ont construit. Être vrai, aligné, sans masque : c'est ainsi que j'inspire." },
  { num: '02', title: "Foi en l'humain", text: "Je crois profondément en l'humain. Chaque personne porte un potentiel, même s'il est enfoui. Je vois des étincelles où d'autres voient des limites." },
  { num: '03', title: 'Excellence', text: "L'excellence est un choix quotidien. Je me forme, je progresse, je ne me contente jamais du minimum. Pas pour paraître, mais pour servir." },
  { num: '04', title: 'Transmission', text: "Tout ce que j'apprends, je le transmets. La transmission est pour moi un devoir, pas une option." },
]
const services = [
  { num: '01', title: 'Coaching individuel', tag: 'Sur mesure', desc: "Un espace sur mesure pour se découvrir, se redéfinir, s'élever. Chaque personne est unique, c'est pourquoi j'adapte l'accompagnement selon l'âge, les enjeux, et le moment de vie.", items: ['Adolescents : avec le Club des Champions (Lion, Meute, Aigle), ils développent confiance, identité et leadership.', 'Parents / Adultes : un accompagnement sur mesure pour retrouver équilibre, clarté et épanouissement.'] },
  { num: '02', title: 'Coaching de groupe', tag: 'Collectif', desc: "Parce qu'on avance souvent plus vite ensemble. Ces espaces collectifs permettent de grandir à travers l'interaction, la pratique et la dynamique de groupe.", items: ['Ateliers jeunes : des espaces dynamiques et bienveillants pour révéler le potentiel des jeunes.', 'Théâtre éducatif pour ados : exprimer, ressentir, apprendre autrement.', 'Groupes restreints : partages puissants et progression collective.'] },
  { num: '03', title: 'Bien-être en entreprise', tag: "Replacer l'humain au cœur de la performance", desc: "Parce que performance et bien-être ne sont pas incompatibles. J'interviens pour réintroduire l'humain, le calme et la clarté dans les environnements de travail.", items: ['Séances de respiration et relaxation guidée', 'Méditation active pour dirigeants', 'Coaching des managers vers un leadership bienveillant'] },
  { num: '04', title: 'Teambuilding & Leadership', tag: 'Renforcer les liens. Créer du sens.', desc: "Des expériences immersives pour souder les équipes, développer l'intelligence collective et faire émerger un leadership authentique.", items: ['Escape Game émotionnel : collaboration & expression créative', 'Jeux de rôle professionnels : résolution de conflits', "Débriefs avec coaching ciblé : ancrage des apprentissages"] },
]

const ti = ref(0)
let timer
onMounted(() => {
  timer = setInterval(() => { ti.value = (ti.value + 1) % props.testimonials.length }, 7000)
})
onBeforeUnmount(() => clearInterval(timer))
const current = computed(() => props.testimonials[ti.value % props.testimonials.length])
</script>

<template>
  <Head title="Accueil">
    <meta name="description" content="Coach certifié en développement personnel avec plus de 10 ans d'expérience. J'aide les ados, les leaders et les entreprises en Côte d'Ivoire à se reconnecter, à croire en eux et oser." />
  </Head>
  <PublicLayout>
    <main>
      <section class="container-site" style="padding-top:48px">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:32px;align-items:end">
          <div style="max-width:44ch;min-width:0">
            <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-accent-700);margin:0 0 24px">Coach certifié en développement personnel</p>
            <h1 style="font-size:clamp(28px,4.2vw,52px);margin:0 0 24px;overflow-wrap:break-word">{{ content.heroTitle }}</h1>
            <p style="font-size:15px;max-width:40ch;margin:0 0 28px;padding-left:14px;border-left:4px solid var(--c2)">{{ content.tagline }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:12px">
              <a href="#apropos" class="btn btn-secondary">Qui suis-je ?</a>
              <Link :href="route('reserver-une-session')" class="btn btn-primary">Réserver une session</Link>
            </div>
            <p class="text-muted" style="margin:36px 0 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase">↓ Scroll for more</p>
          </div>
          <PlaceholderMedia :src="content.heroImage" aspect="4/5" label="portrait pro — stéphane ouattara" />
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0;border-top:2px solid var(--color-divider);margin-top:48px">
          <div v-for="(s, i) in heroStats" :key="s.l" v-reveal="{ delay: i * 90 }" :style="{ padding: '20px 16px 20px 0', borderRight: '1px solid var(--color-divider)', borderTop: `4px solid ${HUES[i % 4]}`, marginTop: '-2px' }">
            <p style="font-family:var(--font-heading);font-weight:800;font-size:28px;margin:0"><CountUp :value="s.n" /></p>
            <p class="text-muted" style="margin:0;font-size:11px;letter-spacing:0.08em;text-transform:uppercase">{{ s.l }}</p>
          </div>
        </div>
      </section>

      <section id="apropos" class="container-site" style="padding-top:64px">
        <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--c3);margin:0 0 20px">02 — Qui suis-je ?</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:40px">
          <div v-reveal>
            <h2 style="font-size:clamp(26px,4.4vw,42px);margin:0 0 12px">L'histoire d'un appel, le parcours d'un bâtisseur</h2>
            <p style="font-size:15px;color:var(--color-neutral-700);margin:0 0 24px">Quelle est l'histoire derrière mon parcours ?</p>
            <p>Depuis plus de dix ans, j'accompagne celles et ceux qui cherchent à se révéler. Ce parcours a commencé en Côte d'Ivoire, dans un environnement où le potentiel est partout mais où les cadres pour le faire grandir manquent souvent.</p>
            <p>J'ai construit ma pratique auprès des adolescents, des professionnels en transition et des leaders qui veulent aligner performance et sens. Chaque accompagnement part du même principe : la transformation personnelle précède la transformation collective.</p>
            <p>Ce que j'ai traversé, appris et expérimenté — de la formation à l'incubation, de la stratégie à la communication d'influence — devient matière à transmettre.</p>
          </div>
          <div v-reveal="{ delay: 120 }" style="display:flex;flex-direction:column;gap:32px">
            <div>
              <h6 style="margin:0 0 12px">Diplômes &amp; certifications</h6>
              <p v-for="c in credentials" :key="c" style="margin:0;padding:12px 0;border-bottom:1px solid var(--color-divider);font-size:14px">{{ c }}</p>
            </div>
            <div>
              <h6 style="margin:0 0 12px">Engagements professionnels</h6>
              <p v-for="e in engagements" :key="e" style="margin:0;padding:12px 0;border-bottom:1px solid var(--color-divider);font-size:14px">{{ e }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="container-site" style="padding-top:64px">
        <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--c2);margin:0 0 20px">03 — Mes valeurs</p>
        <h2 style="font-size:clamp(26px,4.4vw,42px);margin:0 0 32px">Mes valeurs</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:2px;background:var(--color-bg)">
          <div v-for="(v, i) in values" :key="v.num" v-reveal="{ delay: i * 80 }" :style="{ background: HUES_SOFT[i % 4], boxShadow: '0 0 0 1px var(--color-divider)', borderTop: `6px solid ${HUES[i % 4]}`, padding: '24px 20px', display: 'flex', flexDirection: 'column', gap: '12px' }">
            <span :style="{ fontFamily: 'var(--font-heading)', fontWeight: 800, fontSize: '13px', color: HUES[i % 4] }">{{ v.num }}</span>
            <h3 style="margin:0;font-size:22px">{{ v.title }}</h3>
            <p style="margin:0;font-size:13px;line-height:1.65;color:var(--color-neutral-800)">{{ v.text }}</p>
          </div>
        </div>
        <div style="border-top:2px solid var(--color-divider);margin-top:40px;padding-top:32px;display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:32px">
          <h3 style="margin:0;font-size:clamp(22px,3.4vw,32px);max-width:20ch">Pourquoi travailler avec moi ?</h3>
          <div>
            <p style="font-size:14px;line-height:1.75">Parce que je ne me contente pas d'accompagner, je m'implique. Depuis plus de 10 ans, je marche aux côtés de celles et ceux qui veulent se révéler, se repositionner, se dépasser.</p>
            <p style="font-size:14px;line-height:1.75">Je n'apporte pas que des outils ou des méthodes — j'apporte une présence, une écoute, une vision. Je ne suis pas là pour impressionner, je suis là pour élever.</p>
            <p style="font-size:14px;line-height:1.75">Je construis des ponts, je crée des cadres d'élévation, je transmets ce que j'ai appris. Et toujours avec un seul objectif : faire émerger la meilleure version de vous-même.</p>
          </div>
        </div>
      </section>

      <section class="container-site" style="padding-top:64px">
        <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--c4);margin:0 0 20px">04 — Nos réalisations</p>
        <h2 style="font-size:clamp(26px,4.4vw,42px);margin:0 0 8px">Nos Programmes Phares</h2>
        <p class="text-muted" style="margin:0 0 32px;font-size:14px">Découvrez nos programmes transformationnels et leurs impacts</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px">
          <article v-for="(f, i) in flagship" :key="f.id" v-reveal="{ delay: i * 100 }" class="edge-card" :style="{ border: '2px solid var(--color-text)', borderTop: `8px solid ${audienceHue(f.audience)}`, display: 'flex', flexDirection: 'column' }">
            <PlaceholderMedia :src="f.image_url" aspect="16/10" border="2px solid var(--color-text)" />
            <div style="padding:20px;display:flex;flex-direction:column;gap:12px;flex:1">
              <h3 style="margin:0;font-size:22px">{{ f.title }}</h3>
              <p style="margin:0;font-size:13px;line-height:1.65;flex:1">{{ f.description }}</p>
              <div style="display:flex;flex-wrap:wrap;gap:16px;border-top:1px solid var(--color-divider);padding-top:12px">
                <div>
                  <p :style="{ margin: 0, fontFamily: 'var(--font-heading)', fontWeight: 800, fontSize: '22px', color: audienceHue(f.audience) }">{{ f.max_participants - f.available_seats }}/{{ f.max_participants }}</p>
                  <p class="text-muted" style="margin:0;font-size:10px;letter-spacing:0.08em;text-transform:uppercase">Inscrits</p>
                </div>
                <div>
                  <p :style="{ margin: 0, fontFamily: 'var(--font-heading)', fontWeight: 800, fontSize: '22px', color: audienceHue(f.audience) }">{{ formatDate(f.start_date) }}</p>
                  <p class="text-muted" style="margin:0;font-size:10px;letter-spacing:0.08em;text-transform:uppercase">Début</p>
                </div>
              </div>
              <Link :href="route('programmes.show', f.slug)" class="btn btn-secondary btn-block">Voir les détails →</Link>
            </div>
          </article>
        </div>

        <div style="margin-top:48px">
          <h3 style="margin:0 0 6px;font-size:clamp(20px,3vw,28px)">Galerie de Réalisations</h3>
          <p class="text-muted" style="margin:0 0 20px;font-size:13px">Quelques moments forts de mes accompagnements</p>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:2px;background:var(--color-bg)">
            <figure v-for="(g, i) in gallery" :key="g.id" v-reveal="{ delay: (i % 3) * 90 }" style="background:var(--color-bg);box-shadow:0 0 0 1px var(--color-divider)">
              <PlaceholderMedia :src="g.image_url" aspect="4/3" :label="g.slot_label" />
              <figcaption :style="{ padding: '10px', color: 'var(--color-text)', fontSize: '13px', borderTop: `4px solid ${HUES[i % 4]}` }">
                <strong>{{ g.title }}</strong><br><span class="text-muted">{{ g.subtitle }}</span>
              </figcaption>
            </figure>
          </div>
        </div>
      </section>

      <section class="container-site" style="padding-top:64px">
        <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--c3);margin:0 0 20px">05 — Ce que je fais</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:32px;margin-bottom:40px">
          <h2 style="font-size:clamp(26px,4.4vw,42px);margin:0">Des services à fort impact humain</h2>
          <p style="font-size:14px;line-height:1.75;margin:0">J'accompagne les personnes, les équipes et les organisations dans leur transformation intérieure et structurelle. Mon approche allie écoute profonde, expériences concrètes et outils puissants.</p>
        </div>
        <div style="border-top:2px solid var(--color-divider)">
          <div v-for="(s, i) in services" :key="s.num" v-reveal="{ delay: i * 70 }" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;padding:28px 0;border-bottom:1px solid var(--color-divider)">
            <div :style="{ borderTop: `6px solid ${HUES[i % 4]}`, paddingTop: '12px' }">
              <span :style="{ fontFamily: 'var(--font-heading)', fontWeight: 800, fontSize: '13px', color: HUES[i % 4] }">{{ s.num }}</span>
              <h3 style="margin:8px 0 0;font-size:24px">{{ s.title }}</h3>
              <p class="text-muted" style="margin:8px 0 0;font-size:12px;letter-spacing:0.06em;text-transform:uppercase">{{ s.tag }}</p>
            </div>
            <div>
              <p style="margin:0 0 16px;font-size:14px;line-height:1.7">{{ s.desc }}</p>
              <div style="display:flex;flex-direction:column;gap:10px">
                <p v-for="it in s.items" :key="it" :style="{ margin: 0, fontSize: '13px', lineHeight: 1.6, paddingLeft: '14px', borderLeft: `3px solid ${HUES[i % 4]}` }">{{ it }}</p>
              </div>
            </div>
          </div>
        </div>
        <Link :href="route('programmes.index')" class="btn btn-secondary" style="margin-top:24px">Voir tous les programmes →</Link>
      </section>

      <section v-if="testimonials.length" class="container-site" style="padding-top:64px">
        <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-accent-700);margin:0 0 20px">06 — Témoignages de clients</p>
        <h2 style="font-size:clamp(26px,4.4vw,42px);margin:0 0 32px">Ce qu'ils disent de moi</h2>
        <div style="border-top:2px solid var(--color-text);border-bottom:2px solid var(--color-text);padding:32px 0;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;align-items:start;overflow:hidden;position:relative">
          <Transition name="slide-fade" mode="out-in">
            <blockquote :key="ti" style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:clamp(20px,3.2vw,30px);line-height:1.25">“{{ current.quote }}”</blockquote>
          </Transition>
          <Transition name="slide-fade" mode="out-in">
            <div :key="ti">
              <p style="margin:0;font-family:var(--font-heading);font-weight:800;font-size:16px">{{ current.name }}</p>
              <p class="text-muted" style="margin:2px 0 20px;font-size:13px">{{ current.role }}</p>
              <div style="display:flex;gap:8px;align-items:center">
                <button type="button" class="btn btn-secondary btn-icon" aria-label="Précédent" @click="ti = (ti - 1 + testimonials.length) % testimonials.length">←</button>
                <button type="button" class="btn btn-secondary btn-icon" aria-label="Suivant" @click="ti = (ti + 1) % testimonials.length">→</button>
                <span class="text-muted" style="font-size:12px;margin-left:8px">{{ (ti % testimonials.length) + 1 }} / {{ testimonials.length }}</span>
              </div>
            </div>
          </Transition>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:2px;background:var(--color-bg);margin-top:2px">
          <button v-for="(t, i) in testimonials" :key="t.id" type="button"
            :style="{ textAlign: 'left', background: 'var(--color-bg)', border: 0, borderTop: `4px solid ${HUES[i % 4]}`, boxShadow: '0 0 0 1px var(--color-divider)', cursor: 'pointer', padding: '16px', display: 'flex', flexDirection: 'column', gap: '8px', minHeight: '44px', fontFamily: 'var(--font-body)' }"
            @click="ti = i">
            <span style="font-family:var(--font-heading);font-weight:800;font-size:13px">{{ t.name }}</span>
            <span class="text-muted" style="font-size:11px">{{ t.role }}</span>
          </button>
        </div>
      </section>

      <section style="margin-top:64px;background:var(--color-accent);color:var(--color-bg)">
        <div class="container-site" style="padding:56px 20px">
          <h2 style="margin:0 0 16px;font-size:clamp(32px,7vw,64px);color:var(--color-bg)">{{ content.ctaTitle }}</h2>
          <p style="max-width:52ch;font-size:15px;line-height:1.7;margin:0 0 28px">{{ content.ctaText }}</p>
          <div style="display:flex;flex-wrap:wrap;gap:12px">
            <Link :href="route('reserver-une-session')" class="btn" style="background:var(--color-bg);color:var(--color-accent)">Réserver une session</Link>
            <Link :href="route('programmes.index')" class="btn" style="border:1px solid var(--color-bg);color:var(--color-bg)">Voir les programmes</Link>
          </div>
        </div>
      </section>
    </main>
  </PublicLayout>
</template>
