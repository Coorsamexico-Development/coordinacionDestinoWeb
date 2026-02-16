<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    emailGroups: Array,
    statuses: Array,
});

const editingGroup = ref(null);
const managingRecipientsGroupId = ref(null);

const managingRecipientsGroup = computed(() => {
    if (!managingRecipientsGroupId.value) return null;
    return props.emailGroups.find(
        (g) => g.id === managingRecipientsGroupId.value,
    );
});

const form = useForm({
    name: "",
    description: "",
    active: true,
    status_ids: [],
});

const recipientForm = useForm({
    email: "",
    name: "",
    type: "to",
});

const createGroup = () => {
    editingGroup.value = { id: null };
    form.reset();
    form.clearErrors();
};

const editGroup = (group) => {
    editingGroup.value = group;
    form.name = group.name;
    form.description = group.description;
    form.active = Boolean(group.active);
    form.status_ids = group.status ? group.status.map((s) => s.id) : [];
};

const saveGroup = () => {
    if (editingGroup.value.id) {
        form.put(route("catalogs.email-groups.update", editingGroup.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("catalogs.email-groups.store"), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteGroup = (group) => {
    if (confirm("¿Estás seguro de eliminar este grupo?")) {
        router.delete(route("catalogs.email-groups.destroy", group.id));
    }
};

const closeModal = () => {
    editingGroup.value = null;
    form.reset();
    form.clearErrors();
};

const manageRecipients = (group) => {
    managingRecipientsGroupId.value = group.id;
    recipientForm.reset();
    recipientForm.clearErrors();
};

const closeRecipientsModal = () => {
    managingRecipientsGroupId.value = null;
    recipientForm.reset();
    recipientForm.clearErrors();
};

const addRecipient = () => {
    recipientForm.post(
        route(
            "catalogs.email-groups.add-recipient",
            managingRecipientsGroup.value.id,
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                recipientForm.reset();
            },
        },
    );
};

const removeRecipient = (recipientId) => {
    if (confirm("¿Estás seguro de eliminar este destinatario?")) {
        router.delete(
            route("catalogs.email-groups.remove-recipient", recipientId),
            {
                preserveScroll: true,
            },
        );
    }
};
</script>

<template>
    <AppLayout title="Grupos de Email">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Grupos de Email
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-end">
                    <PrimaryButton @click="createGroup">
                        Nuevo Grupo
                    </PrimaryButton>
                </div>

                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
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
                                        Descripción
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Activo
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
                                <tr
                                    v-for="group in emailGroups"
                                    :key="group.id"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ group.name }}
                                        <div class="text-xs text-gray-500">
                                            {{
                                                group.recipients
                                                    ? group.recipients.length
                                                    : 0
                                            }}
                                            destinatarios
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ group.description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="
                                                group.active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            "
                                        >
                                            {{
                                                group.active
                                                    ? "Activo"
                                                    : "Inactivo"
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2"
                                    >
                                        <SecondaryButton
                                            @click="manageRecipients(group)"
                                        >
                                            Destinatarios
                                        </SecondaryButton>
                                        <SecondaryButton
                                            @click="editGroup(group)"
                                        >
                                            Editar
                                        </SecondaryButton>
                                        <DangerButton
                                            @click="deleteGroup(group)"
                                        >
                                            Eliminar
                                        </DangerButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Crear/Editar Grupo -->
        <DialogModal :show="editingGroup != null" @close="closeModal">
            <template #title>
                {{ editingGroup?.id ? "Editar Grupo" : "Nuevo Grupo" }}
            </template>

            <template #content>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            autofocus
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Descripción" />
                        <TextInput
                            id="description"
                            v-model="form.description"
                            type="text"
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <div class="block">
                        <label class="flex items-center">
                            <Checkbox
                                v-model:checked="form.active"
                                name="active"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Activo</span
                            >
                        </label>
                    </div>

                    <div v-if="statuses.length > 0">
                        <InputLabel value="Estatus Asociados" />
                        <div
                            class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2"
                        >
                            <label
                                v-for="status in statuses"
                                :key="status.id"
                                class="flex items-center"
                            >
                                <Checkbox
                                    v-model:checked="form.status_ids"
                                    :value="status.id"
                                />
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ status.nombre }}
                                    <span
                                        class="ml-1 w-3 h-3 inline-block rounded-full"
                                        :style="{
                                            backgroundColor: status.color,
                                        }"
                                    ></span>
                                </span>
                            </label>
                        </div>
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
                    @click="saveGroup"
                >
                    Guardar
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Modal Gestionar Destinatarios -->
        <DialogModal
            :show="managingRecipientsGroup != null"
            @close="closeRecipientsModal"
        >
            <template #title>
                Destinatarios - {{ managingRecipientsGroup?.name }}
            </template>

            <template #content>
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                        Agregar Destinatario
                    </h4>
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end"
                    >
                        <div>
                            <InputLabel for="recipient_email" value="Email" />
                            <TextInput
                                id="recipient_email"
                                v-model="recipientForm.email"
                                type="email"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="recipientForm.errors.email"
                                class="mt-2"
                            />
                        </div>
                        <div>
                            <InputLabel
                                for="recipient_name"
                                value="Nombre (Opcional)"
                            />
                            <TextInput
                                id="recipient_name"
                                v-model="recipientForm.name"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>
                        <div>
                            <InputLabel for="recipient_type" value="Tipo" />
                            <select
                                id="recipient_type"
                                v-model="recipientForm.type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            >
                                <option value="to">Para (To)</option>
                                <option value="cc">CC</option>
                                <option value="bcc">CCO (BCC)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <PrimaryButton
                            :class="{ 'opacity-25': recipientForm.processing }"
                            :disabled="recipientForm.processing"
                            @click="addRecipient"
                        >
                            Agregar
                        </PrimaryButton>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                        Lista de Destinatarios
                    </h4>
                    <div class="bg-white border rounded-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                                    >
                                        Tipo
                                    </th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr
                                    v-for="recipient in managingRecipientsGroup?.recipients"
                                    :key="recipient.id"
                                >
                                    <td class="px-4 py-2 text-sm">
                                        {{ recipient.email }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        {{ recipient.name || "-" }}
                                    </td>
                                    <td class="px-4 py-2 text-sm uppercase">
                                        <span
                                            :class="{
                                                'bg-blue-100 text-blue-800':
                                                    recipient.type === 'to',
                                                'bg-gray-100 text-gray-800':
                                                    recipient.type === 'cc',
                                                'bg-yellow-100 text-yellow-800':
                                                    recipient.type === 'bcc',
                                            }"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ recipient.type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <button
                                            @click="
                                                removeRecipient(recipient.id)
                                            "
                                            class="text-red-600 hover:text-red-900 text-sm"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        !managingRecipientsGroup?.recipients
                                            ?.length
                                    "
                                >
                                    <td
                                        colspan="4"
                                        class="px-4 py-4 text-center text-gray-500 text-sm"
                                    >
                                        No hay destinatarios en este grupo.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeRecipientsModal">
                    Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
