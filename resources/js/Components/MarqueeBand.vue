<script setup>
const items = [
    'Club des Champions',
    'Compétence 360',
    'La Connexion',
    'OMSY EDUC',
    'Bénévoles du Monde',
    'Teambuilding Impact',
    'Leadership',
    'Confiance en soi',
]

const loop = [...items, ...items]
</script>

<template>
    <div
        class="relative overflow-hidden border-y border-line/50 bg-primary-gradient py-4"
        aria-hidden="true"
    >
        <div class="marquee-track items-center gap-10">
            <span
                v-for="(item, i) in loop"
                :key="i"
                class="flex shrink-0 items-center gap-10 font-sans text-sm font-semibold uppercase tracking-widest text-white/90"
            >
                {{ item }}
                <svg class="h-3 w-3 text-accent-light animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.05 2.9a1 1 0 0 1 1.9 0l1.4 4.3h4.5a1 1 0 0 1 .6 1.8l-3.7 2.6 1.4 4.3a1 1 0 0 1-1.5 1.1L10 14.4l-3.8 2.6a1 1 0 0 1-1.5-1.1l1.4-4.3L2.4 9a1 1 0 0 1 .6-1.8h4.5l1.4-4.3Z" />
                </svg>
            </span>
        </div>

        <!-- Fondu latéral -->
        <div class="pointer-events-none absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-[#5F4823] to-transparent" />
        <div class="pointer-events-none absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-[#B8740F] to-transparent" />
    </div>
</template>
