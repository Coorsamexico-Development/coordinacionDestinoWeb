<script setup>
 var props = defineProps({
     color:String, 
 });

</script>
<template>
   <button :style="{backgroundColor:color}" class="flex items-center justify-center px-2 py-1 rounded-full w-9 ">
      <img class="" src="../../assets/img/eye.png" />
   </button>
</template>