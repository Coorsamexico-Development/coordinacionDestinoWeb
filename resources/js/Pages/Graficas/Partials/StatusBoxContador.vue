<script setup>
 var props = defineProps({
   statu:Object
});
</script>
<template>
    <div v-if="statu.id !== 6" class="flex flex-row justify-between px-5 py-4 my-2 rounded-xl" :style="{backgroundColor:statu.color}">
       <h1 class="text-white" style="font-family: 'Montserrat';">
         {{statu.nombre}}
       </h1>
       <h1 class="text-4xl font-semibold text-white" style="font-family: 'Montserrat';">
         {{ statu.confirmaciones_dts.length }}
       </h1>
    </div>
    <div v-else class="flex flex-row justify-between px-5 py-4 my-2 rounded-xl bg-[#E86881]">
      <h1 class="text-white" style="font-family: 'Montserrat';">
         {{statu.nombre}}
       </h1>
       <h1 class="text-4xl font-semibold text-white" style="font-family: 'Montserrat';">
         {{ statu.confirmaciones_dts.length }}
       </h1>
    </div>
</template>