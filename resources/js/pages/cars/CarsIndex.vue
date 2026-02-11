<script setup lang="ts">

import {computed, reactive, ref} from "vue";
import Modal from "@/pages/components/Modal.vue";
import CarInputs from "@/pages/cars/components/CarInputs.vue";
import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import { toast } from 'vue-toastflow'
import AppLayout from "@/pages/layouts/AppLayout.vue";
import AddButton from "@/pages/components/AddButton.vue";
import CarCard from "@/pages/cars/components/CarCard.vue";
import { Head } from '@inertiajs/vue3';
import {Car} from "@/types/car";
import {Part} from "@/types/part";

const props = defineProps<{
    cars: Car[]
    parts: Part[]
}>()

const formState = reactive({
    form: useForm({ name: '', registration_number: '', is_registered: false, parts: [] as number[]}),
    mode: 'add' as 'edit' | 'add' | 'delete' ,
    carToHandle: null as Car | null,
    processing: { form: false, deleteOne: false }
});

const isFormModalOpen = ref(false);

//open edit/add modal form
function openFormModal(mode: 'add' | 'edit', car?: Car ){
    isFormModalOpen.value = true;
    formState.mode = mode;
    formState.carToHandle = car ?? null;

    if(mode === 'edit' && formState.carToHandle){
        formState.form.name = formState.carToHandle.name;
        formState.form.registration_number = formState.carToHandle.registration_number;
        formState.form.is_registered = !!formState.carToHandle.is_registered; //!! to change from 1/0 to true/false
        formState.form.parts = props.parts.filter((part) => formState.carToHandle?.id === part.car_id).map(part => part.id);
    }
}

function openAddModal(){
    openFormModal('add');
}

function openUpdateModal(car: Car){
    openFormModal('edit', car);
}

//close edit/add form
function handleCloseFormModal(){
  isFormModalOpen.value = false;
  resetForm();
}

//open close delete modal
const isDeleteModalOpen = ref(false);
function openDeleteModal(car: Car){
    isDeleteModalOpen.value = true;
    formState.carToHandle = car;
    formState.mode = 'delete'
}

function closeDeleteModal(){
    isDeleteModalOpen.value = false;
    resetForm();
}


//submitting add/edit/delete
function handleSubmitModal(){
    if(formState.mode === 'add'){
        create();
    }
    else if(formState.mode === 'edit'){
        update();
    }
    else if(formState.mode === 'delete'){
        remove();
    }
}

//reset the form
function resetForm(){
    formState.form.resetAndClearErrors()
}

//create part
function create(){
    formState.processing.form = true;

    formState.form.post(route('cars.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Auto bolo úspešne pridané !"
            })
            handleCloseFormModal();
        },
        onError: () => {
            toast.error({
                title: "Nastala chyba pri pridavaní auta !",
            })

        },
        onFinish: () => {
            formState.processing.form = false;
        }
    })

}

//update car
function update(){
    if(!formState.carToHandle){
        toast.error({
            title: "Nevybrali ste auto na úpravu !"
        })
        handleCloseFormModal();
        return;
    }
    formState.processing.form = true;

    formState.form.patch(route('cars.update',  {car: formState.carToHandle}), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Auto bolo úspešne upravené !"
            });
            handleCloseFormModal();
        },
        onError: () => {
            toast.error({
                title: "Nastala chyba pri úprave auta !"
            })
        },
        onFinish: () => {
            formState.processing.form = false;
        }
    })
}

//delete car
function remove(){
    if(!formState.carToHandle){
        toast.error({
            title: "Nevybrali ste auto na mazanie !"
        })
        closeDeleteModal();
        return;
    }
    formState.processing.deleteOne = true

    router.delete(route('cars.delete', {car: formState.carToHandle}), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Auto bolo úspešne vymazané !"
            });
            closeDeleteModal();
        },
        onError: () => {
            toast.error({
                title: "Nastala chyba pri mazaní auta !"
            })
        },
        onFinish: () => {
            formState.processing.deleteOne = false;
        }
    })
}

//parts modal
const isInfoModalOpen = ref(false);
function openInfoModal(car: Car){
  isInfoModalOpen.value = true
  formState.carToHandle = car;
}

function closeInfoModal(){
  isInfoModalOpen.value = false;
  formState.carToHandle = null;
}

const carParts = computed(() => props.parts.filter((part) => part.car_id === formState.carToHandle?.id));

