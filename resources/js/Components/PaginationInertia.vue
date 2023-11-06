<script setup>
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios';
import { computed, ref, watchEffect } from 'vue';
const props = defineProps({
    pagination: Object,
})


const loadPage = (newPage) => 
{
    console.log(newPage);
    console.log(usePage().url)

    router.get(usePage().url, { page: newPage }, {
        replace: true,
        preserveScroll: true,
        preserveState: true
    });
    
}


const noPreviousPage = computed(() => {
    return props.pagination.current_page - 1 <= 0;
})
const noNextPage = computed(() => {
    return props.pagination.current_page + 1 > props.pagination.last_page;
})

const page = computed(() => {
    return props.pagination.current_page
})

</script>

<template>
    <div class="inline-flex items-center justify-center px-10">
        <div class="hidden mr-2 text-sm text-gray-500 lg:block">{{ pagination.total }} elementos</div>

        <div class="flex space-x-1 items-top" v-if="pagination.last_page > 1">
            <button :disabled="noPreviousPage" :class="{ 'opacity-50': noPreviousPage }" @click="loadPage(1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded shadow-sm outline-none w-11 h-11 hover:bg-gray-50 lg:h-9 lg:w-9 lg:text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:h-3 lg:w-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
            <button :disabled="noPreviousPage" :class="{ 'opacity-50': noPreviousPage }"
                @click="loadPage(pagination.current_page - 1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded shadow-sm outline-none w-11 h-11 hover:bg-gray-50 lg:h-9 lg:w-9 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:h-3 lg:w-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:items-center md:space-x-1">
                <input type="number" @keydown.enter="loadPage(page)" v-model="page"
                    class="px-2 text-center border border-gray-400 rounded shadow-sm w-11 h-11 lg:h-9 lx:w-10 lg:text-sm focus:ring-blue-500 focus:border-blue-500" />
                <div class="px-2 text-gray-600 lg:text-sm">de {{ pagination.last_page }}</div>
            </div>

            <button :disabled="noNextPage" :class="{ 'opacity-50': noNextPage }"
                @click="loadPage(pagination.current_page + 1)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-300 rounded shadow-sm outline-none w-11 h-11 hover:bg-gray-50 lg:h-9 lg:w-9 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:h-3 lg:w-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <button :disabled="noNextPage" :class="{ 'opacity-50': noNextPage }" @click="loadPage(pagination.last_page)"
                class="inline-flex items-center justify-center text-gray-500 bg-white border border-gray-300 rounded shadow-sm outline-none w-11 h-11 hover:bg-gray-50 lg:h-9 lg:w-9 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:h-3 lg:w-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</template>
