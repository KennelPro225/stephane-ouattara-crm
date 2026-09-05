<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  programme: { type: Object, default: null },
  submitRoute: { type: String, required: true },
  method: { type: String, default: 'post' },
  title: { type: String, required: true },
})

const imagePreview = ref(props.programme?.image_url ?? null)

const form = useForm({
  title: props.programme?.title ?? '',
  audience: props.programme?.audience ?? 'adolescents',
  type: props.programme?.type ?? 'group',
  level: props.programme?.level ?? '',
  description: props.programme?.description ?? '',
  price_amount: props.programme?.price_amount ?? '',
  price_label: props.programme?.price_label ?? '',
  start_date: props.programme?.start_date ?? '',
  end_date: props.programme?.end_date ?? '',
  registration_deadline: props.programme?.registration_deadline ?? '',
  duration_label: props.programme?.duration_label ?? '',
  ages_label: props.programme?.ages_label ?? '',
  max_participants: props.programme?.max_participants ?? 20,
  featured: props.programme?.featured ?? false,
  status: props.programme?.status ?? 'draft',
  image: null,
})

function onImageChange (event) {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

function submit () {
  const options = { forceFormData: true }
  if (props.method === 'put') {
    form.put(props.submitRoute, options)
  } else {
    form.post(props.submitRoute, options)
  }
}
</script>

<template>
  <AdminLayout>
    <h1 style="margin:0 0 24px;font-size:clamp(24px,4vw,36px)">{{ title }}</h1>

    <form style="max-width:760px;display:flex;flex-direction:column;gap:20px" @submit.prevent="submit">
      <div class="field">
        <label for="title">Titre</label>
        <input id="title" v-model="form.title" class="input" type="text">
        <p v-if="form.errors.title" class="field-error">{{ form.errors.title }}</p>
      </div>

      <div class="field">
        <label for="image">Photo du programme</label>
        <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap">
          <div class="grayscale placeholder-media" style="width:160px;aspect-ratio:4/3;flex:none" :style="imagePreview ? 'background:none;filter:none' : ''">
            <img v-if="imagePreview" :src="imagePreview" alt="" style="width:100%;height:100%;object-fit:cover">
            <span v-else>aperçu</span>
          </div>
          <div style="flex:1;min-width:200px">
            <input id="image" class="input" type="file" accept="image/png,image/jpeg,image/webp" @change="onImageChange">
            <p class="text-muted" style="margin:6px 0 0;font-size:11px">JPG, PNG ou WEBP, 4 Mo maximum.</p>
            <p v-if="form.errors.image" class="field-error">{{ form.errors.image }}</p>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        <div class="field">
          <label for="audience">Public</label>
          <select id="audience" v-model="form.audience" class="input">
            <option value="adolescents">Adolescents</option>
            <option value="adultes">Adultes</option>
            <option value="entreprises">Entreprises</option>
          </select>
        </div>
        <div class="field">
          <label for="type">Type</label>
          <select id="type" v-model="form.type" class="input">
            <option value="individual">Individuel</option>
            <option value="group">Groupe</option>
            <option value="corporate">Entreprise</option>
          </select>
        </div>
        <div class="field">
          <label for="level">Niveau</label>
          <input id="level" v-model="form.level" class="input" type="text" placeholder="Lion, Standard…">
        </div>
        <div class="field">
          <label for="status">Statut</label>
          <select id="status" v-model="form.status" class="input">
            <option value="draft">Brouillon</option>
            <option value="published">Publié</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label for="description">Description</label>
        <textarea id="description" v-model="form.description" class="input" style="min-height:140px"></textarea>
        <p v-if="form.errors.description" class="field-error">{{ form.errors.description }}</p>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        <div class="field">
          <label for="price_label">Prix affiché</label>
          <input id="price_label" v-model="form.price_label" class="input" type="text" placeholder="150 000 FCFA ou Sur devis">
          <p v-if="form.errors.price_label" class="field-error">{{ form.errors.price_label }}</p>
        </div>
        <div class="field">
          <label for="price_amount">Montant (tri, optionnel)</label>
          <input id="price_amount" v-model="form.price_amount" class="input" type="number" step="0.01" placeholder="Vide si « Sur devis »">
        </div>
        <div class="field">
          <label for="max_participants">Participants max</label>
          <input id="max_participants" v-model="form.max_participants" class="input" type="number" min="1">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        <div class="field">
          <label for="start_date">Début</label>
          <input id="start_date" v-model="form.start_date" class="input" type="date">
          <p v-if="form.errors.start_date" class="field-error">{{ form.errors.start_date }}</p>
        </div>
        <div class="field">
          <label for="end_date">Fin</label>
          <input id="end_date" v-model="form.end_date" class="input" type="date">
          <p v-if="form.errors.end_date" class="field-error">{{ form.errors.end_date }}</p>
        </div>
        <div class="field">
          <label for="registration_deadline">Clôture des inscriptions</label>
          <input id="registration_deadline" v-model="form.registration_deadline" class="input" type="date">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        <div class="field">
          <label for="duration_label">Durée</label>
          <input id="duration_label" v-model="form.duration_label" class="input" type="text" placeholder="12 semaines">
        </div>
        <div class="field">
          <label for="ages_label">Public visé (âges)</label>
          <input id="ages_label" v-model="form.ages_label" class="input" type="text" placeholder="12 – 15 ans">
        </div>
      </div>

      <label class="radio">
        <input v-model="form.featured" type="checkbox"><span class="dot" style="border-radius:2px"></span>Programme phare (page d'accueil)
      </label>

      <div style="display:flex;gap:12px;border-top:2px solid var(--color-divider);padding-top:20px">
        <button type="submit" class="btn btn-primary" style="min-width:140px" :class="{ 'btn-loading': form.processing }" :disabled="form.processing">Enregistrer</button>
      </div>
    </form>
  </AdminLayout>
</template>
