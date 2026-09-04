<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion — Admin" />

        <div class="mb-5 text-center">
            <h1 class="font-sans text-xl font-bold tracking-tight text-ink">Espace administrateur</h1>
            <p class="mt-1 text-sm text-ink-muted">Connectez-vous pour gérer le CRM.</p>
        </div>

        <div v-if="status" class="badge badge-soft-success mb-4 w-full !justify-start !rounded-md">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="!text-base"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Mot de passe" />

                <TextInput
                    id="password"
                    type="password"
                    class="!text-base"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label class="flex items-center gap-2.5 text-sm text-ink-secondary">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>Se souvenir de moi</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-semibold text-primary transition-colors duration-150 hover:text-accent"
                >
                    Mot de passe oublié ?
                </Link>
            </div>

            <div class="mt-6 flex flex-col items-center gap-4">
                <PrimaryButton class="w-full" :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                    Se connecter
                </PrimaryButton>

                <Link :href="route('home')" class="text-xs font-medium text-ink-muted transition-colors hover:text-accent">
                    ← Retour au site public
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
