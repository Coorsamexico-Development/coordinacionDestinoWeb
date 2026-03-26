<template>
    <div class="relative w-full" ref="containerRef">
        <div
            class="flex min-h-[40px] w-full flex-wrap items-center gap-1.5 rounded-full border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50 disabled:cursor-not-allowed disabled:opacity-50 cursor-text"
            @click="open"
        >
            <div
                v-for="item in selectedItems"
                :key="item[valueKey]"
                class="inline-flex items-center rounded-sm bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800"
            >
                {{ item[labelKey] }}
                <button
                    type="button"
                    class="ml-1 inline-flex h-4 w-4 shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none"
                    @click.stop="removeItem(item)"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-x"
                    >
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <input
                v-model="search"
                type="text"
                class="flex-1 bg-transparent px-1 py-0.5 text-sm outline-none placeholder:text-gray-500 border-none focus:ring-0 p-0 m-0 w-auto min-w-[50px]"
                :placeholder="selectedItems.length === 0 ? placeholder : ''"
                @focus="isOpen = true"
                @keydown.delete="handleDelete"
                @keydown.down.prevent="navigateOptions(1)"
                @keydown.up.prevent="navigateOptions(-1)"
                @keydown.enter.prevent="selectHighlighted"
                @keydown.esc="isOpen = false"
            />
        </div>

        <!-- Dropdown menu -->
        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute top-full z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-md"
            >
                <div
                    v-for="(option, index) in filteredOptions"
                    :key="option[valueKey]"
                    class="relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none hover:bg-gray-100 focus:bg-gray-100"
                    :class="{ 'bg-gray-100': highlightedIndex === index }"
                    @click.stop="toggleOption(option)"
                    @mouseenter="highlightedIndex = index"
                >
                    <span
                        v-if="isSelected(option)"
                        class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center text-indigo-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-check"
                        >
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                    </span>
                    <span
                        class="block text-gray-800 truncate"
                        :class="{ 'font-medium': isSelected(option) }"
                        >{{ option[labelKey] }}</span
                    >
                </div>
                <div
                    v-if="filteredOptions.length === 0"
                    class="relative cursor-default select-none py-2 px-4 text-gray-500 text-sm"
                >
                    No se encontraron opciones.
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: "Selecciona...",
    },
    labelKey: {
        type: String,
        default: "label",
    },
    valueKey: {
        type: String,
        default: "id",
    },
});

const emit = defineEmits(["update:modelValue"]);

const containerRef = ref(null);
const isOpen = ref(false);
const search = ref("");
const highlightedIndex = ref(-1);

const selectedItems = computed(() => {
    return props.options.filter((opt) =>
        props.modelValue.includes(opt[props.valueKey]),
    );
});

const filteredOptions = computed(() => {
    if (!search.value) return props.options;
    const s = search.value.toLowerCase();
    return props.options.filter((opt) =>
        opt[props.labelKey].toLowerCase().includes(s),
    );
});

watch(search, () => {
    highlightedIndex.value = 0;
});

const open = () => {
    isOpen.value = true;
};

const close = () => {
    isOpen.value = false;
    search.value = "";
    highlightedIndex.value = -1;
};

const toggleOption = (option) => {
    let newValue = [...props.modelValue];
    const index = newValue.indexOf(option[props.valueKey]);
    if (index > -1) {
        newValue.splice(index, 1);
    } else {
        newValue.push(option[props.valueKey]);
    }
    emit("update:modelValue", newValue);

    if (search.value) {
        search.value = "";
    } else {
        containerRef.value?.querySelector("input")?.focus();
    }
};

const removeItem = (option) => {
    const newValue = props.modelValue.filter(
        (id) => id !== option[props.valueKey],
    );
    emit("update:modelValue", newValue);
};

const isSelected = (option) => {
    return props.modelValue.includes(option[props.valueKey]);
};

const handleDelete = (e) => {
    if (search.value === "" && props.modelValue.length > 0) {
        const newValue = [...props.modelValue];
        newValue.pop();
        emit("update:modelValue", newValue);
    }
};

const navigateOptions = (dir) => {
    let next = highlightedIndex.value + dir;
    if (next < 0) next = filteredOptions.value.length - 1;
    if (next >= filteredOptions.value.length) next = 0;
    highlightedIndex.value = next;
};

const selectHighlighted = () => {
    if (
        isOpen.value &&
        highlightedIndex.value > -1 &&
        filteredOptions.value[highlightedIndex.value]
    ) {
        toggleOption(filteredOptions.value[highlightedIndex.value]);
    } else {
        isOpen.value = true;
    }
};

const handleClickOutside = (e) => {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        close();
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});
</script>
