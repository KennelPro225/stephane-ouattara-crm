<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const WEEKDAY_LABELS = { 1: 'Lundi', 2: 'Mardi', 3: 'Mercredi', 4: 'Jeudi', 5: 'Vendredi', 6: 'Samedi', 0: 'Dimanche' }

const props = defineProps({
  rules: Array,
  slotMinutes: Number,
})

const form = useForm({
  slot_minutes: props.slotMinutes,
  rules: props.rules.map((r) => ({
    id: r.id,
    weekday: r.weekday,
    is_open: r.is_open,
    start_time: r.start_time ? r.start_time.slice(0, 5) : '09:00',
    end_time: r.end_time ? r.end_time.slice(0, 5) : '18:00',
  })),
})

function save () {
  form.put(route('admin.availability.update'))
}
</script>

<template>
  <Head title="Disponibilités — Admin" />
  <AdminLayout>
    <h1 style="margin:0 0 8px;font-size:clamp(24px,4vw,36px)">Disponibilités</h1>
    <p style="max-width:64ch;font-size:14px;margin:0 0 24px">Définissez vos jours et heures de prestation. Les heures proposées sur la page de réservation sont calculées à partir de ces paramètres, en retirant les créneaux déjà pris par des réservations ou occupés par des programmes de groupe planifiés ce jour-là.</p>

    <form @submit.prevent="save">
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:24px">
        <button type="submit" class="btn btn-primary" style="min-width:140px" :class="{ 'btn-loading': form.processing }" :disabled="form.processing">Enregistrer</button>
        <span v-if="form.recentlySuccessful" style="font-size:12px;color:var(--c4)">Enregistré ✓</span>
      </div>

      <div class="field" style="max-width:280px;margin-bottom:28px">
        <label for="slot_minutes">Durée d'un créneau (minutes)</label>
        <input id="slot_minutes" v-model.number="form.slot_minutes" class="input" type="number" min="15" max="240" step="15">
        <p v-if="form.errors.slot_minutes" class="field-error">{{ form.errors.slot_minutes }}</p>
      </div>

      <div style="overflow-x:auto">
        <table class="table" style="min-width:640px">
          <thead>
            <tr><th>Jour</th><th>Ouvert</th><th>Début</th><th>Fin</th></tr>
          </thead>
          <tbody>
            <tr v-for="(rule, i) in form.rules" :key="rule.id">
              <td><strong>{{ WEEKDAY_LABELS[rule.weekday] }}</strong></td>
              <td>
                <label class="radio">
                  <input v-model="rule.is_open" type="checkbox"><span class="dot" style="border-radius:2px"></span>
                  {{ rule.is_open ? 'Ouvert' : 'Fermé' }}
                </label>
              </td>
              <td>
                <input v-model="rule.start_time" class="input" type="time" :disabled="!rule.is_open" style="min-width:120px">
                <p v-if="form.errors[`rules.${i}.start_time`]" class="field-error">{{ form.errors[`rules.${i}.start_time`] }}</p>
              </td>
              <td>
                <input v-model="rule.end_time" class="input" type="time" :disabled="!rule.is_open" style="min-width:120px">
                <p v-if="form.errors[`rules.${i}.end_time`]" class="field-error">{{ form.errors[`rules.${i}.end_time`] }}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </form>
  </AdminLayout>
</template>
