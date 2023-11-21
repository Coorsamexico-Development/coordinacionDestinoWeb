<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import {ref, watch, computed, reactive } from "vue";
import { throttle } from 'lodash';
import axios from 'axios';

 var props = defineProps({
    permisos:Object,
    rol:Object
  });

  const emit = defineEmits(["selectPermission"])

  const selectFila = async (id, checked) => 
  {

      const response = await axios.get(route('roles.permissions',{
            permission:id,
            checked:checked,
            rol:props.rol.id
        }));

        console.log(response)

    //emit('selectPermission', id)
  }

  const permissions = ref([]);
  const getPermissions = async () => 
  {
    //hay que obtener los permisos que tiene
    const response = await axios.get(route('getPermisosByRol',{rol:props.rol.id}));
    permissions.value = response.data.permissonId;
    //console.log(response)
  }

  watch(props, throttle(function () {
    if (props.rol.id != -1) {
      getPermissions()
    }
}, 150))
</script>
<template>
    <div class="w-full">
        <table class="w-full">
            <thead class="">
                <tr class="border-b-2">
                    <th class="font-semibold">Nombre</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr  v-for="(permiso, index) in permisos" :key="index">
                   <td class="text-center">{{ permiso.nombre }}</td>
                   <td class="text-center">
                     <Checkbox 
                     :name="'checke-' + index" 
                     @click="selectFila(permiso.id, $event.target.checked)"  
                     :value="'' + permiso.id"
                     v-model:checked="permissions"
                       />

                   </td>
                </tr>
            </tbody>
        </table>  
    </div>
</template>