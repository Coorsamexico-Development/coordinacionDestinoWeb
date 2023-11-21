<script setup>
  import DialogModal from '@/Components/DialogModal.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { useForm } from '@inertiajs/vue3'

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
   const formNewRol = useForm({
       nombre: '',
    });

   const saveNewRol = () => 
   {
      formNewRol.post(route('saveRole'),{
        onSuccess : () => 
            {
               formNewRol.reset();
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
          <h1>Nuevo rol</h1>
          <span @click="close()">
             Cerrar
          </span>
       </div>
     </template>
     <template #content>
        <InputLabel value="Nombre del rol:" class="my-2" />
        <TextInput v-model="formNewRol.nombre" class="w-full" />
        <button @click="saveNewRol" class="w-full my-2 bg-[#697FEA] text-white text-lg rounded-full">
            Guardar nuevo rol
        </button>
     </template>
  </DialogModal>
</template>