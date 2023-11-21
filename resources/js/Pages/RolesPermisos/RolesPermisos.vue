<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import {ref, watch, computed, reactive } from "vue";
  import TableRoles from './Partials/TableRoles.vue';
  import TablePermissions from './Partials/TablePermissions.vue';
  import ModalAddRoles from './Partials/ModalAddRoles.vue';
  import ModalAddPermission from './Partials/ModalAddPermission.vue';

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
 
 const modalAddRoles = ref(false);
 const openModalAddRoles = () => 
 {
   modalAddRoles.value = true;
 }
 const closeModalAddRoles = () => 
 {
  modalAddRoles.value = false;
 }

 const modalAddPermissions = ref(false);
 const openModalAddPermissions = () => 
 {
  modalAddPermissions.value = true;
 }
 const closeModalAddPermissions = () => 
 {
  modalAddPermissions.value = false;
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
                <button v-if="$page.props.auth.user.cans['add-new-rol']" @click="openModalAddRoles" class="bg-[#697FEA] px-2 rounded-full">
                   <p class="text-white">+</p>
                </button>
              </div>
               <TableRoles :roles="roles" @selectRol="selectRol" />
            </div>
            <!--Permisos-->
            <div class="py-2 bg-white rounded-lg">
              <div class="flex flex-row justify-center">
                <h1 class="text-center mr-4">Permisos asignables</h1>
                <button v-if="$page.props.auth.user.cans['add-new-permission']" @click="openModalAddPermissions" class="bg-[#697FEA] px-2 rounded-full">
                   <p class="text-white">+</p>
                </button>
              </div>
              <TablePermissions 
              :permisos="permisos"  
              :rol="rol"
                />
            </div>
        </div>
        <ModalAddRoles :show="modalAddRoles" @close="closeModalAddRoles" />
        <ModalAddPermission :show="modalAddPermissions" @close="closeModalAddPermissions" />
     </AppLayout>
</template>