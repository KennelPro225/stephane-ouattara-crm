<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import AdminPagination from '@/Components/AdminPagination.vue'

const props = defineProps({
  testimonials: Object,
  settings: Object,
  galleries: Array,
})

const settingsForm = useForm({ ...props.settings })

const testimonialForm = useForm({
  author_name: '',
  author_title: '',
  author_company: '',
  programme_id: '',
  rating: 5,
  message: '',
  image: null,
  featured: false,
  approved: true,
})

function saveSettings() {
  settingsForm.put(route('admin.content.update'))
}

function addTestimonial() {
  testimonialForm.post(route('admin.testimonials.store'), {
    onSuccess: () => testimonialForm.reset(),
  })
}

function removeTestimonial(testimonial) {
  if (confirm(`Supprimer le témoignage de ${testimonial.author_name} ?`)) {
    router.delete(route('admin.testimonials.destroy', testimonial.id))
  }
}

const galleryForm = useForm({
  title: '',
  category: '',
  caption: '',
  media: null,
  media_type: 'image',
  programme_id: '',
})

function onMediaChange(event) {
  galleryForm.media = event.target.files[0]
}

function addGalleryItem() {
  galleryForm.post(route('admin.galleries.store'), {
    onSuccess: () => galleryForm.reset(),
  })
}

function removeGalleryItem(item) {
  if (confirm(`Retirer « ${item.title} » de la galerie ?`)) {
    router.delete(route('admin.galleries.destroy', item.id))
  }
}

