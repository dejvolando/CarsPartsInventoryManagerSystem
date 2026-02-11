<script setup lang="ts">

import EditButton from "@/pages/components/EditButton.vue";
import DeleteButton from "@/pages/components/DeleteButton.vue";
import {Part} from "@/types/part";
import {Car} from "@/types/car";

defineProps<{
    part: Part
    car: Car | undefined
}>()

defineEmits<{
    (e: 'edit', part: Part): void
    (e: 'delete', part: Part): void
}>()

</script>

<template>
  <div class="card h-100 shadow-sm border-2 border-dark-subtle">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title text-truncate" :title="part.name">{{ part.name }}</h5>
      <h6 class="card-subtitle mb-3 text-body-secondary">
        {{ 'S.Č: ' + part.serial_number }}
      </h6>
      <p class="card-text text-truncate" :title="car ? car.name + ': ' + (car.registration_number ?? 'Bez EČV') : 'Nepriradený'">
        Priradený k autu: <strong>{{ car ? car.name + ': ' + (car.registration_number ?? 'Bez EČV') : "Nepriradený" }}</strong>
      </p>
      <div class="d-flex gap-2 mt-auto pt-3 border-top">
        <EditButton
            @click="$emit('edit', part)"
            title=""
            class="flex-grow-1 justify-content-center"/>
        <DeleteButton
            @click.prevent="$emit('delete', part)"
            title=""
            class="flex-grow-1 justify-content-center"/>
      </div>
    </div>
  </div>
</template>
