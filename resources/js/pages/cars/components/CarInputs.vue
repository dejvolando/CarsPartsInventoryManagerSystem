<script setup lang="ts">
import {Part} from "@/types/part";
import {Car} from "@/types/car";
import {computed} from "vue";

const props = defineProps<{
    form: any | Car
    parts: Part[]
    car: Car | null
}>()

const availableParts = computed(() => props.parts.filter((part) => !part.car_id || props.car?.id === part.car_id));
</script>

<template>
    <div class="mb-3">
        <label for="carName" class="form-label">Názov auta</label>
        <input
            v-model="form.name"
            type="text"
            id="carName"
            class="form-control"
            placeholder="Napr. Seat Leon"
        >
        <div v-if="form.errors.name" class="text-danger mt-1">
            {{ form.errors.name }}
        </div>
    </div>

    <div class="mb-3">
        <label for="regNum" class="form-label">Registračné číslo (EČV)</label>
        <input
            v-model="form.registration_number"
            @input="form.registration_number = form.registration_number.toUpperCase()"
            maxlength="7"
            type="text"
            id="regNum"
            class="form-control"
            placeholder="Napr. KS123XY">
        <div v-if="form.errors.registration_number" class="text-danger mt-1">
            {{ form.errors.registration_number}}
        </div>
    </div>

    <div class="form-check form-switch d-flex align-items-center gap-2 mb-3">
        <input
            v-model="form.is_registered"
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="isRegisteredSwitch"
            style="cursor: pointer;">
        <label class="form-check-label cursor-pointer" for="isRegisteredSwitch">
            Vozidlo je registrované
        </label>
    </div>
    <div class="d-flex flex-column  align-items-start gap-2">
        <label for="selectParts" class="form-label">Priradiť/odobrať diely dostupné k danému autu</label>
            <div v-if="!availableParts.length">
                Žiadne diely niesu aktuálne dostupné.
            </div>
            <select v-else id="selectParts" v-model="form.parts" class="form-select" size="3" multiple>
                <option v-for="part in availableParts" :key="part.id" :value="part.id">{{ part.name + ': ' + part.serial_number }}</option>
            </select>
        <label v-if="availableParts.length" for="selectParts" class="form-label">(CTRL + ľavé tlačitko myši pre zvolenie/odobratie viacero dielov)</label>
    </div>
</template>
