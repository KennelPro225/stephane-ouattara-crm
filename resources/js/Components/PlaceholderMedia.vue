<script setup>
defineProps({
  label: { type: String, default: '' },
  aspect: { type: String, default: '4/3' },
  border: { type: String, default: '' },
})
</script>

<template>
  <div class="grayscale placeholder-media" :style="{ aspectRatio: aspect, borderBottom: border }">
    <span v-if="label">{{ label }}</span>
  </div>
</template>
