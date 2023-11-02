<script setup>
import ToastOptions  from 'vue3-toastify';
var props = defineProps({
    toastProps: Object
});

const watch = (id) => 
{
  console.log(id)
}
</script>
<template>
    <h1><strong>El DT : </strong>{{ props.toastProps.data.dt }}</h1>
    <h1><strong>Con confirmación :</strong> {{ props.toastProps.data.confirmacion }}</h1>
    <h1>Ha cambiado al status <strong>{{ props.toastProps.data.status }}</strong></h1>
    <button @click="watch(props.toastProps.data.id)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
         <img class="w-6" src="../../../../assets/img/eye.png" />
    </button>
</template>