<script setup>
import { ref } from "vue";
import PickerCalendar from "./PickerCalendar.vue";

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
});
const buttonCalendar = ref(null);
</script>

<template>
    <PickerCalendar @changeDate="emit('changeDate', $event)" :refComponent="buttonCalendar" :year="props.year"
        :month="props.month">
        <template #trigger>
            <div class="">
                <button ref="buttonCalendar" :type="type"
                    class="items-center w-full px-4 py-2 text-[15px] font-semibold uppercase transition text-fuente-500 rounded-2xl bg-gris-500 disabled:opacity-25 flex justify-between gap-4">
                    <button @click.stop="
                        emit('changeDate', { month: 0, year: props.year - 1 })
                    " class="hover:opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-900" fill="none"
                            viewBox="0 0 24 24" stroke="#1D96F1" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    {{ year }}
                    <button @click.stop="
                        emit('changeDate', { month: 0, year: props.year + 1 })
                    " class="hover:opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-900" fill="none"
                            viewBox="0 0 24 24" stroke="#1D96F1" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </button>
            </div>
        </template>
    </PickerCalendar>
</template>
