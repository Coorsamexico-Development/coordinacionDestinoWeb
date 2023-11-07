<script setup>
 var props = defineProps({
     color:String, 
 });

</script>
<template>
   <button :style="{backgroundColor:color}" class="flex items-center justify-center px-2 py-0 rounded-full">
      <img class="w-5" src="../../assets/img/eye.png" />
   </button>
</template>