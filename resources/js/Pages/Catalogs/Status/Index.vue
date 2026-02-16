<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Select from "@/Components/Select.vue";

const props = defineProps({
    statuses: Array,
    emailGroups: Array,
});

const editingStatus = ref(null);
const form = useForm({
    nombre: "",
    color: "",
    email_group_id: "",
});

const editStatus = (status) => {
    editingStatus.value = status;
    form.nombre = status.nombre;
    form.color = status.color;
    form.email_group_id = status.email_group_id;
};

const updateStatus = () => {
    form.put(route("catalogs.status.update", editingStatus.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingStatus.value = null;
            form.reset();
        },
    });
};

const closeModal = () => {
    editingStatus.value = null;
    form.reset();
    form.clearErrors();
};
</script>

<template>
    <AppLayout title="Catálogo de Estatus">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Catálogo de Estatus
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        ID
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Color
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Grupo de Email
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="status in statuses" :key="status.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ status.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ status.nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 rounded-full mr-2"
                                                :style="{
                                                    backgroundColor:
                                                        status.color,
                                                }"
                                            ></div>
                                            {{ status.color }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{
                                            status.email_group
                                                ? status.email_group.name
                                                : "Sin grupo"
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    >
                                        <button
                                            @click="editStatus(status)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edición -->
        <DialogModal :show="editingStatus != null" @close="closeModal">
            <template #title> Editar Estatus </template>

            <template #content>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <InputLabel for="nombre" value="Nombre" />
                        <TextInput
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            class="mt-1 block w-full"
                            autofocus
                        />
                        <InputError
                            :message="form.errors.nombre"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel for="color" value="Color" />
                        <div class="flex items-center space-x-2">
                            <input
                                type="color"
                                v-model="form.color"
                                class="h-10 w-10 p-0 border-0 rounded-md cursor-pointer"
                            />
                            <TextInput
                                id="color"
                                v-model="form.color"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>
                        <InputError :message="form.errors.color" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel
                            for="email_group_id"
                            value="Grupo de Email"
                        />
                        <Select
                            id="email_group_id"
                            v-model="form.email_group_id"
                            class="mt-1 block w-full"
                        >
                            <option value="">Seleccione un grupo</option>
                            <option
                                v-for="group in emailGroups"
                                :key="group.id"
                                :value="group.id"
                            >
                                {{ group.name }}
                            </option>
                        </Select>
                        <InputError
                            :message="form.errors.email_group_id"
                            class="mt-2"
                        />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="updateStatus"
                >
                    Guardar
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
