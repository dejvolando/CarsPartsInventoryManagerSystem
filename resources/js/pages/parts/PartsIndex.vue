<script setup lang="ts">

import AppLayout from "@/pages/layouts/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import AddButton from "@/pages/components/AddButton.vue";
import Modal from "@/pages/components/Modal.vue";
import {computed, reactive, ref} from "vue";
import PartInputs from "@/pages/parts/components/PartInputs.vue";
import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {toast} from "vue-toastflow";
import {Part} from "@/types/part";
import PartCard from "@/pages/parts/components/PartCard.vue";
import {Car} from "@/types/car";

const props = defineProps<{
    parts: Part[]
    cars: Car[]
}>()

const formState = reactive({
    form: useForm({name: '', serial_number: '', car_id: NaN}),
    mode: 'add' as 'edit' | 'add' | 'delete',
    partToHandle: null as Part | null,
    processing: {form: false, deleteOne: false}
})

//form modal opening/closing add/edit
const isFormModalOpen = ref(false);
function openFormModal(mode: 'edit' | 'add', part?: Part){
    isFormModalOpen.value = true;
    formState.mode = mode;
    formState.partToHandle = part ?? null;

    if(mode === 'edit' && formState.partToHandle){
        formState.form.name = formState.partToHandle.name;
        formState.form.serial_number = formState.partToHandle.serial_number;
        formState.form.car_id = formState.partToHandle.car_id ?? NaN;
    }
}

function openAddModal(){
    openFormModal('add');
}

function openEditModal(part: Part){
    openFormModal('edit', part);
}

//delete modal opening/closing
const isDeleteModalOpen = ref(false);
function openDeleteModal(part: Part){
    isDeleteModalOpen.value = true
    formState.partToHandle = part;
    formState.mode = 'delete';
}

function closeDeleteModal(){
    isDeleteModalOpen.value = false;
    resetForm();
}

function handleCloseModal(){
    isFormModalOpen.value = false;
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

//resetting form
function resetForm(){
    formState.form.resetAndClearErrors()
}

//create part
function create(){
    formState.processing.form = true;
    formState.form.post(route('parts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Diel auta bol úspešne pridaný !"
            });
            handleCloseModal();
        },
        onError: () => {
            toast.error({
               title: "Nastala chyba pri pridávaní dielu !"
            })
        },
        onFinish: () => {
            formState.processing.form = false;
        }
    })
}

//update part
function update(){
    if(!formState.partToHandle){
        toast.error({
            title: "Nezvolili ste diel na úpravu !"
        })
        return;
    }

    formState.processing.form = true;
    formState.form.patch(route('parts.update', {part: formState.partToHandle}), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Diel auta bol úspešne upravený !"
            });
            handleCloseModal();
        },
        onError: () => {
            toast.error({
                title: "Nastala chyba pri úprave dielu !"
            })
        },
        onFinish: () => {
            formState.processing.form = false;
        }
    })
}

//delete part
function remove(){
    if(!formState.partToHandle){
        toast.error({
            title: "Nezvolili ste diel na mazanie !"
        })
        return;
    }

    formState.processing.deleteOne = true;
    router.delete(route('parts.delete', {part: formState.partToHandle}), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                title: "Diel auta bol úspešne vymazaný !"
            });
            closeDeleteModal();
        },
        onError: () => {
            toast.error({
                title: "Nastala chyba pri mazaní dielu !"
            })
        },
        onFinish: () => {
            formState.processing.deleteOne = false;
        }
    })
}

//filtering/searching parts
const searchByName = ref('');
const searchBySerialNumber = ref('');
const filterByAssignedToCar = ref<'assigned' | 'not_assigned' | 'all'>('all');
const displayedParts = computed(() => props.parts.filter((part) => filterByAssignedToCar.value === 'all'
    || (filterByAssignedToCar.value === 'assigned' && part.car_id != null)
    || (filterByAssignedToCar.value === 'not_assigned' && !part.car_id))
    .filter((part) => part.name.toLowerCase().includes(searchByName.value.toLowerCase()))
    .filter((part) => part?.serial_number?.toUpperCase().includes(searchBySerialNumber.value.toUpperCase())))

</script>

<template>
    <Head title="Zoznam dielov" />

    <AppLayout>
        <div class="container-fluid">
            <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between p-4">
                <div class="d-flex flex-column flex-md-row gap-2 flex-grow-1">
                    <form class="d-flex flex-grow-1" role="search">
                        <input v-model="searchByName" class="form-control me-2 w-" type="search" placeholder="Zadajte názov dielu"/>
                    </form>
                    <form class="d-flex flex-grow-1" role="search">
                        <input v-model="searchBySerialNumber" class="form-control me-2 w-" type="search" placeholder="Zadajte S.Č. dielu"/>
                    </form>
                    <div>
                        <select v-model="filterByAssignedToCar" class="form-select">
                          <option selected value="all">Všetky diely</option>
                          <option value="assigned">Priradené</option>
                          <option value="not_assigned">Nepriradené</option>
                        </select>
                    </div>
                </div>
                <AddButton @click="openAddModal" title="diel"/>
              </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 px-4 justify-content-start">
                <div class="col" v-for="part in displayedParts" :key="part.id" >
                    <PartCard :car="cars.find((car) => car.id === part.car_id)" :part="part"
                            @edit="openEditModal" @delete="openDeleteModal"/>
                </div>
            </div>

            <div v-if="!displayedParts.length" class="text-center text-muted py-5">
                <p class="fs-5">Nenašli sa žiadne diely.</p>
            </div>
        </div>
    </AppLayout>

    <Modal :title="formState.mode === 'edit' ? 'Úprava atribútov dielu' : 'Atribúty dielu'" :mode="formState.mode" :is-modal-open="isFormModalOpen" button-title="diel" :processing="formState.processing.form"
           @close="handleCloseModal" @confirm="handleSubmitModal">
        <PartInputs :form="formState.form" :cars="cars"/>
    </Modal>

    <Modal title="Vymazať diel" mode="delete" :is-modal-open="isDeleteModalOpen" button-title="" :processing="formState.processing.deleteOne"
           @close="closeDeleteModal" @confirm="handleSubmitModal">
        Naozaj chcete vymazať tento diel ?
    </Modal>
</template>

<style scoped>

</style>
