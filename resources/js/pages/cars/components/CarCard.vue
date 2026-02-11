<script setup lang="ts">

import EditButton from "@/pages/components/EditButton.vue";
import DeleteButton from "@/pages/components/DeleteButton.vue";
import {Car} from "@/types/car";

defineProps<{
    car: Car
}>()

defineEmits<{
    (e: 'edit', car: Car): void
    (e: 'delete', car: Car): void
    (e: 'show', car: Car): void
}>()

</script>

<template>
  <div class="card h-100 shadow-sm border-2 border-dark-subtle">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title text-truncate" :title="car.name" >
          {{ car.name }}
        </h5>
        <h6 class="card-subtitle mb-2 text-muted">
          {{car.registration_number ? 'EČV: ' + car.registration_number : 'Bez EČV' }}
        </h6>
      <div class="mb-2">
        <p class="card-text mb-1">
          Stav:
          <span :class="car.is_registered ? 'text-success fw-bold' : 'text-danger fw-bold'">
              {{ car.is_registered ? 'Registrované' : 'Neregistrované' }}
          </span>
        </p>
        <p class="card-text">
          Počet dielov: <span class="fw-bold">{{ car?.parts?.length ?? 0 }}</span>
        </p>
      </div>
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-auto border-top pt-3">
        <div class="d-flex  flex-row gap-2">
          <EditButton @click="$emit('edit', car)" title=""/>
          <DeleteButton @click.prevent="$emit('delete', car)" title=""/>
        </div>
        <button @click="$emit('show', car)" class="btn btn-info text-white">
          <i class="bi bi-eye"></i> Zobraziť diely
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
