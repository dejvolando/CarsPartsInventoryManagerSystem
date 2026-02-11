<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/pages/layouts/AppLayout.vue";

defineProps<{
  totalCars: number,
  totalParts: number
}>();

</script>

<template>
    <Head title="CarsPartsManagerSystem"/>

    <AppLayout>
      <div class="d-flex justify-content-center align-items-center text-body">
        <div class="card">
          <div class="card-body d-flex flex-column text-center">
            <div>
              <span>Vitajte v systéme CarsPartsManager!</span>
            </div>
            <div>
              <strong>Počet áut: {{ totalCars }} Počet dielov: {{ totalParts }} </strong>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
</template>
