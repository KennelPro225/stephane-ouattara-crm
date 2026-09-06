<script setup>
import { Head } from '@inertiajs/vue3'
import ProgrammeForm from '@/Components/Admin/ProgrammeForm.vue'

defineProps({
  availabilityRules: { type: Array, default: () => [] },
})
</script>

<template>
  <Head title="Nouveau programme — Admin" />
  <ProgrammeForm title="Nouveau programme" :availability-rules="availabilityRules" :submit-route="route('admin.programmes.store')" method="post" />
</template>