const selectClass = 'w-full rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <Head title="Contenu du site — Admin" />
  <AdminLayout>
    <div class="animate-slide-up">
      <span class="eyebrow">Personnalisation</span>
      <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Contenu du site</h1>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
      <!-- Textes & contact -->
      <section class="card animate-slide-up [animation-delay:100ms]">
        <h2 class="font-sans font-bold text-ink">Textes & contact</h2>
        <form class="mt-5 space-y-4" @submit.prevent="saveSettings">
          <div>
            <InputLabel for="hero_title" value="Titre de la bannière *" />
            <TextInput id="hero_title" v-model="settingsForm.hero_title" type="text" required />
            <InputError :message="settingsForm.errors.hero_title" class="mt-2" />
          </div>
          <div>
            <InputLabel for="hero_subtitle" value="Sous-titre *" />
            <textarea id="hero_subtitle" v-model="settingsForm.hero_subtitle" rows="2"
              class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="settingsForm.errors.hero_subtitle" class="mt-2" />
          </div>
          <div>
            <InputLabel for="about_text" value="Texte « À propos » *" />
            <textarea id="about_text" v-model="settingsForm.about_text" rows="3"
              class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="settingsForm.errors.about_text" class="mt-2" />
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <InputLabel for="contact_email" value="Email de contact *" />
              <TextInput id="contact_email" v-model="settingsForm.contact_email" type="email" required />
              <InputError :message="settingsForm.errors.contact_email" class="mt-2" />
            </div>
            <div>
              <InputLabel for="contact_phone" value="Téléphone *" />
              <TextInput id="contact_phone" v-model="settingsForm.contact_phone" type="text" required />
              <InputError :message="settingsForm.errors.contact_phone" class="mt-2" />
            </div>
          </div>
          <button type="submit" :disabled="settingsForm.processing" class="btn-primary">
            Enregistrer
          </button>
        </form>
      </section>

      <!-- Nouveau témoignage -->
      <section class="card animate-slide-up [animation-delay:200ms]">
        <h2 class="font-sans font-bold text-ink">Ajouter un témoignage</h2>
        <form class="mt-5 space-y-4" @submit.prevent="addTestimonial">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <InputLabel for="author_name" value="Nom de l'auteur *" />
              <TextInput id="author_name" v-model="testimonialForm.author_name" type="text" required />
              <InputError :message="testimonialForm.errors.author_name" class="mt-2" />
            </div>
            <div>
              <InputLabel for="author_title" value="Fonction" />
              <TextInput id="author_title" v-model="testimonialForm.author_title" type="text" />
            </div>
            <div>
              <InputLabel for="author_company" value="Entreprise" />
              <TextInput id="author_company" v-model="testimonialForm.author_company" type="text" />
            </div>
            <div>
              <InputLabel for="t_rating" value="Note (1-5) *" />
              <select id="t_rating" v-model.number="testimonialForm.rating" :class="selectClass">
                <option v-for="n in 5" :key="n" :value="n">{{ n }} ★{{ '★'.repeat(n - 1) }}</option>
              </select>
            </div>
          </div>
          <div>
            <InputLabel for="t_message" value="Témoignage *" />
            <textarea id="t_message" v-model="testimonialForm.message" rows="3" required
              class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="testimonialForm.errors.message" class="mt-2" />
          </div>
          <button type="submit" :disabled="testimonialForm.processing"
            class="btn-primary w-full !bg-none bg-primary-gradient">
            Ajouter le témoignage
          </button>
        </form>
      </section>
    </div>

    <!-- Galerie -->
    <section class="mt-10 animate-slide-up [animation-delay:250ms]">
      <h2 class="font-sans font-bold text-ink">Galerie de réalisations
        <span class="badge badge-outline ml-2 align-middle">{{ galleries.length }}</span>
      </h2>

      <form class="card mt-4 grid gap-4 sm:grid-cols-2" @submit.prevent="addGalleryItem">
        <div>
          <InputLabel for="g-title" value="Titre *" />
          <TextInput id="g-title" v-model="galleryForm.title" type="text" required />
          <InputError :message="galleryForm.errors.title" class="mt-2" />
        </div>
        <div>
          <InputLabel for="g-category" value="Catégorie *" />
          <select id="g-category" v-model="galleryForm.category" :class="selectClass" required>
            <option value="">— Choisir —</option>
            <option>Coaching Individuel</option>
            <option>Ateliers de Groupe</option>
            <option>Teambuilding</option>
            <option>Conférences</option>
            <option>Coaching Jeunes</option>
            <option>Formation Leadership</option>
          </select>
          <InputError :message="galleryForm.errors.category" class="mt-2" />
        </div>
        <div>
          <InputLabel for="g-caption" value="Légende" />
          <TextInput id="g-caption" v-model="galleryForm.caption" type="text" />
        </div>
        <div>
          <InputLabel for="g-media" value="Média (image ou vidéo) *" />
          <label class="btn-secondary cursor-pointer w-fit !py-2.5 !text-xs">
            Choisir un fichier
            <input id="g-media" type="file" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm"
              class="sr-only" @change="onMediaChange" />
          </label>
          <p v-if="galleryForm.media" class="mt-1 text-xs text-success">{{ galleryForm.media.name }}</p>
          <InputError :message="galleryForm.errors.media" class="mt-2" />
        </div>
        <input type="hidden" name="media_type" value="image" />
        <div class="sm:col-span-2">
          <button type="submit" :disabled="galleryForm.processing || !galleryForm.media"
            class="btn-primary !py-2.5 !text-xs disabled:opacity-50">
            Ajouter à la galerie
          </button>
        </div>
      </form>

      <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="item in galleries" :key="item.id" class="card card-hover !p-4 animate-fade-in">
          <img :src="`/storage/${item.media_path}`" :alt="item.title"
            class="aspect-video w-full rounded-md object-cover" />
          <p class="mt-3 font-semibold text-ink">{{ item.title }}</p>
          <p class="text-xs text-ink-muted">{{ item.category }}<template v-if="item.caption"> · {{ item.caption }}</template></p>
          <button @click="removeGalleryItem(item)"
            class="mt-2 rounded-md px-2 py-1 text-xs font-medium text-danger transition-colors hover:bg-danger/10">
            Retirer
          </button>
        </li>
        <li v-if="!galleries.length" class="rounded-lg border border-dashed border-line p-8 text-center text-sm text-ink-muted sm:col-span-2 lg:col-span-3">
          Aucun média dans la galerie.
        </li>
      </ul>
    </section>

    <!-- Témoignages existants -->
    <section class="mt-10 animate-slide-up [animation-delay:300ms]">
      <h2 class="font-sans font-bold text-ink">Témoignages existants
        <span class="badge badge-outline ml-2 align-middle">{{ testimonials.total }}</span>
      </h2>
      <ul class="mt-4 space-y-3">
        <li v-for="(testimonial, i) in testimonials.data" :key="testimonial.id"
          class="card card-hover flex items-start justify-between gap-4 animate-fade-in"
          :style="{ animationDelay: `${Math.min(i, 8) * 50}ms` }">
          <div class="min-w-0">
            <p class="flex flex-wrap items-center gap-2 font-semibold text-ink">
              {{ testimonial.author_name }}
              <span v-if="testimonial.featured" class="badge badge-solid !py-0.5 !text-[10px]">À la une</span>
            </p>
            <p class="mt-1.5 text-sm italic leading-relaxed text-ink-secondary">« {{ testimonial.message }} »</p>
            <p class="mt-2 flex items-center gap-1 text-accent">
              <svg v-for="s in testimonial.rating" :key="s" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.05 2.9a1 1 0 0 1 1.9 0l1.4 4.3h4.5a1 1 0 0 1 .6 1.8l-3.7 2.6 1.4 4.3a1 1 0 0 1-1.5 1.1L10 14.4l-3.8 2.6a1 1 0 0 1-1.5-1.1l1.4-4.3L2.4 9a1 1 0 0 1 .6-1.8h4.5l1.4-4.3Z"/></svg>
              <span class="ml-2 text-xs normal-case text-ink-muted">{{ testimonial.programme?.title ?? 'Sans programme' }}</span>
            </p>
          </div>
          <button @click="removeTestimonial(testimonial)"
            class="shrink-0 rounded-md px-2 py-1 text-sm font-medium text-danger transition-colors hover:bg-danger/10">
            Supprimer
          </button>
        </li>
        <li v-if="!testimonials.data.length" class="flex flex-col items-center rounded-lg border border-dashed border-line p-10 text-center">
          <svg class="h-12 w-12 text-primary-bright" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" /></svg>
          <p class="mt-3 text-sm text-ink-muted">Aucun témoignage pour le moment.</p>
        </li>
      </ul>
      <AdminPagination :links="testimonials.links" />
    </section>
  </AdminLayout>
</template>