//filtering and searching cars
const searchByName = ref('');
const searchByRegistrationNumber = ref('');
const filterByRegistrationNumber = ref<'has_reg_num' | 'without_reg_num' | 'all'>('all');
const filterByRegistration = ref<'registered' | 'not_registered' | 'all'>('all');
const filterByParts = ref<'has_parts' | 'without_parts' | 'all'>('all')

const displayedCars = computed(() =>
    props.cars.filter((car) => (filterByRegistration.value === 'all'
    || (filterByRegistration.value === 'registered' && car.is_registered)
    || (filterByRegistration.value === 'not_registered' && !car.is_registered))

    && (filterByParts.value === 'all'
    || (filterByParts.value === 'has_parts' && car.parts?.length)
    || (filterByParts.value === 'without_parts' && !car.parts?.length))

    && (filterByRegistrationNumber.value === 'all'
    || (filterByRegistrationNumber.value === 'has_reg_num' && car.registration_number)
    || (filterByRegistrationNumber.value === 'without_reg_num' && !car.registration_number))

    && (car.name.toLowerCase().includes(searchByName.value.toLowerCase()))

    && (!searchByRegistrationNumber.value)
    || ((car.registration_number ?? '').toUpperCase().includes(searchByRegistrationNumber.value.toUpperCase())))
);
</script>

<template>
    <Head title="Zoznam áut" />

    <AppLayout >
        <div class="container-fluid">
            <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between p-4">
                <div class="d-flex flex-column flex-md-row gap-2 flex-grow-1">
                    <form class="flex-grow-1" role="search" @submit.prevent>
                        <input v-model="searchByName" class="form-control" type="search" placeholder="Zadajte názov auta"/>
                    </form>
                    <form class="flex-grow-1" role="search" @submit.prevent>
                      <input v-model="searchByRegistrationNumber" :disabled="filterByRegistrationNumber === 'without_reg_num'" class="form-control" type="search" placeholder="Zadajte EČV auta"/>
                    </form>
                    <div class="d-flex gap-1">
                        <select v-model="filterByRegistration" class="form-select">
                            <option value="all">Všetky autá</option>
                            <option value="registered">Registrované</option>
                            <option value="not_registered">Neregistrované</option>
                        </select>

                        <select v-model="filterByParts" class="form-select">
                            <option value="all">Všetky autá</option>
                            <option value="has_parts">Má diely</option>
                            <option value="without_parts">Nemá diely</option>
                        </select>

                        <select v-model="filterByRegistrationNumber" :disabled="searchByRegistrationNumber.toString().length > 0" class="form-select">
                          <option value="all">Všetky autá</option>
                          <option value="has_reg_num">S EČV</option>
                          <option value="without_reg_num">Bez EČV</option>
                        </select>
                    </div>
                </div>
                <AddButton @click="openAddModal" title="auto" />
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 px-4 justify-content-start">
                <div class="col" v-for="car in displayedCars" :key="car.id">
                    <CarCard
                            class="h-100"
                            :car="car"
                            @edit="openUpdateModal"
                            @delete="openDeleteModal"
                            @show="openInfoModal"
                    />
                </div>
            </div>

            <div v-if="!displayedCars.length" class="text-center text-muted py-5">
                <p class="fs-5">Nenašli sa žiadne autá.</p>
            </div>
        </div>
    </AppLayout>

    <Modal :title="formState.mode === 'add' ? 'Atribúty auta' : 'Úprava atribútov auta' " :mode="formState.mode" :is-modal-open="isFormModalOpen" button-title="auto" :processing="formState.processing.form"
           @close="handleCloseFormModal" @confirm="handleSubmitModal">
        <CarInputs :form="formState.form" :parts="parts" :car="formState.carToHandle"/>
    </Modal>

    <Modal title="Vymazať auto" mode="delete" :is-modal-open="isDeleteModalOpen" button-title="" :processing="formState.processing.deleteOne"
           @close="closeDeleteModal" @confirm="handleSubmitModal">
        Naozaj chcete vymazať toto auto ?
    </Modal>

    <Modal title="Zoznam dielov priradených k autu" mode="info" :is-modal-open="isInfoModalOpen" button-title="" :processing="false" @close="closeInfoModal">
        <div v-if="!carParts.length">
            Žiadne diely niesu priradené k tomuto autu.
        </div>
        <div v-else v-for="part in carParts" :key="part.id" class="card mb-2">
            <div class="card-body">
                <p class="card-text">
                    {{ 'Názov: ' + part.name + ' S.Č: ' + part.serial_number }}
                </p>
            </div>
        </div>
    </Modal>
</template>
