<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalNewUser from './Modals/ModalNewUser.vue';
import TableUsers from './Partials/TableUsers.vue';
import {ref, watch, computed, reactive } from "vue";

var props = defineProps({
    users:Object,
    roles:Object,
    ubicaciones:Object
});

let modalNewUser = ref(false);

let openModalNewUser = () =>
{
  modalNewUser.value = true;
}

let closeModalNewUser = () => 
{
  modalNewUser.value = false;
}

</script>
<template>
       <AppLayout title="Manage Users">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Usuarios
            </h2>
            <button @click="openModalNewUser()" class="px-4 mt-2 -ml-4 text-white bg-lime-500 rounded-xl">
                Nuevo usuario
            </button>
        </template>
        <div class="p-4 mx-10 mt-16 bg-white rounded-lg shadow-lg">
            <TableUsers :users="users" :roles="roles" :ubicaciones="ubicaciones" />
        </div>
    </AppLayout>
    <ModalNewUser :show="modalNewUser" @close="closeModalNewUser()" :ubicaciones="ubicaciones" :roles="roles" />
</template>