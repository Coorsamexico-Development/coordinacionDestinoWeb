<script setup>
import { onMounted, ref, onUnmounted } from "vue";
import { listMonths } from "../data/calendar";
import ItemMonth from "./ItemMonth.vue";

const emit = defineEmits(["changeDate"]);

const props = defineProps({
    type: {
        type: String,
        default: "button",
    },
    month: {
        type: Number,
        required: true,
    },
    year: {
        type: Number,
        required: true,
    },
    refComponent: {
        type: null,
        required: true,
    },
});

//Show month text
const monthText = ref(listMonths);
const pickerCalendar = ref(null);
const divSlot = ref(null);
let open = ref(false);

const closeOnEscape = (e) => {
    if (open.value && e.key === "Escape") {
        open.value = false;
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));
onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

onMounted(() => {
    const x =
        divSlot.value.offsetLeft -
        pickerCalendar.value.clientWidth / 2 +
        divSlot.value.clientWidth;
    const y = divSlot.value.offsetTop + 45;
    pickerCalendar.value.style.top = `${y}px`;
    pickerCalendar.value.style.left = `${x}px`;
});

const changeIndexMes = (newMonth) => {
    let newDate = { month: 0, year: props.year };
    switch (newMonth) {
        case 12:
            newDate.month = 0;
            newDate.year = props.year + 1;
            break;
        case -1:
            newDate.month = 11;
            newDate.year = props.year - 1;
            break;
        default:
            newDate.month = newMonth;
    }
    emit("changeDate", newDate);
};
const changeYear = (newYear) => {
    emit("changeDate", { month: props.month, year: newYear });
};
</script>

<template>
    <div>
        <div ref="divSlot" @click="open = !open">
            <slot name="trigger" />
        </div>
        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false" />
        <teleport to="body">
            <div ref="pickerCalendar"
                class="bg-gris-500 rounded-xl text-fuente-500 absolute z-[1000] w-[20em] shadow-md shadow-gray-400">
                <transition leave-active-class="duration-200">
                    <div v-show="open"
                        class="absolute  bg-white rounded-xl text-fuente-500 shadow-md w-[20em] shadow-gray-400 right-16">
                        <div class="flex justify-center items-center p-[5px] my-[10px] border-b-2 border-aqua-500">
                            <svg @click="changeYear(props.year - 1)" xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-4 hover:cursor-pointer hover:opacity-40" fill="none" viewBox="0 0 24 24"
                                stroke="#44BFFC" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span class="px-2 py-1 text-sm text-[#0A0F2C] rounded-lg bg-aqua-500 hover:cursor-pointer">
                                {{ props.year }}
                            </span>
                            <svg @click="changeYear(props.year + 1)" xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-4 hover:cursor-pointer hover:opacity-40" fill="none" viewBox="0 0 24 24"
                                stroke="#44BFFC" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div class="w-full px-[10px] py-[2px]">
                            <div class="grid grid-cols-3 gap-[5px]">
                                <ItemMonth v-for="(mText, index) in monthText" :key="mText" @click="
                                    changeIndexMes(index), (open = !open)
                                " :current-month="props.month" :month="index">
                                    {{ mText }}
                                </ItemMonth>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </teleport>
    </div>
</template>
