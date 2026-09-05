<script setup>
import { computed, ref } from 'vue'
import { Head, usePage, useForm } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  programmes: Array,
  selectedProgrammeId: { type: [Number, String], default: null },
})

const page = usePage()
const content = computed(() => page.props.content)

const serviceOpts = ['Coaching individuel', 'Club des Champions', 'Coaching de groupe', 'Bien-être entreprise', 'Teambuilding', 'Autres']
const timeSlots = ['09:00', '10:30', '14:00', '15:30', '17:00']

const sent = ref(false)

const form = useForm({
  service: '',
  prenom: '', nom: '', email: '', tel: '', societe: '', poste: '',
  programme_id: props.selectedProgrammeId ?? '',
  date: '', heure: '09:00', message: '',
})

const selectedProgramme = computed(() => props.programmes.find((p) => p.id == form.programme_id))
const selectedProgrammeSeats = computed(() => selectedProgramme.value?.available_seats ?? null)

function submit () {
  form.post(route('bookings.store'), {
    preserveScroll: true,
    onSuccess: () => { sent.value = true; window.scrollTo(0, 0) },
  })
}
function resetForm () {
  sent.value = false
  form.reset()
}
</script>

<template>
  <Head title="Réserver une session" />
  <PublicLayout>
    <main class="container-site" style="padding-top:40px">
      <p style="font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-accent-700);margin:0 0 16px">Réservation</p>
      <h1 style="font-size:clamp(30px,6vw,52px);margin:0 0 16px;max-width:22ch">Réserver une session</h1>
      <p style="max-width:56ch;font-size:15px;line-height:1.7;margin:0 0 32px">Votre transformation commence par une conversation. Je vous propose une consultation découverte gratuite de 30 minutes pour explorer vos objectifs et définir le meilleur parcours d'accompagnement.</p>

      <div v-if="sent" style="border:2px solid var(--color-accent);padding:24px;max-width:640px;margin-bottom:40px">
        <h2 style="margin:0 0 8px;font-size:24px;color:var(--color-accent-700)">Demande envoyée</h2>
        <p style="margin:0 0 12px;font-size:14px">Merci {{ form.prenom }}. Votre demande de <strong>{{ form.service }}</strong> a été enregistrée pour le {{ form.date }}{{ form.heure ? ` à ${form.heure}` : '' }}.</p>
        <p class="text-muted" style="margin:0 0 16px;font-size:13px">Une confirmation part à {{ form.email }}. Je reviens vers vous sous 24 h ouvrées.</p>
        <button type="button" class="btn btn-secondary" @click="resetForm">Nouvelle demande</button>
      </div>

      <div v-else style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:40px;border-top:2px solid var(--color-divider);padding-top:32px">
        <form style="display:flex;flex-direction:column;gap:20px" @submit.prevent="submit">
          <div>
            <h6 style="margin:0 0 12px">Type de service</h6>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px">
              <label v-for="s in serviceOpts" :key="s" class="radio" style="border:1px solid var(--color-divider);padding:0 12px;min-height:44px">
                <input v-model="form.service" type="radio" name="service" :value="s"><span class="dot"></span>{{ s }}
              </label>
            </div>
            <p v-if="form.errors.service" class="field-error">{{ form.errors.service }}</p>
          </div>

          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">
            <div class="field">
              <label for="prenom">Prénom *</label>
              <input id="prenom" v-model="form.prenom" class="input" type="text" placeholder="Awa">
              <p v-if="form.errors.prenom" class="field-error">{{ form.errors.prenom }}</p>
            </div>
            <div class="field">
              <label for="nom">Nom *</label>
              <input id="nom" v-model="form.nom" class="input" type="text" placeholder="Koné">
              <p v-if="form.errors.nom" class="field-error">{{ form.errors.nom }}</p>
            </div>
            <div class="field">
              <label for="email">Email *</label>
              <input id="email" v-model="form.email" class="input" type="email" placeholder="awa@exemple.ci">
              <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
            </div>
            <div class="field">
              <label for="tel">Téléphone</label>
              <input id="tel" v-model="form.tel" class="input" type="tel" placeholder="+225 07 00 00 00 00">
            </div>
            <div class="field">
              <label for="societe">Société</label>
              <input id="societe" v-model="form.societe" class="input" type="text" placeholder="Si applicable">
            </div>
            <div class="field">
              <label for="poste">Fonction</label>
              <input id="poste" v-model="form.poste" class="input" type="text" placeholder="Si applicable">
            </div>
            <div class="field">
              <label for="programme">Programme</label>
              <select id="programme" v-model="form.programme_id" class="input">
                <option value="">Aucun</option>
                <option v-for="p in programmes" :key="p.id" :value="p.id">{{ p.title }}</option>
              </select>
              <p v-if="selectedProgrammeSeats !== null" class="text-muted" style="margin:6px 0 0;font-size:12px" :style="selectedProgrammeSeats <= 0 ? 'color:var(--color-accent-700)' : ''">
                {{ selectedProgrammeSeats > 0 ? `${selectedProgrammeSeats} place(s) restante(s)` : 'Complet — choisissez une autre option' }}
              </p>
              <p v-if="form.errors.programme_id" class="field-error">{{ form.errors.programme_id }}</p>
            </div>
            <div class="field">
              <label for="date">Date souhaitée *</label>
              <input id="date" v-model="form.date" class="input" type="date" :min="new Date().toISOString().slice(0, 10)">
              <p v-if="form.errors.date" class="field-error">{{ form.errors.date }}</p>
            </div>
            <div class="field">
              <label for="heure">Heure souhaitée</label>
              <select id="heure" v-model="form.heure" class="input">
                <option v-for="s in timeSlots" :key="s" :value="s">{{ s }}</option>
              </select>
            </div>
          </div>

          <div class="field">
            <label for="message">Message / précisions</label>
            <textarea id="message" v-model="form.message" class="input" placeholder="Contexte, attentes, contraintes…"></textarea>
          </div>

          <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;border-top:2px solid var(--color-divider);padding-top:20px">
            <button type="submit" class="btn btn-primary" style="min-width:180px" :class="{ 'btn-loading': form.processing }" :disabled="form.processing">Envoyer ma demande</button>
            <span class="text-muted" style="font-size:12px">Réponse sous 24 h ouvrées</span>
          </div>
        </form>

        <aside style="display:flex;flex-direction:column;gap:20px">
          <div style="border:2px solid var(--color-text);padding:20px">
            <h6 style="margin:0 0 12px">Votre demande</h6>
            <div style="display:grid;grid-template-columns:auto 1fr;gap:8px 16px;font-size:13px">
              <span class="text-muted">Service</span><span>{{ form.service || '—' }}</span>
              <span class="text-muted">Programme</span><span>{{ selectedProgramme?.title || 'Aucun' }}</span>
              <span class="text-muted">Date</span><span>{{ form.date || '—' }}</span>
              <span class="text-muted">Heure</span><span>{{ form.heure }}</span>
            </div>
          </div>
          <div>
            <h6 style="margin:0 0 12px">La consultation découverte</h6>
            <p style="margin:0;font-size:13px;line-height:1.7">30 minutes, sans engagement, en présentiel au cabinet OMSY EDUC (Cocody, Abidjan) ou en visioconférence.</p>
          </div>
          <div>
            <h6 style="margin:0 0 12px">Contact direct</h6>
            <p style="margin:0;font-size:13px;line-height:1.8">{{ content.email }}<br>{{ content.phone }}<br><span class="text-muted">{{ content.hours }} · {{ content.hours2 }}</span></p>
          </div>
        </aside>
      </div>
    </main>
  </PublicLayout>
</template>
