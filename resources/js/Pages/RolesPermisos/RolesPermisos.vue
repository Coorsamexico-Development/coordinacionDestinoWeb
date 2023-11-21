<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import {ref, watch, computed, reactive } from "vue";
  import TableRoles from './Partials/TableRoles.vue';
  import TablePermissions from './Partials/TablePermissions.vue';
  import axios from 'axios';

  var props = defineProps({
      roles:Object,
      permisos:Object
  });

  const rol = ref(null);

  const selectRol = (id) => 
  { 
    //console.log(id)
    rol.value = id
  }
 
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
            <div class="py-2 bg-white rounded-lg">
              <div class="flex flex-row justify-center">
                <h1 class="text-center mr-4">Roles para usuario</h1>
                <button class="bg-[#697FEA] px-2 rounded-full">
                   <p class="text-white">+</p>
                </button>
              </div>
               <TableRoles :roles="roles" @selectRol="selectRol" />
            </div>
            <!--Permisos-->
            <div class="py-2 bg-white rounded-lg">
              <h1 class="text-center">Permisos asignables</h1>
              <TablePermissions 
              :permisos="permisos"  
              :rol="rol"
                />
            </div>
        </div>
     </AppLayout>
</template>