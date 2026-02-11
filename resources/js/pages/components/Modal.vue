<script setup lang="ts">
defineProps<{
    isModalOpen: boolean
    title: string
    processing: boolean
    mode: string
    buttonTitle: string
}>()

defineEmits(['close', 'confirm'])

</script>

<template>
    <div v-if="isModalOpen" class="modal d-block"
         style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button @click="$emit('close')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot/>
                </div>
                <div class="modal-footer">
                    <button v-if="mode === 'delete'" @click="$emit('close')" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                    <button v-else @click="$emit('close')" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>

                    <button v-if="mode === 'delete'" @click="$emit('confirm')" :disabled="processing" type="button"  class="btn btn-danger">{{processing ? 'Maže sa ...' : 'Áno'}}</button>
                    <button v-else-if="mode === 'edit'" @click="$emit('confirm')" :disabled="processing" type="button" class="btn btn-success"><i v-if="!processing" class="bi bi-pencil"/> {{ processing ? 'Upravuje sa ...' : `Upraviť ${buttonTitle}`}}</button>
                    <button v-else-if="mode === 'add'" @click="$emit('confirm')" :disabled="processing" type="button" class="btn btn-primary"><i v-if="!processing" class="bi bi-plus-square "/> {{ processing ? 'Pridáva sa...' : `Pridať ${buttonTitle}` }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

