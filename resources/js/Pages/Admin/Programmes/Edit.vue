<script setup>
import { Head } from '@inertiajs/vue3'
import ProgrammeForm from '@/Components/ProgrammeForm.vue'

const props = defineProps({
  programme: Object,
})
</script>

<template>
  <Head :title="`Modifier : ${programme.title} — Admin`" />
  <ProgrammeForm
    :title="`Modifier « ${programme.title} »`"
    :programme="programme"
    :submit-route="() => route('admin.programmes.update', programme.id)"
    :method="put"
  />
</template>
