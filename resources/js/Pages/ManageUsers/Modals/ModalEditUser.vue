<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import InputLabel from '@/Components/InputLabel.vue'
 import TextInput from '@/Components/TextInput.vue'
 import { Link, useForm } from '@inertiajs/vue3'
 import  Select from '@/Components/Select.vue'
  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      user:{
         type:Object,
         required:true
      },
      roles:Object,
      ubicaciones:Object
  });
  
  const close = () => {    
     emit('close');
  };


  const form = useForm({
    nombre:'',
    ap_paterno:'',
    ap_materno:'',
    email:'',
    contraseña:'',
    role_id:'',
    ubicacion_id:''
   /*
    nombre:props.user.name,
    ap_paterno:props.user.apellido_paterno,
    ap_materno:props.user.apellido_materno,
    email:props.user.email,
    contraseña:'',
    role_id:props.user.role_id,
    ubicacion_id:props.user.ubicacion_id
    */
  })


  watch(() => props.user,(nuevosValores) => 
    { //el whatcher observa el cambio de la data
        console.log(nuevosValores);  //lo imprim
        form.nombre = nuevosValores.name
        form.ap_paterno = nuevosValores.apellido_paterno
        form.ap_materno = nuevosValores.apellido_materno
        form.email = nuevosValores.email
        form.role_id = nuevosValores.role_id
        form.ubicacion_id = nuevosValores.ubicacion_id
     });


  const editUser = () => 
  {
    form.get(route('editUser',{id:props.user.id}),{
        preserveScroll: true,
        preserveState: true,
        only: ['users','errors'],
        onSuccess: () => {
                  close(),
                  form.reset();
                }
    })
  }

</script>
<template>
   <DialogModal  :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between">
            <h1>Edicion de usuario</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
         {{ user }}
          <div class="grid grid-cols-3">
            <div>
               <InputLabel :value="'Nombre'" />
               <TextInput type="text" placeholder="Nombre" v-model="form.nombre" />
               <span class="text-red-500">{{ form.errors.nombre }}</span>
            </div>
            <div>
               <InputLabel :value="'Apellido paterno'" />
               <TextInput type="text" placeholder="Apellido paterno" v-model="form.ap_paterno" />
               <span class="text-red-500">{{ form.errors.ap_paterno }}</span>
            </div>
            <div>
               <InputLabel :value="'Apellido materno'" />
               <TextInput type="text" placeholder="Apellido materno" v-model="form.ap_materno" />
               <span class="text-red-500">{{ form.errors.ap_materno }}</span>
            </div>
          </div>
          <div class="grid grid-cols-3 my-6">
            <div>
               <InputLabel :value="'Email'" />
               <TextInput type="email" placeholder="Email" v-model="form.email" />
               <span class="text-red-500">{{ form.errors.email }}</span>
            </div>
            <div>
               <InputLabel :value="'Contraseña'" />
               <TextInput type="password" v-model="form.contraseña" />
               <span class="text-red-500">{{ form.errors.contraseña }}</span>
            </div>
            <div>
              <InputLabel :value="'Rol'" />
              <Select v-model="form.role_id">
                <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                    {{ rol.nombre }}
                </option>
              </Select>
              <span class="text-red-500">{{ form.errors.role_id }}</span>
            </div>
          </div>
          <div class="grid grid-cols-2">
            <div>
               <InputLabel :value="'Ubicacion'" />
               <Select v-model="form.ubicacion_id">
                  <option  v-for="ubicacion in ubicaciones" :key="ubicacion.id" :value="ubicacion.id">
                    {{ ubicacion.nombre_ubicacion }}
                  </option>
               </Select>
               <span class="text-red-500">{{ form.errors.ubicacion_id }}</span>
            </div>
          </div>
       </template>
       <template #footer>
           <button @click="editUser()" class="bg-[#44BFFC] rounded-xl px-4 py-2 text-white">
               Guardar
           </button>
       </template>
   </DialogModal>
</template>