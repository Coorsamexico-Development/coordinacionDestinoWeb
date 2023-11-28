<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import { Link, useForm } from '@inertiajs/vue3'
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import axios from 'axios';
  import ButtonDropZone from '@/Components/ButtonDropZone.vue'
 
  const emit = defineEmits(["close","reconsultar"])
 
  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       },
       confirmacion:String,
       ocsAxios:Object
   });
 
   const close = () => { 
      emit('close');
   };
 
   const ocs = ref([]);
   let contador = ref(1);
 
   const addNewOc = () => 
   {
     
     let newOc = {
       id:contador.value ++,
       referencia:''
     }
 
     ocs.value.push(newOc);
   }
 
   const removeOc = (id) => 
   {
     ocs.value =  ocs.value.filter(oc => oc.id !== id );
   }
 
   const saveOcs = () => 
   {
     try 
     {
         axios.get(route('saveOcs', {ocs:ocs.value, confirmacion:props.confirmacion})).then(response => 
         {
            ocs.value = [];
            emit('reconsultar');
         })
         .catch(err=> 
         {
             
         })   
     } 
     catch (error) 
     {
         
     }
   }

//Formulario para subir excel
const document = ref(null)
const formNewDts = useForm({
  document: null,
  confirmacion:props.confirmacion
});

//Watcher para la carga del reporte
watch(document, (documentoCargado) => 
{
   formNewDts.document = documentoCargado
   try 
   {
      if(formNewDts.document !== null)
      {
         formNewDts.post(route('newOcsExcel'),
         {
            onSuccess: () => {
               formNewDts.reset();
               document.value = null;
               emit('reconsultar');
            },
            onError:(err) => 
            {
              console.log(err);
              formNewDts.reset();
              document.value = null;
            }
         }
         );
      }
   } 
   catch (error) 
   {
     console.log(error)  
   }
});

 
 </script>
 <template>
      <DialogModal  :show="show" @close="close()">
         <template #title>
          <div class="flex flex-row justify-between">
             <h1>OCS</h1>
             <div class="flex flex-row">
                <a :href="route('getOcsExample')" class="mx-4 text-white px-2 rounded-xl bg-[#697FEA] py-1 text-sm">
                  Descargar ejemplo
                </a>
                <ButtonDropZone v-model="document"  />
             </div>
             <span id="cerrar-modal-ocs" @click="close()">
                Cerrar
             </span>
          </div>
        </template>
        <template #content>
         <div>
               <table>
                 <thead>
                     <tr>
                       <th>Referencia</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr v-for="oc in ocs" :key="oc.id">
                       <td>
                          <button @click="removeOc(oc.id)" class="mr-2 text-red-400 uppercase ">
                             x
                          </button>
                          <TextInput v-model="oc.referencia" :required="true" >
                          </TextInput>
                       </td>
                     </tr>
                 </tbody>
               </table>
          </div>
          <div class="flex justify-center">
             <button id="boton-add-oc"  @click="addNewOc()" class="p-2 px-4 border-2 rounded-full">
               <p class="text-xl font-bold text-red-400">+</p>
            </button>
          </div>
          <div class="flex justify-end" v-if="ocs.length > 0">
             <button @click="saveOcs()" class="px-2 py-1 text-white rounded-full bg-[#44BFFC]">
               Guardar
             </button>
          </div>
          <div id="ocs-creadas">
             <h1 >OCS Creadas</h1>
             <table class="w-full">
                 <thead>
                     <tr class="border-b border-teal-300">
                         <th>
                             Referencia
                         </th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr class="text-center border-b" v-for="oc in ocsAxios" :key="oc.id">
                        <td>
                           {{ oc.referencia }}
                        </td>
                     </tr>
                 </tbody>
             </table>
          </div>
        </template>
      </DialogModal>
 </template>