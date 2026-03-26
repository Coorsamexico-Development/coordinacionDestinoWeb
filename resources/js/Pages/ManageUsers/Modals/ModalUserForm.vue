<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import MultiSelect from "@/Components/MultiSelect.vue";
import Select from "@/Components/Select.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const emit = defineEmits(["close"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        default: null,
    },
    roles: Object,
    ubicaciones: Object,
});

const close = () => {
    console.log("cerrando modal");
    emit("close");
};

const form = useForm({
    nombre: "",
    ap_paterno: "",
    ap_materno: "",
    email: "",
    contraseña: "",
    role_id: "",
    ubicacion_id: [],
});

watch(
    () => props.show,
    (isOpen) => {
        if (isOpen) {
            console.log(props.user);
            form.clearErrors();
            if (props.user && props.user.id) {
                // Modo Edición
                form.nombre = props.user.name || "";
                form.ap_paterno = props.user.apellido_paterno || "";
                form.ap_materno = props.user.apellido_materno || "";
                form.email = props.user.email || "";
                form.role_id = props.user.role_id || "";
                form.ubicacion_id = props.user.ubicaciones
                    ? props.user.ubicaciones.map((u) => u.id)
                    : [];
                form.contraseña = "";
            } else {
                // Modo Creación
                form.reset();
                form.ubicacion_id = [];
            }
        }
    },
    {
        immediate: true,
    },
);

const saveUser = () => {
    if (props.user && props.user.id) {
        form.get(route("editUser", { id: props.user.id }), {
            preserveScroll: true,
            preserveState: true,
            only: ["users", "errors"],
            onSuccess: () => {
                close();
                form.reset();
            },
        });
    } else {
        form.get(route("saveUser"), {
            preserveScroll: true,
            preserveState: true,
            only: ["users", "errors"],
            onSuccess: () => {
                close();
                form.reset();
            },
        });
    }
};
</script>

<template>
    <DialogModal :show="show" @close="close">
        <template #title>
            <div class="flex flex-row justify-between">
                <h1>
                    {{
                        user && user.id ? "Edición de usuario" : "Nuevo usuario"
                    }}
                </h1>
                <span
                    @click="close"
                    class="cursor-pointer font-bold text-gray-500 hover:text-gray-700"
                >
                    X
                </span>
            </div>
        </template>
        <template #content>
            <div class="mx-2">
                <InputLabel :value="'Ubicacion'" />
                <MultiSelect
                    v-model="form.ubicacion_id"
                    :options="ubicaciones"
                    labelKey="nombre_ubicacion"
                    valueKey="id"
                    placeholder="Selecciona ubicaciones..."
                />
                <span class="text-red-500 text-sm block mt-1">{{
                    form.errors.ubicacion_id
                }}</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-2">
                <div>
                    <InputLabel :value="'Nombre'" />
                    <TextInput
                        type="text"
                        placeholder="Nombre"
                        v-model="form.nombre"
                        class="w-full"
                    />
                    <span class="text-red-500 text-sm">{{
                        form.errors.nombre
                    }}</span>
                </div>
                <div>
                    <InputLabel :value="'Apellido paterno'" />
                    <TextInput
                        type="text"
                        placeholder="Apellido paterno"
                        v-model="form.ap_paterno"
                        class="w-full"
                    />
                    <span class="text-red-500 text-sm">{{
                        form.errors.ap_paterno
                    }}</span>
                </div>
                <div>
                    <InputLabel :value="'Apellido materno'" />
                    <TextInput
                        type="text"
                        placeholder="Apellido materno"
                        v-model="form.ap_materno"
                        class="w-full"
                    />
                    <span class="text-red-500 text-sm">{{
                        form.errors.ap_materno
                    }}</span>
                </div>
                <div>
                    <InputLabel :value="'Email'" />
                    <TextInput
                        type="email"
                        placeholder="Email"
                        v-model="form.email"
                        class="w-full"
                    />
                    <span class="text-red-500 text-sm">{{
                        form.errors.email
                    }}</span>
                </div>
                <div>
                    <InputLabel :value="'Contraseña'" />
                    <TextInput
                        type="password"
                        v-model="form.contraseña"
                        class="w-full"
                    />
                    <span class="text-red-500 text-sm">{{
                        form.errors.contraseña
                    }}</span>
                </div>

                <div>
                    <InputLabel :value="'Rol'" />
                    <Select v-model="form.role_id" class="w-full">
                        <option
                            v-for="rol in roles"
                            :key="rol.id"
                            :value="rol.id"
                        >
                            {{ rol.nombre }}
                        </option>
                    </Select>
                    <span class="text-red-500 text-sm">{{
                        form.errors.role_id
                    }}</span>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="saveUser()"
                class="bg-[#44BFFC] hover:bg-blue-500 transition-colors rounded-xl px-4 py-2 text-white"
            >
                Guardar
            </button>
        </template>
    </DialogModal>
</template>
