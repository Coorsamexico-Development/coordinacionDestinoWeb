<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalNewUser from './Modals/ModalNewUser.vue';
import TableUsers from './Partials/TableUsers.vue';
import {ref, watch, computed, reactive } from "vue";
import TextInput from '@/Components/TextInput.vue';
import PaginationInertia from '@/Components/PaginationInertia.vue'
import { router, Link, useForm  } from '@inertiajs/vue3'

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

const buscador = ref('');

watch(buscador, (newBusqueda) => 
{
  router.visit(route('manageUsers.index'), 
  {
    preserveScroll:true,
    preserveState:true,
    replace:true,
    data:{busqueda:newBusqueda},
    only:['users'],
  })
});

</script>
<template>
       <AppLayout title="Manage Users">
        <template #header>
            <div class="grid grid-cols-5">
                <div class="col-start-1 col-end-3 flex">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 " style="font-family: 'Montserrat';">
                     Usuarios
                   </h2>
                   <button @click="openModalNewUser()" class="px-4  mx-6 text-white bg-lime-500 rounded-xl">
                       Nuevo usuario
                   </button>
                </div>
                <div class="col-start-5">
                    <TextInput v-model="buscador" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar"  />
                </div>
            </div>
        </template>
        <div class="p-4 py-6 mx-10 mt-16 bg-white rounded-lg shadow-lg">
            <TableUsers :users="users.data" :roles="roles" :ubicaciones="ubicaciones" />
            <PaginationInertia :pagination="users" />
        </div>
    </AppLayout>
    <ModalNewUser :show="modalNewUser" @close="closeModalNewUser()" :ubicaciones="ubicaciones" :roles="roles" />
</template>