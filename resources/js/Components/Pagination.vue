<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: { type: Array, required: true },
})
</script>

<template>
  <div v-if="links.length > 3" class="mt-10 flex flex-wrap items-center justify-center gap-2">
    <template v-for="(link, key) in links" :key="key">
      <component
        :is="link.url ? Link : 'span'"
        v-html="link.label"
        :href="link.url ?? '#'"
        class="rounded-md px-3.5 py-2 text-sm transition-colors duration-150"
        :class="link.active
          ? 'bg-primary font-semibold text-white shadow-md'
          : link.url
            ? 'border border-line bg-surface text-ink-secondary hover:bg-surface-hover hover:text-ink'
            : 'text-ink-muted'"
      />
    </template>
  </div>
</template>
