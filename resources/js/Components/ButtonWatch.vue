<script setup>
 var props = defineProps({
     color:String, 
 });

</script>
<template>
   <button :style="{backgroundColor:color}" class="p-1 rounded-2xl">
      <img class="w-6" src="../../assets/img/eye.png" />
   </button>
</template>