<script setup>
import { Head } from '@inertiajs/vue3'
import ProgrammeForm from '@/Components/Admin/ProgrammeForm.vue'

const props = defineProps({
  programme: Object,
  availabilityRules: { type: Array, default: () => [] },
})
</script>

<template>
  <Head :title="`Modifier ${programme.title} — Admin`" />
  <ProgrammeForm title="Modifier le programme" :programme="programme" :availability-rules="availabilityRules" :submit-route="route('admin.programmes.update', programme.id)" method="put" />
</template>
