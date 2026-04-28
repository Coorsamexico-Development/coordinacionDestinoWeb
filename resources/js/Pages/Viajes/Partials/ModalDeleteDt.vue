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
    dt: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close", "deleted"]);

const dtInput = ref("");

const close = () => {
    dtInput.value = "";
    emit("close");
};

const deleteDtConfirmado = () => {
    if (dtInput.value === props.dt.referencia_dt) {
        router.delete(route("dts.destroy", props.dt.dt_id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit("deleted");
                close();
            },
        });
    } else {
        alert("La referencia del DT ingresada no coincide.");
    }
};

watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            dtInput.value = "";
        }
    },
);
</script>

<template>
    <DialogModal :show="show" @close="close">
        <template #title> Eliminar DT (Documento de Transporte) </template>

        <template #content>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Advertencia crítica</p>
                <p>Al eliminar este DT, se eliminarán **todas** las confirmaciones y OCs asociadas a él.</p>
            </div>

            ¿Estás seguro de que deseas eliminar este DT? Esta acción no se
            puede deshacer.
            <p class="mt-4">
                Para confirmar, ingresa la referencia del DT:
                <strong>{{ dt?.referencia_dt }}</strong>
            </p>
            <TextInput
                v-model="dtInput"
                type="text"
                class="mt-1 block w-3/4"
                placeholder="Referencia del DT"
                @keyup.enter="deleteDtConfirmado"
            />
        </template>

        <template #footer>
            <SecondaryButton @click="close"> Cancelar </SecondaryButton>

            <DangerButton
                class="ml-3"
                :class="{
                    'opacity-25': dtInput !== dt?.referencia_dt,
                }"
                :disabled="dtInput !== dt?.referencia_dt"
                @click="deleteDtConfirmado"
            >
                Eliminar DT y Confirmaciones
            </DangerButton>
        </template>
    </DialogModal>
</template>
