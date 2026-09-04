<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
  users: Array,
})

const page = usePage()

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'staff',
})

function submit() {
  form.post(route('admin.users.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset('password', 'password_confirmation'),
  })
}

function destroy(user) {
  if (confirm(`Supprimer le compte de ${user.name} ?`)) {
    form.delete(route('admin.users.destroy', user.id), { preserveScroll: true })
  }
}

const roleLabels = { admin: 'Administrateur', staff: 'Collaborateur' }
</script>

<template>
  <Head title="Comptes — Admin" />
  <AdminLayout>
    <div class="animate-slide-up">
      <span class="eyebrow">Accès</span>
      <h1 class="font-sans text-2xl font-bold tracking-tight text-ink">Comptes administrateurs</h1>
      <p class="mt-1 text-sm text-ink-secondary">L'inscription publique est désactivée : seuls les administrateurs peuvent créer de nouveaux comptes ici.</p>
    </div>

    <div class="mt-6 grid gap-8 xl:grid-cols-3">
      <!-- Liste des comptes -->
      <section class="xl:col-span-2 animate-slide-up [animation-delay:100ms]">
        <div class="card overflow-hidden !p-0">
          <table class="min-w-full divide-y divide-line/50 text-sm">
            <thead class="bg-surface-hover text-left text-xs font-semibold uppercase tracking-wide text-ink-muted">
              <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Rôle</th>
                <th class="px-4 py-3 font-mono text-[11px]">Créé le</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-line/40">
              <tr v-for="user in users" :key="user.id" class="transition-colors hover:bg-surface-hover/60">
                <td class="px-4 py-3.5 font-semibold text-ink">{{ user.name }}</td>
                <td class="px-4 py-3.5 text-ink-secondary">{{ user.email }}</td>
                <td class="px-4 py-3.5">
                  <span class="badge" :class="user.role === 'admin' ? 'badge-soft-success' : 'badge-outline'">{{ roleLabels[user.role] ?? user.role }}</span>
                </td>
                <td class="px-4 py-3.5 font-mono text-xs text-ink-muted">{{ new Date(user.created_at).toLocaleDateString('fr-FR') }}</td>
                <td class="px-4 py-3.5 text-right">
                  <button v-if="user.id !== page.props.auth.user.id" type="button" class="text-xs font-semibold text-danger hover:underline" @click="destroy(user)">
                    Supprimer
                  </button>
                </td>
              </tr>
              <tr v-if="!users.length">
                <td colspan="5" class="px-4 py-12 text-center text-ink-muted">Aucun compte pour le moment.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Nouveau compte -->
      <section class="card h-fit animate-slide-up [animation-delay:200ms]">
        <h2 class="font-sans text-lg font-bold text-ink">Nouveau compte</h2>
        <form class="mt-4 space-y-4" @submit.prevent="submit">
          <div>
            <InputLabel for="name" value="Nom complet" />
            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autocomplete="name" />
            <InputError class="mt-1" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <InputError class="mt-1" :message="form.errors.email" />
          </div>
          <div>
            <InputLabel for="role" value="Rôle" />
            <select id="role" v-model="form.role" class="mt-1 w-full rounded-md border border-line bg-surface px-4 py-2.5 text-sm text-ink shadow-xs transition-all duration-150 focus:border-primary focus:shadow-focus-ring focus:ring-0">
              <option value="staff">Collaborateur</option>
              <option value="admin">Administrateur</option>
            </select>
            <InputError class="mt-1" :message="form.errors.role" />
          </div>
          <div>
            <InputLabel for="password" value="Mot de passe" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
            <InputError class="mt-1" :message="form.errors.password" />
          </div>
          <div>
            <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
          </div>
          <button type="submit" class="btn-primary w-full" :disabled="form.processing">Créer le compte</button>
        </form>
      </section>
    </div>
  </AdminLayout>
</template>
