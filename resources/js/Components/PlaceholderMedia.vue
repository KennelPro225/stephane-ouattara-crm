<script setup>
defineProps({
  src: { type: String, default: null },
  alt: { type: String, default: '' },
  label: { type: String, default: '' },
  aspect: { type: String, default: '4/3' },
  border: { type: String, default: '' },
})
</script>

<template>
  <img v-if="src" :src="src" :alt="alt" :style="{ aspectRatio: aspect, borderBottom: border, width: '100%', objectFit: 'cover' }">
  <div v-else class="grayscale placeholder-media" :style="{ aspectRatio: aspect, borderBottom: border }">
    <span v-if="label">{{ label }}</span>
  </div>
</template>
