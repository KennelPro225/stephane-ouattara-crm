<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  programme: { type: Object, default: null },
  submitRoute: { type: Function, required: true },
  title: { type: String, required: true },
  method: { type: String, default: 'post' },
})

const imagePreview = ref(
  props.programme?.image_path ? `/storage/${props.programme.image_path}` : null
)

const form = useForm({
  title: props.programme?.title ?? '',
  description: props.programme?.description ?? '',
  short_description: props.programme?.short_description ?? '',
  age_min: props.programme?.age_min ?? 10,
  age_max: props.programme?.age_max ?? 60,
  type: props.programme?.type ?? 'group',
  price: props.programme?.price ?? 0,
  max_participants: props.programme?.max_participants ?? 20,
  start_date: props.programme?.start_date ?? '',
  end_date: props.programme?.end_date ?? '',
  registration_deadline: props.programme?.registration_deadline ?? '',
  session_time: props.programme?.session_time ?? '',
  duration_hours: props.programme?.duration_hours ?? 2,
  location: props.programme?.location ?? "Abidjan, Côte d'Ivoire",
  featured: props.programme?.featured ?? false,
  status: props.programme?.status ?? 'draft',
  image: null,
})

function onImageChange(event) {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

function submit() {
  form.transform((data) => ({
    ...data,
    featured: data.featured ? 1 : 0,
  }))
  form.post(props.submitRoute(), {
    method: method,
    forceFormData: true,
  })
}

const selectClass = 'w-full rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0'
</script>

<template>
  <AdminLayout>
    <div class="mx-auto max-w-3xl animate-slide-up">
      <span class="eyebrow">{{ programme ? 'Modification' : 'Création' }}</span>
      <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">{{ title }}</h1>

      <form class="card mt-6 space-y-8 shadow-lg" @submit.prevent="submit" enctype="multipart/form-data" :method="method">
        <input v-if="method && method.toLowerCase() !== 'post' && method.toLowerCase() !== 'get'" type="hidden" name="_method" :value="method" />
        <!-- Informations générales -->
        <section class="space-y-5">
          <h2 class="font-sans text-sm font-semibold uppercase tracking-wide text-accent">Informations générales</h2>
          <div>
            <InputLabel for="title" value="Titre *" />
            <TextInput id="title" v-model="form.title" type="text" required />
            <InputError :message="form.errors.title" class="mt-2" />
          </div>
          <div>
            <InputLabel for="short_description" value="Description courte" />
            <textarea id="short_description" v-model="form.short_description" rows="2"
              class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="form.errors.short_description" class="mt-2" />
          </div>
          <div>
            <InputLabel for="description" value="Description complète *" />
            <textarea id="description" v-model="form.description" rows="6" required
              class="block w-full resize-y rounded-md border border-line bg-surface px-4 py-3 text-base text-ink shadow-xs transition-all duration-150 placeholder:text-ink-muted focus:border-primary focus:shadow-focus-ring focus:ring-0"></textarea>
            <InputError :message="form.errors.description" class="mt-2" />
          </div>
        </section>

        <!-- Paramètres -->
        <section class="grid gap-5 sm:grid-cols-2">
          <h2 class="font-sans text-sm font-semibold uppercase tracking-wide text-accent sm:col-span-2">Paramètres</h2>
          <div>
            <InputLabel for="type" value="Type *" />
            <select id="type" v-model="form.type" :class="selectClass">
              <option value="individual">Individuel</option>
              <option value="group">Groupe</option>
              <option value="corporate">Entreprise</option>
              <option value="adolescents">Adolescents</option>
            </select>
            <InputError :message="form.errors.type" class="mt-2" />
          </div>
          <div>
            <InputLabel for="status" value="Statut *" />
            <select id="status" v-model="form.status" :class="selectClass">
              <option value="draft">Brouillon</option>
              <option value="published">Publié</option>
              <option value="archived">Archivé</option>
            </select>
            <InputError :message="form.errors.status" class="mt-2" />
          </div>
          <div>
            <InputLabel for="age_min" value="Âge minimum *" />
            <TextInput id="age_min" v-model="form.age_min" type="number" min="5" max="80" required />
            <InputError :message="form.errors.age_min" class="mt-2" />
          </div>
          <div>
            <InputLabel for="age_max" value="Âge maximum *" />
            <TextInput id="age_max" v-model="form.age_max" type="number" min="5" max="100" required />
            <InputError :message="form.errors.age_max" class="mt-2" />
          </div>
          <div>
            <InputLabel for="price" value="Prix (FCFA) *" />
            <TextInput id="price" v-model="form.price" type="number" min="0" step="0.01" required />
            <InputError :message="form.errors.price" class="mt-2" />
          </div>
          <div>
            <InputLabel for="max_participants" value="Nombre de places *" />
            <TextInput id="max_participants" v-model="form.max_participants" type="number" min="1" required />
            <InputError :message="form.errors.max_participants" class="mt-2" />
          </div>
        </section>

        <!-- Planning -->
        <section class="grid gap-5 sm:grid-cols-2">
          <h2 class="font-sans text-sm font-semibold uppercase tracking-wide text-accent sm:col-span-2">Planning & lieu</h2>
          <div>
            <InputLabel for="start_date" value="Date de début *" />
            <TextInput id="start_date" v-model="form.start_date" type="date" required />
            <InputError :message="form.errors.start_date" class="mt-2" />
          </div>
          <div>
            <InputLabel for="end_date" value="Date de fin *" />
            <TextInput id="end_date" v-model="form.end_date" type="date" required />
            <InputError :message="form.errors.end_date" class="mt-2" />
          </div>
          <div>
            <InputLabel for="registration_deadline" value="Date limite d'inscription" />
            <TextInput id="registration_deadline" v-model="form.registration_deadline" type="date" />
            <InputError :message="form.errors.registration_deadline" class="mt-2" />
          </div>
          <div>
            <InputLabel for="session_time" value="Heure de session" />
            <TextInput id="session_time" v-model="form.session_time" type="time" />
            <InputError :message="form.errors.session_time" class="mt-2" />
          </div>
          <div>
            <InputLabel for="duration_hours" value="Durée (heures)" />
            <TextInput id="duration_hours" v-model="form.duration_hours" type="number" min="1" max="12" />
            <InputError :message="form.errors.duration_hours" class="mt-2" />
          </div>
          <div>
            <InputLabel for="location" value="Lieu" />
            <TextInput id="location" v-model="form.location" type="text" />
            <InputError :message="form.errors.location" class="mt-2" />
          </div>
        </section>

        <!-- Image -->
        <section>
          <h2 class="mb-4 font-sans text-sm font-semibold uppercase tracking-wide text-accent">Image du programme</h2>
          <div class="flex flex-wrap items-center gap-6">
            <div v-if="imagePreview" class="h-24 w-40 overflow-hidden rounded-lg border border-line/60 shadow-sm transition-transform duration-150 hover:scale-[1.02]">
              <img :src="imagePreview" alt="Aperçu de l'image" class="h-full w-full object-cover" />
            </div>
            <label class="btn-secondary cursor-pointer !py-2.5 !text-xs">
              Choisir une image
              <input id="image" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="onImageChange" />
            </label>
          </div>
          <p class="mt-2 text-xs text-ink-muted">JPG, PNG ou WebP — 2 Mo maximum.</p>
          <InputError :message="form.errors.image" class="mt-2" />
        </section>

        <!-- Mise en avant -->
        <label class="flex cursor-pointer items-center justify-between rounded-lg bg-surface-hover p-4 transition-colors hover:bg-line/30">
          <span>
            <span class="block text-sm font-semibold text-ink">Mettre en avant sur la page d'accueil</span>
            <span class="text-xs text-ink-muted">Le programme apparaîtra dans la section « À la une ».</span>
          </span>
          <!-- Toggle switch -->
          <button type="button" role="switch" :aria-checked="Boolean(form.featured)"
            @click="form.featured = !form.featured"
            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200 ease-entrance"
            :class="form.featured ? 'bg-success' : 'bg-line'">
            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200 ease-entrance"
              :class="form.featured ? 'translate-x-[22px]' : 'translate-x-0.5'" />
          </button>
        </label>

        <div class="flex items-center justify-end gap-3 border-t border-line/50 pt-6">
          <a :href="route('admin.programmes.index')" class="btn-secondary">Annuler</a>
          <button type="submit" :disabled="form.processing" class="btn-primary min-w-[180px]">
            {{ programme ? 'Mettre à jour' : 'Créer le programme' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
