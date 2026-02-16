<script setup>
import ButtonWatch from "@/Components/ButtonWatch.vue";
import { computed } from "vue";

const emit = defineEmits(["consultarHistoria"]);
const props = defineProps({
    historialStatus: {
        type: Object,
        required: true,
    },
    isLast: {
        type: Boolean,
        default: false,
    },
    beforeStatusDocumentado: {
        type: Object,
        default: null,
    },
});

const canWath = computed(() => {
    if (props.beforeStatusDocumentado == null) {
        return true;
    }

    if (props.historialStatus.status_id < 6) {
        return props.historialStatus.id == props.beforeStatusDocumentado.id;
    }
    return true;
});
</script>

<template>
    <div>
        <!--
               <button @click="cambio(historialStatus.confirmacion_dt_id)">
                  Cambio
               </button>0
               -->
        <div :class="[isLast ? 'timeline2' : 'timeline']">
            <div id="timeline-item">
                <div
                    id="timeline-icon"
                    class="flex items-center"
                    :style="{ backgroundColor: historialStatus.color }"
                >
                    <img
                        class="w-8"
                        :src="historialStatus.icon"
                        v-if="historialStatus.status == 'Enrampado'"
                    />
                    <img
                        class="w-4"
                        :src="historialStatus.icon"
                        v-else-if="historialStatus.status == 'Documentado'"
                    />
                    <img class="w-5" :src="historialStatus.icon" v-else />
                </div>
                <div id="timeline-content">
                    <div class="flex flex-row justify-between">
                        <div>
                            <h2
                                class="text-lg"
                                :style="{
                                    color: historialStatus.color,
                                }"
                            >
                                {{ historialStatus.status }}
                            </h2>
                            <div class="flex flex-row items-center">
                                <img
                                    class="w-4 h-4 mr-2"
                                    src="../../../../assets/img/reloj-de-pared.png"
                                />
                                <h1 class="text-[#9B9B9B] text-base">
                                    {{
                                        historialStatus.created_at.substring(
                                            8,
                                            10,
                                        ) +
                                        "/" +
                                        historialStatus.created_at.substring(
                                            5,
                                            7,
                                        ) +
                                        "  " +
                                        " " +
                                        historialStatus.created_at.substring(
                                            11,
                                            16,
                                        )
                                    }}
                                </h1>
                            </div>
                        </div>
                        <div
                            v-if="canWath"
                            class="flex items-center justify-center"
                        >
                            <ButtonWatch
                                class="w-8"
                                :color="'#44BFFC'"
                                @click="
                                    $emit('consultarHistoria', historialStatus)
                                "
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="absolute bg-[#9B9B9B] w-11/12 opacity-25 ml-9 mt-2"
                    style="height: 2px"
                ></div>
            </div>
        </div>
    </div>
</template>
