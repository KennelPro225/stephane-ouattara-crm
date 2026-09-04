<script setup>
import { Head } from '@inertiajs/vue3'
import ProgrammeForm from '@/Components/ProgrammeForm.vue'
</script>

<template>
  <Head title="Nouveau programme — Admin" />
  <ProgrammeForm title="Créer un nouveau programme" :submit-route="() => route('admin.programmes.store')" :method="post" />
</template>
