<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableUsers from './Partials/TableUsers.vue';
var props = defineProps({
    users:Object,
    roles:Object,
    ubicaciones:Object
});
</script>
<template>
       <AppLayout title="Manage Users">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Usuarios
            </h2>
        </template>
        <div class="p-4 mx-10 mt-16 bg-white rounded-lg shadow-lg">
            <TableUsers :users="users" :roles="roles" :ubicaciones="ubicaciones" />
        </div>
    </AppLayout>
</template>