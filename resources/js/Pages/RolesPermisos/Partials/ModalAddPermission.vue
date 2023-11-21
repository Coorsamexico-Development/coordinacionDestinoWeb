<script setup>
  import DialogModal from '@/Components/DialogModal.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { useForm } from '@inertiajs/vue3';

  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       }
   });

   const emit = defineEmits(["close"])
   const close = () => 
   {
     emit('close');
   }

   //Formulario
   const formNewPermiso = useForm({
       nombre: '',
    });

   const saveNewPermiso = () => 
   {
     formNewPermiso.post(route('savePermission'),{
        onSuccess : () => 
            {
                formNewPermiso.reset();
               close();
            },
        preserveScroll:true,
        preserveState:true
      });
   }

</script>
<template>
  <DialogModal :maxWidth="'3xl'"  :show="show" @close="close()">
    <template #title>
       <div class="flex flex-row justify-between">
          <h1>Nuevo permiso</h1>
          <span @click="close()">
             Cerrar
          </span>
       </div>
     </template>
     <template #content>
        <InputLabel value="Nombre del permiso:" class="my-2" />
        <TextInput v-model="formNewPermiso.nombre" class="w-full" />
        <button @click="saveNewPermiso" class="w-full my-2 bg-[#697FEA] text-white text-lg rounded-full">
            Guardar nuevo permiso
        </button>
     </template>
  </DialogModal>
</template>