<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { HUES } from '@/constants'

const props = defineProps({
  settings: Object,
  images: Object,
  testimonials: Array,
  gallery: Array,
})

const form = useForm({ ...props.settings })
function saveSettings () {
  form.put(route('admin.content.update'))
}

const heroPreview = ref(props.images.hero_image)
const coachPreview = ref(props.images.coach_image)
const imagesForm = useForm({ hero_image: null, coach_image: null })

function onHeroImageChange (event) {
  const file = event.target.files[0]
  if (file) {
    imagesForm.hero_image = file
    heroPreview.value = URL.createObjectURL(file)
  }
}
function onCoachImageChange (event) {
  const file = event.target.files[0]
  if (file) {
    imagesForm.coach_image = file
    coachPreview.value = URL.createObjectURL(file)
  }
}
function saveImages () {
  imagesForm.post(route('admin.content.images'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => { imagesForm.hero_image = null; imagesForm.coach_image = null },
  })
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
  Object.fromEntries(props.gallery.map((g) => [g.id, { title: g.title, subtitle: g.subtitle, image: null, saving: false }]))
)
const galleryPreviews = reactive(Object.fromEntries(props.gallery.map((g) => [g.id, g.image_url])))
function onGalleryImageChange (event, id) {
  const file = event.target.files[0]
  if (file) {
    galleryForms[id].image = file
    galleryPreviews[id] = URL.createObjectURL(file)
  }
}
function saveGallery (id) {
  galleryForms[id].saving = true
  router.put(route('admin.gallery.update', id), galleryForms[id], {
    forceFormData: true,
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
    <p style="max-width:60ch;font-size:14px;margin:0 0 20px">Modifiez ici les textes et les images du site public. Les changements s'appliquent immédiatement.</p>

    <h2 style="font-size:20px;margin:0 0 16px;border-left:6px solid var(--c1);padding-left:12px">Images du site</h2>
    <form style="border-bottom:2px solid var(--color-divider);padding-bottom:24px;margin-bottom:24px" @submit.prevent="saveImages">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-bottom:16px">
        <div>
          <p style="font-size:12px;margin:0 0 6px;color:color-mix(in srgb, var(--color-text) 70%, transparent)">Portrait du hero (page d'accueil)</p>
          <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap">
            <div class="grayscale placeholder-media" style="width:140px;aspect-ratio:4/5;flex:none" :style="heroPreview ? 'background:none;filter:none' : ''">
              <img v-if="heroPreview" :src="heroPreview" alt="" style="width:100%;height:100%;object-fit:cover">
              <span v-else>aperçu</span>
            </div>
            <div style="flex:1;min-width:180px">
              <input class="input" type="file" accept="image/png,image/jpeg,image/webp" @change="onHeroImageChange">
              <p v-if="imagesForm.errors.hero_image" class="field-error">{{ imagesForm.errors.hero_image }}</p>
            </div>
          </div>
        </div>
        <div>
          <p style="font-size:12px;margin:0 0 6px;color:color-mix(in srgb, var(--color-text) 70%, transparent)">Photo du coach (fiches programme)</p>
          <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap">
            <div class="grayscale placeholder-media" style="width:140px;aspect-ratio:1/1;flex:none" :style="coachPreview ? 'background:none;filter:none' : ''">
              <img v-if="coachPreview" :src="coachPreview" alt="" style="width:100%;height:100%;object-fit:cover">
              <span v-else>aperçu</span>
            </div>
            <div style="flex:1;min-width:180px">
              <input class="input" type="file" accept="image/png,image/jpeg,image/webp" @change="onCoachImageChange">
              <p v-if="imagesForm.errors.coach_image" class="field-error">{{ imagesForm.errors.coach_image }}</p>
            </div>
          </div>
        </div>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center">
        <button type="submit" class="btn btn-primary" style="min-width:170px" :class="{ 'btn-loading': imagesForm.processing }" :disabled="imagesForm.processing">Enregistrer les images</button>
        <span v-if="imagesForm.recentlySuccessful" style="font-size:12px;color:var(--c4)">Enregistré ✓</span>
      </div>
    </form>

    <form style="border-bottom:2px solid var(--color-divider);padding-bottom:20px;margin-bottom:24px" @submit.prevent="saveSettings">
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:20px">
        <button type="submit" class="btn btn-primary" style="min-width:140px" :class="{ 'btn-loading': form.processing }" :disabled="form.processing">Enregistrer</button>
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
        <div class="grayscale placeholder-media" style="aspect-ratio:16/9" :style="galleryPreviews[g.id] ? 'background:none;filter:none' : ''">
          <img v-if="galleryPreviews[g.id]" :src="galleryPreviews[g.id]" alt="" style="width:100%;height:100%;object-fit:cover">
          <span v-else>{{ g.slot_label }}</span>
        </div>
        <div class="field"><label>Titre</label><input v-model="galleryForms[g.id].title" class="input" type="text"></div>
        <div class="field"><label>Légende</label><input v-model="galleryForms[g.id].subtitle" class="input" type="text"></div>
        <div class="field">
          <label>Image</label>
          <input class="input" type="file" accept="image/png,image/jpeg,image/webp" @change="onGalleryImageChange($event, g.id)">
        </div>
        <button type="button" class="btn btn-secondary" :disabled="galleryForms[g.id].saving" @click="saveGallery(g.id)">Enregistrer</button>
      </div>
    </div>
  </AdminLayout>
</template>
