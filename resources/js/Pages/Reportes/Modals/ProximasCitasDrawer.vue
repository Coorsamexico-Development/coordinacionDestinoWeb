<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import {
    Bell,
    Clock,
    User,
    Truck,
    MapPin,
    AlertCircle,
    Calendar,
    X,
    ClockAlertIcon,
    CheckCircle2,
    MessageSquare,
    ChevronRight,
    Forklift,
    Home,
} from "lucide-vue-next";

const notifications = ref([]);
const loading = ref(false);
const isOpen = ref(false);

const fetchNotifications = async () => {
    loading.value = true;
    try {
        const response = await axios.get(route("getProximasCitas"));
        notifications.value = response.data;
    } catch (error) {
        console.error("Error cargando notificaciones:", error);
    } finally {
        loading.value = false;
    }
};

const toggleContacto = async (notif) => {
    try {
        const newValue = !notif.cliente_contactado;
        const response = await axios.post(route("toggleContacto"), {
            id: notif.id,
            cliente_contactado: newValue,
        });

        if (response.data.success) {
            notif.cliente_contactado = response.data.cliente_contactado;
        }
    } catch (error) {
        console.error("Error al actualizar estado de contacto:", error);
        // Opcional: revertir en UI si falla
    }
};

const setManualStatus = async (notif, newStatusId) => {
    if (!notif.cliente_contactado || notif.status_id == newStatusId) return;

    try {
        const response = await axios.post(route("updateStatus"), {
            id: notif.id,
            status_id: newStatusId,
        });

        if (response.data.success) {
            notif.status_id = response.data.status_id;
            notif.status_nombre = response.data.status_nombre;
            notif.status_color = response.data.status_color;
        }
    } catch (error) {
        console.error("Error al actualizar status manualmente:", error);
    }
};

const closeDrawer = () => {
    isOpen.value = false;
};

onMounted(() => {
    fetchNotifications();
    setInterval(fetchNotifications, 1800000); // Actualización cada 30 minutos
});
</script>

