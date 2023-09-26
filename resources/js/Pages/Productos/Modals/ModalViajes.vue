<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ModalIncidencias from './ModalIncidencias.vue';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      producto:Object,
      viajes:Array
  });

  const close = () => 
  { 
     emit('close');
  };

  const modalWatchIncidencias = ref(false);

  const openModalIncidencias = (referencia) => 
  {
    //checar incidencias
    axios.get(route('consultarOcs'),{
        params:
        {
            confirmacion:referencia
        }
    }).then(response =>
    {
      console.log(response.data);

    }).catch(err => {
        console.log(err);
    })

    modalWatchIncidencias.value = true;
  }

  const closeModalIncidencias = () => 
  {
    modalWatchIncidencias.value = false;
  }

</script>
<template>
     <DialogModal maxWidth="4xl"  :show="show" @close="close()">
        <template #title>
         <div class="flex flex-row justify-between">
            <h1>Viajes en los que se ha registrado con incidencia</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
         <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center">Confirmación</th>
                    <th class="text-center">No.Cajas</th>
                    <th class="text-center">Cerrado</th>
                    <th class="text-center">Incidencias</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="viaje in viajes" :key="viaje.id">
                  <td class="text-center">
                     {{ viaje.confirmacion }}
                  </td>
                  <td class="text-center">
                    {{ viaje.numero_cajas }}
                  </td>
                  <td class="text-center">
                    <div v-if="viaje.cerrado">
                        <svg class="w-6 h-6 m-auto text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div v-else>
                        <svg class="w-6 h-6 m-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                  </td>
                  <td class="text-center">
                     <button @click="openModalIncidencias(viaje.confirmacion)"  class="bg-[#697FEA] rounded-2xl px-2 py-1">
                        <img class="w-4" src="../../../../assets/img/eye.png" />
                     </button>
                  </td>
                </tr>
            </tbody>
         </table>
       </template>
       <ModalIncidencias :show="modalWatchIncidencias" @close="closeModalIncidencias" />
     </DialogModal>
</template>