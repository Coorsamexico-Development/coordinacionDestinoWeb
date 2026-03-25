<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import DialogModal from "@/Components/DialogModal.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TabButton from "@/Components/TabButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    ubicacion: {
        type: Object,
        default: () => ({}),
    },
    ubicaciones: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["close"]);

const formEdit = useForm({
    nombre_ubicacion: "",
});

const formTransfer = useForm({
    target_ubicacion_id: "",
    delete_current: false,
});

const activeTab = ref("transferir");

watch(
    () => props.ubicacion,
    (newVal) => {
        if (newVal) {
            formEdit.nombre_ubicacion = newVal.nombre_ubicacion || "";
        }
    },
    { immediate: true, deep: true },
);

const updateLocation = () => {
    if (!props.ubicacion.id) return;

    formEdit.put(route("ubicaciones.update", props.ubicacion.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const confirmingTransfer = ref(false);

const transferLocation = () => {
    if (!props.ubicacion.id) return;

    if (formTransfer.delete_current) {
        confirmingTransfer.value = true;
        return;
    }

    executeTransfer();
};

const executeTransfer = () => {
    formTransfer.post(route("ubicaciones.transfer", props.ubicacion.id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmingTransfer.value = false;
            formTransfer.reset();
            closeModal();
        },
    });
};

const closeModal = () => {
    emit("close");
    confirmingTransfer.value = false;
    formEdit.reset();
    formEdit.clearErrors();
    formTransfer.reset();
    formTransfer.clearErrors();
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal">
        <template #title>
            Opciones de Ubicación: {{ ubicacion.nombre_ubicacion }}
        </template>

        <template #content>
            <!-- Tabs -->
            <div
                class="flex flex-row justify-start pb-4 border-b mb-4 overflow-x-auto"
            >
                <TabButton
                    :active="activeTab === 'transferir'"
                    @click="activeTab = 'transferir'"
                >
                    <template #icon>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4 mr-2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                            />
                        </svg>
                    </template>
                    Transferencia
                </TabButton>
                <TabButton
                    :active="activeTab === 'editar'"
                    @click="activeTab = 'editar'"
                >
                    <template #icon>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4 mr-2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                            />
                        </svg>
                    </template>
                    Edición
                </TabButton>
            </div>

            <!-- Sección de Edición -->
            <div v-show="activeTab === 'editar'" class="mb-6">
                <h3
                    class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                >
                    Editar Nombre
                </h3>
                <form @submit.prevent="updateLocation">
                    <div class="mb-4">
                        <InputLabel
                            for="nombre_ubicacion"
                            value="Nombre de la ubicación"
                        />
                        <TextInput
                            id="nombre_ubicacion"
                            v-model="formEdit.nombre_ubicacion"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            :message="formEdit.errors.nombre_ubicacion"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex justify-end">
                        <PrimaryButton
                            :class="{ 'opacity-25': formEdit.processing }"
                            :disabled="formEdit.processing"
                        >
                            Actualizar Nombre
                        </PrimaryButton>
                    </div>
                </form>
            </div>

            <!-- Sección de Transferencia -->
            <div v-show="activeTab === 'transferir'" class="mb-6">
                <h3
                    class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                >
                    Transferir Viajes a Otra Ubicación
                </h3>
                <form @submit.prevent="transferLocation">
                    <div class="mb-4">
                        <InputLabel
                            for="target_ubicacion_id"
                            value="Ubicación Destino"
                        />
                        <select
                            id="target_ubicacion_id"
                            v-model="formTransfer.target_ubicacion_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                            required
                        >
                            <option value="" disabled>
                                Seleccione una ubicación
                            </option>
                            <template v-for="u in ubicaciones" :key="u.id">
                                <option
                                    v-if="u.id !== ubicacion.id"
                                    :value="u.id"
                                >
                                    {{ u.nombre_ubicacion }}
                                </option>
                            </template>
                        </select>
                        <InputError
                            :message="formTransfer.errors.target_ubicacion_id"
                            class="mt-2"
                        />
                        <InputError
                            :message="formTransfer.errors.Transfer"
                            class="mt-2"
                        />
                    </div>

                    <div
                        class="mb-4 mt-4 text-red-600 font-bold bg-red-100 p-2 rounded"
                    >
                        <label class="flex items-center">
                            <Checkbox
                                v-model:checked="formTransfer.delete_current"
                                name="delete_current"
                            />
                            <span class="ml-2 text-sm"
                                >Eliminar ubicación "{{
                                    ubicacion.nombre_ubicacion
                                }}" después de transferir (¡Acción
                                irreversible!)</span
                            >
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <PrimaryButton
                            class="bg-indigo-600 hover:bg-indigo-500"
                            :class="{ 'opacity-25': formTransfer.processing }"
                            :disabled="formTransfer.processing"
                        >
                            Transferir Viajes
                        </PrimaryButton>
                    </div>
                </form>
            </div>

            <!-- Sección de Eliminación Directa -->
            <!-- <div>
                <h3 class="text-lg font-medium text-red-600 border-b pb-2 mb-4">Zona de Peligro</h3>
                <p class="text-sm text-gray-600 mb-2">Si la ubicación no tiene viajes asociados, puedes eliminarla directamente.</p>
                <div class="flex">
                    <DangerButton @click="deleteLocation" :class="{ 'opacity-25': formEdit.processing }" :disabled="formEdit.processing">
                        Eliminar Ubicación
                    </DangerButton>
                </div>
                <InputError :message="formEdit.errors.Ubicacion" class="mt-2" />
            </div>
             -->
        </template>

        <template #footer>
            <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
        </template>
    </DialogModal>

    <!-- Modal de confirmación para eliminar la ubicación original -->
    <ConfirmationModal :show="confirmingTransfer" @close="confirmingTransfer = false">
        <template #title>
            Confirmar Transferencia y Eliminación
        </template>

        <template #content>
            ¿Estás seguro de que deseas transferir todos los viajes a la nueva ubicación seleccionada y
            <span class="font-bold text-red-600">eliminar permanentemente "{{ ubicacion.nombre_ubicacion }}"</span>? 
            Esta acción no se puede deshacer.
        </template>

        <template #footer>
            <SecondaryButton @click="confirmingTransfer = false">
                Cancelar
            </SecondaryButton>

            <DangerButton
                class="ml-3"
                :class="{ 'opacity-25': formTransfer.processing }"
                :disabled="formTransfer.processing"
                @click="executeTransfer"
            >
                Sí, Transferir y Eliminar
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
