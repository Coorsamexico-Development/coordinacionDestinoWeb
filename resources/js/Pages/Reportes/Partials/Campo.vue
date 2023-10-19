<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import ButtonWatch from '@/Components/ButtonWatch.vue';
import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import Carrusel from '../Partials/Carrusel.vue'


 const props = defineProps({
   camposValores:Object,
   status:Object
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
});
</script>
<template>
  <div v-if="status.status_id == 4">
    <div class="mt-2 flex flex-row border-b py-2" v-for="campo in camposValores" :key="campo.id">
       <div class="mx-4 flex flex-row items-center">
          <div v-if="campo.campo == 'Cajas'">
             <img class="w-7" src="../../../../assets/img/cajas.png" />
          </div>
          <div v-if="campo.campo == 'Tracto'">
             <img class="w-10" src="../../../../assets/img/camion-carga.png" />
          </div>
          <div v-if="campo.campo == 'Remolque'">
             <img class="w-8" src="../../../../assets/img/remolque.png" />
          </div>
          <div v-if="campo.campo == 'Nombre operador'">
             <img class="w-5" src="../../../../assets/img/user_perfil.png" />
          </div>
          <div v-if="campo.campo == 'Teléfono'">
             <img class="w-6" src="../../../../assets/img/telephone_2.png" />
          </div>
       </div>
       <div>
          <div class="" v-for="(valor,key) in campo.valores" :key="valor.id">
            <h1 class="text-[#9B9B9B] text-base">{{campo.campo}}</h1>
            <div  v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                 <h3 class="text-xl  font-semibold" style="font-family: 'Montserrat';">
                   {{ valor.valor }}
                 </h3>
            </div>
            <div v-if="key == 0">
              <div class="mx-16" v-if="campo.tipo_campo == 'file'">
               <a class="" :href="valor.valor" data-fancybox    >
                  <ButtonWatch :color="'#1D96F1'" />
               </a>
              </div>
            </div>
          </div>
          <!--Carruseles-->
          <div class="" v-if="campo.tipo_campo == 'image'">
            <Carrusel :valores="campo.valores" />
         </div>
       </div>
    </div>
  </div>
  <div v-if="status.status_id == 6">
    <div class="py-2 border-b" v-for="campo in camposValores" :key="campo.id">
       <div>
          <div class="flex " v-for="(valor,key) in campo.valores" :key="valor.id">
            <div class="mx-4 flex flex-row items-center">
              <div v-if="campo.campo == 'Notas'">
                 <img class="w-7" src="../../../../assets/img/comentarios.png" />
              </div>
           </div>
            <div>
              <h1 v-if="key==0" class="text-[#9B9B9B] text-base">{{campo.campo}}</h1>
              <div  v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                   <h3 class="text-lg  font-semibold" style="font-family: 'Montserrat';">
                     {{ valor.valor }}
                   </h3>
              </div>
            </div>
            <div v-if="key == 0">
              <div class="mx-16" v-if="campo.tipo_campo == 'file'">
               <a class="" :href="valor.valor" data-fancybox    >
                  <ButtonWatch :color="'#1D96F1'" />
               </a>
              </div>
            </div>
          </div>
          <!--Carruseles-->
          <div class="mt-2" v-if="campo.tipo_campo == 'image'">
            <Carrusel :valores="campo.valores" />
         </div>
       </div>
    </div>
  </div>
  <div v-if="status.status_id == 7"> 
    <div class="py-3 border-b" v-for="campo in camposValores" :key="campo.id">
       <div>
          <div class="flex " v-for="(valor,key) in campo.valores" :key="valor.id">
            <div class="mx-4 flex flex-row items-center">
              <div v-if="campo.campo == 'Rampa'">
                 <img class="w-7" src="../../../../assets/img/rampa.png" />
              </div>
           </div>
            <div>
              <h1 v-if="key==0" class="text-[#9B9B9B] text-base">{{campo.campo}}</h1>
              <div  v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                   <h3 class="text-lg  font-semibold" style="font-family: 'Montserrat';">
                     {{ valor.valor }}
                   </h3>
              </div>
            </div>
            <div v-if="key == 0">
              <div class="mx-16" v-if="campo.tipo_campo == 'file'">
               <a class="" :href="valor.valor" data-fancybox    >
                  <ButtonWatch :color="'#1D96F1'" />
               </a>
              </div>
            </div>
          </div>
          <!--Carruseles-->
          <div class="mt-2" v-if="campo.tipo_campo == 'image'">
            <Carrusel :valores="campo.valores" />
         </div>
       </div>
    </div>
  </div>
  <div v-if="status.status_id == 9"> 
    <div class="mt-2 flex flex-row border-b py-2" v-for="campo in camposValores" :key="campo.id">
       <div class="mx-4 flex flex-row items-center">
          <div v-if="campo.campo == 'Comentarios'">
             <img class="w-7" src="../../../../assets/img/comentarios.png" />
          </div>
          <div v-if="campo.campo == 'Documento'">
             <img class="w-7" src="../../../../assets/img/table.png" />
          </div>
       </div>
       <div>
          <div class="" v-for="(valor,key) in campo.valores" :key="valor.id">
            <h1 class="text-[#9B9B9B] text-base">{{campo.campo}}</h1>
            <div  v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                 <h3 class="text-lg  font-semibold" style="font-family: 'Montserrat';">
                   {{ valor.valor }}
                 </h3>
            </div>
            <div v-if="key == 0">
              <div class="" v-if="campo.tipo_campo == 'file'">
               <a class="" :href="valor.valor" data-fancybox    >
                  <ButtonWatch :color="'#1D96F1'" />
               </a>
              </div>
            </div>
          </div>
          <!--Carruseles-->
          <div class="" v-if="campo.tipo_campo == 'image'">
            <Carrusel :valores="campo.valores" />
         </div>
       </div>
    </div>
 </div>
</template>