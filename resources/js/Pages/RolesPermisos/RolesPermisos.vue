<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import {ref, watch, computed, reactive } from "vue";
  import TableRoles from './Partials/TableRoles.vue';

  var props = defineProps({
      roles:Object,
      permisos:Object
  });

</script>
<template>
     <AppLayout title="Roles y permisos">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Roles y permisos
            </h2>
        </template>
        <div class="grid grid-cols-2 gap-4 mx-6 mt-8" style="font-family: 'Montserrat';">
            <!--Roles-->
            <div class="bg-white rounded-lg">
               <h1 class="text-center">Roles para usuario</h1>
               <TableRoles :roles="roles" />
            </div>
            <!--Permisos-->
            <div>

            </div>
        </div>
     </AppLayout>
</template>