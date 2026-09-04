<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const visible = ref(false)
const message = ref('')
let timer

watch(
    () => page.props.flash?.success,
    (value) => {
        if (!value) return
        message.value = value
        visible.value = true
        clearTimeout(timer)
        timer = setTimeout(() => (visible.value = false), 5000)
    },
    { immediate: true }
)
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-entrance"
            enter-from-class="translate-y-4 opacity-0"
            leave-active-class="transition duration-200 ease-exit"
            leave-to-class="translate-y-4 opacity-0"
        >
            <div
                v-if="visible && message"
                role="status"
                aria-live="polite"
                class="fixed bottom-4 right-4 z-[60] flex max-w-sm items-start gap-3 rounded-md border border-white/30 bg-success/90 px-5 py-4 text-white shadow-elevated backdrop-blur-md"
            >
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5L11.5 15 16 9.5M12 22a10 10 0 110-20 10 10 0 010 20z" />
                </svg>
                <p class="flex-1 text-sm font-medium leading-relaxed">{{ message }}</p>
                <button aria-label="Fermer" class="text-white/80 transition hover:text-white" @click="visible = false">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>
    </Teleport>
</template>
