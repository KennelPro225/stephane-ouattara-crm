<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { HUES } from '@/constants'

const props = defineProps({
  settings: Object,
  testimonials: Array,
  gallery: Array,
})

const form = useForm({ ...props.settings })
function saveSettings () {
  form.put(route('admin.content.update'))
}

const testimonialForms = reactive(
  Object.fromEntries(props.testimonials.map((t) => [t.id, { name: t.name, role: t.role, quote: t.quote, featured: t.featured, saving: false }]))
)
function saveTestimonial (id) {
  testimonialForms[id].saving = true
  router.put(route('admin.testimonials.update', id), testimonialForms[id], {
    preserveScroll: true,
    onFinish: () => { testimonialForms[id].saving = false },
  })
}

const galleryForms = reactive(
  Object.fromEntries(props.gallery.map((g) => [g.id, { title: g.title, subtitle: g.subtitle, saving: false }]))
)
function saveGallery (id) {
  galleryForms[id].saving = true
  router.put(route('admin.gallery.update', id), galleryForms[id], {
    preserveScroll: true,
    onFinish: () => { galleryForms[id].saving = false },
  })
}

const fields = [
  { key: 'hero_title', label: 'Titre du hero', area: true },
  { key: 'tagline', label: 'Signature / tagline', area: false },
  { key: 'cta_title', label: "Titre de l'appel à l'action", area: false },
  { key: 'cta_text', label: "Texte de l'appel à l'action", area: true },
  { key: 'contact_email', label: 'Email public', area: false },
  { key: 'contact_phone', label: 'Téléphone public', area: false },
  { key: 'contact_hours', label: 'Horaires (ligne 1)', area: false },
  { key: 'contact_hours_2', label: 'Horaires (ligne 2)', area: false },
  { key: 'contact_address', label: 'Adresse', area: false },
  { key: 'contact_coverage', label: "Zone d'intervention", area: false },
  { key: 'footer_bio', label: 'Bio du pied de page', area: true },
]
</script>

<template>
  <Head title="Contenus — Admin" />
  <AdminLayout>
    <h1 style="margin:0 0 8px;font-size:clamp(24px,4vw,36px)">Contenus</h1>
    <p style="max-width:60ch;font-size:14px;margin:0 0 20px">Modifiez ici les textes du site public. Les changements s'appliquent immédiatement.</p>

    <form style="border-bottom:2px solid var(--color-divider);padding-bottom:20px;margin-bottom:24px" @submit.prevent="saveSettings">
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:20px">
        <button type="submit" class="btn btn-primary" :disabled="form.processing">Enregistrer</button>
        <span v-if="form.recentlySuccessful" style="font-size:12px;color:var(--c4)">Enregistré ✓</span>
      </div>

      <h2 style="font-size:20px;margin:0 0 16px;border-left:6px solid var(--c2);padding-left:12px">Textes du site</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div v-for="f in fields" :key="f.key" class="field">
          <label :for="f.key">{{ f.label }}</label>
          <textarea v-if="f.area" :id="f.key" v-model="form[f.key]" class="input" style="min-height:96px"></textarea>
          <input v-else :id="f.key" v-model="form[f.key]" class="input" type="text">
          <p v-if="form.errors[f.key]" class="field-error">{{ form.errors[f.key] }}</p>
        </div>
      </div>
    </form>

    <h2 style="font-size:20px;margin:0 0 16px;border-left:6px solid var(--c3);padding-left:12px">Témoignages</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:36px">
      <div v-for="(t, i) in testimonials" :key="t.id" :style="{ boxShadow: '0 0 0 1px var(--color-divider)', padding: '16px', display: 'flex', flexDirection: 'column', gap: '10px' }">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
          <span class="text-muted" style="font-size:11px;letter-spacing:0.1em">TÉMOIGNAGE {{ i + 1 }}</span>
          <button type="button" class="btn" style="min-height:36px;padding:0 12px;border:1px solid var(--color-divider)"
            :style="{ background: testimonialForms[t.id].featured ? 'var(--color-accent)' : 'transparent', color: testimonialForms[t.id].featured ? 'var(--color-bg)' : 'var(--color-text)' }"
            @click="testimonialForms[t.id].featured = !testimonialForms[t.id].featured">
            {{ testimonialForms[t.id].featured ? 'À la une' : 'Masqué' }}
          </button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px">
          <div class="field"><label>Nom</label><input v-model="testimonialForms[t.id].name" class="input" type="text"></div>
          <div class="field"><label>Rôle</label><input v-model="testimonialForms[t.id].role" class="input" type="text"></div>
        </div>
        <div class="field"><label>Témoignage</label><textarea v-model="testimonialForms[t.id].quote" class="input" style="min-height:88px"></textarea></div>
        <button type="button" class="btn btn-secondary" :disabled="testimonialForms[t.id].saving" @click="saveTestimonial(t.id)">Enregistrer</button>
      </div>
    </div>

    <h2 style="font-size:20px;margin:0 0 16px;border-left:6px solid var(--c4);padding-left:12px">Galerie</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px">
      <div v-for="(g, i) in gallery" :key="g.id" :style="{ boxShadow: '0 0 0 1px var(--color-divider)', borderTop: `5px solid ${HUES[i % 4]}`, padding: '16px', display: 'flex', flexDirection: 'column', gap: '10px' }">
        <div class="grayscale placeholder-media" style="aspect-ratio:16/9">
          <span>{{ g.slot_label }}</span>
        </div>
        <div class="field"><label>Titre</label><input v-model="galleryForms[g.id].title" class="input" type="text"></div>
        <div class="field"><label>Légende</label><input v-model="galleryForms[g.id].subtitle" class="input" type="text"></div>
        <button type="button" class="btn btn-secondary" :disabled="galleryForms[g.id].saving" @click="saveGallery(g.id)">Enregistrer</button>
      </div>
    </div>
  </AdminLayout>
</template>
