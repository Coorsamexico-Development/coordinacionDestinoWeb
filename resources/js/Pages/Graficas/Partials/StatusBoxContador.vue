<script setup>
 var props = defineProps({
   statu:Object
});
</script>
<template>
    <div class="flex flex-row justify-between px-5 py-4 my-2 rounded-xl" :style="{backgroundColor:statu.color}">
       <h1 class="text-white" style="font-family: 'Montserrat';">
         {{statu.nombre}}
       </h1>
       <h1 class="text-4xl font-semibold text-white" style="font-family: 'Montserrat';">
         {{ statu.confirmaciones_dts.length }}
       </h1>
    </div>
</template>