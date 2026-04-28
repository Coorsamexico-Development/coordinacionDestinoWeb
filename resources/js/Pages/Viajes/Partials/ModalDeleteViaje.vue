<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    viaje: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close", "deleted"]);

const confirmacionInput = ref("");

const close = () => {
    confirmacionInput.value = "";
    emit("close");
};

const deleteViajeConfirmado = () => {
    if (confirmacionInput.value === props.viaje.confirmacion) {
        router.delete(route("confirmacion_dts.destroy", props.viaje.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit("deleted");
                close();
            },
        });
    } else {
        alert("La confirmación ingresada no coincide.");
    }
};

watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            confirmacionInput.value = "";
        }
    },
);
</script>

<template>
    <DialogModal :show="show" @close="close">
        <template #title> Eliminar Viaje </template>

        <template #content>
            ¿Estás seguro de que deseas eliminar este viaje? Esta acción no se
            puede deshacer.
            <p class="mt-4">
                Para confirmar, ingresa el número de confirmación:
                <strong>{{ viaje?.confirmacion }}</strong>
            </p>
            <TextInput
                v-model="confirmacionInput"
                type="text"
                class="mt-1 block w-3/4"
                placeholder="Número de confirmación"
                @keyup.enter="deleteViajeConfirmado"
            />
        </template>

        <template #footer>
            <SecondaryButton @click="close"> Cancelar </SecondaryButton>

            <DangerButton
                class="ml-3"
                :class="{
                    'opacity-25': confirmacionInput !== viaje?.confirmacion,
                }"
                :disabled="confirmacionInput !== viaje?.confirmacion"
                @click="deleteViajeConfirmado"
            >
                Eliminar
            </DangerButton>
        </template>
    </DialogModal>
</template>
