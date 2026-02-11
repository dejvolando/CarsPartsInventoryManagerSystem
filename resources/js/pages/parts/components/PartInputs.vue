<script setup lang="ts">

import {Car} from "@/types/car";
import {Part} from "@/types/part";

defineProps<{
    form: any | Part
    cars: Car[]
}>()

</script>

<template>
    <div class="mb-3">
        <label for="partName" class="form-label">Názov dielu</label>
        <input
            v-model="form.name"
            type="text"
            id="partName"
            class="form-control"
            placeholder="Napr. Výfuk, dvere..."
        >
        <div v-if="form.errors.name" class="text-danger mt-1">
            {{ form.errors.name }}
        </div>
    </div>

    <div class="mb-3">
        <label for="serialNum" class="form-label">Seriové číslo (6-12)</label>
        <input
            v-model="form.serial_number"
            @input="form.serial_number = form.serial_number.toUpperCase()"
            maxlength="12"
            type="text"
            id="serialNum"
            class="form-control"
            placeholder="Napr. XYSA1136...">
        <div v-if="form.errors.serial_number" class="text-danger mt-1">
            {{ form.errors.serial_number}}
        </div>
    </div>

    <div class="d-flex flex-column  align-items-start gap-2">
        <label for="selectCar" class="form-label">Priradiť k autu</label>
        <select v-model="form.car_id"  class="form-select" id="selectCar">
            <option :value="null">Žiadne auto</option>
            <option v-for="car in cars" :key="car.id" :value="car.id">{{car.name + ': ' + (car.registration_number ?? 'Bez EČV')}}</option>
        </select>
    </div>
</template>
