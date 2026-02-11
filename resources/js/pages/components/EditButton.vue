<script setup lang="ts">
defineProps<{
    title: string
}>()
</script>

<template>
    <button type="button" class="btn btn-success">
      <i class="bi bi-pencil"></i> Upraviť {{ title }}
    </button>
</template>


