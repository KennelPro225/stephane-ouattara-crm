<script setup>
import { Head } from '@inertiajs/vue3'
import ProgrammeForm from '@/Components/Admin/ProgrammeForm.vue'
</script>

<template>
  <Head title="Nouveau programme — Admin" />
  <ProgrammeForm title="Nouveau programme" :submit-route="route('admin.programmes.store')" method="post" />
</template>
