<script setup>
import ButtonWatch from "@/Components/ButtonWatch.vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import ModalWatchHistoricoStatus from "../Modals/ModalWatchHistoricoStatus.vue";
import ModalAddOcs from "../Partials/ModalAddOcs.vue";
import ModalDeleteViaje from "../../Viajes/Partials/ModalDeleteViaje.vue";
import ModalDeleteDt from "../../Viajes/Partials/ModalDeleteDt.vue";
//Props
var props = defineProps({
    dt: Object,
});

const emit = defineEmits(["deleted"]);

let infoModal = ref(null);
let status = ref([]);
//Funcion modales
let modalWatch = ref(false);

let viaje = ref(null);
const modalWatchOpen = (dt_id) => {
    viaje.value = dt_id;
    //console.log(props.dt.id)
    modalWatch.value = true;
    axios
        .get(route("showHistorico"), {
            params: {
                id: props.dt.id,
            },
        })
        .then((response) => {
            //console.log(response);
            infoModal.value = response.data.historico;
            status.value = response.data.status;
        })
        .catch((err) => {
            console.log(err);
        });
};
const modalWatchClose = () => {
    modalWatch.value = false;
    viaje.value = null;
};

let modalOcs = ref(false);
let ocs = ref([]);

const modalOcsOpen = () => {
    modalOcs.value = true;
    consultarOcs();
};

const modalOcsClose = () => {
    modalOcs.value = false;
};

const consultarOcs = () => {
    try {
        axios
            .get(route("consultarOcs", { confirmacion: props.dt.confirmacion }))
            .then((response) => {
                console.log(response.data);
                ocs.value = response.data;
            })
            .catch((err) => {});
    } catch (error) {}
};

const reVisit = (viajeAConsultar) => {
    router.visit(route("reportes.index"), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        only: ["contadores", "ubicaciones"],
    });

    axios
        .get(route("showHistorico"), {
            params: {
                id: viajeAConsultar,
            },
        })
        .then((response) => {
            console.log(response);
            infoModal.value = response.data.historico;
            status.value = response.data.status;
        })
        .catch((err) => {
            console.log(err);
        });
};

const modalDeleteViaje = ref(false);
const viajeAEliminar = ref(null);
const confirmarEliminacionViaje = (dt) => {
    viajeAEliminar.value = dt;
    modalDeleteViaje.value = true;
};

const closeModalDeleteViaje = () => {
    modalDeleteViaje.value = false;
    viajeAEliminar.value = null;
};

const modalDeleteDt = ref(false);
const dtAEliminar = ref(null);
const confirmarEliminacionDt = (dt) => {
    dtAEliminar.value = dt;
    modalDeleteDt.value = true;
};

const closeModalDeleteDt = () => {
    modalDeleteDt.value = false;
    dtAEliminar.value = null;
};

const onDelete = () => {
    emit("deleted");
};
</script>
<template>
    <div
        class="grid grid-cols-12 m-3 border rounded-lg"
        style="box-shadow: 0.3em 0.3em 1em rgba(0, 0, 0, 0.3)"
    >
        <div
            class="w-4 col-start-1 mr-4 rounded-s-lg"
            :style="{ backgroundColor: dt.color }"
        ></div>
        <div class="col-start-2 col-end-7 py-2">
            <div class="flex flex-row my-1" id="dt">
                <h1 class="text-xl font-semibold uppercase">IDENTIFICADOR:</h1>
                <p class="text-xl">{{ dt.referencia_dt }}</p>
            </div>
            <div class="flex flex-row my-1" id="confirmacion">
                <h1
                    class="text-sm font-semibold uppercase"
                    style="letter-spacing: 2px"
                >
                    Conf:
                </h1>
                <p class="text-sm">{{ dt.confirmacion }}</p>
            </div>
            <div class="flex flex-row my-1" id="linea-transporte">
                <h1 class="text-xs font-bold uppercase text-[#9B9B9B]">LT:</h1>
                <p class="text-xs text-[#9B9B9B]">{{ dt.linea_transporte }}</p>
            </div>
        </div>
        <div class="justify-center col-start-7 col-end-13 px-2 py-2">
            <div class="flex flex-row-reverse flex-end">
                <ButtonWatch
                    class=""
                    @click="modalWatchOpen(dt.id)"
                    :color="dt.color"
                    id="botonHistorico"
                />
                <button
                    id="botonOcs"
                    @click="modalOcsOpen()"
                    :style="{ backgroundColor: dt.color }"
                    class="flex items-center justify-center px-3 py-1 mx-2 rounded-full w-9"
                >
                    <p class="text-sm text-white">Oc's</p>
                </button>
            </div>
            <div
                class="flex flex-row-reverse items-center mt-2"
                :style="{ color: dt.color }"
            >
                <h1 class="text-sm">{{ dt.status }}</h1>
                <span
                    class="w-2 h-2 mr-2 rounded-full"
                    :style="{ backgroundColor: dt.color }"
                ></span>
            </div>
            <div class="flex flex-row items-center justify-center mt-2 ml-20">
                <div class="flex flex-row mr-2" id="fecha-cita">
                    <img
                        class="w-3 h-3 mx-1"
                        src="../../../../assets/img/calendario.png"
                    />
                    <div>
                        <p class="text-[#9B9B9B]" style="font-size: 0.7rem">
                            {{ dt.cita.substring(5, 7) }} /
                            {{ dt.cita.substring(8, 10) }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-row" id="fecha-hora">
                    <img
                        class="w-3 h-3 mx-1"
                        src="../../../../assets/img/reloj-de-pared.png"
                    />
                    <p class="text-xs text-[#9B9B9B]">
                        {{ dt.cita.substring(10, 16) }}
                    </p>
                </div>
            </div>
            <div class="flex flex-row space-x-2 mt-4 justify-end mr-4">
                <button
                    v-if="dt.status_id == 4 || dt.status_id == 5"
                    title="Eliminar viaje individual"
                    @click="confirmarEliminacionViaje(dt)"
                    class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all duration-300 shadow-sm border border-red-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
                <button
                    v-if="dt.status_id == 4 || dt.status_id == 5"
                    title="Eliminar DT completo y sus confirmaciones"
                    @click="confirmarEliminacionDt(dt)"
                    class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-all duration-300 shadow-sm border border-amber-200 flex items-center space-x-1"
                >
                    <span class="text-[10px] font-bold">DT</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div v-if="infoModal !== null">
        <ModalWatchHistoricoStatus
            :show="modalWatch"
            @close="modalWatchClose()"
            :infoModal="infoModal"
            :status="status"
            :viaje="viaje"
            :dt="dt"
            @reVisit="reVisit"
        />
    </div>
    <ModalAddOcs
        :show="modalOcs"
        @close="modalOcsClose()"
        @reconsultar="consultarOcs()"
        :ocsAxios="ocs"
        :confirmacion="dt.confirmacion"
    />
    <ModalDeleteViaje
        :show="modalDeleteViaje"
        :viaje="viajeAEliminar"
        @close="closeModalDeleteViaje()"
        @deleted="onDelete()"
    />
    <ModalDeleteDt
        :show="modalDeleteDt"
        :dt="dtAEliminar"
        @close="closeModalDeleteDt()"
        @deleted="onDelete()"
    />
</template>
