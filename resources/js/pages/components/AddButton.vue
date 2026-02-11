<script setup lang="ts">
defineProps<{
    title: string
}>()
</script>

<template>
    <button type="button" class="btn btn-primary">
        <i class="bi bi-plus-square"></i> Pridať {{ title }}
    </button>
</template>


