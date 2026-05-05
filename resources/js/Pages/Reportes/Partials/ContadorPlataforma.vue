<script setup>
import { computed } from "vue";
//Props
var props = defineProps({
    confirmaciones: Array,
    ubicacion: Object,
    statusId: Number,
});

const Newconfirmaciones = computed(() => {
    let confirmacionesTemporales = [];
    for (let index = 0; index < props.confirmaciones.length; index++) {
        const confirmacion = props.confirmaciones[index];
        if (confirmacion.status_id == props.statusId) {
            if (confirmacion.ubicacion_id == props.ubicacion.id) {
                confirmacionesTemporales.push(confirmacion);
            }
        }
    }

    return confirmacionesTemporales;
});
</script>
<template>
    <div class="font-semibold">
        {{ Newconfirmaciones.length }}
    </div>
</template>
