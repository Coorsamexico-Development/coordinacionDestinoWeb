<script setup>
import { ref } from "vue";
import ButtonWatch from "@/Components/ButtonWatch.vue";

const props = defineProps({
    title: String,
    items: Array,
    type: {
        type: String,
        default: "oc", // 'oc' or 'factura'
    },
    downloadRoute: String,
    viaje: Number,
});

const emit = defineEmits(["openIncidencias"]);

const showDetails = ref(false);
const itemActual = ref(-1);

const toggleDetails = (item) => {
    itemActual.value = item;
    showDetails.value = !showDetails.value;
};
</script>

<template>
    <div class="mt-2 border-t-2">
        <div class="flex flex-row mt-2 justify-evenly">
            <h1 class="mt-2 text-lg">{{ title }}</h1>
            <a
                v-if="downloadRoute"
                :href="route(downloadRoute, { viaje: viaje })"
            >
                <button class="bg-[#44BFFC] px-8 py-2 rounded-2xl">
                    <img
                        class="w-3"
                        src="../../../../assets/img/down_arrow.png"
                    />
                </button>
            </a>
        </div>
        <div
            class="p-4 mx-2 my-4 bg-white rounded-lg drop-shadow-lg"
            v-for="item in items"
            :key="item.id"
        >
            <div
                @click="toggleDetails(item)"
                class="flex justify-between py-1 cursor-pointer"
            >
                <h1 class="text-lg font-semibold">
                    {{ type === "oc" ? item.referencia : item.factura }}
                </h1>
                <div>
                    <svg
                        v-if="showDetails"
                        class="mx-2"
                        xmlns="http://www.w3.org/2000/svg"
                        width="27.203"
                        height="15.723"
                        viewBox="0 0 27.203 15.723"
                    >
                        <path
                            id="Trazado_4273"
                            data-name="Trazado 4273"
                            d="M0,0,11.48,11.48,22.96,0"
                            transform="translate(25.081 13.602) rotate(180)"
                            fill="none"
                            stroke="#9b9b9b"
                            stroke-linecap="round"
                            stroke-width="3"
                        />
                    </svg>
                    <svg
                        v-if="!showDetails"
                        class="mx-2 rotate-180"
                        xmlns="http://www.w3.org/2000/svg"
                        width="27.203"
                        height="15.723"
                        viewBox="0 0 27.203 15.723"
                    >
                        <path
                            id="Trazado_4273"
                            data-name="Trazado 4273"
                            d="M0,0,11.48,11.48,22.96,0"
                            transform="translate(25.081 13.602) rotate(180)"
                            fill="none"
                            stroke="#9b9b9b"
                            stroke-linecap="round"
                            stroke-width="3"
                        />
                    </svg>
                </div>
            </div>
            <div>
                <Transition name="slide-fade" class="mt-2 border-t-2">
                    <div v-if="showDetails && item.id == itemActual.id">
                        <table class="w-full mt-2">
                            <thead>
                                <tr>
                                    <template v-if="type === 'oc'">
                                        <td class="text-center">Facturado</td>
                                        <td class="text-center">En POD</td>
                                    </template>
                                    <td class="text-center">Incidencias</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <template v-if="type === 'oc'">
                                        <td class="text-center">
                                            {{ item.facturado }}
                                        </td>
                                        <td class="text-center">
                                            {{ item.enPOD }}
                                        </td>
                                    </template>
                                    <td class="flex justify-center">
                                        <ButtonWatch
                                            @click="
                                                emit('openIncidencias', item, type)
                                            "
                                            class="w-8 h-6"
                                            :color="'#44BFFC'"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Transition>
            </div>
        </div>
    </div>
</template>
