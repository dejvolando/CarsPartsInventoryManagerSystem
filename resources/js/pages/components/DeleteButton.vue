<script setup lang="ts">
defineProps<{
    title: string
}>()
</script>

<template>
    <button type="button" class="btn btn-danger">
      <i class="bi bi-trash"></i> Vymazať {{ title }}
    </button>
</template>