<template>
    <div>
        <!-- Botón Disparador -->
        <button
            type="button"
            @click="isOpen = true"
            class="relative flex items-center justify-center px-4 h-11 border border-slate-200 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-200 transition-all active:scale-95 group z-10"
        >
            <ClockAlertIcon
                class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors mr-2"
            />
            <span
                class="font-black text-slate-700 text-xs uppercase tracking-wider"
            >
                Próximos Arribos
            </span>

            <span
                v-if="notifications.length > 0"
                class="ml-2 flex items-center justify-center min-w-[22px] h-[22px] px-1.5 text-[10px] font-black text-white bg-red-600 rounded-full shadow-lg shadow-red-200 border-2 border-white"
            >
                {{ notifications.length }}
            </span>
        </button>

        <!-- Drawer Custom -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="isOpen"
                    @click="closeDrawer"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9998]"
                ></div>
            </Transition>

            <Transition name="slide-right">
                <div
                    v-if="isOpen"
                    class="fixed right-0 top-0 bottom-0 w-full sm:w-[540px] bg-white shadow-2xl z-[9999] flex flex-col border-l border-slate-200 overflow-hidden"
                >
                    <!-- Header -->
                    <div class="p-8 border-b bg-white relative overflow-hidden">
                        <!-- Decoración de fondo -->
                        <div
                            class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full opacity-50 blur-3xl"
                        ></div>

                        <button
                            @click="closeDrawer"
                            class="absolute top-8 right-8 p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-xl transition-all border border-slate-100 z-10"
                        >
                            <X class="w-5 h-5" />
                        </button>

                        <div class="flex items-center gap-5 mb-6 relative z-10">
                            <div
                                class="p-4 bg-gradient-to-br from-blue-600 to-blue-700 rounded-[2rem] shadow-xl shadow-blue-200 text-white"
                            >
                                <Clock class="w-8 h-8" />
                            </div>
                            <div>
                                <h2
                                    class="text-3xl font-black text-slate-900 tracking-tight leading-none"
                                >
                                    Logística Crítica
                                </h2>
                                <div
                                    class="flex items-center gap-2 text-blue-600 font-bold text-[10px] uppercase tracking-[0.25em] mt-2"
                                >
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"
                                        ></span>
                                        <span
                                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"
                                        ></span>
                                    </span>
                                    Ventana de 2 Horas
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-slate-900 rounded-3xl p-5 text-white shadow-xl shadow-slate-200 border border-slate-800 relative z-10"
                        >
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-blue-500/20 rounded-xl">
                                    <AlertCircle
                                        class="w-5 h-5 text-blue-400"
                                    />
                                </div>
                                <div>
                                    <h4
                                        class="font-bold text-sm text-blue-100 mb-1"
                                    >
                                        Acciones Requeridas
                                    </h4>
                                    <p
                                        class="text-[11px] text-slate-400 leading-relaxed"
                                    >
                                        Monitoree el arribo y asegure el
                                        contacto previo con el cliente para
                                        confirmar la disponibilidad de rampa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listado -->
                    <div
                        class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/80"
                    >
                        <div
                            v-if="loading && notifications.length === 0"
                            class="flex flex-col items-center py-32"
                        >
                            <div class="relative w-16 h-16">
                                <div
                                    class="absolute inset-0 border-4 border-blue-600/10 rounded-full"
                                ></div>
                                <div
                                    class="absolute inset-0 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"
                                ></div>
                            </div>
                            <p
                                class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]"
                            >
                                Sincronizando Arribos...
                            </p>
                        </div>

                        <div
                            v-else-if="notifications.length === 0"
                            class="flex flex-col items-center justify-center py-32 px-10 text-center"
                        >
                            <div
                                class="w-24 h-24 bg-white rounded-[3rem] shadow-sm border border-slate-100 flex items-center justify-center mb-6"
                            >
                                <Bell class="w-10 h-10 text-slate-200" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">
                                Sin Pendientes
                            </h3>
                            <p class="text-slate-400 text-sm max-w-[240px]">
                                No hay confirmaciones programadas para las
                                próximas 2 horas.
                            </p>
                        </div>

                        <div
                            v-for="notif in notifications"
                            :key="notif.id"
                            class="bg-white border border-slate-200 rounded-[2.5rem] p-6 shadow-sm hover:shadow-2xl hover:shadow-blue-100/50 transition-all group relative overflow-hidden"
                        >
                            <!-- Indicador de Status lateral -->
                            <div
                                class="absolute left-0 top-0 bottom-0 w-2 transition-all group-hover:w-3"
                                :style="{ backgroundColor: notif.status_color }"
                            ></div>

                            <!-- Header de Tarjeta -->
                            <div
                                class="flex items-start justify-between mb-6 pl-2"
                            >
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                            >Viaje</span
                                        >
                                    </div>
                                    <h3
                                        class="text-2xl font-black text-slate-900 tracking-tighter"
                                    >
                                        #{{ notif.confirmacion }}
                                    </h3>
                                    <!-- Control Segmentado de Status -->
                                    <div
                                        v-if="!notif.cliente_contactado"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-300 bg-white shadow-sm border border-slate-200 text-slate-900"
                                    >
                                        <div
                                            class="w-2 h-2 rounded-full"
                                            :style="{
                                                backgroundColor:
                                                    notif.status_color,
                                            }"
                                        ></div>
                                        <span
                                            class="text-[9px] font-black uppercase tracking-wider"
                                            >A Tiempo</span
                                        >
                                    </div>
                                    <div
                                        v-else
                                        class="inline-flex p-1 bg-slate-100/80 rounded-2xl border border-slate-200/50 mt-3 transition-all"
                                    >
                                        <!-- Opción A Tiempo (ID 4) -->
                                        <button
                                            @click="setManualStatus(notif, 4)"
                                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-300"
                                            :class="
                                                notif.status_id == 4
                                                    ? 'bg-white shadow-sm border border-slate-200 text-slate-900'
                                                    : 'text-slate-400 hover:text-slate-600 hover:bg-white/40'
                                            "
                                        >
                                            <div
                                                class="w-2 h-2 rounded-full"
                                                :class="
                                                    notif.status_id == 4
                                                        ? 'animate-pulse'
                                                        : 'opacity-50'
                                                "
                                                :style="{
                                                    backgroundColor:
                                                        notif.status_id == 4
                                                            ? notif.status_color
                                                            : '#94a3b8',
                                                }"
                                            ></div>
                                            <span
                                                class="text-[9px] font-black uppercase tracking-wider"
                                                >A Tiempo</span
                                            >
                                        </button>

                                        <!-- Opción En Riesgo (ID 5) -->
                                        <button
                                            @click="setManualStatus(notif, 5)"
                                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-300"
                                            :class="
                                                notif.status_id == 5
                                                    ? 'bg-white shadow-sm border border-slate-200 text-slate-900'
                                                    : 'text-slate-400 hover:text-slate-600 hover:bg-white/40'
                                            "
                                        >
                                            <div
                                                class="w-2 h-2 rounded-full"
                                                :class="
                                                    notif.status_id == 5
                                                        ? 'animate-pulse'
                                                        : 'opacity-50'
                                                "
                                                :style="{
                                                    backgroundColor:
                                                        notif.status_id == 5
                                                            ? notif.status_color
                                                            : '#94a3b8',
                                                }"
                                            ></div>
                                            <span
                                                class="text-[9px] font-black uppercase tracking-wider"
                                                >En Riesgo</span
                                            >
                                        </button>
                                    </div>
                                </div>

                                <!-- Botón Toggle Contacto -->
                                <button
                                    @click="toggleContacto(notif)"
                                    class="flex flex-col items-center gap-2 transition-all active:scale-95"
                                >
                                    <div
                                        class="w-14 h-7 rounded-full p-1 transition-all duration-300 relative border"
                                        :class="
                                            notif.cliente_contactado
                                                ? 'bg-green-500 border-green-600 shadow-inner'
                                                : 'bg-slate-100 border-slate-200'
                                        "
                                    >
                                        <div
                                            class="w-5 h-5 bg-white rounded-full shadow-md transition-all duration-300 flex items-center justify-center"
                                            :style="{
                                                transform:
                                                    notif.cliente_contactado
                                                        ? 'translateX(28px)'
                                                        : 'translateX(0px)',
                                            }"
                                        >
                                            <CheckCircle2
                                                v-if="notif.cliente_contactado"
                                                class="w-3 h-3 text-green-600"
                                            />
                                            <X
                                                v-else
                                                class="w-3 h-3 text-slate-400"
                                            />
                                        </div>
                                    </div>
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest"
                                        :class="
                                            notif.cliente_contactado
                                                ? 'text-green-600'
                                                : 'text-slate-400'
                                        "
                                    >
                                        {{
                                            notif.cliente_contactado
                                                ? "Contactado"
                                                : "Sin Contactar"
                                        }}
                                    </span>
                                </button>
                            </div>

                            <!-- Cuerpo de Tarjeta -->
                            <div class="space-y-4 pl-2">
                                <!-- Cliente Info -->

                                <!-- Detalles Logísticos -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="bg-slate-50 border border-slate-100 p-4 rounded-3xl flex items-center gap-4 group-hover:bg-blue-50/30 group-hover:border-blue-100 transition-colors"
                                    >
                                        <div
                                            class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-slate-400 group-hover:text-blue-600 transition-colors"
                                        >
                                            <Home class="w-6 h-6" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-[9px] font-bold text-slate-400 uppercase"
                                            >
                                                Ubicación / Plataforma
                                            </p>
                                            <p
                                                class="text-[11px] font-black text-slate-900 truncate uppercase leading-tight"
                                            >
                                                {{ notif.nombre_ubicacion
                                                }}<br />
                                                <span
                                                    class="text-blue-600 text-[10px]"
                                                    >{{
                                                        notif.plataforma_nombre ||
                                                        "Sin Plataforma"
                                                    }}</span
                                                >
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-slate-50/50 p-4 rounded-3xl border border-slate-100/50"
                                    >
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <div
                                                class="p-1.5 bg-white rounded-lg text-slate-400 shadow-sm"
                                            >
                                                <Clock class="w-3.5 h-3.5" />
                                            </div>
                                            <p
                                                class="text-[9px] font-bold text-slate-400 uppercase"
                                            >
                                                Cita
                                            </p>
                                        </div>
                                        <p
                                            class="text-xs font-black text-blue-700"
                                        >
                                            {{
                                                notif.cita.split(" ")[1] ||
                                                notif.cita
                                            }}
                                        </p>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 mt-0.5"
                                        >
                                            {{ notif.cita.split(" ")[0] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Ubicación -->
                                <div
                                    class="flex items-center justify-between pt-2"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-red-50 text-red-500 rounded-xl flex items-center justify-center"
                                        >
                                            <Truck />
                                        </div>
                                        <div>
                                            <p
                                                class="text-[9px] font-bold text-slate-400 uppercase"
                                            >
                                                Linea de transporte
                                            </p>
                                            <p
                                                class="text-xs font-black text-slate-700"
                                            >
                                                {{ notif.linea_transporte }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="p-8 bg-white border-t mt-auto shadow-[0_-10px_20px_rgba(0,0,0,0.02)] relative z-10"
                    >
                        <div
                            class="flex items-center justify-between text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]"
                        >
                            <span class="text-blue-600">COORSAMEXICO</span>
                            <span class="flex items-center gap-3">
                                <span
                                    class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"
                                ></span>
                                Control de Tráfico
                            </span>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
/* Animaciones de entrada/salida */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-right-enter-active,
.slide-right-leave-active {
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-right-enter-from,
.slide-right-leave-to {
    transform: translateX(100%);
}

/* Scrollbar estética */
::-webkit-scrollbar {
    width: 5px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 20px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
