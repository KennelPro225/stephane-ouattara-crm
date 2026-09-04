<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center bg-cream pt-6 sm:justify-center sm:pt-0">
        <div class="pointer-events-none absolute left-1/2 top-0 h-72 w-[500px] -translate-x-1/2 rounded-full bg-accent/10 blur-3xl" aria-hidden="true" />
        <div class="absolute right-4 top-4">
            <ThemeToggle />
        </div>

        <div>
            <Link href="/">
                <span class="grid h-16 w-16 place-items-center rounded-xl bg-primary-gradient font-sans text-lg font-bold text-white shadow-elevated transition-transform duration-150 ease-bounce hover:scale-105">SO</span>
            </Link>
        </div>

        <div class="glass card mt-6 w-full !max-w-md animate-scale-in">
            <slot />
        </div>
    </div>
</template>
