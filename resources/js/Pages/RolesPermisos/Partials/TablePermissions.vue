<script setup>
 var props = defineProps({
    permisos:Object
  });

</script>
<template>
    <div class="w-full">
        <table class="w-full">
            <thead class="">
                <tr class="border-b-2">
                    <th class="font-semibold">Nombre</th>
                    <th class="font-semibold">Activo</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="permiso in permisos" :key="permiso.id">
                   <td>{{ permiso.nombre }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>